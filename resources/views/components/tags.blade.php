@if(!Auth::guest() || $pageItem->tags->count() > 0)
    <div class="wz-tag-container">
        <div class="wz-tags">
            @can('page-edit', $pageItem)
                <input type="text" name="tags" placeholder="添加标签" class="tm-input wz-tag-input"/>
                @push('script')
                    <script>
                        $(function () {
                            $(".tm-input").tagsManager({
                                prefilled: "{{ $pageItem->tags->pluck('name')->implode(',') }}",
                                CapitalizeFirstLetter: false,
                                AjaxPush: '/tag',
                                AjaxPushAllTags: true,
                                AjaxPushParameters: {'p': {{ $pageID }} },
                                delimiters: [44, 9, 13], // 支持tab, enter, comma分隔标签
                                backspace: [], // 不使用删除键删除
                                hiddenTagListName: 'hiddenTagListA',
                                hiddenTagListId: null,
                                deleteTagsOnBackspace: true,
                                tagsContainer: null,
                                tagCloseIcon: '<span class="fa fa-close" style="color: #545454;"></span>',
                                tagClass: 'tm-tag-success',
                                validator: null,
                                onlyTagList: false
                            });
                        });
                    </script>
                @endpush
            @else
                @foreach($pageItem->tags->pluck('name') as $tag)
                    <span class="tm-tag tm-tag-disabled"><span>{{ $tag }}</span></span>
                @endforeach
            @endcan
        </div>
    </div>
@endif


@push('script')
    <script>
        $(function () {
            $('.wz-tag-container').on('click', '.tm-tag>span', function () {
                window.location.href = "{{ wzRoute('search:search') }}?tag=" + $(this).text();
            });
        });
    </script>
@endpush