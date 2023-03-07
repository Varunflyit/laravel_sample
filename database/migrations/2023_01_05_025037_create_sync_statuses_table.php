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
        Schema::create('sync_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('company_id', 36);
            $table->string('integration_instance_id', 36);
            $table->enum('type', ['product', 'tracking', 'inventory', 'order']);
            $table->dateTime('last_synced_at');
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
        Schema::dropIfExists('sync_statuses');
    }
};
