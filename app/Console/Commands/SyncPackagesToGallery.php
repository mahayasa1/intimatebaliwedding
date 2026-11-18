<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Services\MediaSync;
use Illuminate\Console\Command;

class SyncPackagesToGallery extends Command
{
    protected $signature   = 'gallery:sync-packages';
    protected $description = 'Impor foto Package & Subpackage yang sudah ada ke Gallery (tanpa label NEW)';

    public function handle(): int
    {
        $count = 0;

        Package::with('subpackages')->each(function (Package $package) use (&$count) {
            MediaSync::syncAll($package, false);
            $count++;
        });

        $this->info("Selesai. {$count} package disinkronkan ke gallery.");

        return self::SUCCESS;
    }
}
