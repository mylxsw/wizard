
@foreach($logs as $log)
    <div class="media text-muted pt-3">
        <img src="{{ user_face($log->context->username) }}" class="wz-userface-small">
        <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
            <strong class="d-block text-gray-dark">{{ $log->created_at }}</strong>
            @if ($log->message == 'document_updated')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 修改了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
                @if(!Auth::guest())
                    【<a href="#" wz-doc-compare-submit
                        data-doc1="{{ wzRoute('project:doc:json', ['id' => $log->project_id, 'page_id' => $log->context->doc_id]) }}"
                        data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $log->context->history_id ?? 0, 'id' => $log->project_id, 'page_id' => $log->context->doc_id]) }}">@lang('common.btn_diff')</a>】
                @endif
            @elseif ($log->message == 'document_created')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 创建了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @elseif ($log->message == 'document_deleted')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 删除了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @elseif ($log->message == 'document_recovered')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 还原了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @elseif ($log->message == 'project_deleted')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 删除了项目
                <span class="wz-text-dashed">{{ $log->context->project_name }}</span>
            @elseif ($log->message == 'project_updated')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 更新了项目
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id]) }}">{{ $log->context->project_name }}</a> </span>
            @elseif ($log->message == 'project_created')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 创建了项目
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id]) }}">{{ $log->context->project_name }}</a> </span>
            @elseif ($log->message == 'comment_created')
                <span class="wz-text-dashed">{{ $log->context->username }}</span> 评论了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @endif
        </p>
    </div>
@endforeach