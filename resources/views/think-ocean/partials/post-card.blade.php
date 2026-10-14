@php
    $fallbacks = ['news-turtle', 'news-cleanup', 'news-fish', 'news-boat', 'news-mangrove', 'news-diver'];
    $image = $post->image_path
        ? (str_starts_with($post->image_path, 'http') ? $post->image_path : asset($post->image_path))
        : asset('images/think-ocean/'.$fallbacks[$post->id % count($fallbacks)].'.jpg');
@endphp
<article class="card post-card">
    <a class="card-media" href="{{ route('think-ocean.posts.show', $post) }}" tabindex="-1" aria-hidden="true"><img src="{{ $image }}" alt="" loading="lazy"></a>
    <div class="card-body">
        <span class="post-category">{{ $post->category }}</span>
        <h3><a href="{{ route('think-ocean.posts.show', $post) }}">{{ $post->title }}</a></h3>
        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j M Y') }}</time>
        <a class="link-arrow" href="{{ route('think-ocean.posts.show', $post) }}">Read More @include('think-ocean.partials.icon', ['name' => 'arrow'])<span class="sr-only"> about {{ $post->title }}</span></a>
    </div>
</article>
