<?php

namespace App\Http\Requests\Stages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStageOrdersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ids'   => 'array|nullable',
            'ids.*' => 'required|int',
        ];
    }
}
