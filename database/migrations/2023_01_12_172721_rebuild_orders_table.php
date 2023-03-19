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
        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('integration_instance_id');
            $table->string('channel_id')->nullable();
            $table->string('source_id')->nullable();
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->string('sync_status', 10)->nullable();
            $table->string('message')->nullable();
            $table->boolean('action_required')->default(false);
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
        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('integration_instance_id', 36);
            $table->string('channel_id');
            $table->string('source_id')->nullable();
            $table->json('channel_payload');
            $table->json('source_payload');
            $table->json('source_response');
            $table->string('sync_status')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }
};
