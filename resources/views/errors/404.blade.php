@extends('layouts.default')
@section('title', "404-您访问的页面（资源）不存在")
@section('container-style', 'container container-small')
@section('content')

    <div class="row">
        <div class="card-body center">
            <div class="card text-white bg-warning" style="">
                <div class="card-header">ERROR 404</div>
                <div class="card-body">
                    <h5 class="card-title">您访问的页面（资源）不存在</h5>
                    <p class="card-text"></p>
                </div>
            </div>
        </div>
    </div>
@endsection