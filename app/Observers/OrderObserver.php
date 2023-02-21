<?php

namespace App\Observers;

use App\Models\Checkbox;
use App\Models\Order;
use App\Models\OrderProcessTask;
use App\Models\PipelineProcessTask;
use App\Models\PipelineTask;
use App\Models\ProcessCategory;
use App\Models\ProcessCheckbox;
use App\Models\Task;
use App\Models\TaskMap;
use App\Services\Tasks\TaskService;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class OrderObserver
{
    /**
     * Обрабатывать события после фиксирования всех транзакций.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * TaskController constructor.
     * @param  TaskService  $taskService
     */
    public function __construct(private TaskService $taskService, private Media $media) {}

    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $this->setTask($order);
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $this->setTask($order);
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }

    private function setTask(Order $order): void
    {
        $processTasks = $order->processCategory?->processTasks ?: [];

        foreach($processTasks as $processTask) {
            if ($order->processTasks()->pluck('process_task_id')->contains($processTask->id)) continue;

            $stageId = $processTask->order_stage_id;

            if ( $stageId === $order->orderStage()->first()->id ) {
                DB::transaction(function () use ($order, $processTask) {
                    $task = Task::create([
                        'name' => $processTask->name,
                        'description' => $processTask->description,
                        'status' => Task::STATUS_WAIT,
                        'start_at' => now(),
                        'deadline_at' => now()->add($processTask->time, 'minutes'),
                        'position' => $processTask->position,
                        'author_id' => id(),
                        'order_id' => $order->id,
                        'department_id' => $order->department_id,
                        'is_map' => $processTask->is_map,
                    ]);

                    OrderProcessTask::create([
                        'order_id' => $order->id,
                        'process_task_id' => $processTask->id,
                    ]);

                    $pipelineProcessTasks = PipelineProcessTask::where('process_task_id', $processTask->id)->get();

                    foreach($pipelineProcessTasks as $pipelineProcessTask) {
                        PipelineTask::create([
                            'pipeline_id' => $pipelineProcessTask->pipeline_id,
                            'stage_id' => $pipelineProcessTask->stage_id,
                            'task_id' => $task->id
                        ]);
                    }

                    if ($processTask->is_map && $processTask->map_id) {
                        TaskMap::query()->create([
                            'task_id' => $task->id,
                            'map_id' => $processTask->map_id,
                        ]);

                        return;
                    }

                    $processCheckboxes = ProcessCheckbox::where('process_task_id', $processTask->id)->get();

                    foreach($processCheckboxes as $processCheckbox) {
                        Checkbox::create([
                            'description' => $processCheckbox->description,
                            'is_checked' => $processCheckbox->is_checked,
                            'task_id' => $task->id,
                        ]);
                    }

                    $processFiles = $processTask->files()->orderBy('id')->get();

                    foreach ($processFiles as $processFile) {
                        try {
                            $task->addMedia($processFile->getPath())
                                ->usingName($processFile->file_name)
                                ->preservingOriginal()
                                ->toMediaCollection('files', Task::FILES_DISK);
                        } catch (Throwable $e) {
                            info('Не удалось добавить временный файл к задаче', [$e->getTraceAsString()]);
                        }
                    }
                });
            }
        }
    }
}
