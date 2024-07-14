<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {     
        if (!Schema::hasTable('etudiants')) {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->String('nni')->unique();
            $table->string('nomprenom');
            $table->string('diplome')->nullable();
            $table->string('genre');
            $table->string('lieunaissance')->nullable();
            $table->string('adress')->nullable();
            $table->date('datenaissance')->nullable();
            $table->string('email', 191)->unique()->nullable();
            $table->integer('phone');
            $table->integer('wtsp')->nullable();
            $table->foreignId('country_id')->constrained('countries');
            $table->timestamps();
        });
    }
    }
    public function down()
    {
        Schema::dropIfExists('etudiants');
    }
};
