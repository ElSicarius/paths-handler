<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndpointsTable extends Migration
{
    public function up()
    {
        Schema::create('endpoints', function (Blueprint $table) {
            $table->id();
            $table->string('path');      // ex: "/v1/utilities"
            $table->string('method');    // GET, POST, etc.
            $table->boolean('is_active')->default(true);
            $table->text('params_json')->nullable();   // JSON pour gérer param -> réponses
            $table->text('response_json')->nullable(); // Réponse(s) JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('endpoints');
    }
}
