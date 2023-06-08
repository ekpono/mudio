<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'flag_id' => 'required|exists:flag_types,id',
            'reason' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'flag_id.required' => 'Please select a category',
            'reason.required' => 'Please provide additional details',
        ];
    }
}
