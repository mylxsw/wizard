<p class="wz-document-header">
    @foreach($pageItem->tags as $tag)
        <span class="badge badge-pill badge-primary">{{$tag->name}}</span>
    @endforeach
</p>