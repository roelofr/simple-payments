<?php

return [
    /**
     * The default provider of your application, which is used for all invoices
     * from this point forward.
     *
     * Set to null to disable payments (no methods will be returned) or set to
     * dummy to allow test payments.
     *
     * Choose from: null, dummy
     */
    'default-provider' => env('PAYMENTS_DEFAULT_PROVIDER', 'null'),

    /**
     * Default currency to use, as ISO-code
     */
    'default-currency' => env('PAYMENTS_DEFAULT_PROVIDER', 'EUR'),

    /**
     * Invoice model to use, if you want to override this.
     */
    'invoice-model' => \Roelofr\SimplePayments\Models\Invoice\Invoice::class,

    /**
     * Taxation
     */
    'vat' => [
        /**
         * Should the script determine tax values
         */
        'enable' => env('PAYMENTS_VAT_ENABLE', true),

        /**
         * Country to determine taxes for, if null, the user's IP is used as
         * target.
         */
        'tax-country' => env('PAYMENTS_VAT_COUNTRY', null),

        /**
         * By default, the invoice total is assumed to be the net price. If
         * you advertise all products with tax included, set this to true to
         * compute the VAT as included in the price.
         */
        'included-in-price' => env('PAYMENTS_VAT_INCLUDED', false),
    ],

    'providers' => [
        'dummy' => [
            'cost-fixed' => '0.25 EUR',
            'cost-flexible' => 0.00,
        ]
    ]
];
