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
            $table->renameColumn('ContentID', 'content_id');
            $table->renameColumn('PlatformID', 'platform_id');
            $table->renameColumn('ContentName', 'content_name');
            $table->renameColumn('ContentType', 'content_type');
            $table->renameColumn('ParentContentID', 'parent_content_id');
            $table->renameColumn('Active', 'active');
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
            $table->renameColumn('content_id','ContentID');
            $table->renameColumn('platform_id','PlatformID');
            $table->renameColumn('content_name','ContentName');
            $table->renameColumn('content_type','ContentType');
            $table->renameColumn('parent_content_id','ParentContentID');
            $table->renameColumn('active','Active');
        });
    }
};
