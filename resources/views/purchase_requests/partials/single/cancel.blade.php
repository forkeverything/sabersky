<div class="state-control">
    <div class="cancel-pr" v-if="purchaseRequest.state === 'open'">
    <button type="button" class="btn btn-small btn-outline-red btn-show-confirm-cancel" @click="toggleConfirm" v-show="! showConfirm">Cancel</button>
    <div class="confirm-cancel" v-show="showConfirm">
        <p>Cancelling this request will only apply to outstanding quantities only. Fulfilled amounts cannot be cancelled.</p>
        <button type="button" class="btn btn-outline-grey btn-return" @click="toggleConfirm">Return</button>
        <button type="button" class="btn btn-solid-red btn-cancel" @click="sendRequest('cancel')">Yes, cancel with @{{ purchaseRequest.quantity }} quantities outstanding</button>
        </div>
    </div>
<div class="uncancel-pr"  v-if="purchaseRequest.state === 'cancelled'">
<button type="button" class="btn btn-solid-blue" @click="sendRequest('reopen')">Reopen Request</button>
</div>
</div>