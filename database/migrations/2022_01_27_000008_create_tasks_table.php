<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 50);
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->date('deadline_at')->nullable();
            $table->unsignedInteger('position');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->boolean('is_map')->default(false)->comment('Диагностическая карта');
            $table->timestamps();

            $table->foreignId('department_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
