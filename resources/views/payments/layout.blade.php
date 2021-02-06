<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Invoice details') - {{ $invoice->code }} - {{ config('app.name') }}</title>

    <link href="{{ mix('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="bg-blue-100 dark:bg-blue-900">
    <div class="bg-white dark:bg-gray-900">
        <div class="container mx-auto">
            <div class="mx-0 md:mx-auto md:w-6/12 py-16 dark:text-white">
                <h2 class="text-5xl font-title text-center">{{ config('app.name') }}</h2>
            </div>
        </div>

        <div class="container mx-auto pt-8 relative payment-detail">
            <div class="mx-0 md:mx-auto md:w-6/12">
                <div class="rounded-lg shadow-lg bg-white p-8 mb-8 border bg-gray-100 border-gray-600 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700">
                    <p class="text-xl font-bold">
                        You're using the Dummy Payment Provider.
                    </p>

                    <p>
                        Clicking the below buttons will mark the invoice as paid or failed.
                    </p>
                </div>

                <div class="rounded-lg shadow-lg bg-white p-8 border border-blue-200 dark:bg-gray-900 dark:text-gray-300 dark:border-blue-800">
                    <div class="mb-8">
                        <h1 class="text-3xl font-title font-bold mb-2 text-blue-800 dark:text-blue-400">@lang("Invoice details")</h1>
                        <h2 class="text-2xl font-title text-gray-800 dark:text-gray-400">Invoice #{{ $invoice->code }}</h2>
                    </div>

                    <div class="leading-relaxed text-lg mb-8">
                        <p>
                            Thank you for your order with <strong>{{ config('app.name') }}</strong>.
                        </p>
                        <p>
                            Please review the order details below, and chose your payment method
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="pt-16" style="background-color: inherit">
        <div class="container mx-auto">
            <div class="mx-0 md:mx-auto md:w-6/12 py-16">

                <div class="flex flex-row items-center">
                    <a href="#" class="flex-grow text-gray-600 dark:text-gray-400">
                        Cancel and return to vendor
                    </a>
                    <div class="flex-none w-16"></div>
                    <button class="flex-grow shadow px-8 py-4 text-xl rounded bg-blue-600 text-white">
                        Pay
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
