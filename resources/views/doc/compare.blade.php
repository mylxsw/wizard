@extends('layouts.default')

@section('container-style', 'container-fluid')
@section('content')
    @if(!$noheader)
    <nav class="wz-page-control clearfix">
        <h1 class="wz-page-title">
            @lang('document.document_differ')
        </h1>
        <ul class="nav nav-pills pull-right">
            <li><a href="javascript: window.history.go(-1)"
                   class="btn btn-link" style="margin-right: 30px;">@lang('common.btn_back')</a></li>
        </ul>
    </nav>
    @endif

    <div class="row wz-full-box" id="wz-main-box">
        <div id="wz-compared" class="wz-compare-container w-100">
            <div class="wz-compare-box">
                <div class="wz-compare-title">
                    <span class="label label-default">{{ $doc2title }}</span>
                </div>
                <div class="wz-compare-title">
                    <span class="label label-success">{{ $doc1title }}</span>
                </div>
            </div>
        </div>
    </div>
    <div id="wz-doc1-content" style="display: none;">{!! base64_encode($doc1) !!}</div>
    <div id="wz-doc2-content" style="display: none;">{!! base64_encode($doc2) !!}</div>
@endsection

@push('stylesheet')
<link href="{{ cdn_resource('/assets/vendor/mergely-3.4.4/lib/codemirror.css') }}" rel="stylesheet"/>
<link href="{{ cdn_resource('/assets/vendor/mergely-3.4.4/lib/mergely.css') }}" rel="stylesheet"/>
@endpush

@push('script')
<script src="{{ cdn_resource('/assets/vendor/base64.min.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/mergely-3.4.4/lib/codemirror.min.js') }}"></script>
<script src="{{ cdn_resource('/assets/vendor/mergely-3.4.4/lib/mergely.min.js') }}"></script>

<script>
$(function () {
    var compared = $('#wz-compared');
    compared.mergely({
        cmsettings: {
            readOnly: false,
            lineNumbers: true
        },
        editor_width: ($('#wz-main-box').width() / 2 - 40) + 'px',
        fgcolor: {
            a:'#eaffea',
            c:'#cccccc',
            d:'#ffecec'
        },
        sidebar: false,
        lhs: function(setValue) {
            setValue(Base64.decode($('#wz-doc2-content').text()));
        },
        rhs: function(setValue) {
            setValue(Base64.decode($('#wz-doc1-content').text()));
        }
    });
});
</script>
@endpush