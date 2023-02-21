<?php

use App\Models\Pipeline;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipelineUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_user', function (Blueprint $table) {
            $table->foreignIdFor(Pipeline::class)
                ->constrained()
                ->onDelete('cascade');

            $table->foreignIdFor(User::class)
                ->constrained()
                ->onDelete('cascade');

            $table->index('pipeline_id');
            $table->index('user_id');
        });

        Pipeline::query()
            ->with('tasks')
            ->where('type', '<>', 'personal')
            ->get()
            ->each(function (Pipeline $pipeline) {
                $userIds = [];

                $pipeline->tasks->each(function (Task $task) use (&$userIds) {
                    if ($task->user_id) {
                        $userIds[] = $task->user_id;
                    }
                    if ($task->author_id) {
                        $userIds[] = $task->author_id;
                    }
                });

                if (count($userIds)) {
                    $userIds = array_unique($userIds);

                    $pipeline->users()->sync($userIds);
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipeline_user');
    }
}
