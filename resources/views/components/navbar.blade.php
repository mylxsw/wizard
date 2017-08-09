@foreach($navbars as $nav)
    <li class="{{ $nav['selected'] ? 'active' : '' }}">
        <a href="{{ $nav['url'] }}" title="{{ $nav['name'] }}" class="wz-nav-item">
            {{ $nav['name'] }}
            @if($nav['type'] == 'sw')
                <span class="wz-nav-item-badge label label-success">sw</span>
            @endif
        </a>
        @if(!empty($nav['nodes']))
            <ul>
                @include('components.navbar', ['navbars' => $nav['nodes']])
            </ul>
        @endif
    </li>
@endforeach