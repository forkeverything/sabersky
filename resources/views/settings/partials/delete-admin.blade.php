<section id="delete-admin">
    <div class="form-group">
        <button type="button" class="btn btn-outline-red" data-toggle="modal" data-target="#modalDeleteAdminAccount">
            Permanently Delete Account & Company
        </button>
    </div>

    <!-- Modal -->
    <div class="modal" id="modalDeleteAdminAccount" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h2 class="modal-title" id="myModalLabel">Sorry to see you're thinking of leaving...</h2>
                </div>
                <div class="modal-body">
                    <p>When you delete your account: <strong>You will also lose all company information, purchasing data as well as all registered staff.</strong> Your company will have start from the beginning if you decide to join us again in the future.</p>
                </div>
                <div class="modal-footer">
                    <form action="/admin" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-outline-grey" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-solid-red">Delete Everything</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>