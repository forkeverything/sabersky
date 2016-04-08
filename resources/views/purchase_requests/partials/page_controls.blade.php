<div class="page-controls">
    <div class="pr-items-per-page-selecter">
        <select-picker :name.sync="itemsPerPage" :options.sync="itemsPerPageOptions" :function="changeItemsPerPage"></select-picker>
    </div>
    @include('purchase_requests.partials.paginator')
</div>