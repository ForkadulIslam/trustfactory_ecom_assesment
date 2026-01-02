<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
</head>
<body>
    <h1>Daily Sales Report for {{ now()->format('Y-m-d') }}</h1>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td>{{ $sale->product->name }}</td>
                    <td>{{ $sale->total_quantity }}</td>
                    <td>${{ number_format($sale->price, 2) }}</td>
                    <td>${{ number_format($sale->total_quantity * $sale->price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No sales today.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total Revenue:</th>
                <th>${{ number_format($totalRevenue, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
