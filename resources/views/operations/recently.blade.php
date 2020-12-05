
@foreach($logs as $log)
    @if(empty($log->context->username))
        @continue
    @endif
    <div class="media text-muted pt-3">
        <img src="{{ user_face($log->context->username) }}" class="wz-userface-small">
        <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
            <span class="d-block text-gray-dark">
                <span class="wz-text-dashed">{{ $log->context->username }}
                    @if(!empty($log->context->impersonate))
                        <i style="font-size: 90%; color: #848484">（扮演者：{{ $log->context->impersonate->name ?? ''}}） </i>
                    @endif
                </span> 在
                <span class="wz-operation-log-time" title="{{ $log->created_at }}">{{ $log->created_at }}</span>
            </span>
            @if ($log->message == 'document_updated')
                 修改了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
                @if(!Auth::guest())
                    【<a href="#" wz-doc-compare-submit
                        data-doc1="{{ wzRoute('project:doc:json', ['id' => $log->project_id, 'page_id' => $log->context->doc_id]) }}"
                        data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $log->context->history_id ?? 0, 'id' => $log->project_id, 'page_id' => $log->context->doc_id]) }}">@lang('common.btn_diff')</a>】
                @endif
            @elseif ($log->message == 'document_created')
                创建了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @elseif ($log->message == 'document_deleted')
                删除了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @elseif ($log->message == 'document_mark_updated')
                将文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
                标记为 {{ $log->context->status == \App\Repositories\Document::STATUS_NORMAL ? '正常' : '已过时' }}
            @elseif ($log->message == 'document_recovered')
                还原了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @elseif ($log->message == 'project_deleted')
                删除了项目
                <span class="wz-text-dashed">{{ $log->context->project_name }}</span>
            @elseif ($log->message == 'project_updated')
                更新了项目
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id]) }}">{{ $log->context->project_name }}</a> </span>
            @elseif ($log->message == 'project_created')
                创建了项目
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id]) }}">{{ $log->context->project_name }}</a> </span>
            @elseif ($log->message == 'comment_created')
                评论了文档
                <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $log->project_id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
            @endif
        </p>
    </div>
@endforeach