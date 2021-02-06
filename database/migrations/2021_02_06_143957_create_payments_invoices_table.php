<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('paid_at')->default(null)->nullable();

            $table->string('code', 50)->unique();
            $table->string('status', 10);

            $table->json('products')->default('[]');
            $table->json('price')->default('[]');

            $table->string('payment_provider', 20)->nullable()->default(null);
            $table->string('payment_provider_id', 20)->nullable()->default(null);

            $table->string('payment_method', 50)->nullable()->default(null);
            $table->json('payment_method_price')->nullable()->default(null);

            $table->nullableMorphs('invoicable');

            $table->unique(['payment_method', 'payment_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments_invoices');
    }
}
