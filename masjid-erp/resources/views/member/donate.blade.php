@extends('layouts.member')

@section('content')
    <h1>Make a Donation</h1>

    <form method="POST" action="{{ route('member.donations.store') }}">
        @csrf

        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <label for="amount">Amount</label>
        <input type="number" name="amount" id="amount" min="1" step="0.01" required>

        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="cash">Cash</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="online">Online</option>
            <option value="card">Card</option>
            <option value="cheque">Cheque</option>
        </select>

        <label for="purpose">Purpose</label>
        <input type="text" name="purpose" id="purpose" maxlength="255">

        <label>
            <input type="checkbox" name="is_anonymous" value="1"> Donate anonymously
        </label>

        <label>
            <input type="checkbox" name="is_recurring" value="1"> Make this recurring
        </label>

        <button type="submit">Donate</button>
    </form>
@endsection
