<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendEndpointsTableForCustomResponses extends Migration
{
    public function up()
    {
        Schema::table('endpoints', function (Blueprint $table) {
            $table->integer('status_code')->default(200);
            $table->string('status_message', 50)->nullable();
            $table->text('headers_json')->nullable();
        });
    }

    public function down()
    {
        Schema::table('endpoints', function (Blueprint $table) {
            $table->dropColumn('status_code');
            $table->dropColumn('status_message');
            $table->dropColumn('headers_json');
        });
    }
}
