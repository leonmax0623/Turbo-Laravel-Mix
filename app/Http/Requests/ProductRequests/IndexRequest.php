<?php

namespace App\Http\Requests\ProductRequests;

use App\Models\ProductRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
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
            'order_id'      => 'nullable|integer|exists:orders,id',
        ];
    }
}
