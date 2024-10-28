<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timer_cards', function (Blueprint $table) {
            $table->foreignId('therapist_id')->nullable()->constrained('therapists')->onDelete('set null'); // Menambahkan relasi therapist_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timer_cards', function (Blueprint $table) {
            $table->dropForeign(['therapist_id']); // Hapus foreign key constraint
            $table->dropColumn('therapist_id'); // Hapus kolom therapist_id
        });
    }
};
