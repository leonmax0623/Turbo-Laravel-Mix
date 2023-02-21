<?php

namespace App\Http\Requests\MapAnswers;

use App\Models\Map;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'task_id'                           => [
                'required',
                'integer',
                'exists:tasks,id',
                Rule::unique('map_answers', 'task_id')->ignore($this->map_answer),
            ],
            'data'                              => 'required|array',
//            'data.*.groups'                     => 'required|array',
//            'data.*.groups.*.title'             => 'required|string',
//            'data.*.groups.*.items'             => 'required|array',
//            'data.*.groups.*.items.*.name'      => 'required|string',
//            'data.*.groups.*.items.*.answer'    => 'required|string',
//            'data.*.groups.*.items.*.type'      => [
//                'required',
//                'string',
//                Rule::in(Map::types)
//            ]
        ];
    }
}
