<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // Nom du client
            $table->string('address'); // Adresse
            $table->string('postal_code'); // Code postal
            $table->string('city'); // Ville
            $table->string('country'); // Pays
            $table->string('email')->unique(); // Courriel
            $table->string('phone_number'); // Numéro de téléphone
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
