@php $___index = 0; @endphp
@foreach(navigatorSort($navbars) as $nav)
    <li class="wz-nav-editor-line" data-type="{{ $nav['type'] }}">
        <input type="number" class="wz-sort-level" data-id="{{ $nav['id'] }}" data-index="{{ $___index ++ }}" data-original="{{ $nav['sort_level'] }}" value="{{ $nav['sort_level'] }}">
        <a href="{{ $nav['url'] }}" target="_blank" title="{{ $nav['url'] }}" >
           <pre style="display: inline;">{{ str_pad("▌", $indent * 8, ' ', STR_PAD_LEFT) }}</pre> {{ $nav['name'] }} <span class="wz-modified-sign">*</span>
        </a>

        <span class="wz-control">
            <a href="#" class="fa fa-arrow-circle-up wz-control-up" data-direction="up"></a>
            <a href="#" class="fa fa-arrow-circle-down wz-control-down" data-direction="down"></a>
        </span>

        @if(!empty($nav['nodes']))
            <ul>
                @include('components.navbar-edit', ['navbars' => $nav['nodes'], 'indent' => $indent + 1])
            </ul>
        @endif
    </li>
@endforeach