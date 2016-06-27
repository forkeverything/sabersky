<div id="registration-billing" v-show="section === 'billing'">
    <h4>Credit Card</h4>
    <form-credit-card></form-credit-card>
    <a class="link-return-account" href="#" @click.prevent="goToSection('account')">Back to account</a>
</div>