@extends('layouts.member')

@section('content')
    <h1>Welcome, {{ $member->first_name }}</h1>

    <div class="dashboard-stats">
        <div>Total Donations: {{ number_format($totalDonations, 2) }}</div>
        <div>Total Receipts: {{ $totalReceipts }}</div>
        <div>Active Students: {{ $activeStudents }}</div>
        <div>Upcoming Events: {{ $upcomingEvents }}</div>
        <div>Credit Balance: {{ number_format($creditBalance, 2) }}</div>
    </div>

    <h2>Recent Transactions</h2>
    <ul>
        @foreach ($recentTransactions as $transaction)
            <li>{{ $transaction->donation_date }} — {{ number_format($transaction->amount, 2) }}</li>
        @endforeach
    </ul>

    <h2>Family Members</h2>
    <ul>
        @foreach ($familyMembers as $family)
            <li>{{ $family->full_name }} ({{ $family->relationship }})</li>
        @endforeach
    </ul>

    <h2>Children</h2>
    <ul>
        @foreach ($children as $child)
            <li>{{ $child->full_name }}</li>
        @endforeach
    </ul>

    <h2>Upcoming Payments</h2>
    <ul>
        @foreach ($upcomingPayments as $payment)
            <li>{{ $payment['type'] }} — {{ $payment['student'] }} — {{ number_format($payment['amount'], 2) }} due {{ $payment['due_date'] }}</li>
        @endforeach
    </ul>
@endsection
