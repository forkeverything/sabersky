<div id="po-submit-2" v-show="step === 2" class="submit-step animated" transition="slide-right">
    <button type="button" class="btn btn-solid-blue btn-next" @click="goStep(1)">Next Step <i class="fa fa-angle-double-right"></i></button>
    <div class="row flexing">
        <div class="col-sm-4">
            <div class="page-body">
                <h5>Vendor</h5>
                <div class="name">
                    <label>Name</label>
                    <span class="name">@{{ vendor.name }}</span>
                </div>
                <div class="select-address">
                    <label>Addresses</label>
                    <ul class="list-unstyled list-address">
                        <li v-for="address in availableAddresses">
                            <span class="phone">@{{ address.phone }}</span>
                            <span class="address_1 display-block">@{{ address.address_1 }}</span>
                                    <span class="address_2 display-block"
                                          v-if="address.address_2">@{{ address.address_2 }}</span>
                            <span class="city">@{{ address.city }}</span>,
                            <div class="zip">@{{ address.zip }}</div>
                            <div class="state-country display-block">
                                <span class="state">@{{ address.state }}</span>
                                <span class="country">@{{ address.country }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-body">
                <h5>
                    Purchase Information
                </h5>
            </div>
        </div>
    </div>
    <div class="page-body">
        <h5>Order Details</h5>
    </div>
</div>