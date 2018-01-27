@extends('layouts.default')
@section('title', "403-{$exception->getMessage()}")
@section('container-style', 'container container-small')
@section('content')

    <div class="row">
        <div class="card-body center">
            <div class="card text-white bg-warning" style="">
                <div class="card-header">ERROR 403</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $exception->getMessage() }}</h5>
                    <p class="card-text"></p>
                </div>
            </div>
        </div>
    </div>
@endsection