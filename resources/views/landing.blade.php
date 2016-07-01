@extends('layouts.app')

@section('content')
    <landing inline-template>
        <div id="landing">
            <div id="hero_div">
                <div class="hero_video_wrap hidden-xs">
                    <video id="hero-vid" autoplay muted loop>
                        <source src="videos/landing_hero_v1.mp4" type="video/mp4">
                        <img class="hero-still" src="images/landing_hero_v1.jpg">
                    </video>
                </div>
                <div class="hero_image_wrap visible-xs">
                    <img class="hero_image" src="images/landing_hero_v1.jpg">
                </div>
                <div class="hero_content">
                    <div class="container">
                        <div class="grid_align">
                            <h1 class="hidden-xs">Expansive Purchasing System</h1>
                            <h5 class="col-md-7 center-block">We help your business grow through better
                                purchasing.</h5>
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

            <div id="problem">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-1 text_wrap">
                            <h2 class="hidden-xs">Cut ahead of the competiton</h2>
                            <p>
                                Mismanaged purchasing account for business losses of upwards of $1.5bn in America, anually.
                                For developing economies, that figure increases by several orders of magnitude. Sabersky is
                                here to stop your company from losing those dollars.
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
                <h2>Sabersky is
                    here to stop your company from losing those dollars.</h2>
            </div>

            <div id="features">
                <div class="container">
                    <div class="row one">
                        <div class="search_wrap col-md-6 wrapper">

                            <i class="livicon-evo"
                               data-options="name:clock.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #FFB400;"></i>
                            <h2>Faster Approvals</h2>

                            <p>
                                A consolidated purchasing system puts all the information where it needs to be, ready for
                                action. No more fussing about who to chase to approve orders.
                            </p>
                        </div>
                        <div class="meetings_wrap col-md-6 wrapper">
                            <i class="livicon-evo icon-overview"
                               data-options="name: shoppingcart-in.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #27AE60;"></i>

                            <h2>Real Control</h2>

                            <p>
                                Define your staff roles and purchasing rules. Make your purchasing process as tightly
                                controlled or as expedited as your comfortable with. We designed it to make sure nothing
                                gets past you without you knowing about it.
                            </p>
                        </div>
                    </div>
                    <div class="row two">
                        <div class="follows_wrap col-md-6 wrapper">
                            <i class="livicon-evo"
                               data-options="name: pie-chart.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #2980B9;"></i>

                            <h2>Actionable Reports</h2>

                            <p>
                                Instantly see spending for various vendors, items or even staff. Ensures your projects are
                                on track and ready to deliver.
                            </p>
                        </div>
                        <div class="team_wrap col-md-6 wrapper">
                            <i class="livicon-evo icon-overview"
                               data-options="name: truck.svg; size: 70px; eventOn: parent; repeat: loop; style: lines; strokeColor: #C0392B;"></i>

                            <h2>Vendor Management</h2>

                            <p>
                                Proper management helps you forge relationships, get better deals and compound the savings.
                                Add extra information such as bank accounts, addresses, notes to keep everybody in the loop.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pricing">
                <div class="header">
                    <h2>Pricing</h2>
                    <p class="subheading">When we say we're here to help you grow, we mean it.</p>
                </div>
                <div class="pricing-container">
                    <div class="row">
                        <div id="pricing-enterprise" class="col-sm-6 price-section">
                            <h3>Enterprise</h3>
                            <h1 class="price">
                                $500
                            </h1>
                            <p class="month">
                                / month
                            </p>
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <p>
                                        For company's with 200 or more active staff $2.50 / staff / month for every user over 200
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="pricing-growth" class="col-sm-6 price-section">
                            <h3>Growth</h3>
                            <h1 class="price">
                                $1
                            </h1>
                            <p class="month">
                                / month
                            </p>
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <p>
                                        Less than 200 staff. No hidden fees, no contracts. Cancel anytime if you feel you're not getting your dollar's worth.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="about">
                <div class="container">
                    <div class="row flexing">
                        <div class="col-md-5 left">
                            <h2>Build your business with you</h2>
                            <img id="bottom-logo" src="/images/logo/logo-cloud-white.png">
                        </div>
                        <div class="col-md-7 right">
                            <p>
                                Once you join, it becomes our sole mission to make sure your company's purchasing works for
                                you. To put you ahead of your competitors by saving you time and money. Once you join, we're
                                on your side and we play to win.
                            </p>
                            <button type="button" class="btn btn-solid-green" @click="clickedJoin">
                            Join Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </landing>
@endsection
