<div class="card wz-panel-limit wz-comment-panel" style="box-shadow: none">
    <div class="card-body">
        @can('project-comment', $project)
            <form method="post"
                  action="{{ wzRoute('project:doc:comment', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
                  id="wz-new-comment-form" style="position: relative;">
                {{ csrf_field() }}
                <div class="alert alert-info wz-comment-tip">
                    <i class="fa fa-bullhorn"></i>
                    你可以在评论中 <b>@某人</b>，当前支持语法为 <b>@用户名 </b>，需要注意的是，用户名后面必须要有至少一个空格。
                </div>

                <div class="wz-comment-editor-box">
                    <img src="{{ user_face(Auth::user()->name) }}" class="wz-userface-small"/>
                    <div class="wz-comment-editor">
                        <div class="wz-comment-editor-header">
                            <button class="wz-comment-editor-write" data-tab=".wz-tab1" data-action="write">写评论</button>
                            <button class="wz-comment-editor-write wz-comment-editor-readonly" data-tab=".wz-tab2" data-action="preview">预览</button>
                        </div>
                        <div class="wz-comment-editor-body">
                            <div class="wz-tab wz-tab1">
                                <textarea class="wz-form-comment-content" rows="5" name="content" id="wz-comment-textarea" placeholder="留下你的评论"></textarea>
                            </div>
                            <div class="wz-tab wz-tab2 wz-markdown-comment" style="display: none;"></div>
                        </div>
                        <div class="wz-comment-editor-footer">
                            <span class="wz-comment-editor-tip"><i class="fa fa-magic m-2"></i>支持Markdown语法</span>
                            <button type="button" id="wz-comment-submit" class="btn btn-raised btn-success pull-right">评论</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </form>
        @endcan
        <div class="wz-comments-box">
            @forelse($pageItem->comments as $comment)
                <div class="media text-muted pt-3 {{ (isset($comment_highlight) && $comment_highlight == $comment->id) ? 'wz-comment-highlight':'' }}"
                     id="cm-{{ $comment->id }}">
                    <img src="{{ user_face($comment->user->name) }}" class="wz-userface-small" title="{{ $comment->user->name }}（{{ $comment->user->email }}）">
                    <div class="media-body pb-3 mb-0 lh-125 border-bottom border-gray wz-comment-box">
                        <div class="d-block text-gray-dark wz-comment-header"><strong>{{ $comment->user->name }}</strong> 评论于 <span class="wz-comment-time" title="{{ $comment->created_at }}">{{ $comment->created_at }}</span></div>
                        <div class="wz-comment-body wz-markdown-comment editormd-html-preview">{{ $comment->content }}</div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>

@push('stylesheet')
    <link href="{{ cdn_resource('/assets/vendor/at/css/jquery.atwho.css') }}" rel="stylesheet">
    <link href="{{ cdn_resource('/assets/vendor/markdown-body.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script src="{{ cdn_resource('/assets/vendor/jquery.caret.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/at/js/jquery.atwho.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/moment-with-locales.min.js') }}"></script>
    <script src="{{ cdn_resource('/assets/vendor/markdown-it.min.js') }}"></script>
    <script>
        $(function () {

            // @某人自动提示
            $('#wz-comment-textarea').atwho({
                at: '@',
                data: [
                    {!! ui_usernames(users()) !!}
                ]
            });

            // 发表评论
            $('#wz-comment-submit').on('click', function () {
                var form = $(this).parents('form');

                $.wz.btnAutoLock($(this));

                $.wz.asyncForm(form, {}, function (data) {
                    $.wz.message_success('发表成功', function () {
                        window.location.reload(true);
                    });
                });
            });

            var markdown = window.markdownit();

            // 评论内容解析，高亮@用户
            var users = { {!! users()->map(function ($user) { return "'{$user->id}': {name: '{$user->name}', email: '{$user->email}'}";})->implode(',') !!} };
            $('.wz-comment-body').map(function () {
                var content = markdown.render($(this).html());
                var html = content
                    .replace(/@{uid:(\d+)}/g, function (match, id) {
                        if (users.hasOwnProperty(id)) {
                            var user = users[id];
                            return ' @<span class="wz-text-dashed" style="font-weight: bold;" title="' +  user.email + '">' + user.name + '</span> ';
                        }
                    });
                $(this).html(html);
            });

            // 时间格式化
            moment.locale('zh-cn');
            $('.wz-comments-box').find('.wz-comment-time').map(function() {
                $(this).html(moment($(this).html(), 'YYYY-MM-DD hh:mm:ss').fromNow());
            });

            // 评论框标签切换
            var comment_editor = $('.wz-comment-editor');
            comment_editor.find('.wz-comment-editor-write').on('click', function () {
                if (!$(this).hasClass('wz-comment-editor-readonly')) {
                    return false;
                }

                comment_editor.find('.wz-comment-editor-write').addClass('wz-comment-editor-readonly');
                $(this).removeClass('wz-comment-editor-readonly');

                comment_editor.find('.wz-tab').hide();
                var content_area = comment_editor.find($(this).data('tab'));
                content_area.show();

                if ($(this).data('action') === 'preview') {
                    content_area.html(markdown.render(comment_editor.find('#wz-comment-textarea').val()) + "<hr />");
                }

                return false;
            });

            // 评论框提示效果
            $('.wz-form-comment-content').on('focusin', function () {
                $('.wz-comment-tip').fadeIn('fast');
            }).on('focusout', function () {
                $('.wz-comment-tip').fadeOut('fast');
            });
        });
    </script>
@endpush