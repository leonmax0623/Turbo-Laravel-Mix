<?php

use App\Models\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdToPipelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->foreignIdFor(Department::class)
                ->after('type')
                ->nullable()->constrained()
                ->onUpdate('cascade')->onDelete('set null');
        });

        DB::table('pipelines')->update([
            'department_id' => Department::first()->id,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
    }
}
