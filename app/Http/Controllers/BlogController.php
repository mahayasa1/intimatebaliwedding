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
    | PUBLIC ROUTES
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

        // Upload featured image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                                           ->store('blogs', 'public');
        }

        // Upload PDF + extract content
        if ($request->hasFile('pdf')) {
            $pdfResult = $this->processPdf(
                $request->file('pdf'),
                $validated['slug']
            );

            $validated['pdf']     = $pdfResult['pdf_path'];
            $validated['content'] = $pdfResult['content_html'];

            if (empty($validated['excerpt']) && !empty($pdfResult['plain_text'])) {
                $validated['excerpt'] = Str::limit(
                    strip_tags($pdfResult['plain_text']), 200
                );
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
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')
                                           ->store('blogs', 'public');
        }

        if ($request->hasFile('pdf')) {
            if ($blog->pdf) {
                Storage::disk('public')->delete($blog->pdf);
            }
            $this->deletePdfImages($blog->slug);

            $pdfResult = $this->processPdf(
                $request->file('pdf'),
                $validated['slug']
            );

            $validated['pdf']     = $pdfResult['pdf_path'];
            $validated['content'] = $pdfResult['content_html'];

            if (empty($validated['excerpt']) && !empty($pdfResult['plain_text'])) {
                $validated['excerpt'] = Str::limit(
                    strip_tags($pdfResult['plain_text']), 200
                );
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
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->pdf) {
            Storage::disk('public')->delete($blog->pdf);
        }
        $this->deletePdfImages($blog->slug);
        $blog->delete();

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog post deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE — PDF PROCESSING
    |--------------------------------------------------------------------------
    */

    /**
     * Process an uploaded PDF:
     * 1. Store the file
     * 2. Extract text via smalot/pdfparser
     * 3. Render pages to JPEG via pdftoppm (Linux) or fallback (Windows)
     * 4. Build HTML content string
     */
    private function processPdf($file, string $slug): array
    {
        /*
        |----------------------------------------------------------------------
        | 1. Store the PDF — use forward slashes everywhere
        |----------------------------------------------------------------------
        */
        $pdfName  = time() . '_' . Str::random(6) . '.pdf';
        $pdfPath  = $file->storeAs('blogs/pdf', $pdfName, 'public');

        // storage_path() on Windows returns backslashes — normalise to forward slashes
        $fullPath = str_replace('\\', '/', storage_path('app/public/' . $pdfPath));

        Log::info('[BlogPDF] Stored PDF', ['path' => $fullPath]);

        /*
        |----------------------------------------------------------------------
        | 2. Extract text with smalot/pdfparser
        |----------------------------------------------------------------------
        */
        $plainText = '';
        try {
            $parser    = new Parser();
            $parsed    = $parser->parseFile($fullPath);
            $plainText = $parsed->getText();
            Log::info('[BlogPDF] Text extracted', ['chars' => strlen($plainText)]);
        } catch (\Exception $e) {
            Log::warning('[BlogPDF] Text extraction failed: ' . $e->getMessage());
        }

        /*
        |----------------------------------------------------------------------
        | 3. Render pages to JPEG
        |
        | On Linux/Mac: use pdftoppm (poppler-utils)
        | On Windows:   pdftoppm is usually not available — skip page images
        |               gracefully and only store extracted text.
        |----------------------------------------------------------------------
        */
        $imageDir    = str_replace('\\', '/', storage_path('app/public/blogs/pdf-images/' . $slug));
        $imageRelDir = 'blogs/pdf-images/' . $slug;
        $imageHtml   = '';

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // Detect pdftoppm availability
        $pdftoppm = $this->findExecutable('pdftoppm');

        if ($pdftoppm) {
            $prefix  = $imageDir . '/page';
            $command = sprintf(
                '%s -jpeg -r 150 %s %s 2>&1',
                escapeshellarg($pdftoppm),
                escapeshellarg($fullPath),
                escapeshellarg($prefix)
            );

            Log::info('[BlogPDF] Running pdftoppm', ['cmd' => $command]);
            $output = shell_exec($command);
            Log::info('[BlogPDF] pdftoppm output', ['output' => $output]);

            // Collect generated files — sort numerically (page-1 before page-10)
            $jpegFiles = glob($imageDir . '/page-*.jpg');
            if (empty($jpegFiles)) {
                $jpegFiles = glob($imageDir . '/page*.jpg');
            }

            if (!empty($jpegFiles)) {
                natsort($jpegFiles);
                foreach ($jpegFiles as $jpegFile) {
                    $basename  = basename($jpegFile);
                    $publicUrl = '/storage/' . $imageRelDir . '/' . $basename;
                    $pageLabel = preg_replace('/[^0-9]/', '', pathinfo($basename, PATHINFO_FILENAME));

                    $imageHtml .= '<img src="' . $publicUrl . '" '
                                . 'alt="Halaman ' . $pageLabel . '" '
                                . 'style="width:100%;margin:24px 0;border-radius:6px;box-shadow:0 2px 12px rgba(0,0,0,0.08);">'
                                . "\n";
                }
                Log::info('[BlogPDF] Generated page images', ['count' => count($jpegFiles)]);
            } else {
                Log::warning('[BlogPDF] pdftoppm ran but produced no JPEG files', [
                    'imageDir' => $imageDir,
                    'output'   => $output,
                ]);
            }
        } else {
            Log::warning('[BlogPDF] pdftoppm not found — page images skipped. Install poppler-utils on the server.');
        }

        /*
        |----------------------------------------------------------------------
        | 4. Build final HTML: text paragraphs first, then page images
        |----------------------------------------------------------------------
        */
        $textHtml = '';
        if (!empty(trim($plainText))) {
            $paragraphs = preg_split('/\n{2,}/', trim($plainText));
            foreach ($paragraphs as $para) {
                $para = trim($para);
                if ($para !== '') {
                    $textHtml .= '<p>' . nl2br(htmlspecialchars($para)) . '</p>' . "\n";
                }
            }
        }

        $contentHtml = $textHtml . $imageHtml;

        // Fallback if both extractions gave nothing
        if (empty(trim($contentHtml))) {
            $contentHtml = '<p><em>Could not extract content from this PDF. Please download the PDF to read it.</em></p>';
            Log::warning('[BlogPDF] Both text and image extraction produced no content.');
        }

        return [
            'pdf_path'     => $pdfPath,
            'content_html' => $contentHtml,
            'plain_text'   => $plainText,
        ];
    }

    /**
     * Find the full path of an executable.
     * Checks common locations on Linux/Mac and returns null on Windows
     * (where pdftoppm is typically unavailable).
     *
     * @return string|null  Full path to executable, or null if not found
     */
    private function findExecutable(string $name): ?string
    {
        // On Windows — skip, pdftoppm not available
        if (PHP_OS_FAMILY === 'Windows') {
            return null;
        }

        // Use `which` to locate the binary
        $path = trim((string) shell_exec('which ' . escapeshellarg($name) . ' 2>/dev/null'));
        if ($path && file_exists($path)) {
            return $path;
        }

        // Fallback: check common Linux install locations
        $candidates = [
            '/usr/bin/'     . $name,
            '/usr/local/bin/' . $name,
            '/opt/homebrew/bin/' . $name, // macOS with Homebrew
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Delete all rendered JPEG page images for a given blog slug.
     */
    private function deletePdfImages(string $slug): void
    {
        $imageDir = storage_path('app/public/blogs/pdf-images/' . $slug);

        if (!is_dir($imageDir)) {
            return;
        }

        foreach (glob($imageDir . '/*.jpg') as $file) {
            @unlink($file);
        }

        @rmdir($imageDir);
    }
}