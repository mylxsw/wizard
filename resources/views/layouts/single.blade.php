@extends('layouts.default')
@section('container-style', 'container container-medium')
@section('content')
    <div class="row marketing wz-main-container-full wz-white-panel">
        <div class="col">
            <div class="card card-white">
                <div class="card-body">
                    @yield('page-content')
                </div>
            </div>
        </div>
    </div>
@endsection
