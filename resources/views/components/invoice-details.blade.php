<table class="w-full">
    {{-- All products --}}
    @foreach ($invoice->products as $product)
    <tr class="p-4">
        <td class="py-4 text-gray-400 dark:text-gray-500">
            {{ $product->count }} x
        </td>
        <td class="py-4">
            {{ $product->title }}
        </td>
        <td class="py-4 text-gray-600 dark:text-gray-400">
            {{ $product->price }}
        </td>
        <td class="py-4 text-right">
            {{ $product->total_price }}
        </td>
    </tr>
    @endforeach

    {{-- Line --}}
    <tr>
        <td colspan="3"></td>
        <td>
            <hr class="my-2 bg-gray-400 dark:bg-gray-600">
        </td>
    </tr>

    {{-- Subtotal --}}
    <tr>
        <td>&nbsp;</td>
        <td colspan="2" class="font-bold py-4">
            Subtotal
        </td>
        <td class="text-right font-title text-lg text-right">
            {{ $invoice->price }}
        </td>
    </tr>

    {{-- Add fees --}}
    <tr>
        <td>&nbsp;</td>
        <td class="py-4">
            Transaction fee
        </td>
        <td>&nbsp;</td>
        <td class="py-4 text-right">
            € 0,25
        </td>
    </tr>

    {{-- Add taxes --}}
    <tr>
        <td>&nbsp;</td>
        <td class="py-4">
            Taxes
        </td>
        <td class="py-4 text-gray-600 text-gray-400">
            21 %
        </td>
        <td class="py-4 text-right">
            € 8,05
        </td>
    </tr>

    <!-- Line -->
    <tr>
        <td colspan="3"></td>
        <td>
            <hr class="my-2 bg-gray-400 dark:bg-gray-600">
        </td>
    </tr>

    <!-- Final total -->
    <tr>
        <td>&nbsp;</td>
        <td colspan="2" class="font-bold py-4">
            Total
        </td>
        <td class="text-right">
            <strong class="font-title text-xl text-right">
                € 46,30
            </strong>
        </td>
    </tr>
</table>
