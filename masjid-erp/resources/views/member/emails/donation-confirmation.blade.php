<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank you for your donation</title>
</head>
<body>
    <p>Assalamu Alaikum {{ $donation->member->first_name ?? '' }},</p>

    <p>
        Thank you for your donation of {{ number_format($donation->amount, 2) }}
        received on {{ $donation->donation_date }}.
    </p>

    @if ($donation->receipt)
        <p>Receipt #: {{ $donation->receipt->receipt_no }}</p>
    @endif

    <p>May Allah reward you for your generosity.</p>
</body>
</html>
