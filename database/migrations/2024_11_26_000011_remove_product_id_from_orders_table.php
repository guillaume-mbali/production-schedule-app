<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductIdFromOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Supprime d'abord la clé étrangère
            $table->dropForeign(['product_id']);

            // Supprime ensuite la colonne
            $table->dropColumn('product_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ajoute la colonne avec la clé étrangère si on revient en arrière
            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
}
