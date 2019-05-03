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
    在 <span style="font-weight: bold;">{{ $pageItem->updated_at ?? '' }}</span> 修改了该文档。
</p>