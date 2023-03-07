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
        Schema::table('source_instances_value_lists', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('integration_instances_categories', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('integration_instances_value_lists', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('source_instances_value_lists', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('integration_instances_categories', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('integration_instances_value_lists', function (Blueprint $table) {
            $table->timestamps();
        });
    }
};
