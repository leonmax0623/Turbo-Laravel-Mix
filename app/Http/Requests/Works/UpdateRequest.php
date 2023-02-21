<?php

namespace App\Http\Requests\Works;

use Illuminate\Foundation\Http\FormRequest;

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
            'name'      => 'required|string',
            'order_id'  => 'required|integer|exists:orders,id',
            'user_id'   => 'required|integer|exists:users,id',
            'comments'  => 'nullable|string',
            'sum'       => 'nullable|integer',
            'time'      => 'nullable|integer',
        ];
    }
}
