<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatchedColumnToLogs extends Migration
{
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->boolean('matched')->default(false);
        });
    }

    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn('matched');
        });
    }
}
