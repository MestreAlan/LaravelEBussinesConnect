<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalDataTable extends Migration
{
    public function up()
    {
        Schema::create('personal_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cpf')->unique();
            $table->timestamps(); // Isso automaticamente adiciona os campos 'created_at' e 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_data');
    }
}
