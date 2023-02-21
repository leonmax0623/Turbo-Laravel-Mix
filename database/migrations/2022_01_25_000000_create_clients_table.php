<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->enum('gender', [Client::GENDER_MALE, Client::GENDER_FEMALE])->nullable();
            $table->text('address')->nullable();
            $table->string('passport')->nullable();
            $table->date('born_at')->nullable();
            $table->json('phones')->nullable();
            $table->json('emails')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreignId('department_id')->nullable()->constrained()
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
        Schema::dropIfExists('clients');
    }
}
