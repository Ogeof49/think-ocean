@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'Think Ocean projects tackling ocean challenges, from marine conservation and sustainable livelihoods to climate resilience and education.')

@push('styles')
    <style>
        .project-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:28px;margin-top:40px}
        .project-grid + .cta-band{margin-top:56px}
        @media (max-width:720px){.project-grid{grid-template-columns:1fr}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap">
            <p class="lead">We work on a range of projects that address the most pressing ocean challenges — from marine conservation and sustainable livelihoods to climate resilience and education.</p>

            <div class="project-grid">
                @forelse($projects as $project)
                    @include('think-ocean.partials.project-card')
                @empty
                    <div class="empty">New verified project profiles are being prepared. Contact us to discuss partnership opportunities.</div>
                @endforelse
            </div>

            <div class="cta-band">
                @include('think-ocean.partials.icon', ['name' => 'waves', 'stroke' => 1.5])
                <div>
                    <h2>Want to support our projects?</h2>
                    <p>Your support helps us create real, lasting change for our ocean and coastal communities.</p>
                </div>
                <a class="btn" href="{{ route('think-ocean.pages.show', 'donate') }}">Donate Now</a>
            </div>
        </div>
    </section>
@endsection
