{{ csrf_field() }}
<input type="hidden" name="project_id" id="editor-project_id" value="{{ $project->id or '' }}"/>
<input type="hidden" name="page_id" id="editor-page_id" value="{{ $pageItem->id or '' }}">
<input type="hidden" name="pid" id="editor-pid" value="{{ $pageItem->pid or '' }}">
<input type="hidden" name="last_modified_at" value="{{ $pageItem->updated_at or '' }}">
<div class="col-lg-12 wz-edit-control">

    <div class="form-group input-group">
        <span class="input-group-addon" title="项目名称">{{ $project->name }}</span>
        <input type="text" class="form-control wz-input-long" name="title" id="editor-title"
               value="{{ $pageItem->title or '' }}" placeholder="标题">
    </div>

    <div class="form-group">
        <select class="form-control" name="pid">
            <option value="0">无上级页面</option>
            @include('components.doc-options', ['navbars' => $navigator, 'level' => 0])
        </select>
    </div>

    <div class="form-group pull-right">
        <div class="btn-group">
            <button type="button" class="btn btn-success" wz-doc-form-submit id="wz-doc-form-submit">保存</button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">另存为模板</a></li>
                <li><a href="#">加入草稿箱</a></li>
                <li><a href="#" wz-doc-form-submit data-force="true">强制保存</a></li>
            </ul>
        </div>
        <a href="{{ wzRoute('project:home', ['id' => $project->id] + (empty($pageItem) ? [] : ['p' => $pageItem->id])) }}" class="btn btn-default">返回</a>
    </div>
</div>

@push('script')
<script>
$(function() {
    // 文档保存
    $('[wz-doc-form-submit]').on('click', function () {
        var force = $(this).data('force');
        var form = $(this).parents('form');

        var formSubmit = function (form, force) {
            $.wz.asyncForm(form, {
                force: force ? 1 : 0
            }, function (data) {
                $.wz.alert(data.message, function () {
                    window.location.href = data.redirect;
                });
            });
        };

        if (force) {
            $.wz.confirm('强制保存将可能覆盖其它用户提交的修改，是否确定要强制保存？', function () {
                formSubmit(form, true);
            });
        } else {
            formSubmit(form, false);
        }
    });

    @if(!$newPage)
        // 自动检查文档是否过期
        (function() {
            var lastModifiedAt = $('input[name=last_modified_at]').val();
            var checkExpiredURL = '{{ route('project:doc:expired', ['id' => $project->id, 'page_id' => $pageItem->id]) }}';
            var continueCheck = function () {
                window.setTimeout(function () {
                    $.wz.request('get', checkExpiredURL, {l:lastModifiedAt}, function (data) {
                        // 没有过期则继续检查
                        if (!data.expired) {
                            continueCheck();
                            return false;
                        }

                        // 已过期，禁用保存按钮，同时页面提示
                        $('#wz-doc-form-submit').prop('disabled', 'disabled');
                        $('#wz-error-box').fadeIn('fast').html(data.message);

                    }, continueCheck);
                }, 5000);

                return true;
            };

            continueCheck();
        })();
    @endif
});
</script>
@endpush