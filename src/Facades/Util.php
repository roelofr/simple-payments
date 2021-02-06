<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Facades;

use Illuminate\Support\Facades\Facade;
use Roelofr\SimplePayments\Services\Utilities;

/**
 * @method static \Money\Currency getDefaultCurrency()
 * @method static \Money\Money getMonetaryValue($object)
 * @method static string moneyToString(\Money\Money $money)
 * @method static \Money\Money determineCheapestRate(\Roelofr\SimplePayments\Contracts\PaymentProvider $provider, \Money\Money $price, string &$cheapestProvider)
 * @method static \Money\Money determineValue(\Roelofr\SimplePayments\Contracts\Invoicable $invoicable)
 * @see \Roelofr\SimplePayments\Services\Utilities
 */
class Util extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Utilities::class;
    }
}
