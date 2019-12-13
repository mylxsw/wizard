@extends('layouts.project')
@section('page-content')
    <div class="wz-project-main" style="padding-top: 10px;">
        <nav class="wz-page-control clearfix">
            <a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $pageItem->id]) }}"
               class="btn btn-link" title="@lang('common.btn_back')"><i class="material-icons">arrow_back</i></a>
            <h1 class="wz-page-title">
                {{ $pageItem->title }}
                <span class="label label-default">@lang('document.history_document')</span>
            </h1>

            <hr />
        </nav>

        <div class="wz-page-content">
            <table class="table">
                <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="25%">@lang('document.operation_time')</th>
                    <th width="30%">@lang('document.modified_user')</th>
                    <th>@lang('common.operation')</th>
                </tr>
                </thead>
                <tbody>

                @foreach($histories as $index => $history)
                    <tr>
                        <th scope="row">{{ $history->id }}</th>
                        <td>{{ $history->created_at }}</td>
                        <td>{{ $history->operator->name }}</td>
                        <td>
                            <a href="{{ wzRoute('project:doc:history:show', ['id' => $project->id, 'p' => $pageItem->id, 'history_id' => $history->id]) }}">
                                <i class="material-icons" data-toggle="tooltip" title="@lang('common.btn_view')">remove_red_eye</i>
                            </a>
                            &nbsp;
                            @if($index < count($histories) - 1)
                                <a href="#" wz-doc-compare-submit
                                   data-doc1="{{ wzRoute('project:doc:history:json', ['history_id' => $history->id, 'id' => $project->id, 'page_id' => $pageItem->id]) }}"
                                   data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $histories[$index + 1]->id, 'id' => $project->id, 'page_id' => $pageItem->id]) }}">
                                    <i class="material-icons" data-toggle="tooltip" title=" @lang('common.btn_diff')">tonality</i>
                                </a>
                            @endif

                            @can('page-edit', $pageItem)
                                <a href="#" wz-form-submit data-form="#form-recover-{{ $history->id }}"
                                   data-confirm="@lang('document.recover_confirm')">
                                    <i class="material-icons" data-toggle="tooltip" title="@lang('document.btn_recover')">redo</i>
                                </a>
                                <form id="form-recover-{{ $history->id }}"
                                      action="{{ wzRoute('project:doc:history:recover', ['id' => $project->id, 'p' => $pageItem->id, 'history_id' => $history->id]) }}"
                                      method="post">{{ csrf_field() }}{{ method_field('PUT') }}</form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="wz-pagination">
                {{ $histories->links() }}
            </div>
        </div>
    </div>

@endsection

@include('components.doc-compare-script')