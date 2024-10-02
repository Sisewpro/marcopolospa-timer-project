<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timer_card_id')->constrained('timer_cards')->onDelete('cascade'); // Relasi ke tabel timer_cards
            $table->string('customer');          // Nama customer
            $table->string('therapist_name');    // Nama therapist
            $table->char('time', 8)->default('01:30:00');  // Waktu dalam format 00:00:00
            $table->enum('status', ['Ready', 'Running', 'Done']);  // Status timer
            $table->timestamps();                // Created at & updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rekaps');
    }
}