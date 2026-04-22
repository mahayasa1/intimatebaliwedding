<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subpackages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('name');
            $table->text('image')->nullable();
            $table->json('photo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subpackages');
    }
};