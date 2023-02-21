<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->unsignedSmallInteger('time')->nullable();
            $table->unsignedInteger('position');
            $table->boolean('is_map')->default(false)->comment('Шаблон диагностической карты');
            $table->timestamps();

            $table->foreignId('order_stage_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('role_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('map_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_tasks');
    }
}
