@extends('layouts.member')

@section('content')
    <h1>Donation History</h1>

    <table>
        <thead>
            <tr>
                <th>Donation ID</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($donations as $donation)
                <tr>
                    <td>{{ $donation->donation_id }}</td>
                    <td>{{ $donation->category->name ?? '—' }}</td>
                    <td>{{ number_format($donation->amount, 2) }}</td>
                    <td>{{ $donation->donation_date }}</td>
                    <td>
                        @if ($donation->receipt)
                            <a href="{{ route('member.donations.receipt', $donation->receipt->id) }}">{{ $donation->receipt->receipt_no }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $donations->links() }}
@endsection
