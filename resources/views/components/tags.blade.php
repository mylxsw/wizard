<p class="wz-document-header">
        <input type="text" name="tags" placeholder="Tags"  class="tm-input"/>
</p>

@push('script')
<script>
    jQuery(".tm-input").tagsManager({
        prefilled: "{{$pageItem->tags->pluck('name')->implode(',')}}",
        CapitalizeFirstLetter: false,
        AjaxPush: '/tag',
        AjaxPushAllTags: true,
        AjaxPushParameters: { 'p': {{$pageID}} },
        delimiters: [9, 13, 44],
        backspace: [8],
        blinkBGColor_1: '#FFFF9C',
        blinkBGColor_2: '#CDE69C',
        hiddenTagListName: 'hiddenTagListA',
        hiddenTagListId: null,
        deleteTagsOnBackspace: true,
        tagsContainer: null,
        tagCloseIcon: '×',
        tagClass: '',
        validator: null,
        onlyTagList: false
    });
</script>
@endpush