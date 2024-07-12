<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prof_session', function (Blueprint $table) {
            $table->foreignId('prof_id')->constrained('professeurs');
            $table->foreignId('session_id')->constrained('sessions');
            $table->date('date_paiement')->nullable();
            $table->timestamps();

            $table->primary(['prof_id', 'session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prof_session');
    }
};
