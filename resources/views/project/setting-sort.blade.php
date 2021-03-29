@extends('layouts.project-setting')

@section('project-setting')
    <div class="card card-white">
        <div class="card-body">
            <form method="post" action="{{ wzRoute('project:setting:handle', ['id' => $project->id]) }}"
                  id="wz-sort-form">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="op" value="{{ $op }}">

                <div class="form-group wz-nav-editor">
                    <ul>@include('components.navbar-edit', ['navbars' => $navigators, 'indent' => 0])</ul>
                </div>

                <div class="form-group">
                    <button type="button"
                            class="btn btn-success btn-raised wz-save-sort">@lang('common.btn_save')</button>
                    <a href="{{ wzRoute('project:home', ['id' => $project->id]) }}"
                       class="btn btn-default">@lang('common.btn_back')</a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $(function () {
            // 重新生成排序值
            var regenSortValue = function (parentUl) {
                var items = [];
                parentUl.children('li').map(function () {
                    items.push($(this));
                });

                for (var i in items) {
                    var inputEle = items[i].children('input.wz-sort-level');

                    inputEle.val(i * 10);
                    updateStatus(inputEle);
                }
            };

            // 更新输入框颜色
            var updateStatus = function (inputEle) {
                var newVal = parseInt(inputEle.val());
                var oldVal = parseInt(inputEle.data('original'));

                if (newVal !== oldVal) {
                    inputEle.parent('li').find('.wz-modified-sign').show();
                } else {
                    inputEle.parent('li').find('.wz-modified-sign').hide();
                }
            };

            // 更新排序展示
            var updateSortOrder = function (parentUl) {
                var resorted = [];
                parentUl.children('li').map(function () {
                    var sortLevel = parseInt($(this).children('input.wz-sort-level').val());
                    if (!resorted[sortLevel]) {
                        resorted[sortLevel] = [];
                    }

                    resorted[sortLevel].push($(this));
                });

                var items = [];
                for (var i in resorted) {
                    for (var j in resorted[i]) {
                        items.push({
                            sort: parseInt(i),
                            ele: resorted[i][j],
                            index: parseInt(resorted[i][j].children('input.wz-sort-level').data('index')),
                        });
                    }
                }

                var sortInner = function (a, b) {
                    if (a.sort > b.sort) {
                        return -1;
                    } else if (a.sort < b.sort) {
                        return 1;
                    } else {
                        return a.index > b.index ? -1 : 1;
                    }
                };

                items = items.sort(function (a, b) {
                    var aIsFolder = a.ele.find('ul').length > 0;
                    var bIsFolder = b.ele.find('ul').length > 0;

                    var bothIsFolder = aIsFolder && bIsFolder;
                    var bothNotFolder = (!aIsFolder) && (!bIsFolder);

                    if (bothIsFolder || bothNotFolder) {
                        return sortInner(a, b);
                    } else {
                        return aIsFolder ? 1 : -1;
                    }
                });

                for (var i in items) {
                    parentUl.prepend(items[i].ele);
                }

                regenSortValue(parentUl);
            };


            $('.wz-nav-editor')
                .on('blur', 'input.wz-sort-level', function () {
                    var newVal = parseInt($(this).val());
                    var oldVal = parseInt($(this).data('original'));

                    if (newVal !== oldVal) {
                        // 更新页面展示排序
                        var parentUl = $(this).parent('li').parent('ul');
                        updateSortOrder(parentUl);
                    }
                })
                .on('click', '.wz-control > a', function (e) {
                    e.preventDefault();

                    var parentLi = $(this).parent('.wz-control').parent('li');
                    var direction = $(this).data('direction');

                    if (direction === 'up') {
                        var previous = parentLi.prev();
                        if (previous.length > 0) {
                            parentLi.children('input.wz-sort-level').val(parseInt(previous.children('input.wz-sort-level').val()) - 1);
                            updateSortOrder(parentLi.parent('ul'));
                        }
                    } else {
                        var next = parentLi.next();
                        if (next.length > 0) {
                            parentLi.children('input.wz-sort-level').val(parseInt(next.children('input.wz-sort-level').val()) + 1);
                            updateSortOrder(parentLi.parent('ul'));
                        }
                    }

                });

            $('.wz-save-sort').on('click', function () {
                $.wz.btnAutoLock($(this));

                var params = [];

                $('input.wz-sort-level').map(function () {
                    var id = $(this).data('id');
                    var newVal = parseInt($(this).val());
                    var oldVal = parseInt($(this).data('original'));

                    if (oldVal !== newVal) {
                        params.push({
                            id: id,
                            sort_level: newVal
                        });
                    }
                });

                var req = {
                    project: $('input[name=project_id]').val(),
                    op: $('input[name=op]').val(),
                    sort_levels: JSON.stringify(params)
                };

                $.wz.dynamicFormSubmit('dynamic-form', 'post', $('#wz-sort-form').prop('action'), req);
            });
        });
    </script>
@endpush