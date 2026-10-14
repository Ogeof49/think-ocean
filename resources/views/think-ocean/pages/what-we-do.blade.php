@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'Think Ocean’s focus areas: marine conservation, sustainable solutions, climate resilience and ocean education.')

@php
    $areas = [
        ['icon' => 'fish', 'title' => 'Marine Conservation', 'text' => 'Protecting marine biodiversity and restoring critical habitats so ocean life can recover and thrive.', 'image' => 'focus-conservation', 'alt' => 'A school of tropical fish swimming over a coral reef'],
        ['icon' => 'leaf', 'title' => 'Sustainable Solutions', 'text' => 'Supporting sustainable fisheries, cleaner oceans and circular economy initiatives that work for people and nature.', 'image' => 'focus-solutions', 'alt' => 'A fishing boat silhouetted against a sunset sea'],
        ['icon' => 'globe', 'title' => 'Climate Resilience', 'text' => 'Helping coastal communities adapt to climate change and build long-term resilience.', 'image' => 'focus-climate', 'alt' => 'A tropical coastline with palm trees and a sheltered bay'],
        ['icon' => 'book', 'title' => 'Education & Awareness', 'text' => 'Inspiring action through learning, storytelling and community engagement.', 'image' => 'focus-education', 'alt' => 'Children writing in a classroom'],
    ];
@endphp

@push('styles')
    <style>
        .areas{display:grid;gap:24px;margin-top:32px}
        .area{display:grid;grid-template-columns:auto 1fr 300px;gap:28px;align-items:center;padding:20px 20px 20px 28px;background:#fff;border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
        .area h3{font-size:20px;font-weight:600}
        .area p{margin-top:8px;color:var(--muted);max-width:460px}
        .area img{width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:10px}
        .every-action{margin-top:56px;padding:44px 32px;text-align:center;border-radius:var(--radius);background:var(--light)}
        .every-action p{max-width:620px;margin:0 auto;color:var(--deep);font-size:17px}
        .every-action .btn{margin-top:24px}
        @media (max-width:860px){.area{grid-template-columns:auto 1fr}.area img{grid-column:1/-1}}
        @media (max-width:480px){.area{grid-template-columns:1fr;padding:22px}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap">
            <h2 class="section-title">Our Focus Areas</h2>
            <div class="areas">
                @foreach($areas as $area)
                    <article class="area">
                        <span class="icon-circle">@include('think-ocean.partials.icon', ['name' => $area['icon']])</span>
                        <div><h3>{{ $area['title'] }}</h3><p>{{ $area['text'] }}</p></div>
                        <img src="{{ asset('images/think-ocean/'.$area['image'].'.jpg') }}" alt="{{ $area['alt'] }}" loading="lazy">
                    </article>
                @endforeach
            </div>

            <div class="every-action">
                <p>Every action counts. Whether it’s a community project, a policy change or a simple shift in how we live — it all adds up to a healthier ocean.</p>
                <a class="btn" href="{{ route('think-ocean.pages.show', 'get-involved') }}">Get Involved</a>
            </div>
        </div>
    </section>
@endsection
