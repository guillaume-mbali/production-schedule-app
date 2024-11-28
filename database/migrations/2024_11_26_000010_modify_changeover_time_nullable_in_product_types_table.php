<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->decimal('changeover_time', 8, 2)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->decimal('changeover_time', 8, 2)->nullable(false)->change();
        });
    }
};
