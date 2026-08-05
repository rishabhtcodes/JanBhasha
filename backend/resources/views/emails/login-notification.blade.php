@php
    $emailTitle  = 'New Sign-In — JanBhasha';
    $headerTitle = 'New Sign-In Detected 🔐';
@endphp

@extends('emails.layout')

@section('content')

    <p class="greeting">Hello, {{ $user->name }}!</p>

    <p class="body-text">
        We noticed a new sign-in to your JanBhasha account. Here are the details:
    </p>

    <div class="info-box">
        <p><span class="info-label">Account</span></p>
        <p>{{ $user->email }}</p>

        <br>

        <p><span class="info-label">Date & Time</span></p>
        <p>{{ $loginTime }}</p>

        <br>

        <p><span class="info-label">IP Address</span></p>
        <p>{{ $ipAddress }}</p>
    </div>

    <p class="body-text">
        <strong>Was this you?</strong> No action is needed — you can safely ignore this email.
    </p>

    <p class="body-text">
        <strong>Wasn't you?</strong> Your account may have been compromised.
        Please reset your password immediately.
    </p>

    <div class="cta-container">
        <a href="{{ route('password.request') }}" class="cta-button">
            Reset My Password
        </a>
    </div>

    <div class="divider"></div>

    <p class="body-text" style="font-size: 13px; color: #718096;">
        This is an automated security notification from JanBhasha. If you believe your account is at risk,
        please contact us immediately.
    </p>

@endsection
