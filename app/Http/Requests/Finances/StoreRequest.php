<?php

namespace App\Http\Requests\Finances;

use App\Models\Finance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'required|string',
            'operation_type' => [
                'required',
                'string',
                Rule::in([
                    Finance::OPERATION_SELL,
                    Finance::OPERATION_SELL_RETURN,
                    Finance::OPERATION_BUY,
                    Finance::OPERATION_BUY_RETURN
                ])
            ],
            'payment_type' => [
                'required',
                'string',
                Rule::in([
                    Finance::PAYMENT_CASH,
                    Finance::PAYMENT_ELECTRONICALLY
                ])
            ],
            'finance_group_id' => [
                in_array(request('operation_type'), [
                    Finance::OPERATION_BUY,
                    Finance::OPERATION_BUY_RETURN
                ]) ? 'required' : '',
                'nullable',
                'integer',
                Rule::exists('finance_groups', 'id')
            ],
            'sum' => [
                'required',
                'integer',
                'min:0'
            ],
            'department_id' => 'nullable|integer|exists:departments,id',
            'order_id' => [
                in_array(request('operation_type'), [
                    Finance::OPERATION_SELL,
                    Finance::OPERATION_SELL_RETURN
                ]) ? 'required' : '',
                'integer',
                'exists:orders,id'
            ]
        ];
    }
}
