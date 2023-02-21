<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreignId('department_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('appeal_reason_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('car_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('client_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('process_category_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('order_stage_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
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
    }
}
