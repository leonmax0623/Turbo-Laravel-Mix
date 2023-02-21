<?php

namespace App\Http\Requests\Maps;

use App\Models\Map;
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
            'title'                         => 'required|string',
            'data'                          => 'required|array',
//            'data.*.groups'                 => 'required|array',
//            'data.*.groups.*.title'         => 'required|string',
//            'data.*.groups.*.items'         => 'required|array',
//            'data.*.groups.*.items.*.name'  => 'required|string',
//            'data.*.groups.*.items.*.type'  => [
//                'required',
//                'string',
//                Rule::in(Map::types)
//            ]
        ];
    }
}
