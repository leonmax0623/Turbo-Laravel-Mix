<?php

namespace App\Http\Requests\Products;

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
            'name'        => 'required|string',
            'place'       => 'nullable|string',
            'count'       => 'required|integer|min:0',
            'input_sum'   => 'nullable|integer',
            'output_sum'  => 'nullable|integer',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:10000',
            'sku'         => 'nullable|string',
            'storage_id'  => 'required|integer|exists:storages,id',
            'user_id'     => 'nullable|integer|exists:users,id',
            'producer_id' => 'nullable|integer|exists:producers,id',
        ];
    }
}
