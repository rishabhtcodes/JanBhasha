@php
    $emailTitle  = 'Reset Your Password — JanBhasha';
    $headerTitle = 'Password Reset Request 🔑';
@endphp

@extends('emails.layout')

@section('content')

    <p class="greeting">Hello, {{ $user->name }}!</p>

    <p class="body-text">
        We received a request to reset the password for your JanBhasha account.
        Click the button below to choose a new password.
    </p>

    <div class="cta-container">
        <a href="{{ $resetUrl }}" class="cta-button">
            Reset My Password
        </a>
    </div>

    <div class="info-box">
        <p><span class="info-label">⚠ Important</span></p>
        <p>This password reset link will expire in <strong>{{ $count }} minutes</strong>.</p>
        <p style="margin-top: 8px;">If you did not request a password reset, no action is needed — your account is safe.</p>
    </div>

    <p class="body-text" style="font-size: 13px; color: #718096;">
        If the button above doesn't work, copy and paste this URL into your browser:
    </p>
    <p style="font-size: 12px; color: #4f46e5; word-break: break-all; margin-bottom: 16px;">
        {{ $resetUrl }}
    </p>

    <div class="divider"></div>

    <p class="body-text" style="font-size: 13px; color: #718096;">
        For your security, never share this link with anyone.
        The JanBhasha team will never ask for your password.
    </p>

@endsection
