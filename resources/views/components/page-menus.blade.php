<li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
       aria-haspopup="true" aria-expanded="false">
        @lang('common.btn_more') <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li><a href="#" wz-wait-develop>@lang('common.btn_share')</a></li>
        <li><a href="#" wz-wait-develop>@lang('common.btn_export')</a></li>
        <li>
            <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}">@lang('document.page_history')</a>
        </li>
        @can('page-edit', $pageItem)
            <li>
                <form id="form-{{ $pageItem->id }}" method="post"
                      action="{{ wzRoute('project:doc:delete', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                    {{ method_field('DELETE') }}{{ csrf_field() }}
                </form>
                <a href="#" wz-form-submit data-form="#form-{{ $pageItem->id }}"
                   data-confirm="@lang('document.delete_confirm', ['title' => $pageItem->title])">@lang('common.btn_delete')</a>
            </li>
        @endcan
    </ul>
</li>