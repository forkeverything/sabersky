@extends('layouts.app')

@section('content')
    <register inline-template>
        <div id="register" class="container">
            <h1 class="text-center">Sign-up</h1>
            <p class="text-center">Fill out this quick form and say goodbye to purchasing headaches forever.</p>

            <div class="row">
                <form action="/company" method="post"  id="register-form" class="col-sm-8 col-sm-offset-2">
                    {{ csrf_field() }}
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
                    <div class="align-end">
                        <button type="submit" class="btn btn-solid-green" :disabled="! accountFieldsValid">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </register>
@endsection

@section('google-tracking-code')
    <!-- Google Code for Register Page Conversion Page -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 878587963;
        var google_conversion_language = "en";
        var google_conversion_format = "3";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "fZD7CN_Lp2gQu-D4ogM";
        var google_remarketing_only = false;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt=""
                 src="//www.googleadservices.com/pagead/conversion/878587963/?label=fZD7CN_Lp2gQu-D4ogM&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>
@endsection