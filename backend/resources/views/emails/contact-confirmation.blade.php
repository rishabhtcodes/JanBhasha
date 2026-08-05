@php
    $emailTitle  = 'Message Received — JanBhasha Support';
    $headerTitle = 'We Got Your Message! 💌';
@endphp

@extends('emails.layout')

@section('content')

    <p class="greeting">Hello, {{ $senderName }}! 👋</p>

    <p class="body-text">
        Thank you for reaching out to the <strong>JanBhasha support team</strong>. We have received your message and
        our team will get back to you as soon as possible.
    </p>

    <div class="info-box">
        <p><span class="info-label">📋 Your Inquiry Details</span></p>

        <p style="margin-top: 10px;"><strong>Subject:</strong> {{ $subject }}</p>

        <p style="margin-top: 8px; white-space: pre-wrap; color: #6b7280; font-size: 13px; line-height: 1.6;">{{ $reason }}</p>
    </div>

    <div class="info-box" style="border-left-color: #10b981; background: #f0fdf4;">
        <p style="color: #065f46; font-size: 14px;">
            ⏱ &nbsp;Our team typically responds within <strong>48–72 hours</strong> during business days.
            We appreciate your patience!
        </p>
    </div>

    <p class="body-text">
        While you wait, feel free to explore JanBhasha — you can translate text, manage your glossary, or browse your translation history.
    </p>

    <div class="cta-container">
        <a href="{{ config('app.url') . '/dashboard' }}" class="cta-button">
            Back to JanBhasha →
        </a>
    </div>

    <div class="divider"></div>

    <p class="body-text" style="font-size:13px; color:#718096;">
        If you have an urgent concern, please reply directly to this email.<br>
        The JanBhasha team is here to help. 🙏
    </p>

@endsection
