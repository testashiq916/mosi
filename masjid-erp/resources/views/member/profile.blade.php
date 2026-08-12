@extends('layouts.member')

@section('content')
    <h1>My Profile</h1>

    <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" value="{{ $member->first_name }}" required>

        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" value="{{ $member->last_name }}" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ $member->email }}" required>

        <label for="mobile">Mobile</label>
        <input type="text" name="mobile" id="mobile" value="{{ $member->mobile }}" required>

        <label for="address">Address</label>
        <textarea name="address" id="address">{{ $member->address }}</textarea>

        <label for="profile_image">Profile Image</label>
        <input type="file" name="profile_image" id="profile_image">

        <h2>Family Members</h2>
        @foreach ($member->family as $index => $family)
            <div>
                <input type="text" name="family_members[{{ $index }}][name]" value="{{ $family->full_name }}">
                <input type="text" name="family_members[{{ $index }}][relationship]" value="{{ $family->relationship }}">
                <input type="date" name="family_members[{{ $index }}][dob]" value="{{ $family->date_of_birth }}">
            </div>
        @endforeach

        <button type="submit">Save Changes</button>
    </form>

    <h2>Documents</h2>
    <ul>
        @foreach ($member->documents as $document)
            <li>{{ $document->document_name }} ({{ $document->document_type }})</li>
        @endforeach
    </ul>
@endsection
