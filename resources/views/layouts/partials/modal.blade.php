<div class="modal-roles modal" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-center">@{{ modalTitle }}</h5>
                </div>
                <div class="modal-body">
                    <p>@{{ modalBody }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-ok btn-confirm"
                       :class="{
                        'btn-primary': modalMode === 'update',
                        'btn-danger': modalMode === 'remove'
                       }"
                    @click="modalFunction" data-dismiss="modal">@{{ modalMode }}</a>
                </div>
            </div>
        </div>
    </div>
</div>