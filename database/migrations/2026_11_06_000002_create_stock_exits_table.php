<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_exits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('product_name', 150);
            $table->unsignedInteger('quantity');
            $table->date('exit_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('exit_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_exits');
    }
};
