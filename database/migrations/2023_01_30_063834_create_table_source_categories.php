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
        Schema::create('source_instances_categories', function (Blueprint $table) {
            $table->id();
            $table->string('source_instance_id', 36);
            $table->string('ContentID');
            $table->string('PlatformID');
            $table->string('ContentName');
            $table->string('ContentType');
            $table->string('ParentContentID');
            $table->string('Active');
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
        Schema::dropIfExists('source_instances_categories');
    }
};
