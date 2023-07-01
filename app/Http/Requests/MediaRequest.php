<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string',
            'visibility' => 'required|in:public,private',
            'description' => 'required|string',
            'tags' => 'nullable|array',
            'comments_enabled' => 'required|in:true,false',
        ];

        if ($this->getMethod() === self::METHOD_PATCH) {
            return $rules;
        }

        return array_merge($rules, [
            'file' => 'required|mimetypes:audio/mp3,audio/mpeg,video/mp4,video/x-msvideo|max:50000',
        ]);
    }
}
