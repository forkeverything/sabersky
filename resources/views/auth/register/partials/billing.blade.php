<form id="form-registration-billing" v-show="section === 'billing'" @submit.prevent="submitBilling" v-el:stripe-form>
    <h4>Credit Card</h4>
    <div class="shift-label-input validated-input"
        :class="{
            'is-filled': ccNumber,
            'is-error': cardError.param === 'number'
        }"
    >
        <input data-stripe="number"
               type="text"
               required
               size="20"
               v-model="ccNumber"
        >
        <label placeholder="Card Number"></label>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="shift-label-input">
                <input data-stripe="name"
                       type="text"
                       required
                       v-model="ccName"
                >
                <label placeholder="Name On Card"></label>
            </div>
        </div>
        <div class="col-sm-6 expiry">
            <div class="shift-label-input month validated-input"
                 :class="{
                    'is-filled': ccExpMonth,
                    'is-error': cardError.param === 'exp_month'
                }"
            >
                <input data-stripe="exp_month"
                       type="text"
                       required
                       size="2"
                       v-model="ccExpMonth"
                >
                <label placeholder="MM"></label>
            </div>
            <span class="separator">/</span>
            <div class="shift-label-input year validated-input"
                 :class="{
                    'is-filled': ccExpYear,
                    'is-error': cardError.param === 'exp_year'
                }"
            >
                <input data-stripe="exp_year"
                       type="text"
                       required
                       size="4"
                       v-model="ccExpYear"
                >
                <label placeholder="YYYY"></label>
            </div>
        </div>
    </div>
    <div class="shift-label-input validated-input"
         :class="{
                    'is-filled': ccCVC,
                    'is-error': cardError.param === 'cvc'
                }"
    >
        <input data-stripe="cvc"
               type="text"
               required
               size="4"
               v-model="ccCVC"
        >
        <label placeholder="CVC"></label>
    </div>
    <div class="billing-buttons">
        <button type="submit" class="btn btn-solid-green" :disabled="! validCardDetails">@{{ registerButtonText }}</button>
        <button type="button" class="btn btn-solid-grey" @click="goToSection('account')">Back to account</button>
    </div>
</form>