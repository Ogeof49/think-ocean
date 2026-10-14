@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'Support Think Ocean’s work for healthier oceans and more resilient coastal communities.')

@push('styles')
    <style>
        .donate{display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:start}
        .donate p{margin-top:14px;color:var(--muted);font-size:16px}
        .donate .btn{margin-top:26px}
        .enable{padding:32px;border-radius:var(--radius);background:var(--light)}
        .enable h3{font-size:20px;font-weight:600}
        .enable ul{margin:16px 0 0;padding:0;list-style:none;display:grid;gap:12px}
        .enable li{display:flex;gap:12px;align-items:center;color:var(--deep)}
        .enable li .icon{width:22px;height:22px;color:var(--teal)}
        @media (max-width:860px){.donate{grid-template-columns:1fr}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap donate">
            <div>
                <h2 class="section-title">Support healthier oceans</h2>
                <p>Responsible funding and in-kind support help transform sound ideas into practical conservation action.</p>
                <p>Online payments are not currently collected on this page. Speak directly with the Think Ocean team about accountable ways to contribute.</p>
                <a class="btn" href="mailto:{{ $contactEmail }}?subject=Think%20Ocean%20Support%20Enquiry">Discuss your support</a>
            </div>
            <div class="enable">
                <h3>Your support can enable</h3>
                <ul>
                    <li>@include('think-ocean.partials.icon', ['name' => 'users'])Community action</li>
                    <li>@include('think-ocean.partials.icon', ['name' => 'book'])Ocean education</li>
                    <li>@include('think-ocean.partials.icon', ['name' => 'bulb'])Research collaboration</li>
                    <li>@include('think-ocean.partials.icon', ['name' => 'fish'])Habitat protection</li>
                    <li>@include('think-ocean.partials.icon', ['name' => 'globe'])Climate-resilience initiatives</li>
                </ul>
            </div>
        </div>
    </section>
@endsection
