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
       Schema::create('products', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('name');
        $table->text('description')->nullable();
        $table->uuid('product_type_id'); // Clé étrangère vers product_types
        $table->string('image')->nullable();
        $table->timestamps();

        // Clé étrangère vers product_types
        $table->foreign('product_type_id')
            ->references('id')
            ->on('product_types')
            ->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
