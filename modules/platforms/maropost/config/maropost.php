<?php

return [
    'api' => [
        'v1_base_url' => env('MAROPOST_V1_BASE_URL', 'https://api.netodev.com/v1/stores/%s/do/WS/NetoAPI'),
        'v2_base_url' => env('MAROPOST_V2_BASE_URL', 'https://api.netodev.com/v2/stores/%s'),
    ],

    'output_selector' => [
        'order' => [
            'ShippingOption',
            'DeliveryInstruction',
            'Username',
            'Email',
            'ShipAddress',
            'BillAddress',
            'CustomerRef1',
            'CustomerRef2',
            'CustomerRef3',
            'CustomerRef4',
            'SalesChannel',
            'GrandTotal',
            'ShippingTotal',
            'ShippingDiscount',
            'OrderType',
            'OrderStatus',
            'OrderPayment',
            'OrderPayment.PaymentType',
            'OrderPayment.DatePaid',
            'DatePlaced',
            'DateRequired',
            'DateInvoiced',
            'DatePaid',
            'OrderLine',
            'OrderLine.ProductName',
            'OrderLine.PickQuantity',
            'OrderLine.BackorderQuantity',
            'OrderLine.UnitPrice',
            'OrderLine.WarehouseID',
            'OrderLine.WarehouseName',
            'OrderLine.WarehouseReference',
            'OrderLine.Quantity',
            'OrderLine.PercentDiscount',
            'OrderLine.ProductDiscount',
            'OrderLine.CostPrice',
            'OrderLine.ShippingMethod',
            'OrderLine.ShippingTracking',
            'ShippingSignature',
        ],

        'customer' => [
            'ID', 'Username', 'EmailAddress'
        ],

        'product' => [
            'SKU', 'Brand', 'Model', 'Name',
        ],

        'shipment' => [
            'ID',
            'PurchaseOrderNumber',
            'OrderStatus',
            'DateCompletedUTC',
            'OrderLine',
            'OrderLine.ShippingTracking',
            'OrderLine.ShippingServiceName',
            'OrderLine.ShippingServiceID',
            'OrderLine.ShippingCarrierName',
            'OrderLine.QuantityShipped',
            'OrderLine.ExternalOrderReference',
            'OrderLine.ExternalOrderLineReference'
        ],
        'category' => [
            "ContentID",
            "ID",
            "ContentName",
            "ContentType",
            "ParentContentID",
            "Active"
        ]
    ]
];
