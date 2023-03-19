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
        Schema::create('platform_value_lists', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 36);
            $table->string('type',30);
            $table->string('code',30);
            $table->string('label',30);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_value_lists');
    }
};
