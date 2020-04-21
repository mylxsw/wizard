@php
    $subItems = subDocuments($pageItem->id);
@endphp
@if(count($subItems) > 0)
    <div class="list-group">
    @foreach ($subItems as $item)
        <a class="list-group-item" href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $item->id]) }}">
            {{ $item->title }}
        </a>
    @endforeach
    </div>
@endif