<?php

namespace App\Http\Requests\ProductRequests;

use App\Models\ProductRequest;
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
            'status' => [
                'string',
                Rule::in(ProductRequest::getStatuses())
            ],
            'count'         => 'required|integer',
            'sum'           => 'required|integer',
            'order_id'      => 'nullable|integer|exists:orders,id',
            'product_id'    => 'required|integer|exists:products,id',
        ];
    }
}
