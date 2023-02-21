<?php

namespace App\Http\Requests\Storages;

use App\Exceptions\CustomValidationException;
use App\Models\Storage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * @throws CustomValidationException
     */
    protected function prepareForValidation()
    {
        if (isset($this->name)) {
            if (Storage::where('name', $this->name)->where('department_id', Auth::user()->department_id)->exists()) {
                throw new CustomValidationException('В вашем филиале склад с указанным названием уже существует', 422);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string'
            ],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')
            ]
        ];
    }
}
