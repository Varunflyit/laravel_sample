<?php

namespace App\Http\Controllers\V2\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\Dashboard\IntegrationStatusCollection;
use Ecommify\Core\Models\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IntegrationStatusController extends Controller
{
    /**
     * Integration status dashboard
     *
     * @param Company $company
     * @param Request $request
     * @return IntegrationStatusCollection
     */
    public function __invoke(Company $company, Request $request)
    {
        // TODO: Quick fix. extend default Laravel pagination
        $request->merge([
            'page' => $request->input('page', 0) + 1
        ]);

        $dashboard = $company->integrationInstances()
            ->whereHas('integration')
            ->withCount([
                'orders as total_orders_last_7' => fn (Builder $builder) => $builder->lastNdays(7),
                'orders as total_orders_last_14' => fn (Builder $builder) => $builder->lastNdays(14),
                'orders as total_orders_last_30' => fn (Builder $builder) => $builder->lastNdays(30),
                'trackings as trackings_last_7' => fn (Builder $builder) => $builder->lastNdays(7),
                'trackings as trackings_last_14' => fn (Builder $builder) => $builder->lastNdays(14),
                'trackings as trackings_last_30' => fn (Builder $builder) => $builder->lastNdays(30),
                'orders as failed_orders' => fn (Builder $builder) => $builder->failedOrderSync(),
                'orders as failed_trackings' => fn (Builder $builder) => $builder->failedTrackingSync()
            ])
            ->withSum(
                ['orders as sum_orders_last_7' => fn (Builder $builder) => $builder->lastNdays(7)],
                'grand_total'
            )
            ->withSum(
                ['orders as sum_orders_last_14' => fn (Builder $builder) => $builder->lastNdays(14)],
                'grand_total'
            )
            ->withSum(
                ['orders as sum_orders_last_30' => fn (Builder $builder) => $builder->lastNdays(30)],
                'grand_total'
            )
            ->paginate($request->get('size', 10));

        return new IntegrationStatusCollection($dashboard);
    }
}
