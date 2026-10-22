@if($partners->isNotEmpty())
    @once
        @push('styles')
            <style>
                .partners-head{text-align:center;max-width:620px;margin:0 auto}
                .partners-head p{margin-top:10px;color:var(--muted)}
                .partner-grid{display:flex;flex-wrap:wrap;justify-content:center;gap:20px;margin-top:36px}
                .partner{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;width:200px;min-height:130px;padding:20px;text-align:center;background:#fff;border:1px solid var(--line);border-radius:var(--radius);transition:transform .25s,box-shadow .25s,border-color .25s}
                a.partner:hover{transform:translateY(-3px);border-color:#bfe3e6;box-shadow:var(--shadow)}
                .partner img{max-width:150px;max-height:64px;object-fit:contain;filter:grayscale(1);opacity:.85;transition:filter .25s,opacity .25s}
                .partner:hover img,.partner:focus-visible img{filter:none;opacity:1}
                .partner span{font:600 14px/1.3 Poppins,Inter,sans-serif;color:var(--deep)}
                .partner img + span{font:500 12px/1.3 Inter,sans-serif;color:var(--muted)}
                .partners-cta{margin-top:32px;text-align:center}
                @media (max-width:560px){.partner{width:calc(50% - 10px);min-height:110px;padding:14px}.partner img{max-width:120px;max-height:52px}}
            </style>
        @endpush
    @endonce

    <section class="section {{ $class ?? '' }}" id="partners" aria-labelledby="partners-title">
        <div class="wrap">
            <div class="partners-head">
                <h2 class="section-title" id="partners-title">Our Partners</h2>
                <p>We work alongside organisations, communities and innovators who share our commitment to a healthier ocean.</p>
            </div>
            <ul class="partner-grid" role="list" style="list-style:none;padding:0">
                @foreach($partners as $partner)
                    <li>
                        @if($partner->website_url)
                            <a class="partner" href="{{ $partner->website_url }}" target="_blank" rel="noopener" title="Visit {{ $partner->name }}">
                        @else
                            <div class="partner">
                        @endif
                            @if($partner->logoUrl())<img src="{{ $partner->logoUrl() }}" alt="{{ $partner->name }} logo" loading="lazy">@endif
                            <span>{{ $partner->name }}</span>
                        @if($partner->website_url)</a>@else</div>@endif
                    </li>
                @endforeach
            </ul>
            <p class="partners-cta"><a class="link-arrow" href="{{ route('think-ocean.pages.show', 'get-involved') }}">Become a partner @include('think-ocean.partials.icon', ['name' => 'arrow'])</a></p>
        </div>
    </section>
@endif
