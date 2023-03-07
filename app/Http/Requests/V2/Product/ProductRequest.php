<?php

namespace App\Http\Requests\V2\Product;

use App\Rules\V2\ProductAttributeExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'sku' => ['required', 'string'],
            'attributes' => ['nullable', new ProductAttributeExists($this->company)]
        ];
    }
}
