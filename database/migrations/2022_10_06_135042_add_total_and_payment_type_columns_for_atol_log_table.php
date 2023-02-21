<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalAndPaymentTypeColumnsForAtolLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('atol_logs', function (Blueprint $table) {
            $table->string('operation_type')->nullable();
            $table->string('status')->nullable();
            $table->integer('sum')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('atol_logs', function (Blueprint $table) {
            $table->dropColumn('operation_type');
            $table->dropColumn('status');
            $table->dropColumn('sum');
        });
    }
}
