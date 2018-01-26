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

            <table class="wz-differ-box">
                <tbody>
                @foreach($differContents as $line)
                    @if($line[1] === 1) {{-- added --}}
                    <tr class="differ-added">
                        <td class="line-sign" title="新增" data-toggle="tooltip" data-placement="left">+</td>
                        <td><pre>{{ $line[0] }}</pre></td>
                    </tr>
                    @elseif($line[1] === 2) {{-- removed --}}
                    <tr class="differ-removed">
                        <td class="line-sign" title="移除">-</td>
                        <td><pre>{{ $line[0] }}</pre></td>
                    </tr>
                    @else
                        <tr>
                            <td class="line-sign">&nbsp;</td>
                            <td><pre>{{ $line[0] }}</pre></td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')

@endpush