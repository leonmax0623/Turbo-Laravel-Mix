<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipelineProcessTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_process_task', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('process_task_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('pipeline_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('stage_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');

            $table->unique(['process_task_id', 'pipeline_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipeline_process_task');
    }
}
