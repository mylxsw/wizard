@extends('layouts.default')

@section('title', '首页')
@section('container-style', 'container container-small')
@section('content')

    @include('layouts.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            @foreach($projects ?? [] as $proj)
                <div class="col-lg-3">
                    <a class="wz-box" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}"
                       title="{{ $proj->description }}">
                        @include('components.project-tag', ['proj' => $proj])
                        <p class="wz-title">{{ $proj->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection