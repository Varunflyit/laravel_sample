<?php

namespace App\Http\Requests\V2\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\V2\GroupAttributeExists;

class AttributeGroupRequest extends FormRequest
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
            'attributes' => ['required', 'array',new GroupAttributeExists($this->company)]
        ];
    }
}
