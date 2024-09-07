<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('timer_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_name'); // Nama card
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Relasi ke tabel users, nullable jika staff belum dipilih
            $table->string('customer')->nullable(); // Nama pelanggan opsional
            $table->char('time', 8)->default('01:30:00'); // Waktu dalam format 00:00:00, default 1 jam 30 menit
            $table->string('status')->default('Ready'); // Status card, default 'Ready'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timer_cards');
    }
};
