<?php

namespace App\Http\Requests\Stages;

use Illuminate\Foundation\Http\FormRequest;

class PipelineUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'pipeline_id' => 'required|integer|exists:pipelines,id',
            'user_id'     => 'required|integer|exists:users,id',
        ];
    }
}
