<?php

namespace App\Http\Requests\OrderStages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'color' => 'required|string',
        ];
    }
}
