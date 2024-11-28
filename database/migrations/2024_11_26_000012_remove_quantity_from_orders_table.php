<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveQuantityFromOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
        });
    }
}
