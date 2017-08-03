@extends('layouts.default')

@section('container-style', 'container-fluid')
@section('content')
    @include('layouts.navbar')
    <nav class="wz-page-control clearfix">
        <h1 class="wz-page-title">
            文档差异对比
        </h1>
        <ul class="nav nav-pills pull-right">
            <li>
                <a href="javascript: window.history.go(-1)" class="btn btn-link" style="margin-right: 30px;">返回</a>
            </li>
        </ul>
    </nav>

    <div class="row wz-full-box" id="wz-main-box">
        <div id="wz-compared">
            <div class="wz-compare-box">
                <div class="wz-compare-title">
                    <span class="label label-success">{{ $doc1title }}</span>
                </div>
                <div class="wz-compare-title">
                    <span class="label label-default">{{ $doc2title }}</span>
                </div>
            </div>
        </div>
    </div>
    <div id="wz-doc1-content" style="display: none;">{{ $doc1 }}</div>
    <div id="wz-doc2-content" style="display: none;">{{ $doc2 }}</div>
@endsection

@push('stylesheet')
<link href="/assets/vendor/mergely-3.4.4/lib/codemirror.css" rel="stylesheet"/>
<link href="/assets/vendor/mergely-3.4.4/lib/mergely.css" rel="stylesheet"/>
@endpush

@push('script')
<script src="/assets/vendor/mergely-3.4.4/lib/codemirror.min.js"></script>
<script src="/assets/vendor/mergely-3.4.4/lib/mergely.min.js"></script>

<script>
$(function () {
    $('#wz-compared').mergely({
        cmsettings: { readOnly: false, lineNumbers: true },
        editor_width: ($('#wz-main-box').width() / 2 - 40) + 'px',
        lhs: function(setValue) {
            setValue($('#wz-doc1-content').html());
        },
        rhs: function(setValue) {
            setValue($('#wz-doc2-content').html());
        }
    });
});
</script>
@endpush