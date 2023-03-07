<?php

namespace App\Console\Commands;

use Ecommify\Core\Platform\Facades\Platform;
use Ecommify\Core\Models\IntegrationInstance;
use Ecommify\Integration\Concerns\PlatformAttributeFinder;
use Ecommify\Maropost\OAuth;
use Ecommify\MaropostMirakl\Service;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class TestIntegration extends Command
{
    use PlatformAttributeFinder;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:integration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = json_decode('{"Ack": "Success", "Order": [{"ID": "N180710", "OrderID": "N180710", "OrderLine": [{"SKU": "61008", "Quantity": "1", "OrderLineID": "N180710-0", "QuantityShipped": "1", "ShippingTracking": "34AP86034851", "ShippingServiceID": "46", "ShippingServiceName": "FLAT_RATE_AUSPOST_EPARCEL", "ExternalOrderReference": "M3F-679323", "ExternalOrderLineReference": "M3F-679323"}], "OrderStatus": "Dispatched", "DateCompletedUTC": "2023-02-20 02:20:33", "PurchaseOrderNumber": "679323"}], "CurrentTime": "2023-02-20 10:05:32"}', true);


        $integrationInstance = IntegrationInstance::where('integration_instance_id', 'L1pTT16b7j')->first();

        // $attributes = $integrationInstance->instanceAttributes
        //     ->pluck('attribute_value', 'attribute_key')
        //     ->toArray();

        // $a = Platform::driver('marketplacer_myer')->attributes($attributes);

        // $order = ($a->getOrder('680782'));

        // dd($this->getOrderStatus($order, $a));

        $attributes = $integrationInstance->instanceAttributes
            ->pluck('attribute_value', 'attribute_key')
            ->toArray();

        $a = Platform::driver('mirakl_bunnings')->attributes($attributes);

        $order = ($a->getOrder('W225871824-A'));

        // dd($this->getOrderStatus($order, $a));
        // // dd($a->getOrder(680355)['data']['attributes']['status']);
        // dd(
        //     Arr::get($a->getOrder(680355), 'data.attributes.status')
        // );

        $attributes = $integrationInstance->sourceInstance->instanceAttributes
            ->pluck('attribute_value', 'attribute_key')
            ->toArray();

        $a = Platform::driver('maropost')->attributes($attributes);

        $data = $a->getOrder('N180809');
        dd(
            $this->getOrderStatus($data, $a)
        );

        // dd($a->getOrders([
        //     'Filter' => [
        //         'OrderStatus' => 'New',
        //         'OrderID' => ['N2688', 'N2689'],
        //         'OutputSelector' => ['OrderID', 'GrandTotal', 'DateInvoiced']
        //     ]
        // ]));

        // // dd($a->getOrders());

        dd(
            (new Service($integrationInstance))->syncOrders()
        );

        return Command::SUCCESS;
    }
}
