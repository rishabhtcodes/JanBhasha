@php
    $emailTitle  = 'Welcome to JanBhasha';
    $headerTitle = 'Welcome to the Family! 🎉';
@endphp

@extends('emails.layout')

@section('content')

    <p class="greeting">Hello, {{ $user->name }}! 👋</p>

    <p class="body-text">
        We're so excited to have you on board! Your JanBhasha account is now active and ready to go.
        JanBhasha is your intelligent translation platform, built to break down language barriers and bring people closer together.
    </p>

    <p class="body-text">
        With JanBhasha you can:
    </p>

    <div class="info-box">
        <p>🌐 &nbsp;<strong>Translate text</strong> across dozens of Indian and international languages</p>
        <p>📋 &nbsp;<strong>Keep a history</strong> of all your translations in one place</p>
        <p>🏢 &nbsp;<strong>Collaborate</strong> with your organisation's team</p>
        <p>📖 &nbsp;<strong>Build glossaries</strong> for consistent terminology</p>
    </div>

    <p class="body-text">
        Head over to your dashboard to make your first translation — it's just a click away!
    </p>

    <div class="cta-container">
        <a href="{{ config('app.url') . '/dashboard' }}" class="cta-button">
            Go to My Dashboard →
        </a>
    </div>

    <div class="divider"></div>

    <p class="body-text" style="font-size: 13px; color: #718096;">
        If you did not create this account, please ignore this email or contact us immediately.
        Your security is our top priority.
    </p>

@endsection
