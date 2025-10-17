<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel closures.
     */
    public function up(): void
    {
        Schema::create('closures', function (Blueprint $table) {
            $table->id();
            $table->text('reason');
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->foreignId('attraction_id')->nullable()->constrained('attractions')->nullOnDelete();
            $table->timestamps();

            $table->index(['attraction_id', 'start_at']);
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('closures');
    }
};
