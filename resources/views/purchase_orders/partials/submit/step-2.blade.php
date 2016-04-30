<div id="po-submit-2" v-show="step === 2" class="submit-step animated" transition="slide-right">
    <button type="button" class="btn btn-solid-blue btn-next" @click="goStep(1)">Next Step <i class="fa fa-angle-double-right"></i></button>
    <div class="row flexing">
        <div class="col-sm-4">
            <div class="page-body">
                <h5>Vendor Details</h5>
            </div>
        </div>
        <div class="col-sm-8">

        </div>
    </div>
    <div class="page-body">
        <h5>Order Details</h5>
    </div>
</div>