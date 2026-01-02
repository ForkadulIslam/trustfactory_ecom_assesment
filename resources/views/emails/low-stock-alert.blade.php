<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
</head>
<body>
    <h1>Low Stock Alert</h1>
    <p>The product <strong>{{ $product->name }}</strong> is running low on stock!</p>
    <p>Current quantity: <strong>{{ $product->stock_quantity }}</strong>.</p>
    <p>Please reorder soon.</p>
    <a href="{{ url('/admin/products/' . $product->id . '/edit') }}">View Product</a>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
