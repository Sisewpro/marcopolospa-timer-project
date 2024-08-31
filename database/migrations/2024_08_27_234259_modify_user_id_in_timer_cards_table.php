<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserIdInTimerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timer_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change(); // Mengubah user_id menjadi nullable
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
            $table->unsignedBigInteger('user_id')->nullable(false)->change(); // Mengembalikan user_id ke non-nullable jika rollback
        });
    }
}

