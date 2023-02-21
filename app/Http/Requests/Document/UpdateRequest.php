<?php

namespace App\Http\Requests\Document;

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
            'name' => [
                'required',
                'string',
            ],
            'comments'              => 'string',
            'document_template_id'  => 'required|integer|exists:document_templates,id',
            'order_id'              => 'required|integer|exists:orders,id'
        ];
    }
}
