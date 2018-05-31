@extends('layouts.default')

@section('title', '搜索')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4 mb-4">
        <div class="card-header">
            @if (!empty($project_id))
                <button type="button" data-href="{{ route('project:home', ['id' => $project_id]) }}" class="btn btn-default bmd-btn-icon" id="wz-document-goback">
                    <i class="material-icons">arrow_back</i>
                </button>
            @endif
            @if (!empty($tag))
                <span class="tm-tag tm-tag-success">{{ $tag }}</span>
            @else
            文档搜索
            @endif

            @if (!empty($project_id))
                （共 <b>{{ $documents->total() }}</b> 个文档）
            @endif


        </div>
        <div class="card-body">

            @if (empty($tag))
                <form action="{{ wzRoute('search:search') }}" method="get">
                    <div class="row marketing wz-main-container-full search-panel">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control mr-3" placeholder="输入要搜索的文档标题" name="keyword" value="{{ $keyword ?? '' }}">
                            <input type="hidden" name="project_id" value="{{ $project_id ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit">搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif

            <div class="row marketing">
                <div class="col-12">
                    @foreach($documents as $doc)
                        <div class="media text-muted pt-3">
                            <img src="{{ user_face($doc->user->name) }}" class="wz-userface-small">
                            <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
                                <strong class="d-block text-gray-dark">
                                    <a href="{{ route('project:home', ['id' => $doc->project_id, 'p' => $doc->id]) }}" style="font-size: 1.1rem;">{{ $doc->title }}</a>
                                    <span style="color: #a4a4a4;">{{ $doc->project->name ?? '' }}
                                        @if(!empty($doc->project->catalog_id))
                                            <a href="{{ route('home', ['catalog' => $doc->project->catalog_id]) }}">#{{ $doc->project->catalog->name ?? '' }}</a>
                                        @endif
                                    </span>
                                </strong>
                                由
                                <span class="wz-text-dashed">{{ $doc->user->name ?? '' }}</span>
                                最后更新于
                                <span class="wz-text-dashed">{{ $doc->updated_at ?? '' }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="wz-pagination mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')


    <script>
        $(function () {

        });
    </script>
@endpush
