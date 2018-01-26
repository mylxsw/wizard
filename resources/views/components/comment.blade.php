<div class="card wz-panel-limit" style="box-shadow: none">
    <div class="card-body">
        @can('project-comment', $project)
            <form method="post"
                  action="{{ wzRoute('project:doc:comment', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
                  id="wz-new-comment-form" style="position: relative;">
                {{ csrf_field() }}
                <div class="alert alert-info wz-comment-tip" style="display: none; position: absolute; bottom: -80px;">
                    <strong>提示</strong>
                    你可以在评论中 <b>@某人</b>，当前支持语法为 <b>@用户名 </b>，需要注意的是，用户名后面必须要有至少一个空格。
                </div>
                <div class="form-group">
                    <label for="wz-comment-textarea" class="bmd-label-floating">评论内容</label>
                    <textarea class="form-control wz-form-comment-content" rows="3" name="content" id="wz-comment-textarea"></textarea>
                </div>
                <div class="form-group">
                    <button type="button" id="wz-comment-submit" class="btn btn-raised btn-success pull-right">评论</button>
                </div>
            </form>
        @endcan
        <div class="wz-comments-box">
            @forelse($pageItem->comments as $comment)
                <div class="media text-muted pt-3 {{ (isset($comment_highlight) && $comment_highlight == $comment->id) ? 'wz-comment-highlight':'' }}"
                     id="cm-{{ $comment->id }}">
                    <img src="{{ user_face($comment->user->name) }}" class="wz-userface-small">
                    <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">{{ $comment->user->name }} {{ $comment->created_at }}</strong>
                        {{ $comment->content }}
                    </p>
                </div>
            @empty
                该文档还没有评论~
            @endforelse
        </div>
    </div>
</div>


@push('script')
    <script>
        $(function () {
            $('#wz-comment-submit').on('click', function () {
                var form = $(this).parents('form');

                $.wz.btnAutoLock($(this));

                $.wz.asyncForm(form, {}, function (data) {
                    $.wz.message_success('发表成功', function () {
                        window.location.reload(true);
                    });
                });
            });

            $('.wz-comment-body').map(function () {
                var html = $(this).html()
                    .replace(/(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|&|-)+)/g, ' <a target="_blank" href="$1$2"><span class="glyphicon glyphicon-link"></span> $1$2</a> ')
                    .replace(/@(.*?)(?:\s|$)/g, ' @<span class="wz-text-dashed" style="font-weight: bold;">$1</span> ');
                $(this).html(html);
            });

            $('.wz-form-comment-content').on('focusin', function () {
                $('.wz-comment-tip').fadeIn('fast');
            }).on('focusout', function () {
                $('.wz-comment-tip').fadeOut('fast');
            });
        });
    </script>
@endpush