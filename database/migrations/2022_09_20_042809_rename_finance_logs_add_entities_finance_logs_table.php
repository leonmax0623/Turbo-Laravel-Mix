<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFinanceLogsAddEntitiesFinanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('finance_logs', 'atol_logs');
        Schema::table('atol_logs', function (Blueprint $table) {
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->dropForeign('finance_logs_finance_id_foreign');
            $table->dropColumn('finance_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('atol_logs', 'finance_logs');
        Schema::table('atol_logs', function (Blueprint $table) {
            $table->dropColumn('entity_type');
            $table->dropColumn('entity_id');
            $table->unsignedBigInteger('finance_id');
            $table->foreign('finance_id')
                ->references('id')
                ->on('finances')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
