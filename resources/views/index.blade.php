@extends('layout.default')

@section('container-style', 'container-small')
@section('content')

    @include('layout.navbar')

    <div class="row marketing">
        <div class="col-lg-12">
            @foreach($projects ?? [] as $proj)
            <div class="col-lg-3">
                <a class="wz-box" href="/{{ $proj->id }}" title="{{ $proj->description }}">
                    <p class="wz-title">{{ $proj->title }}</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>

@endsection