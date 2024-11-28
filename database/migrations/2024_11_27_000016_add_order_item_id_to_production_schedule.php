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
            // Ajoutez une colonne pour l'ID de l'OrderItem
            $table->uuid('order_item_id')->nullable();

            // Ajoutez la clé étrangère
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('production_schedule', function (Blueprint $table) {
            // Supprimez la colonne order_item_id si la migration est annulée
            $table->dropColumn('order_item_id');
        });
    }
};
