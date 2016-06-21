@if($user->active)
    <!-- Deactivate User Modal Trigger -->
    <button type="button" class="btn btn-outline-grey" data-toggle="modal" data-target="#modal-deactivate-user">
        Deactivate
    </button>

    <!-- Deactivate User Modal -->
    <div class="modal fade" id="modal-deactivate-user" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Deactivate Account - {{ $user->name }}</h4>
                </div>
                <div class="modal-body">
                    Deactivating an account will lock the user out of Sabersky and they will no longer be able to login
                    or perform any other actions. <strong>You will not be billed for deactivated users & accounts can be
                        reactivated again at any time.</strong>
                </div>
                <div class="modal-footer">
                    <form action="/staff/{{ $user->id }}/active" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button" class="btn btn-outline-grey" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-solid-red">Deactivate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@else
    <form action="/staff/{{ $user->id }}/active" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <button type="submit" class="btn btn-solid-green">Activate</button>
    </form>
@endif