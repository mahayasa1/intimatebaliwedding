<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class BlogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $blogs = Blog::where('is_published', true)
                     ->whereNotNull('published_at')
                     ->orderBy('published_at', 'desc')
                     ->paginate(12);

        return view('blogs.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
                    ->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->firstOrFail();

        return view('blogs.show', compact('blog'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN — LISTING & DETAIL
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $blogs = Blog::latest()->paginate(20);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function adminShow(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN — CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'image'        => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'pdf'          => 'required|mimes:pdf',
            'excerpt'      => 'nullable|string',
            'author'       => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        if ($request->hasFile('pdf')) {
            $result = $this->processPdf($request->file('pdf'), $validated['slug']);
            $validated['pdf']     = $result['pdf_path'];
            $validated['content'] = $result['content_html'];

            if (empty($validated['excerpt']) && !empty($result['plain_text'])) {
                $validated['excerpt'] = Str::limit(strip_tags($result['plain_text']), 200);
            }
        }

        if (empty($validated['content'])) {
            $validated['content'] = '';
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog post created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN — EDIT / UPDATE
    |--------------------------------------------------------------------------
    */

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'pdf'          => 'nullable|mimes:pdf',
            'excerpt'      => 'nullable|string',
            'author'       => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            if ($blog->image) Storage::disk('public')->delete($blog->image);
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        if ($request->hasFile('pdf')) {
            if ($blog->pdf) Storage::disk('public')->delete($blog->pdf);
            $this->deletePdfImages($blog->slug);

            $result = $this->processPdf($request->file('pdf'), $validated['slug']);
            $validated['pdf']     = $result['pdf_path'];
            $validated['content'] = $result['content_html'];

            if (empty($validated['excerpt']) && !empty($result['plain_text'])) {
                $validated['excerpt'] = Str::limit(strip_tags($result['plain_text']), 200);
            }
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog post updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN — DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Blog $blog)
    {
        if ($blog->image) Storage::disk('public')->delete($blog->image);
        if ($blog->pdf)   Storage::disk('public')->delete($blog->pdf);
        $this->deletePdfImages($blog->slug);
        $blog->delete();

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog post deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE — PDF PROCESSING
    |--------------------------------------------------------------------------
    |
    | Rendering tries tools in this priority order:
    |
    |   Windows:
    |     1. gswin64c  (Ghostscript 64-bit — install from ghostscript.com)
    |     2. gswin32c  (Ghostscript 32-bit fallback)
    |     3. spatie/pdf-to-image (requires Imagick)
    |
    |   Linux / Mac:
    |     1. pdftoppm  (poppler-utils — fastest)
    |     2. gs        (Ghostscript)
    |     3. spatie/pdf-to-image (requires Imagick)
    |
    |   Text extraction always uses smalot/pdfparser (pure PHP, no system deps).
    |
    */

    private function processPdf($file, string $slug): array
    {
        // 1. Store PDF — normalise to forward slashes (Windows compat)
        $pdfName  = time() . '_' . Str::random(6) . '.pdf';
        $pdfPath  = $file->storeAs('blogs/pdf', $pdfName, 'public');
        $fullPath = $this->normPath(storage_path('app/public/' . $pdfPath));

        Log::info('[PDF] Stored: ' . $fullPath);

        // 2. Extract plain text
        $plainText = $this->extractText($fullPath);

        // 3. Prepare image output directory
        $imageDir    = $this->normPath(storage_path('app/public/blogs/pdf-images/' . $slug));
        $imageRelDir = 'blogs/pdf-images/' . $slug;

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // 4. Render pages to JPEG
        $pageFiles = $this->renderPages($fullPath, $imageDir);

        // 5. Build HTML
        $textHtml  = $this->buildTextHtml($plainText);
        $imageHtml = '';

        foreach ($pageFiles as $i => $filename) {
            $url = '/storage/' . $imageRelDir . '/' . $filename;
            $imageHtml .= '<img src="' . $url . '" '
                        . 'alt="Halaman ' . ($i + 1) . '" '
                        . 'style="width:100%;margin:24px 0;border-radius:8px;'
                        . 'box-shadow:0 2px 16px rgba(0,0,0,0.10);">' . "\n";
        }

        $contentHtml = $textHtml . $imageHtml;

        if (trim($contentHtml) === '') {
            $contentHtml = '<p><em>Konten tidak dapat diekstrak dari PDF ini. '
                         . 'Gunakan tombol Download PDF untuk membaca.</em></p>';
        }

        Log::info('[PDF] Done — text: ' . strlen($plainText) . ' chars, pages: ' . count($pageFiles));

        return [
            'pdf_path'     => $pdfPath,
            'content_html' => $contentHtml,
            'plain_text'   => $plainText,
        ];
    }

    // ── Text extraction (pure PHP, always works) ────────────────────────────

    private function extractText(string $fullPath): string
    {
        try {
            $text = (new Parser())->parseFile($fullPath)->getText();
            Log::info('[PDF] Text extracted: ' . strlen($text) . ' chars');
            return $text;
        } catch (\Throwable $e) {
            Log::warning('[PDF] Text extraction failed: ' . $e->getMessage());
            return '';
        }
    }

    // ── Page rendering — tries tools in priority order ──────────────────────

    private function renderPages(string $pdfPath, string $imageDir): array
    {
        $isWindows = PHP_OS_FAMILY === 'Windows';

        if ($isWindows) {
            // Windows priority: gswin64c → gswin32c → spatie
            foreach (['gswin64c', 'gswin32c'] as $bin) {
                if ($path = $this->findBin($bin)) {
                    $files = $this->renderViaGhostscript($path, $pdfPath, $imageDir);
                    if ($files) {
                        Log::info('[PDF] Rendered via ' . $bin . ' (' . count($files) . ' pages)');
                        return $files;
                    }
                }
            }
        } else {
            // Linux / Mac priority: pdftoppm → gs
            if ($path = $this->findBin('pdftoppm')) {
                $files = $this->renderViaPdftoppm($path, $pdfPath, $imageDir);
                if ($files) {
                    Log::info('[PDF] Rendered via pdftoppm (' . count($files) . ' pages)');
                    return $files;
                }
            }

            if ($path = $this->findBin('gs') ?? $this->findBin('ghostscript')) {
                $files = $this->renderViaGhostscript($path, $pdfPath, $imageDir);
                if ($files) {
                    Log::info('[PDF] Rendered via gs (' . count($files) . ' pages)');
                    return $files;
                }
            }
        }

        // Final fallback: spatie/pdf-to-image (requires Imagick)
        $files = $this->renderViaSpatie($pdfPath, $imageDir);
        if ($files) {
            Log::info('[PDF] Rendered via spatie (' . count($files) . ' pages)');
            return $files;
        }

        Log::warning('[PDF] All rendering strategies failed. '
            . ($isWindows
                ? 'Install Ghostscript: https://ghostscript.com/releases/gsdnld.html'
                : 'Run: sudo apt-get install poppler-utils'));

        return [];
    }

    // ── Renderer: pdftoppm ──────────────────────────────────────────────────

    private function renderViaPdftoppm(string $bin, string $pdfPath, string $imageDir): array
    {
        $prefix  = $imageDir . '/page';
        $command = sprintf(
            '%s -jpeg -r 150 %s %s 2>&1',
            escapeshellarg($bin),
            escapeshellarg($pdfPath),
            escapeshellarg($prefix)
        );

        $out = shell_exec($command);
        Log::debug('[PDF] pdftoppm: ' . $out);

        return $this->collectJpegs($imageDir);
    }

    // ── Renderer: Ghostscript (gswin64c / gswin32c / gs) ───────────────────

    private function renderViaGhostscript(string $bin, string $pdfPath, string $imageDir): array
    {
        // Output pattern: /path/page-%d.jpg  (Ghostscript fills %d with page number)
        $pattern = $imageDir . '/page-%d.jpg';

        $command = sprintf(
            '%s -dBATCH -dNOPAUSE -dSAFER -sDEVICE=jpeg -r150 -dJPEGQ=90 -sOutputFile=%s %s 2>&1',
            escapeshellarg($bin),
            escapeshellarg($pattern),
            escapeshellarg($pdfPath)
        );

        $out = shell_exec($command);
        Log::debug('[PDF] Ghostscript: ' . $out);

        return $this->collectJpegs($imageDir);
    }

    // ── Renderer: spatie/pdf-to-image (Imagick fallback) ───────────────────

    private function renderViaSpatie(string $pdfPath, string $imageDir): array
    {
        if (!class_exists(\Spatie\PdfToImage\Pdf::class)) {
            Log::warning('[PDF] spatie/pdf-to-image not installed.');
            return [];
        }

        if (!extension_loaded('imagick')) {
            Log::warning('[PDF] Imagick extension not loaded — spatie cannot render pages.');
            return [];
        }

        try {
            $pdf       = new \Spatie\PdfToImage\Pdf($pdfPath);
            $pageCount = $pdf->getNumberOfPages();

            for ($i = 1; $i <= $pageCount; $i++) {
                $pdf->setPage($i)
                    ->setOutputFormat('jpg')
                    ->saveImage($imageDir . '/page-' . $i . '.jpg');
            }

            return $this->collectJpegs($imageDir);
        } catch (\Throwable $e) {
            Log::warning('[PDF] spatie failed: ' . $e->getMessage());
            return [];
        }
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Collect JPEG files from a directory, sorted in natural page order.
     * Returns array of basenames: ['page-1.jpg', 'page-2.jpg', ...]
     */
    private function collectJpegs(string $dir): array
    {
        $files = glob($dir . '/*.jpg') ?: [];

        if (empty($files)) {
            return [];
        }

        natsort($files);

        return array_values(array_map('basename', $files));
    }

    /**
     * Find a system binary. Works on Windows (where.exe) and Linux/Mac (which).
     */
    private function findBin(string $name): ?string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $result = trim((string) shell_exec('where ' . escapeshellarg($name) . ' 2>NUL'));
            // `where` may return multiple lines — take first valid one
            foreach (explode("\n", $result) as $line) {
                $line = trim($line);
                if ($line && file_exists($line)) {
                    return $line;
                }
            }
            return null;
        }

        // Linux / Mac
        $result = trim((string) shell_exec('which ' . escapeshellarg($name) . ' 2>/dev/null'));
        if ($result && file_exists($result)) {
            return $result;
        }

        // Hard-coded common paths
        foreach (['/usr/bin/', '/usr/local/bin/', '/opt/homebrew/bin/'] as $dir) {
            $path = $dir . $name;
            if (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Build HTML <p> tags from plain text.
     */
    private function buildTextHtml(string $text): string
    {
        if (trim($text) === '') {
            return '';
        }

        $html = '';
        foreach (preg_split('/\n{2,}/', trim($text)) as $para) {
            $para = trim($para);
            if ($para !== '') {
                $html .= '<p>' . nl2br(htmlspecialchars($para)) . '</p>' . "\n";
            }
        }

        return $html;
    }

    /**
     * Normalise all backslashes to forward slashes (Windows path compat).
     */
    private function normPath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }

    /**
     * Delete all rendered JPEG page images for a blog slug.
     */
    private function deletePdfImages(string $slug): void
    {
        $dir = storage_path('app/public/blogs/pdf-images/' . $slug);

        if (!is_dir($dir)) {
            return;
        }

        foreach (glob($dir . '/*.jpg') ?: [] as $file) {
            @unlink($file);
        }

        @rmdir($dir);
    }
}