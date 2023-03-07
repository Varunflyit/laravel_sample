<?php

namespace App\Console\Commands\Patch;

use Ecommify\Integration\Models\Order;
use Illuminate\Console\Command;

class AddIntegrationIdOnOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:add_integration_id_on_orders';

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
        foreach (Order::all() as $order) {
            $order->update(['integration_id' => $order->integrationInstance->integration_id]);
        }

        return Command::SUCCESS;
    }
}
