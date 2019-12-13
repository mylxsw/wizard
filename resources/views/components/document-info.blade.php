<div id="wz-document-info" style="margin-top: 20px; display: none;">
    <table class="table table-bordered">
        <tr>
            <th>@lang('document.last_modified_user')</th>
            <td>{{ $pageItem->lastModifiedUser->name ?? '' }}</td>
        </tr>
        <tr>
            <th>@lang('document.last_modified_time')</th>
            <td>{{ $pageItem->updated_at ?? '' }}</td>
        </tr>
        <tr>
            <th>@lang('document.creator')</th>
            <td>{{ $pageItem->user->name ?? '' }}</td>
        </tr>
        <tr>
            <th>@lang('document.create_time')</th>
            <td>{{ $pageItem->created_at ?? '' }}</td>
        </tr>
    </table>
</div>

<p class="wz-document-header">
    该文档由 <span class="wz-text-dashed">{{ $pageItem->user->name ?? '' }}</span>
    创建于 <span style="font-weight: bold;">{{ $pageItem->created_at ?? '' }} </span>，
    <span class="wz-text-dashed">{{ $pageItem->lastModifiedUser->name ?? '' }}</span>
    在 <span style="font-weight: bold;">{{ $pageItem->updated_at ?? '' }}</span> 修改了该文档
    @if(!empty($history))
        <a href="javascript:;" data-toggle="tooltip" title="看看修改了哪些内容？" wz-doc-compare-submit
           data-doc1="{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
           data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $history->id, 'id' => $project->id, 'page_id' => $pageItem->id]) }}"><span class="fa fa-question-circle"></span></a>
    @endif 。
</p>