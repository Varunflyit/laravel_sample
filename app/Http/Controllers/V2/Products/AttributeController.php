<?php

namespace App\Http\Controllers\V2\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\Product\ProductAttributeRequest;
use App\Http\Resources\V2\Product\AttributeCollection;
use App\Http\Resources\V2\Product\AttributeResource;
use Ecommify\Core\Models\Company;
use Ecommify\Product\Models\Attribute;
use Ecommify\Product\Models\Filters\AttributeFilter;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Search attribute
     *
     * @param Company $company
     * @param Request $request
     * @return AttributeCollection
     */
    public function search(Company $company, Request $request)
    {
        return new AttributeCollection(
            $company->attributes()->filter($request->all(), AttributeFilter::class)->paginate($request->get('size', 10))
        );
    }

    /**
     * Store product attribute
     *
     * @param Company $company
     * @param ProductAttributeRequest $request
     * @return AttributeResource
     */
    public function store(Company $company, ProductAttributeRequest $request)
    {
        return new AttributeResource(
            $company->attributes()->create($request->all())
        );
    }
    /**
     * Update product attribute
     *
     * @param Company $company
     * @param Attribute $attribute
     * @param ProductAttributeRequest $request
     * @return AttributeResource
     */
    public function update(Company $company, Attribute $attribute, ProductAttributeRequest $request)
    {
        $company->attributes()->where('id',$attribute->id)->update($request->all());
        return new AttributeResource(
            $company->attributes()->find($attribute->id)
        );
    }
}
