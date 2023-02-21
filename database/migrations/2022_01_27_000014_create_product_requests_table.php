<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProductRequest;

class CreateProductRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('count');
            $table->string('status', 50)->default(ProductRequest::STATUS_WAIT);
            $table->dateTime('date_issue')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('product_id')->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('product_requests');
    }
}
