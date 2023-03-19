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
        Schema::table('source_instances_categories', function (Blueprint $table) {
            $table->dropColumn('platform_id');
            $table->dropColumn('content_type');
            $table->dropColumn('active');
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
        Schema::table('source_instances_categories', function (Blueprint $table) {
            $table->string('PlatformID');
            $table->string('ContentType');
            $table->string('Active');
            $table->timestamps();
        });
    }
};
