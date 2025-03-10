<?php

return [
    'USER_ROLE_ADMIN' => 'admin',
    'USER_ROLE_SELLER' => 'seller',

    'MEASURE_UNIT_UNIT' => 'unit',

    'SALE_TYPES' => [
        'SALE_TYPE_CASH' => 'contado',
        'SALE_TYPE_CREDIT' => 'credito',
    ],

    'PAYMENT_METHODS' => [
        'PAYMENT_METHOD_CASH' => 'efectivo',
        'PAYMENT_METHOD_CARD' => 'tarjeta',
        'PAYMENT_METHOD_YAPE' => 'yape',
        'PAYMENT_METHOD_PLIN' => 'plin',
    ],

    'CASH_TRANSACTION_TYPES' => [
        'CASH_TRANSACTION_EXPENSE' => 'egreso',
        'CASH_TRANSACTION_INCOME' => 'ingreso',
        'CASH_TRANSACTION_PAY' => 'pago',
    ],

];
