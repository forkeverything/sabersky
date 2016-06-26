<registration-popup inline-template>
    <button class="btn btn-solid-green button-nav-signup "
    @click="toggleShowRegistrationPopup"
    >
    Get started</button>
    <div id="registration-popup-overlay"
         v-show="showRegisterPopup"
         class="animated container"
         transition="fade"
         v-cloak
    >
        <div class="popup-content">
            <span class="button-remove clickable"
            @click="toggleShowRegistrationPopup"
            >
            <i class="fa fa-close"></i></span>
            <div class="popup-header">
                <i id="register-popup-icon" class="livicon-evo" data-options="name:dashboard.svg; size: 40px; eventType: none; style: lines; strokeColor: #FFFFFF;"></i>
                <h1 class="popup-title">Super-charge your operations
                </h1>
            </div>
            @include('auth.register.partials.account')
            @include('auth.register.partials.billing')
        </div>
    </div>
</registration-popup>