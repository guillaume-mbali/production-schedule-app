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
        Schema::create('production_schedule', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->uuid('order_id');
            $table->timestamps();

            // Clé étrangère vers la table orders
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
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
        Schema::dropIfExists('production_schedule');
    }
};
