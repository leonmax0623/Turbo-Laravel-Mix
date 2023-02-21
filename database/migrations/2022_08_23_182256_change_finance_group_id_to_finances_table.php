<?php

use App\Traits\DataBase\Migrations\DropIfExists;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFinanceGroupIdToFinancesTable extends Migration
{
    use DropIfExists;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dropIfExistsForeign('finances', 'finance_group_id');

        Schema::table('finances', function (Blueprint $table) {
            $table->foreignId('finance_group_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
