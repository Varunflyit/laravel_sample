<?php

namespace App\Http\Controllers\V2\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\Product\AttributeGroupRequest;
use App\Http\Resources\V2\Base\BaseCollection;
use App\Http\Resources\V2\Base\JsonResource;
use App\Http\Resources\V2\Product\AttributeCollection;
use App\Http\Resources\V2\Product\AttributeResource;
use Ecommify\Core\Models\Company;
use Ecommify\Product\Models\AttributeGroup;
use Ecommify\Product\Models\Filters\AttributeFilter;
use Illuminate\Http\Request;

class AttributeGroupController extends Controller
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
        BaseCollection::wrap('attribute_groups');

        return new BaseCollection(
            $company->attributeGroups()->paginate($request->get('size', 10))
        );
    }

    /**
     * Store product attribute
     *
     * @param Company $company
     * @param AttributeGroupRequest $request
     * @return AttributeResource
     */
    public function store(Company $company, AttributeGroupRequest $request)
    {
        return new JsonResource(
            $company->attributeGroups()->create($request->all())
        );
    }

    /**
     * Update product attribute
     *
     * @param Company $company
     * @param AttributeGroup $attributeGroup
     * @param AttributeGroupRequest $request
     * @return AttributeResource
     */
    public function update(Company $company, AttributeGroup $attributeGroup, AttributeGroupRequest $request)
    {
        $company->attributeGroups()->where('id',$attributeGroup->id)->update($request->all());
        return new JsonResource(
            $company->attributeGroups()->find($attributeGroup->id)
        );
    }
}
