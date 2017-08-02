@if(!empty($error) && $error instanceof \Illuminate\Support\ViewErrorBag && $error->count() > 0)
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </div>
@endif

<div class="alert alert-danger" role="alert" id="wz-error-box" style="display: none;"></div>