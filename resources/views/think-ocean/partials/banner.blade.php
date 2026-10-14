<section class="banner" style="background-image:url('{{ asset('images/think-ocean/'.$image.'.jpg') }}')">
    <div class="wrap">
        <h1>{{ $title }}</h1>
        @if(! empty($subtitle))<p>{{ $subtitle }}</p>@endif
    </div>
</section>
