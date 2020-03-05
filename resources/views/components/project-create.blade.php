
<div class="modal fade" id="wz-new-project" tabindex="-1" role="dialog" aria-labelledby="wz-new-project">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('project.new_project')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ wzRoute('project:new:handle') }}" id="wz-project-save-form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="project-name" class="bmd-label-floating">@lang('project.project_name')：</label>
                        <input type="text" name="name" placeholder="@lang('project.project_name')" class="form-control" id="project-name">
                    </div>
                    <div class="form-group">
                        <label for="catalog-status" class="bmd-label-floating">目录</label>
                        <select id="catalog-status" name="catalog" class="form-control">
                            <option value="0" {{ isset($defaultCatalog) && empty($defaultCatalog) ? 'selected':'' }}>无</option>
                            @foreach(allCatalogs() as $cat)
                                <option value="{{ $cat->id }}" {{ ($defaultCatalog ?? null) === $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="project-description" class="bmd-label-floating">@lang('project.description')：</label>
                        <textarea class="form-control" name="description" placeholder="@lang('project.description')" id="project-description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="project-visibility" class="bmd-label-floating">@lang('project.privilege')：</label>
                        <div class="radio mt-2">
                            <label class="radio-inline">
                                <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" checked>
                                @lang('project.privilege_public')
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}">
                                @lang('project.privilege_private')
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="project-sort" class="bmd-label-floating">排序（值越大越靠后）</label>
                        <input type="number" name="sort_level" class="form-control float-left w-75" id="project-sort" value="1000" {{ Auth::user()->can('project-sort') ? '' : 'disabled' }}/>
                        <i class="fa fa-question-circle ml-2" data-toggle="tooltip" title="" data-original-title="只有管理员可以修改"></i>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-raised mr-2" id="wz-project-save">@lang('common.btn_save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.btn_close')</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            $('#wz-project-save').on('click', function () {
                var form = $('#wz-project-save-form');

                $.wz.btnAutoLock($(this));

                $.wz.asyncForm(form, {}, function (data) {
                    window.location.reload(true);
                });
            });
        });
    </script>
@endpush
