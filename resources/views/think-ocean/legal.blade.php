@extends('think-ocean.layout')

@section('title', $title)

@push('styles')
    <style>
        .legal{padding:64px 0 88px}
        .legal h1{font-size:clamp(32px,4vw,44px);font-weight:600}
        .legal h2{margin-top:32px;font-size:20px;font-weight:600}
        .legal p{margin-top:12px;color:var(--muted)}
        .legal a{color:var(--teal);font-weight:500}
    </style>
@endpush

@section('content')
    <article class="narrow legal">
        <h1>{{ $title }}</h1>
        @if($title === 'Privacy Policy')
            <p>Think Ocean uses personal information only when you contact us, subscribe to updates or ask to participate in our activities. We use it to answer your enquiry, send the updates you asked for, coordinate agreed activities and meet legal obligations.</p>
            <h2>Information we receive</h2>
            <p>This may include your name, email address and information you choose to include in a message.</p>
            <h2>Your choices</h2>
            <p>You may request access, correction or deletion, or unsubscribe from updates, by emailing <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.</p>
        @else
            <p>Think Ocean aims to provide a website that can be used by as many people as possible, including keyboard and assistive-technology users.</p>
            <h2>Current measures</h2>
            <p>The website uses semantic headings, visible keyboard focus, descriptive image text, responsive layouts and reduced-motion support.</p>
            <h2>Report a problem</h2>
            <p>If you experience an accessibility barrier, email <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a> and describe the page and problem.</p>
        @endif
        <p><small>Last reviewed: {{ now()->format('j F Y') }}</small></p>
    </article>
@endsection
