<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class AssignUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tasks' => 'required|array',
            'tasks.*.user_id' => 'required|nullable|integer',
            'tasks.*.task_id' => 'required|nullable|integer',
        ];
    }
}
