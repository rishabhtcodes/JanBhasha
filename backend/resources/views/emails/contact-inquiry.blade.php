@php
    $emailTitle  = 'New Contact Inquiry — JanBhasha';
    $headerTitle = '📬 New Contact Inquiry';
@endphp

@extends('emails.layout')

@section('content')

    <p class="greeting">New message from JanBhasha Contact Form</p>

    <div class="info-box">
        <p><span class="info-label">From</span></p>
        <p>{{ $senderName }} &lt;{{ $senderEmail }}&gt;</p>

        <br>

        <p><span class="info-label">Subject</span></p>
        <p>{{ $subject }}</p>

        <br>

        <p><span class="info-label">Submitted At</span></p>
        <p>{{ $submittedAt }}</p>
    </div>

    <div class="info-box" style="border-left-color: #7c3aed;">
        <p><span class="info-label" style="color:#7c3aed;">Message / Reason</span></p>
        <p style="margin-top: 8px; white-space: pre-wrap; font-size: 14px; color: #1a202c; line-height: 1.7;">{{ $reason }}</p>
    </div>

    <p class="body-text" style="font-size:13px; color:#718096;">
        Reply directly to this email to respond to <strong>{{ $senderName }}</strong> at <strong>{{ $senderEmail }}</strong>.
    </p>

@endsection
