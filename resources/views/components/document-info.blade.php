<div id="wz-document-info" style="margin-top: 20px; display: none;">
    <table class="table table-bordered">
        <tr>
            <th>@lang('document.last_modified_user')</th>
            <td>{{ $pageItem->lastModifiedUser->name or '' }}</td>
        </tr>
        <tr>
            <th>@lang('document.last_modified_time')</th>
            <td>{{ $pageItem->updated_at or '' }}</td>
        </tr>
        <tr>
            <th>@lang('document.creator')</th>
            <td>{{ $pageItem->user->name or '' }}</td>
        </tr>
        <tr>
            <th>@lang('document.create_time')</th>
            <td>{{ $pageItem->created_at or '' }}</td>
        </tr>
    </table>
</div>

<p style="padding-left: 17px;">
    该文档由 <span class="wz-text-dashed">{{ $pageItem->user->name or '' }}</span>
    创建于 <span style="font-weight: bold;">{{ $pageItem->created_at or '' }} </span>，
    <span class="wz-text-dashed">{{ $pageItem->lastModifiedUser->name or '' }}</span>
    在 <span style="font-weight: bold;">{{ $pageItem->updated_at or '' }}</span> 修改了该文档。
</p>