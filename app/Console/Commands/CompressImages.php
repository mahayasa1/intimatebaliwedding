<?php

namespace App\Console\Commands;

use App\Helpers\ImageHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompressImages extends Command
{
    protected $signature = 'images:compress
                            {--dir=* : Direktori spesifik di storage/app/public (kosong = semua)}
                            {--dry-run : Tampilkan daftar file tanpa kompresi}
                            {--thumbs-only : Hanya buat ulang thumbnail, tidak kompres asli}';

    protected $description = 'Kompres & convert semua gambar di storage/public ke WebP';

    /** Extension yang diproses */
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public function handle(): int
    {
        $dirs      = $this->option('dir') ?: ['galleries', 'gallery', 'packages', 'blogs'];
        $dryRun    = $this->option('dry-run');
        $thumbsOnly = $this->option('thumbs-only');

        if (!extension_loaded('gd')) {
            $this->error('PHP GD extension tidak tersedia. Install dulu: sudo apt-get install php-gd');
            return Command::FAILURE;
        }

        $this->info('=== Image Compressor ===');
        if ($dryRun) {
            $this->warn('Mode DRY-RUN — tidak ada perubahan.');
        }

        $total   = 0;
        $saved   = 0; // bytes
        $errors  = 0;

        foreach ($dirs as $dir) {
            $this->line("\n📁 Scanning: {$dir}");

            $files = Storage::disk('public')->allFiles($dir);
            $files = array_filter($files, fn($f) => $this->isImage($f) && !str_contains($f, '/thumbs/'));

            if (empty($files)) {
                $this->line('   (tidak ada gambar)');
                continue;
            }

            $bar = $this->output->createProgressBar(count($files));
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
            $bar->setMessage('mulai…');
            $bar->start();

            foreach ($files as $file) {
                $bar->setMessage(basename($file));

                $fullPath = Storage::disk('public')->path($file);
                $sizeBefore = file_exists($fullPath) ? filesize($fullPath) : 0;

                if (!$dryRun) {
                    try {
                        if ($thumbsOnly) {
                            ImageHelper::createThumbnail($file);
                        } else {
                            $newPath = ImageHelper::compress($file);
                            ImageHelper::createThumbnail($newPath);
                        }

                        $newFull = Storage::disk('public')->path($thumbsOnly ? $file : ImageHelper::thumb($file));
                        $sizeAfter = file_exists($newFull) ? filesize($newFull) : $sizeBefore;
                        $saved += max(0, $sizeBefore - $sizeAfter);
                        $total++;
                    } catch (\Throwable $e) {
                        $this->newLine();
                        $this->warn("  ⚠ Error [{$file}]: " . $e->getMessage());
                        $errors++;
                    }
                } else {
                    $this->newLine();
                    $this->line("   → {$file} (" . $this->humanBytes($sizeBefore) . ')');
                    $total++;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
        }

        $this->newLine();
        $this->info("✅ Selesai: {$total} gambar diproses | " . $this->humanBytes($saved) . ' dihemat | ' . $errors . ' error');

        return Command::SUCCESS;
    }

    private function isImage(string $path): bool
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, self::EXTENSIONS, true);
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes < 1024)       return "{$bytes} B";
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}