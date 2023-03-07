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
        Schema::create('integration_instances_categories', function (Blueprint $table) {
            $table->id();
            $table->string('integration_instance_id', 36);
            $table->string('content_id');
            $table->string('content_name');
            $table->string('parent_content_id');
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
        Schema::dropIfExists('integration_instances_categories');
    }
};
