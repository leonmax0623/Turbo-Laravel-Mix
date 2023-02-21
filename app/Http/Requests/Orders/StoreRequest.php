<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'appeal_reason_id'      => 'nullable|integer|exists:appeal_reasons,id',
            'car_id'                => 'nullable|integer|exists:cars,id',
            'client_id'             => 'nullable|integer|exists:clients,id',
            'user_id'               => 'nullable|integer|exists:users,id',
            'department_id'         => 'nullable|integer|exists:departments,id',
            'order_stage_id'        => 'nullable|integer|exists:order_stages,id',
            'process_category_id'   => 'nullable|integer|exists:process_categories,id',
            'comment'               => 'nullable|string',
        ];
    }
}
