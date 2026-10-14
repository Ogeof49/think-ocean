@extends('think-ocean.layout')

@section('title', $title)
@section('description', $summary)

@push('styles')
    <style>
        .article-head{padding:64px 0 32px}
        .article-head .back{display:inline-flex;align-items:center;gap:6px;margin-bottom:22px;color:var(--teal);font-weight:600;font-size:14px}
        .article-head .back .icon{transform:rotate(180deg)}
        .article-head h1{font-size:clamp(32px,4.6vw,50px);font-weight:600}
        .article-head .summary{margin-top:16px;font-size:19px;color:var(--muted)}
        .article-head .meta{margin-top:14px;color:var(--teal);font-weight:600;font-size:14px}
        .cover{width:100%;max-height:520px;object-fit:cover;border-radius:var(--radius);box-shadow:var(--shadow)}
        .article-body{padding:44px 0 80px}
        .article-body p{font-size:17px;white-space:pre-line}
        .article-body .actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:36px}
        .btn-light{background:var(--light);border-color:var(--light);color:var(--deep)}
        .btn-light:hover{background:#d3eef3;border-color:#d3eef3}
    </style>
@endpush

@section('content')
    @php($isProject = $type === 'Project')
    <header class="narrow article-head">
        <a class="back" href="{{ route('think-ocean.pages.show', $isProject ? 'projects' : 'news') }}">@include('think-ocean.partials.icon', ['name' => 'arrow']) {{ $isProject ? 'All projects' : 'All news & insights' }}</a>
        <span class="eyebrow">{{ $type }}</span>
        <h1>{{ $title }}</h1>
        <p class="summary">{{ $summary }}</p>
        @if($meta)<p class="meta">{{ $meta }}</p>@endif
    </header>
    @if($imagePath)
        <div class="narrow"><img class="cover" src="{{ str_starts_with($imagePath, 'http') ? $imagePath : asset($imagePath) }}" alt="{{ $title }}"></div>
    @endif
    <article class="narrow article-body">
        <p>{{ $content ?: $summary }}</p>
        <div class="actions">
            <a class="btn" href="mailto:{{ $contactEmail }}?subject={{ rawurlencode('Think Ocean enquiry: '.$title) }}">Contact Think Ocean</a>
            <a class="btn btn-light" href="{{ route('think-ocean.pages.show', 'get-involved') }}">Get involved</a>
        </div>
    </article>
@endsection
