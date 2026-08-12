<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $receipt->receipt_no }}</title>
</head>
<body>
    <h1>Receipt #{{ $receipt->receipt_no }}</h1>
    <p>Date: {{ $receipt->receipt_date }}</p>
    <p>Type: {{ $receipt->receipt_type }}</p>
    <p>Amount: {{ number_format($receipt->amount, 2) }}</p>
    <p>Payment Method: {{ $receipt->payment_method }}</p>
    <p>Description: {{ $receipt->description }}</p>
</body>
</html>
