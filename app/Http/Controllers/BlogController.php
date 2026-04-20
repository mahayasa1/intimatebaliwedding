<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use App\Helpers\ImageHelper;

class BlogController extends Controller
{
    // =========================================================================
    // PUBLIC
    // =========================================================================

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

    // =========================================================================
    // ADMIN
    // =========================================================================

    public function adminIndex()
    {
        $blogs = Blog::latest()->paginate(20);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function adminShow(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

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
            $result = ImageHelper::storeAndCompress($request->file('image'), 'blogs');
            $validated['image'] = $result['path'];
        }

        if ($request->hasFile('pdf')) {
            $result = $this->processPdf($request->file('pdf'), $validated['slug']);
            $validated['pdf']     = $result['pdf_path'];
            $validated['content'] = $result['content_html'];

            // Auto-fill excerpt from plain text (for SEO / listing preview only)
            // Text is NOT shown in the blog body — only used for excerpt
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
            if ($blog->image) ImageHelper::delete($blog->image);
            $result = ImageHelper::storeAndCompress($request->file('image'), 'blogs');
            $validated['image'] = $result['path'];
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

    public function destroy(Blog $blog)
    {
        if ($blog->image) Storage::disk('public')->delete($blog->image);
        if ($blog->pdf)   Storage::disk('public')->delete($blog->pdf);
        $this->deletePdfImages($blog->slug);
        $blog->delete();

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog post deleted successfully.');
    }

    // =========================================================================
    // PDF PROCESSING
    // =========================================================================
    //
    // Alur:
    //   1. Upload & simpan PDF
    //   2. Extract plain text → dipakai untuk auto-fill excerpt saja (tidak ditampilkan di blog)
    //   3. Render setiap halaman PDF → PNG via Ghostscript
    //   4. Auto-crop white margin tiap halaman → simpan sebagai WebP
    //   5. content HTML = HANYA deretan <img> halaman (tidak ada teks)
    //
    // Hasilnya: blog body hanya menampilkan gambar halaman PDF,
    // persis seperti membaca PDF tapi di dalam website.
    // =========================================================================

    private function processPdf($file, string $slug): array
    {
        // 1. Simpan PDF
        $pdfName  = time() . '_' . Str::random(6) . '.pdf';
        $pdfPath  = $file->storeAs('blogs/pdf', $pdfName, 'public');
        $fullPath = $this->normPath(storage_path('app/public/' . $pdfPath));

        Log::info('[PDF] Stored: ' . $fullPath);

        // 2. Extract text — untuk excerpt saja, TIDAK masuk ke content HTML
        $plainText = $this->extractText($fullPath);
        Log::info('[PDF] Text extracted: ' . strlen($plainText) . ' chars (for excerpt only)');

        // 3. Siapkan folder output gambar
        $imageDir    = $this->normPath(storage_path('app/public/blogs/pdf-images/' . $slug));
        $imageRelDir = 'blogs/pdf-images/' . $slug;

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // 4. Render halaman PDF → gambar (crop white margin → WebP)
        $pageUrls = $this->renderPages($fullPath, $imageDir, $imageRelDir);
        Log::info('[PDF] Page images: ' . count($pageUrls));

        // 5. content HTML = HANYA gambar halaman, tanpa teks apapun
        $contentHtml = '';
        foreach ($pageUrls as $url) {
            $contentHtml .= '<img src="' . htmlspecialchars($url) . '" '
                          . 'alt="" loading="lazy" '
                          . 'style="width:100%;height:auto;display:block;margin:1.5rem 0;">'
                          . "\n";
        }

        if (trim($contentHtml) === '') {
            $contentHtml = '<p><em>Konten tidak dapat diekstrak dari PDF ini. '
                         . 'Gunakan tombol Download PDF untuk membaca.</em></p>';
        }

        return [
            'pdf_path'     => $pdfPath,
            'content_html' => $contentHtml,
            'plain_text'   => $plainText, // hanya untuk excerpt
        ];
    }

    // -------------------------------------------------------------------------
    // Text extraction (untuk excerpt saja)
    // -------------------------------------------------------------------------

    private function extractText(string $fullPath): string
    {
        // Coba smalot dulu
        try {
            $text = (new Parser())->parseFile($fullPath)->getText();
            if (strlen(trim($text)) > 10) {
                return $text;
            }
        } catch (\Throwable $e) {
            Log::warning('[PDF] smalot failed: ' . $e->getMessage());
        }

        // Fallback: pdftotext
        if ($bin = $this->findBin('pdftotext')) {
            $tmp = sys_get_temp_dir() . '/pdftext_' . uniqid() . '.txt';
            shell_exec(sprintf(
                '%s %s %s 2>&1',
                escapeshellarg($bin),
                escapeshellarg($fullPath),
                escapeshellarg($tmp)
            ));
            if (file_exists($tmp)) {
                $text = (string) file_get_contents($tmp);
                @unlink($tmp);
                if (strlen(trim($text)) > 10) {
                    return $text;
                }
            }
        }

        return '';
    }

    // -------------------------------------------------------------------------
    // Render PDF halaman → gambar (Ghostscript + auto-crop + WebP)
    // -------------------------------------------------------------------------

    private function renderPages(string $pdfPath, string $imageDir, string $imageRelDir): array
    {
        $bin = $this->findGsBin();

        if (!$bin) {
            Log::warning('[PDF] Ghostscript not found. Install dari https://ghostscript.com');
            return [];
        }

        // Render semua halaman ke PNG resolusi tinggi
        $pattern = $imageDir . '/raw-%d.png';

        if (PHP_OS_FAMILY === 'Windows') {
            $patternArg = str_replace('/', '\\', $pattern);
            $command = sprintf(
                '%s -dBATCH -dNOPAUSE -dSAFER -sDEVICE=png16m -r200 '
                . '-dTextAlphaBits=4 -dGraphicsAlphaBits=4 '
                . '"-sOutputFile=%s" %s 2>&1',
                escapeshellarg($bin),
                $patternArg,
                escapeshellarg($pdfPath)
            );
        } else {
            $command = sprintf(
                '%s -dBATCH -dNOPAUSE -dSAFER -sDEVICE=png16m -r200 '
                . '-dTextAlphaBits=4 -dGraphicsAlphaBits=4 '
                . '-sOutputFile=%s %s 2>&1',
                escapeshellarg($bin),
                escapeshellarg($pattern),
                escapeshellarg($pdfPath)
            );
        }

        $out = shell_exec($command);
        Log::info('[PDF] GS render: ' . $out);

        // Kumpulkan raw PNG
        $rawFiles = glob($imageDir . '/raw-*.png') ?: [];
        if (empty($rawFiles)) {
            Log::warning('[PDF] Tidak ada PNG dihasilkan.');
            return [];
        }
        natsort($rawFiles);

        $urls = [];

        // GD tersedia → crop + save WebP
        if (extension_loaded('gd')) {
            foreach (array_values($rawFiles) as $i => $rawFile) {
                $pageNum  = $i + 1;
                $destFile = $imageDir . '/page-' . $pageNum . '.webp';
                $destUrl  = '/storage/' . $imageRelDir . '/page-' . $pageNum . '.webp';

                $cropped = $this->autoCrop($rawFile);

                if ($cropped) {
                    imagewebp($cropped, $destFile, 88);
                    imagedestroy($cropped);
                } else {
                    // GD gagal crop, simpan PNG langsung sebagai WebP via copy
                    copy($rawFile, $destFile);
                }

                @unlink($rawFile); // hapus raw PNG
                $urls[] = $destUrl;
            }
        } else {
            // GD tidak ada → pakai PNG langsung
            Log::warning('[PDF] GD extension tidak tersedia, menggunakan PNG mentah.');
            foreach (array_values($rawFiles) as $i => $rawFile) {
                $pageNum  = $i + 1;
                $destFile = $imageDir . '/page-' . $pageNum . '.png';
                $destUrl  = '/storage/' . $imageRelDir . '/page-' . $pageNum . '.png';
                rename($rawFile, $destFile);
                $urls[] = $destUrl;
            }
        }

        return $urls;
    }

    // -------------------------------------------------------------------------
    // Auto-crop white/near-white margins via GD
    // -------------------------------------------------------------------------

    /**
     * Hapus margin putih dari PNG — hasilnya hanya konten (teks/gambar).
     * Threshold 245: pixel yang ketiga channel-nya >= 245 dianggap "putih".
     */
    private function autoCrop(string $filePath)
    {
        $src = @imagecreatefrompng($filePath);
        if (!$src) return null;

        $w = imagesx($src);
        $h = imagesy($src);
        $t = 245; // threshold warna putih

        $top = $bottom = $left = $right = null;

        // Temukan batas konten (bukan putih)
        for ($y = 0; $y < $h && $top === null; $y++) {
            if (!$this->isRowWhite($src, $y, $w, $t)) $top = $y;
        }
        for ($y = $h - 1; $y >= 0 && $bottom === null; $y--) {
            if (!$this->isRowWhite($src, $y, $w, $t)) $bottom = $y;
        }
        for ($x = 0; $x < $w && $left === null; $x++) {
            if (!$this->isColWhite($src, $x, $h, $t)) $left = $x;
        }
        for ($x = $w - 1; $x >= 0 && $right === null; $x--) {
            if (!$this->isColWhite($src, $x, $h, $t)) $right = $x;
        }

        // Semua putih (halaman kosong) → kembalikan null
        if ($top === null || $bottom === null || $left === null || $right === null) {
            imagedestroy($src);
            return null;
        }

        // Tambah padding 16px supaya konten tidak mepet tepi
        $pad    = 16;
        $top    = max(0, $top    - $pad);
        $bottom = min($h - 1, $bottom + $pad);
        $left   = max(0, $left   - $pad);
        $right  = min($w - 1, $right  + $pad);

        $cw = $right  - $left + 1;
        $ch = $bottom - $top  + 1;

        if ($cw <= 0 || $ch <= 0) {
            imagedestroy($src);
            return null;
        }

        $dst   = imagecreatetruecolor($cw, $ch);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $cw - 1, $ch - 1, $white);
        imagecopy($dst, $src, 0, 0, $left, $top, $cw, $ch);
        imagedestroy($src);

        return $dst;
    }

    private function isRowWhite($img, int $y, int $w, int $t): bool
    {
        for ($x = 0; $x < $w; $x += 4) {
            $c = imagecolorat($img, $x, $y);
            if ((($c >> 16) & 0xFF) < $t
             || (($c >>  8) & 0xFF) < $t
             || ( $c        & 0xFF) < $t) {
                return false;
            }
        }
        return true;
    }

    private function isColWhite($img, int $x, int $h, int $t): bool
    {
        for ($y = 0; $y < $h; $y += 4) {
            $c = imagecolorat($img, $x, $y);
            if ((($c >> 16) & 0xFF) < $t
             || (($c >>  8) & 0xFF) < $t
             || ( $c        & 0xFF) < $t) {
                return false;
            }
        }
        return true;
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function findGsBin(): ?string
    {
        foreach (['gswin64c', 'gswin32c', 'gs', 'ghostscript'] as $name) {
            if ($bin = $this->findBin($name)) return $bin;
        }
        return null;
    }

    private function findBin(string $name): ?string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $out = trim((string) shell_exec('where ' . escapeshellarg($name) . ' 2>NUL'));
            foreach (explode("\n", $out) as $line) {
                $line = trim($line);
                if ($line && file_exists($line)) return $line;
            }
            return null;
        }

        $out = trim((string) shell_exec('which ' . escapeshellarg($name) . ' 2>/dev/null'));
        if ($out && file_exists($out)) return $out;

        foreach (['/usr/bin/', '/usr/local/bin/', '/opt/homebrew/bin/'] as $dir) {
            $p = $dir . $name;
            if (file_exists($p) && is_executable($p)) return $p;
        }

        return null;
    }

    private function normPath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }

    private function deletePdfImages(string $slug): void
    {
        $dir = storage_path('app/public/blogs/pdf-images/' . $slug);
        if (!is_dir($dir)) return;
        foreach (glob($dir . '/*') ?: [] as $f) {
            if (is_file($f)) @unlink($f);
        }
        @rmdir($dir);
    }
}