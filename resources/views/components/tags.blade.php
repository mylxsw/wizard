<div class="card-body">
    <input type="text" name="tags" placeholder="Tags" class="tm-input" @if(Auth::guest()) style="display: none;" @endif/>
</div>

@push('script')
@if(!Auth::guest())
    <script>
        $(function () {
            $(".tm-input").tagsManager({
                prefilled: "{{$pageItem->tags->pluck('name')->implode(',')}}",
                CapitalizeFirstLetter: false,
                AjaxPush: '/tag',
                AjaxPushAllTags: true,
                AjaxPushParameters: {'p': {{$pageID}} },
                delimiters: [9, 13, 44],
                backspace: [8],
                blinkBGColor_1: '#FFFF9C',
                blinkBGColor_2: '#CDE69C',
                hiddenTagListName: 'hiddenTagListA',
                hiddenTagListId: null,
                deleteTagsOnBackspace: true,
                tagsContainer: null,
                tagCloseIcon: '×',
                tagClass: 'tm-tag-success',
                validator: null,
                onlyTagList: false
            });
        });
    </script>
@else
    <script>
        $(function () {
            $(".tm-input").tagsManager({
                prefilled: "{{$pageItem->tags->pluck('name')->implode(',')}}",
                CapitalizeFirstLetter: false,
                delimiters: [9, 13, 44],
                backspace: [8],
                blinkBGColor_1: '#FFFF9C',
                blinkBGColor_2: '#CDE69C',
                hiddenTagListName: 'hiddenTagListA',
                hiddenTagListId: null,
                deleteTagsOnBackspace: true,
                tagsContainer: null,
                tagCloseIcon: '×',
                tagClass: 'tm-tag-disabled',
                validator: null,
                onlyTagList: false
            });
        });
    </script>
@endif
@endpush