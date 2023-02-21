<?php

namespace App\Http\Requests\ProductRequests;

use App\Models\ProductRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
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
                'required',
                'string',
                Rule::in(ProductRequest::getStatuses())
            ],
        ];
    }
}
