<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseBodyToEndpoints extends Migration
{
    public function up()
    {
        Schema::table('endpoints', function (Blueprint $table) {
            $table->longText('response_body')->nullable();
        });
    }

    public function down()
    {
        Schema::table('endpoints', function (Blueprint $table) {
            $table->dropColumn('response_body');
        });
    }
}
