<?php

namespace App\Http\Requests\ProcessTasks;

use App\Exceptions\CustomValidationException;
use App\Models\Pipeline;
use App\Models\Stage;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
     /**
     * @throws CustomValidationException
     */
    protected function prepareForValidation()
    {
        foreach(data_get($this, 'pipelines', []) as $pipeline) {
            if(!Pipeline::where('id', data_get($pipeline, 'pipeline_id'))->exists()) {
                throw new CustomValidationException("Такой воронки не существует", 422);
            }
            if(data_get($pipeline, 'stage_id') && !Stage::where('pipeline_id', data_get($pipeline, 'pipeline_id'))->where('id', data_get($pipeline, 'stage_id'))->exists()) {
                throw new CustomValidationException("У данной воронки нет данного этапа", 422);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'role_id' => 'required|integer|exists:roles,id',
            'time' => 'nullable|integer',
            'position' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'order_stage_id' => 'required|integer|exists:order_stages,id',
            'pipelines' => 'array',
            'pipelines.*.pipeline_id' => 'required|integer|exists:pipelines,id',
            'pipelines.*.stage_id' => 'nullable|integer|exists:stages,id',
            'process_checkboxes' => 'nullable|array',
            'process_categories' => 'array',
            'process_categories.*' => 'integer|exists:process_categories,id',
            'is_map' => 'boolean',
            'map_id'                    => [
                request('is_map') ? 'required' : '',
                'integer',
                'exists:maps,id'
            ],
            'temp_file_ids' => 'nullable|array',
            'temp_file_ids.*' => 'integer'
        ];
    }
}
