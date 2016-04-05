@if(Auth::user())
  @include('layouts.partials.nav.user')
@else
    @include('layouts.partials.nav.guest')
@endif