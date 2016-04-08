@if ($errors->any())
    <div class="validation-errors">
        <h5 class="errors-heading"><i class="fa fa-warning"></i>Could not process request due to</h5>
        <ul class="errors-list list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif