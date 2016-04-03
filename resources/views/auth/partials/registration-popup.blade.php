<registration-popup inline-template>
    <button class="btn btn-solid-green button-nav-signup no-outline"
    @click="toggleShowRegistrationPopup"
    >
    Get started</button>
    <div id="registration-popup-overlay"
         v-show="showRegisterPopup"
         class="animated"
         transition="fade"
         v-cloak
         @click="toggleShowRegistrationPopup"
    >
        <div class="popup-content"
             @click.stop=""
        >
            <span class="button-remove clickable"
            @click="toggleShowRegistrationPopup"
            >
            <i class="fa fa-close"></i></span>
            <div class="popup-header">
                <h1 class="popup-title">
                    Super-charge your operation.
                    <span class="popup-subheading">Free for up to 5 Team Members</span>
                </h1>
            </div>
            <form id="form-registration">

                <div class="shift-label-input validated-input"
                     :class="{
                    'is-filled': validCompanyName !== 'unfilled',
                    'is-loading': validCompanyName === 'loading',
                    'is-success': validCompanyName,
                    'is-error': validCompanyName === false
                 }"
                >
                    <input id="register-popup-company-name"
                           type="text"
                           name="company_name"
                           required
                           @blur="checkCompanyName"
                           v-model="companyName"
                    >
                    <label for="register_name" placeholder="Company Name" class="label_auth"></label>
                    <span class="error-msg"
                          v-show="companyNameError"
                    >@{{ companyNameError }}</span>
                </div>

                <div class="shift-label-input validated-input"
                     :class="{
                    'is-filled': validName !== 'unfilled',
                    'is-success': validName,
                    'is-error': ! validName
                }"
                >
                    <input id="register_name"
                           type="text"
                           name="name"
                           required
                           @blur="checkName"
                           v-model="name"
                    >
                    <label alt="register_name" placeholder="Full Name" class="label_auth"></label>
                </div>

                <div class="shift-label-input validated-input"
                     :class="{
                    'is-filled': validEmail !== 'unfilled',
                    'is-success': validEmail,
                    'is-loading': validEmail === 'loading',
                    'is-error': !validEmail
                 }"
                >
                    <input id="register-popup-email"
                           type="text"
                           name="email"
                           required
                           @blur="checkEmail"
                           v-model="email"
                    >
                    <label for="register_email" placeholder="Email" class="label_auth"></label>
                                        <span class="error-msg"
                                              v-show="emailError"
                                        >@{{ emailError }}</span>
                </div>

                <div class="shift-label-input validated-input"
                     :class="{
                    'is-filled': validPassword !== 'unfilled',
                    'is-success': validPassword,
                    'is-error': ! validPassword
                }"
                >
                    <input id="register_password"
                           type="password"
                           name="password"
                           required
                           @blur="checkPassword"
                           v-model="password"
                    >
                    <label alt="register_password" placeholder="Password" class="label_auth"></label>
                </div>

                <button type="button"
                        class="btn btn-solid-green no-outline button-register-company"
                        :disabled="validCompanyName !== true || validName !== true || validEmail !== true || validPassword !== true"
                        @click="registerNewCompany"
                >Register your company
                </button>
            </form>
        </div>
    </div>
</registration-popup>