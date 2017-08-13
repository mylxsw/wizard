@extends('layouts.default')
@section('container-style', 'container container-small')
@section('content')
    <div class="row marketing">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @yield('page-content')
                </div>
            </div>
        </div>
    </div>
@endsection
