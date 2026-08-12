@extends('layouts.member')

@section('content')
    <h1>Recurring Donations</h1>

    <table>
        <thead>
            <tr>
                <th>Donation ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recurringDonations as $donation)
                <tr>
                    <td>{{ $donation->donation_id }}</td>
                    <td>{{ number_format($donation->amount, 2) }}</td>
                    <td>{{ $donation->donation_date }}</td>
                    <td>
                        <form method="POST" action="{{ route('member.donations.recurring.cancel', $donation->id) }}">
                            @csrf
                            <button type="submit">Cancel</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
