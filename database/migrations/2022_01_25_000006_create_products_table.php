<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('place', 100)->nullable();
            $table->unsignedInteger('count');
            $table->unsignedInteger('input_sum')->nullable();
            $table->unsignedInteger('output_sum')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('sku')->nullable()->comment('артикул');
            $table->timestamps();

            $table->foreignId('user_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('storage_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('producer_id')->nullable()->constrained()
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
        Schema::dropIfExists('products');
    }
}
