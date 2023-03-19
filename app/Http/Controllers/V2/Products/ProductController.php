<?php

namespace App\Http\Controllers\V2\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\Product\ProductRequest;
use App\Http\Resources\V2\Product\ProductCollection;
use App\Http\Resources\V2\Product\ProductResource;
use Ecommify\Core\Models\Company;
use Ecommify\Product\Models\Filters\ProductFilter;
use Ecommify\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Search product
     *
     * @param Company $company
     * @param Request $request
     * @return ProductCollection
     */
    public function search(Company $company, Request $request)
    {
        return new ProductCollection(
            $company->products()
                ->filter($request->all(), ProductFilter::class)
                ->paginate($request->get('size', 10))
        );
    }

    /**
     * Store product
     *
     * @param Company $company
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function store(Company $company, ProductRequest $request)
    {
        /** @var Product | null */
        $product = null;
        DB::transaction(function () use ($company, $request, &$product) {
            $productData = $request->except('attributes');
            $productData['attributes'] = json_encode($request->get('attributes', []));
            $product = $company->products()->create($productData);
        });

        return new ProductResource(
            $product
        );
    }

    /**
     * Update product
     *
     * @param Company $company
     * @param Product $product
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function update(Company $company, Product $product, ProductRequest $request)
    {
        DB::transaction(function () use ($company, $request) {
            $productData = $request->except('attributes');
            $productData['attributes'] = json_encode($request->get('attributes', []));
            $company->products()->where('id',$product->id)->update($productData);
        });

        return new ProductResource(
            $company->products()->find($product->id)
        );
    }
}
