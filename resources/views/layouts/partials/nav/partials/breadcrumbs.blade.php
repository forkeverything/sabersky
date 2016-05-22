<div class="breadcrumbs">
    @foreach($breadcrumbs as $key => $breadcrumb)
        @if($key === count($breadcrumbs) - 1)
            <span class="current">{!! $breadcrumb[0] !!}</span>
        @elseif($breadcrumb[1] === '#')
            <span>{!! $breadcrumb[0] !!}</span> <span class="breadcrumb-separator">/</span>
        @else
            <a href="{{ $breadcrumb[1] }}" class="breadcrumb-link">{!! $breadcrumb[0] !!}</a> <span class="breadcrumb-separator">/</span>
        @endif
    @endforeach
</div>