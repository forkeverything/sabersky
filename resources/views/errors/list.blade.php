@if ($errors->any())
    <div class="validation-errors">
        <h5 class="errors-heading">Error(s) occurred</h5>
        <ul class="errors-list list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif