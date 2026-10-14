@php
    $fallbacks = ['project-reef', 'project-fisheries', 'project-blue-carbon', 'project-education'];
    $image = $project->image_path
        ? (str_starts_with($project->image_path, 'http') ? $project->image_path : asset($project->image_path))
        : asset('images/think-ocean/'.$fallbacks[$project->id % count($fallbacks)].'.jpg');
@endphp
<article class="card">
    <a class="card-media" href="{{ route('think-ocean.projects.show', $project) }}" tabindex="-1" aria-hidden="true"><img src="{{ $image }}" alt="" loading="lazy"></a>
    <div class="card-body">
        <h3><a href="{{ route('think-ocean.projects.show', $project) }}">{{ $project->title }}</a></h3>
        <p>{{ $project->summary }}</p>
        <a class="link-arrow" href="{{ route('think-ocean.projects.show', $project) }}">Learn More @include('think-ocean.partials.icon', ['name' => 'arrow'])<span class="sr-only"> about {{ $project->title }}</span></a>
    </div>
</article>
