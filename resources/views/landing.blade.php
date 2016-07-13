@extends('layouts.landing')

@section('content')
    <div id="landing">
        <div id="hero_div">
            <div class="hero_image_wrap">
                <img class="hero_image" src="images/construction.jpg">
            </div>
            <div class="hero_content">
                <div class="container">
                    <div class="row">
                        <div class="hand-ipad col-sm-4 hidden-xs">
                            <div class="ipad-wrap">
                                <img class="img-ipad" src="images/ipad-hands-600.png" alt="ipad">
                                <div class="screen-wrap">
                                    <img class="img-screen" src="images/po-screen-1200.jpg"
                                         alt="Purchase Order screenshot">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 hero-text">
                            <h1 class="hidden-xs">Better purchasing, better business.</h1>
                            <h5 class="col-md-7 center-block">Sabersky helps your company handle purchasing better.</h5>
                            <span class="display-block">View features</span>
                            <a href="#features" id="link-features">
                                <button class="hero_button btn btn-default">
                                    <i class="livicon-evo icon-overview"
                                       data-options="name:chevron-bottom.svg; size: 25px; repeat: loop; style: lines; strokeColor: #FFF; eventOn: parent; strokeWidth: 3px;"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="problem">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-1 text_wrap">
                        <h2 class="hidden-xs">Rocket powered purchasing system</h2>
                        <p>
                            You're tired of your purchasing system. Your team's running around chasing approvals,
                            confirming request quantities and triple checking vendors. And after all that, you've got to
                            go back and make sure there's no funny business. Not anymore, now you've got Sabersky.
                        </p>
                    </div>
                    <div class="col-md-5 visible-md visible-lg">
                        <i class="livicon-evo icon-rocket"
                           data-options="name: rocket.svg; size: 350px; style: original; strokeColor: #2980B9; eventOn: grandparent;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div id="join">
            <h2>
                We want to make your life easier, and purchasing system awesomer
            </h2>
        </div>

        <div id="features">
            <div class="container">
                <div class="row one">
                    <div class="search_wrap col-md-6 wrapper">

                        <i class="livicon-evo"
                           data-options="name:clock.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #FFB400;"></i>
                        <h2>Faster Approvals</h2>
                        <p>
                            View all unapproved orders as well as receive notifications as soon as an order is made that
                            needs approval. Goodbye hourly reminders. Goodbye asking where Mr. Boss-man is.
                        </p>
                    </div>
                    <div class="meetings_wrap col-md-6 wrapper">
                        <i class="livicon-evo icon-overview"
                           data-options="name: shoppingcart-in.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #27AE60;"></i>

                        <h2>It's Your System</h2>
                        <p>
                            Define your staff roles and purchasing rules. Make your purchasing process as tightly
                            controlled or as expedited as your comfortable with. We designed it to make sure nothing
                            slips by you.
                        </p>
                    </div>
                </div>
                <div class="row two">
                    <div class="follows_wrap col-md-6 wrapper">
                        <i class="livicon-evo"
                           data-options="name: pie-chart.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #2980B9;"></i>

                        <h2>Easy, Instant Reports</h2>
                        <p>
                            We used to hate spending reports. Updating spreadsheets for every order, sorting out the unloved
                            pile of invoices, resizing the same chart endlessly. That's why we've made
                            making spending reports as easy and painless as possible. 2 clicks.
                        </p>
                    </div>
                    <div class="team_wrap col-md-6 wrapper">
                        <i class="livicon-evo icon-overview"
                           data-options="name: truck.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #C0392B;"></i>

                        <h2>Vendor Management</h2>
                        <p>
                            Find the vendor your looking for and see all information you need in one place - previous orders, address, bank accounts, notes, delivery times. Keeps everyone on the same page, everytime.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="pricing">

            <h2>Pricing</h2>
            <p class="subheading">When we say we're here to help you grow, we mean it.</p>
                <h1>$0</h1>
            <p class="subheading">/month</p>
        </div>

        <div id="about">
            <div class="container">
                <div class="row flexing">
                    <div class="col-md-5 left">
                        <h2>Build your business with you</h2>
                        <img id="bottom-logo" src="images/logo/logo-cloud-white.png">
                    </div>
                    <div class="col-md-7 right">
                        <p>
                            Sabersky is here to help small businesses grow into big ones through easier, better purchasing. We'd love for you to try our product, tell us how we can do better and help you solve your problems. So go ahead and click the button below, we promise you're team will love you for it.
                        </p>
                        <a href="/register" alt="Register Link">
                            <button type="button" class="btn btn-solid-green">
                                Show me what you got
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
