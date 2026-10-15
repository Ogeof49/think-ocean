@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'News, insights, stories and events from Think Ocean.')

@push('styles')
    <style>
        .news-tools{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px}
        .filters{display:flex;flex-wrap:wrap;gap:8px}
        .filters a{padding:8px 18px;border:1px solid var(--line);border-radius:999px;font-size:14px;font-weight:500;color:var(--text);background:#fff}
        .filters a:hover{border-color:var(--teal);color:var(--teal)}
        .filters a.active{background:var(--deep);border-color:var(--deep);color:#fff}
        .search{display:flex;align-items:center;width:min(100%,300px);border:1px solid var(--line);border-radius:999px;background:#fff;padding-left:16px}
        .search input{flex:1;min-width:0;border:0;background:transparent;padding:10px 0;font:inherit;font-size:14px;outline:none}
        .search button{display:grid;place-items:center;width:42px;height:42px;border:0;background:none;color:var(--deep);cursor:pointer}
        .search button .icon{width:18px;height:18px}
        .search:focus-within{border-color:var(--teal)}
        .post-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;margin-top:36px}
        .pager{display:flex;justify-content:center;gap:12px;margin-top:40px}
        .newsletter{margin-top:64px}
        .newsletter form{display:flex;gap:8px;margin-left:auto;width:min(100%,380px)}
        .newsletter input{flex:1;min-width:0;padding:11px 18px;border:0;border-radius:999px;font:inherit;font-size:14px}
        .newsletter .feedback{margin-top:8px;font-size:14px;color:#9ff0ec}
        @media (max-width:960px){.post-grid{grid-template-columns:repeat(2,1fr)}}
        @media (max-width:620px){.post-grid{grid-template-columns:1fr}.search{width:100%}.newsletter form{margin-left:0}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap">
            <div class="news-tools">
                <nav class="filters" aria-label="Filter by category">
                    <a href="{{ route('think-ocean.pages.show', ['news', 'q' => $search ?: null]) }}" @class(['active' => ! $category])>All</a>
                    @foreach(\App\Models\ThinkOceanPost::CATEGORIES as $option)
                        <a href="{{ route('think-ocean.pages.show', ['news', 'category' => $option, 'q' => $search ?: null]) }}" @class(['active' => $category === $option])>{{ $option }}</a>
                    @endforeach
                </nav>
                <form class="search" role="search" method="GET" action="{{ route('think-ocean.pages.show', 'news') }}">
                    @if($category)<input type="hidden" name="category" value="{{ $category }}">@endif
                    <label class="sr-only" for="news-search">Search articles</label>
                    <input id="news-search" type="search" name="q" value="{{ $search }}" placeholder="Search articles...">
                    <button type="submit" aria-label="Search">@include('think-ocean.partials.icon', ['name' => 'search'])</button>
                </form>
            </div>

            <div class="post-grid">
                @forelse($posts as $post)
                    @include('think-ocean.partials.post-card')
                @empty
                    <div class="empty">{{ $search || $category ? 'No articles match your search yet.' : 'News and insights will be published here.' }}</div>
                @endforelse
            </div>

            @if($posts->hasPages())
                <nav class="pager" aria-label="Pagination">
                    @if($posts->previousPageUrl())<a class="btn btn-sm btn-deep" href="{{ $posts->previousPageUrl() }}">← Newer</a>@endif
                    @if($posts->nextPageUrl())<a class="btn btn-sm btn-deep" href="{{ $posts->nextPageUrl() }}">Older →</a>@endif
                </nav>
            @endif

            <div class="cta-band newsletter" id="newsletter">
                @include('think-ocean.partials.icon', ['name' => 'mail', 'stroke' => 1.5])
                <div>
                    <h2>Stay in the loop</h2>
                    <p>Get the latest news, project updates and ocean stories delivered to your inbox.</p>
                </div>
                <div style="margin-left:auto">
                    <form method="POST" action="{{ route('think-ocean.subscribe') }}#newsletter">
                        @csrf
                        <label class="sr-only" for="newsletter-email">Email address</label>
                        <input id="newsletter-email" type="email" name="email" placeholder="Your email address" required value="{{ old('email') }}">
                        <button class="btn" type="submit">Subscribe</button>
                    </form>
                    @if(session('subscribed'))<p class="feedback" role="status">{{ session('subscribed') }}</p>@endif
                    @error('email', 'subscribe')<p class="feedback" role="alert">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </section>
@endsection
