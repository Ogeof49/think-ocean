@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'Contact Think Ocean about a question, partnership idea or way to support healthier oceans.')

@push('styles')
    <style>
        .contact{display:grid;grid-template-columns:.85fr 1.15fr;gap:56px;align-items:start}
        .contact .intro{margin-top:12px;color:var(--muted)}
        .details{display:grid;gap:24px;margin-top:32px}
        .detail{display:flex;gap:16px;align-items:flex-start}
        .detail > .icon{width:22px;height:22px;margin-top:3px;color:var(--deep)}
        .detail strong{display:block;font-size:14px;color:var(--deep)}
        .detail a,.detail span{color:var(--muted);white-space:pre-line}
        .detail a:hover{color:var(--teal)}
        .form-card{padding:36px;background:#fff;border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
        .form-card h2{font-size:22px;font-weight:600}
        .form-card form{display:grid;gap:18px;margin-top:22px}
        .form-card label{display:block;margin-bottom:6px;font-size:14px;font-weight:500;color:var(--deep)}
        .form-card input,.form-card textarea{width:100%;padding:12px 14px;border:1px solid #cfdde3;border-radius:10px;font:inherit;font-size:15px;color:var(--text);background:#fff}
        .form-card input:focus,.form-card textarea:focus{border-color:var(--teal);outline:none;box-shadow:0 0 0 3px rgba(0,167,167,.15)}
        .form-card textarea{min-height:150px;resize:vertical}
        .form-card .btn{justify-self:start}
        .honeypot{position:absolute;left:-9999px}
        @media (max-width:860px){.contact{grid-template-columns:1fr}.form-card{padding:24px}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap contact">
            <div>
                <h2 class="section-title">Get in Touch</h2>
                <p class="intro">Have a question, partnership idea or just want to say hello? We’d love to hear from you.</p>
                <div class="details">
                    <div class="detail">@include('think-ocean.partials.icon', ['name' => 'mail'])<div><strong>Email</strong><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></div></div>
                    @if($settings['contact_phone'])
                        <div class="detail">@include('think-ocean.partials.icon', ['name' => 'phone'])<div><strong>Phone</strong><a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone']) }}">{{ $settings['contact_phone'] }}</a></div></div>
                    @endif
                    @if($settings['contact_address'] || $settings['registered_office'])
                        <div class="detail">@include('think-ocean.partials.icon', ['name' => 'pin'])<div><strong>Location</strong><span>{{ $settings['contact_address'] ?: $settings['registered_office'] }}</span></div></div>
                    @endif
                </div>
            </div>

            <div class="form-card">
                <h2>Send Us a Message</h2>
                @if(session('status'))<p class="status" role="status" style="margin-top:18px">{{ session('status') }}</p>@endif
                <form method="POST" action="{{ route('think-ocean.contact.store') }}">
                    @csrf
                    <div class="honeypot" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
                    <div>
                        <label for="name">Name *</label>
                        <input id="name" name="name" value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required maxlength="120">
                        @error('name')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email">Email *</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required maxlength="255">
                        @error('email')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" placeholder="How can we help?" required maxlength="5000">{{ old('message') }}</textarea>
                        @error('message')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <button class="btn" type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>
@endsection
