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
                Super-charge your operations.
                <span class="popup-subheading">Free for up to 5 Team Members</span>
            </h1>
        </div>
        <form id="form-registration">

            <div class="shift-label-input">
                <input id="register_name" type="text" name="name" value="{{ old('name') }}" required>
                <label for="register_name" placeholder="Name" class="label_auth"></label>
            </div>

            <div class="shift-label-input">
                <input id="register_email" type="text" name="email" value="{{ old('email') }}" required>
                <label for="register_email" placeholder="Email" class="label_auth"></label>
            </div>

            <div class="shift-label-input">
                <input id="register_password" type="password" name="password" required>
                <label alt="register_password" placeholder="Password" class="label_auth"></label>
            </div>

            <button type="button" class="btn btn-solid-green no-outline button-register-company">Register your company
            </button>
        </form>
    </div>
    </div>
</registration-popup>