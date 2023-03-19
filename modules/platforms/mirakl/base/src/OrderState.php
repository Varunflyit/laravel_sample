<?php

namespace Ecommify\Mirakl;

class OrderState
{
    const STAGING = 'STAGING';

    const WAITING_ACCEPTANCE = 'WAITING_ACCEPTANCE';

    const WAITING_DEBIT = 'WAITING_DEBIT';

    const WAITING_DEBIT_PAYMENT = 'WAITING_DEBIT_PAYMENT';

    const SHIPPING = 'SHIPPING';

    const SHIPPED = 'SHIPPED';

    const TO_COLLECT = 'TO_COLLECT';

    const RECEIVED = 'RECEIVED';

    const CLOSED = 'CLOSED';

    const REFUSED = 'REFUSED';

    const CANCELED = 'CANCELED';
}
