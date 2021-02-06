<?php

declare(strict_types=1);

namespace Roelofr\SimplePayments\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;
use Roelofr\SimplePayments\Models\Invoice;
use Roelofr\SimplePayments\Models\InvoiceLine;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products = array_fill(0, $this->faker->numberBetween(1, 20), null);

        foreach (array_keys($products) as $key) {
            $price = Money::EUR($this->faker->numberBetween(1_50, 600_00));

            $products[$key] = (new InvoiceLine())
                ->setName($this->faker->words(3, true))
                ->setPrice($this->faker->optional(0.25)->passthrough($price))
                ->setCount($this->faker->optional()->numberBetween(1, 15))
                ->setDescription($this->faker->optional()->sentence);
        }

        return [
            'products' => $products,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Invoice $invoice) {
            $invoice->price = collect($invoice->products)
                ->pluck('price')
                ->filter()
                ->toArray();
        });
    }
}
