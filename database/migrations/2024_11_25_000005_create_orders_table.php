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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Identifiant unique en UUID
            $table->uuid('client_id'); // Clé étrangère vers product_types
            $table->uuid('product_id');
            $table->date('deadline'); // Date limite pour la commande
            $table->integer('quantity'); // Quantité commandée
            $table->timestamps();

            // Définir la clé étrangère vers clients
            $table->foreign('client_id')
                ->references('id') // Correspond à la colonne id dans clients
                ->on('clients') // Nom correct de la table
                ->cascadeOnDelete(); // Suppression en cascade

            // Définir la clé étrangère vers products
            $table->foreign('product_id')
                ->references('id') // Correspond à la colonne id dans products
                ->on('products') // Nom correct de la table
                ->cascadeOnDelete(); // Suppression en cascade
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
