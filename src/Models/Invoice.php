<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Date;
use Money\Money;
use Roelofr\SimplePayments\Casts\MoneyCast;
use Roelofr\SimplePayments\Casts\ProductCast;
use Roelofr\SimplePayments\Enums\InvoiceStatus;
use Roelofr\SimplePayments\Facades\Payments;

/**
 * @property Collection<InvoiceLine> $products
 * @package Roelofr\SimplePayments\Models
 */
class Invoice extends Model
{
    /**
     * Register automatic code generation
     * @return void
     */
    public static function booted(): void
    {
        self::creating(function (Invoice $invoice) {
            $invoice->code ??= $invoice->generateInvoiceCode();
        });
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'uuid';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => MoneyCast::class,
        'products' => ProductCast::class,
        'state' => EnumCast::class . ':' . InvoiceStatus::class,
    ];

    /**
     * The owning invoice
     * @return MorphTo
     */
    public function invoicable(): MorphTo
    {
        return $this->morphTo('invoicable');
    }

    /**
     * Generates a code for this invoice, based on YYYY-12345.
     *
     * @return string
     */
    public function generateInvoiceCode(): string
    {
        $thisYear = Date::now()->year;

        // Not race condition safe :(
        $yearSum = static::query()->whereYear('created_at', $thisYear)->count();

        return sprintf('%s-%05d', $thisYear, $yearSum + 1);
    }

    public function getPaymentProvider()
    {
        return Payments::provider($this);
    }

    public function getValueAttribute(): Money
    {
        return Money::sum($this->products->pluck('price'));
    }
}
