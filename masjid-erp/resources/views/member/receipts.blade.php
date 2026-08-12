@extends('layouts.member')

@section('content')
    <h1>My Receipts</h1>

    <table>
        <thead>
            <tr>
                <th>Receipt No</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receipts as $receipt)
                <tr>
                    <td>{{ $receipt->receipt_no }}</td>
                    <td>{{ $receipt->receipt_type }}</td>
                    <td>{{ number_format($receipt->amount, 2) }}</td>
                    <td>{{ $receipt->receipt_date }}</td>
                    <td><a href="{{ route('member.receipts.download', $receipt->id) }}">PDF</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $receipts->links() }}
@endsection
