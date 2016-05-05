<div id="po-submit-2" v-show="step === 2" class="submit-step animated" transition="slide-right">
    <div class="top-nav-buttons">
        <button type="button" class="btn btn-solid-blue btn-prev" @click="goStep(1)"><i
                class="fa fa-angle-double-left"></i>Prev Step</button>
    </div>
    <div class="row flexing">
        <div class="col-md-4">
            <div class="page-body vendor-details">
                @include('purchase_orders.submit.step-2.vendor')
            </div>
        </div>
        <div class="col-md-8">
            <div class="page-body order-details">
                @include('purchase_orders.submit.step-2.order')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="item-details page-body">
                @include('purchase_orders.submit.step-2.items')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-6 col-md-6">
                @include('purchase_orders.submit.step-2.summary')
        </div>
    </div>
    <div class="bottom-nav-buttons align-end">
        <button type="button" class="btn btn-solid-green btn-create" @click="createOrder" :disabled="! canCreateOrder">Create Order</button>
    </div>
</div>