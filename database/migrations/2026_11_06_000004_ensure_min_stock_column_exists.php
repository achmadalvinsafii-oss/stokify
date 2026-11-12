<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            throw new RuntimeException('Tabel products belum tersedia. Jalankan migration tabel produk terlebih dahulu.');
        }

        if (! Schema::hasColumn('products', 'min_stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedInteger('min_stock')->default(10);
            });
        }
    }

    public function down(): void
    {
        // Keep the repair migration non-destructive; the original migration owns this column.
    }
};
