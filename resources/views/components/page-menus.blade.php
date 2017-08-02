<li role="presentation" class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
       aria-haspopup="true" aria-expanded="false">
        更多 <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li><a href="#">分享</a></li>
        <li><a href="#">导出</a></li>
        <li>
            <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}">页面历史</a>
        </li>
        @can('page-edit', $pageItem)
            <li>
                <form id="form-{{ $pageItem->id }}" method="post"
                      action="{{ wzRoute('project:doc:delete', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">
                    {{ method_field('DELETE') }}{{ csrf_field() }}
                </form>
                <a href="#" wz-form-submit data-form="#form-{{ $pageItem->id }}"
                   data-confirm="确定要删除文档“{{ $pageItem->title }}”？">删除</a>
            </li>
        @endcan
    </ul>
</li>