<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">评论</h3>
    </div>
    <div class="panel-body">
        @if(!Auth::guest())
            <form method="post"
                  action="{{ wzRoute('project:doc:comment', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
                  id="wz-new-comment-form">
                {{ csrf_field() }}

                <div class="form-group">
                    <textarea class="form-control" rows="3" name="content" placeholder="评论内容"></textarea>
                </div>
                <div class="form-group">
                    <button type="button" id="wz-comment-submit" class="btn btn-success pull-right">评论</button>
                </div>
            </form>
        @endif
        <div class="wz-comments-box">
            <ol>
                @foreach($pageItem->comments as $comment)
                    <li class="media {{ (isset($comment_highlight) && $comment_highlight == $comment->id) ? 'wz-comment-highlight':'' }}"
                        id="cm-{{ $comment->id }}">
                        <div class="media-body">
                            <h5 class="media-heading">
                                {{ $comment->user->name }}
                                <span class="wz-comment-date glyphicon glyphicon-time">{{ $comment->created_at }}</span>
                            </h5>
                            <div class="wz-comment-body">
                                {{ $comment->content }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
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
        });
    </script>
@endpush