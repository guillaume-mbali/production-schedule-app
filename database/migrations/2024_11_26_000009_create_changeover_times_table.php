<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('changeover_times', function (Blueprint $table) {
            $table->id();
            $table->uuid('from_product_type_id'); // Type d'origine
            $table->uuid('to_product_type_id');   // Type de destination
            $table->integer('changeover_time');   // Temps de changement en minutes
            $table->timestamps();

            // Clés étrangères
            $table->foreign('from_product_type_id')->references('id')->on('product_types')->cascadeOnDelete();
            $table->foreign('to_product_type_id')->references('id')->on('product_types')->cascadeOnDelete();

            // Empêcher les doublons
            $table->unique(['from_product_type_id', 'to_product_type_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('changeover_times');
    }
};
