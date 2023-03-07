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
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->dropColumn('source_payload');
            $table->string('transactionable_type')->after('type');
            $table->string('transactionable_id')->after('type');
            $table->json('payload')->nullable()->change();
            $table->json('response')->nullable()->change();
            $table->string('message')->nullable()->after('response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->json('source_payload')->nullable();
            $table->dropColumn('transactionable_type');
            $table->dropColumn('transactionable_id');
            $table->dropColumn('message');
        });
    }
};
