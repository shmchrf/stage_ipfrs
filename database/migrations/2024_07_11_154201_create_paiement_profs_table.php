<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('paiement_profs')) {
            Schema::create('paiement_profs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('prof_id')->constrained('professeurs')->onDelete('cascade');
                $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
                $table->foreignId('mode_paiement_id')->constrained('modes_paiement');
                $table->foreignId('typeymntprofs_id')->constrained('typeymntprofs');
                $table->integer('montant');
                $table->integer('montant_a_paye');
                $table->integer('montant_paye');
                $table->date('date_paiement')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('paiement_profs');
    }
};
