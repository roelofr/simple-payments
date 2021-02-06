<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Money\Money;
use Roelofr\SimplePayments\Casts\EnumCast;
use Roelofr\SimplePayments\Casts\InvoiceLineCast;
use Roelofr\SimplePayments\Casts\MoneyCast;
use Roelofr\SimplePayments\Contracts\Invoicable;
use Roelofr\SimplePayments\Database\Factories\InvoiceFactory;
use Roelofr\SimplePayments\Enums\InvoiceStatus;
use Roelofr\SimplePayments\Exceptions\Logic\ReadOnlyException;
use Roelofr\SimplePayments\Facades\Payments;

/**
 * @property string $id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property null|DateTime $deleted_at
 * @property null|DateTime $paid_at
 * @property string $code
 * @property array<InvoiceLine> $products
 * @property string $payment_provider
 * @property null|string $payment_provider_id
 * @property null|string $payment_method
 * @property Money $price
 * @property InvoiceStatus $status
 * @property Invoicable $invoicable
 * @property null|Money $payment_method_price
 * @property-read Money $total_price
 */
class Invoice extends Model implements Invoicable
{
    use HasFactory;
    use SoftDeletes;

    public const INVOICE_FORMAT = '%s-%04d';

    /**
     * Finds invoice with the given code.
     * @param string $code
     * @return null|static
     */
    public static function findByCode(string $code): ?self
    {
        return static::query()->whereCode($code)->first();
    }

    /**
     * Register automatic code generation
     * @return void
     */
    public static function booted(): void
    {
        // Ensure ID and code is set
        self::creating(function (Invoice $invoice) {
            $invoice->id = (string) Str::uuid();
            $invoice->code ??= $invoice->generateInvoiceCode();
        });

        // Disallow changing invoice items or price
        self::updating(function (Invoice $invoice) {
            if ($invoice->hasChanges(['price', 'products'])) {
                throw new ReadOnlyException(<<<MESSAGE
                    The price and products of an invoice cannot be altered after creation.
                    Void the invoice and create a new one instead.
                MESSAGE);
            }
        });
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new InvoiceFactory();
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => InvoiceStatus::OPEN,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments_invoices';

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
        'paid_at' => 'datetime',
        'status' => EnumCast::class . ':' . InvoiceStatus::class,
        'price' => MoneyCast::class,
        'products' => InvoiceLineCast::class,
        'payment_method_price' => MoneyCast::class,
    ];

    /**
     * The owning invoice
     * @return MorphTo
     */
    public function subject(): MorphTo
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

        return sprintf(self::INVOICE_FORMAT, $thisYear, $yearSum + 1);
    }

    public function getPaymentProvider()
    {
        return Payments::provider($this);
    }

    public function getInvoiceLines(): array
    {
        return $this->products;
    }

    public function getTotalPriceAttribute(): Money
    {
        if (! $this->payment_method_price) {
            return $this->price;
        }

        return $this->price->add($this->payment_method_price);
    }
}
