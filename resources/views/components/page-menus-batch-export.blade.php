<li role="presentation" class="mr-2">
    <button type="button" data-toggle="modal" data-target="#wz-export" title="导出全部文档"
            class="btn btn-primary bmd-btn-icon" id="wz-export-trigger">
        <span class="fa fa-download"></span>
    </button>
</li>

<div class="modal fade" id="wz-export" tabindex="-1" role="dialog" aria-labelledby="wz-export">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">导出为</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group wz-document-form-select">
                    <label for="form-pid" class="bmd-label-static">选择要导出的目录</label>
                    <select class="form-control" name="pid" id="wz-export-pid">
                        <option value="0">所有文件</option>
                        @include('components.doc-options', ['navbars' => navigator($project->id, 0), 'level' => 0, 'excludeLeaf' => true])
                    </select>
                </div>

                <a href="#" class="dropdown-item wz-export" data-type="pdf">
                    <span class="fa fa-download mr-2"></span>
                    PDF
                </a>
                <a href="#" class="dropdown-item wz-export" data-type="raw">
                    <span class="fa fa-download mr-2"></span>
                    原始内容
                </a>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        $(function () {
            $('.wz-export').on('click', function (e) {
                e.preventDefault();

                var type = $(this).data('type');
                var pid = $('#wz-export-pid').val();

                layer.load(3, {time: (type === 'raw' ? 3 : 6) * 1000});

                $.wz.dynamicFormSubmit(
                    'generate-archive-{{ $project->id }}',
                    'POST',
                    '{{ wzRoute('export:batch', ['project_id' => $project->id]) }}',
                    {
                        type: type,
                        pid: pid, // 指定pid可以只下载指定目录下的内容
                    }
                )
            });

        });
    </script>
@endpush
