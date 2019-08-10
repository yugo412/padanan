<?php

namespace App\Http\Requests\Word;

use App\Models\Category;
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
        $default = Category::whereIsDefault(true)->first();

        return [
            'origin' => ['required', 'string', 'max:200'],
            'locale' => ['required', 'string', 'max:200'],
            'category' => [empty($default) ? 'required' : 'nullable', 'string', 'exists:categories,slug'],
            'source' => ['nullable', 'string', 'max:300'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'category' => __('"bidang"'),
            'origin' => __('istilah asing'),
            'locale' => __('padanan'),
            'source' => __('sumber'),
        ];
    }
}
