@extends('layouts.default')
@section('container-style', 'container container-medium')
@section('content')
    <div class="row marketing wz-main-container-full">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    @yield('page-content')
                </div>
            </div>
        </div>
    </div>
@endsection
