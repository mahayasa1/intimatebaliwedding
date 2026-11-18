<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // 'manual'      = dibuat langsung dari menu Gallery
            // 'package'     = mirror otomatis dari sebuah Package
            // 'subpackage'  = mirror otomatis dari sebuah Subpackage
            $table->string('source')->default('manual')->after('order');

            // Penanda sumber (hanya terisi untuk mirror otomatis)
            $table->uuid('package_id')->nullable()->after('source')->index();
            $table->uuid('subpackage_id')->nullable()->after('package_id')->index();

            // Untuk item manual yang di-include ke package/subpackage:
            // {"package_id": "...", "include_package": true, "subpackage_ids": ["..."]}
            $table->json('linked_targets')->nullable()->after('subpackage_id');

            // Label NEW tampil selama new_until masih di masa depan
            $table->timestamp('new_until')->nullable()->after('linked_targets');
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn([
                'source', 'package_id', 'subpackage_id', 'linked_targets', 'new_until',
            ]);
        });
    }
};
