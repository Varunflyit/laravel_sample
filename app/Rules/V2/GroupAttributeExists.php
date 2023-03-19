<?php

namespace App\Rules\V2;

use Ecommify\Core\Models\Company;
use Illuminate\Contracts\Validation\Rule;

class GroupAttributeExists implements Rule
{
    /**
     * List of missing attributes
     *
     * @var array
     */
    protected $missingAttributes;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public Company $company)
    {
        //
    }
   
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $attributeKeys = $value;

        $companyAttributes = $this->company->attributes();
        $companyAttributes = $companyAttributes->whereIn('id', $attributeKeys)
                                ->pluck('id')
                                ->toArray();
        $this->missingAttributes = array_diff($attributeKeys, $companyAttributes);

        return empty($this->missingAttributes);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        
        return array_map(function ($attribute) {
            return trans('app.product_attribute_not_exists', ['attribute' => $attribute]);
        }, $this->missingAttributes);
        
    }
}
