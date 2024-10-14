<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartEndTimeToTimerCardsTable extends Migration
{
    public function up()
    {
        Schema::table('timer_cards', function (Blueprint $table) {
            $table->timestamp('start_time')->nullable()->after('status');
            $table->timestamp('end_time')->nullable()->after('start_time');
        });
    }

    public function down()
    {
        Schema::table('timer_cards', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
}
