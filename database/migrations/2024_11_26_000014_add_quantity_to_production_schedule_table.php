<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_schedule', function (Blueprint $table) {
            // Ajout de la colonne 'quantity'
            $table->integer('quantity')->default(1);  // Vous pouvez ajuster le type si nécessaire
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_schedule', function (Blueprint $table) {
            // Suppression de la colonne 'quantity' si la migration est annulée
            $table->dropColumn('quantity');
        });
    }
};
