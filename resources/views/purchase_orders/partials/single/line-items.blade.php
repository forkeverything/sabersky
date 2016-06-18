 <div class="table-controls table-view-select">
    <div class="requests">
            <a class="clickable dotted"
               :class="{ 'active': tableView === 'requests' }"
            @click="changeTable('requests')"
            >
            Requests</a>
        </div>
        <div class="items">
            <a class="clickable dotted"
               :class="{ 'active': tableView === 'items' }"
            @click="changeTable('items')"
            >
            Items
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <!-- PO Single - Requests Table -->
        @include('purchase_orders.partials.single.table-requests')
        <!-- PO Single - Items Table -->
        @include('purchase_orders.partials.single.table-items')
    </div>