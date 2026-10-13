<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $summary }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $title }} | Think Ocean</title>
    <style>
        :root{--navy:#063f52;--teal:#00a9b5;--ink:#15333d;--muted:#5f7780;--line:#dcebed}*{box-sizing:border-box}body{margin:0;background:#f4fafb;color:var(--ink);font-family:Arial,sans-serif;line-height:1.75}a{color:inherit;text-decoration:none}.wrap{width:min(90%,920px);margin:auto}.header{background:#fff;border-bottom:1px solid var(--line)}.nav{min-height:76px;display:flex;align-items:center;justify-content:space-between;gap:20px}.brand{color:var(--navy);font-weight:900;letter-spacing:.08em}.back{color:#08758b;font-weight:800}.hero{padding:76px 0 42px}.eyebrow{text-transform:uppercase;letter-spacing:.16em;font-size:12px;font-weight:900;color:#08758b}.hero h1{font-size:clamp(38px,6vw,66px);line-height:1.05;letter-spacing:-.04em;color:var(--navy);margin:12px 0 20px}.summary{font-size:20px;color:var(--muted);max-width:760px}.meta{font-weight:800;color:#08758b}.cover{width:100%;max-height:520px;object-fit:cover;border-radius:24px;box-shadow:0 20px 50px #063f5220}.article{background:#fff;border:1px solid var(--line);border-radius:22px;padding:clamp(28px,6vw,60px);margin:36px auto 72px;box-shadow:0 18px 50px #063f520a}.article p{font-size:18px;white-space:pre-line}.actions{display:flex;gap:14px;flex-wrap:wrap;margin-top:34px}.button{display:inline-flex;align-items:center;min-height:46px;padding:10px 22px;border-radius:999px;background:var(--teal);color:#fff;font-weight:800}.button.secondary{background:#e5f6f7;color:var(--navy)}.footer{padding:28px 0;background:var(--navy);color:#d8f0f2}.footer .wrap{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap}
    </style>
</head>
<body>
    <header class="header"><nav class="wrap nav" aria-label="Page navigation"><a class="brand" href="{{ route('think-ocean') }}">THINK OCEAN</a><a class="back" href="{{ route('think-ocean') }}">← Back to Think Ocean</a></nav></header>
    <main>
        <header class="wrap hero"><span class="eyebrow">{{ $type }}</span><h1>{{ $title }}</h1><p class="summary">{{ $summary }}</p>@if($meta)<p class="meta">{{ $meta }}</p>@endif</header>
        @if($imagePath)<div class="wrap"><img class="cover" src="{{ asset($imagePath) }}" alt="{{ $title }}"></div>@endif
        <article class="wrap article"><p>{{ $content ?: $summary }}</p><div class="actions"><a class="button" href="mailto:{{ $contactEmail }}?subject={{ rawurlencode('Think Ocean enquiry: '.$title) }}">Contact Think Ocean</a><a class="button secondary" href="{{ route('think-ocean').'#get-involved' }}">Get involved</a></div></article>
    </main>
    <footer class="footer"><div class="wrap"><span>© {{ date('Y') }} Think Ocean</span><span><a href="{{ route('think-ocean.privacy') }}">Privacy</a> · <a href="{{ route('think-ocean.accessibility') }}">Accessibility</a> · <a href="{{ route('home') }}">Omney Safari</a></span></div></footer>
</body>
</html>
