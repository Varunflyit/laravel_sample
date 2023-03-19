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
        Schema::create('integration_instances_value_lists', function (Blueprint $table) {
            $table->id();
            $table->string('integration_instance_id', 36);
            $table->string('type',30);
            $table->string('code',30);
            $table->string('label',30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integration_instances_value_lists');
    }
};
