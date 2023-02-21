<?php

namespace App\Observers;

use App\Models\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BaseObserver
{

    public function updating(Model $model)
    {
        $data = $this->prepareData($model);

        $this->logging($model, $data, 'updated');
    }

    public function created($model)
    {
        $this->logging($model, $model->getAttributes(), 'created');
    }

    public function removing($model)
    {
    }

    public function saving($model)
    {
    }

    public function logging(Model $model, array $data, $type = 'logged'): void
    {
        try {

            if ($model->pipelineTasks?->count('task_id') > 0) {
                $data['pipelines'] = [];
                foreach ($model->pipelineTasks as $pipelineTask) {
                    $data['pipelines'][] = [
                        'pipeline_id' => $pipelineTask?->pipeline_id,
                        'stage_id' => $pipelineTask?->stage_id,
                    ];
                }
            }

            if (count($data) > 0) {
                ModelLog::query()->create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'data' => $data,
                    'type' => strtolower(data_get(explode('\\', get_class($model)), '2', 'model')) . '_' . $type,
                    'created_at' => now(),
                    'created_by' => auth()->id(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при сохранении лога модели: ' . get_class($model), [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);
        }
    }

    public function prepareData($model): array
    {

        $data = [];

        $originals = $model->getRawOriginal();

        unset($originals['id']);
        unset($originals['created_at']);
        unset($originals['updated_at']);

        foreach ($originals as $column => $original) {

            try {
                $attribute = $model->$column;

                // приравниваю типы данных по casts
                switch (true) {
                    case is_object($attribute) && get_class($attribute) === 'Illuminate\Support\Carbon':
                        $attribute = date('Y-m-d H:i:s', strtotime($attribute));
                        $original = date('Y-m-d H:i:s', strtotime($original));
                        break;
                    case is_int($original):
                        $attribute = (int)$attribute;
                        break;
                    case is_array($original):
                        $attribute = (array)$attribute;
                        break;
                    case is_bool($original):
                        $attribute = (bool)$attribute;
                        break;
                }

                if ($original !== $attribute) {
                    $data[$column] = $original;
                }
            } catch (\Throwable $e) {
                Log::error('Ошибка при логировании модели: ' . get_class($model), [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTrace(),
                ]);
            }

        }

        return $data;

    }
}
