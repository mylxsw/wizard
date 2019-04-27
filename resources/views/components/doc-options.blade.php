@foreach($navbars as $nav)
    <option value="{{ $nav['id'] }}" {{ $nav['selected'] ? 'selected' : ' ' }}>{!! str_repeat('&nbsp;', $level * 8) !!}{{ $nav['name'] }}</option>
    @if(!empty($nav['nodes']))
        @include('components.doc-options', ['navbars' => $nav['nodes'], 'level' => $level + 1])
    @endif
@endforeach