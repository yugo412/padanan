<?php

namespace App\Http\Requests\Word;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'origin' => ['required', 'string', 'max:200'],
            'locale' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'exists:categories,slug'],
        ];
    }
}
