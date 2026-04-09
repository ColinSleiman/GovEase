<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="GovEase is a secure e-services platform that helps citizens, offices, and administrators manage public service requests with speed, clarity, and trust.">
        <meta name="author" content="GovEase">
        <link rel="preconnect" href="{{ asset('https://fonts.googleapis.com') }}">
        <link rel="preconnect" href="{{ asset('https://fonts.gstatic.com') }}" crossorigin>
        <link href="{{ asset('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap') }}" rel="stylesheet">

        <title>{{ $title }}</title>

        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('https://use.fontawesome.com/releases/v5.8.1/css/all.css') }}" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('assets/css/templatemo-chain-app-dev.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/animated.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.css') }}">

    </head>

    <body>
        <!-- ***** Preloader Start ***** -->
        <div id="js-preloader" class="js-preloader">
            <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
            </div>
        </div>
        <!-- ***** Preloader End ***** -->


        <!-- ***** Header Area Start ***** -->
        <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="main-nav">
                            <!-- ***** Logo Start ***** -->
                            <a href="index.html" class="logo">
                            <img src="assets/images/logo (2).png" alt="GovEase">
                            </a>
                            <!-- ***** Logo End ***** -->

                            <!-- ***** Menu Start ***** -->
                            <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#services">Services</a></li>
                            <li class="scroll-to-section"><a href="#about">Platform</a></li>
                            <li class="scroll-to-section"><a href="#pricing">Solutions</a></li>
                            <li class="scroll-to-section"><a href="#newsletter">Updates</a></li>
                            <li><div class="gradient-button"><a id="modal_trigger" href="#modal"><i class="fa fa-sign-in-alt"></i> Access Portal</a></div></li> 
                            </ul>        
                            <a class='menu-trigger'>
                                <span>Menu</span>
                            </a>
                            <!-- ***** Menu End ***** -->
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- ***** Header Area End ***** -->
        
        <!-- ***** Modal Start ***** -->
        <div id="modal" class="popupContainer" style="display:none;">
            <div class="popupHeader">
                <span class="header_title">Portal Access</span>
                <span class="modal_close"><i class="fa fa-times"></i></span>
            </div>

            <section class="popupBody">
                <!-- Social Login -->
                <div class="social_login">
                    <div class="">
                        <a href="#" class="social_box fb">
                            <span class="icon"><i class="fab fa-facebook"></i></span>
                            <span class="icon_title">Continue with Facebook</span>

                        </a>

                        <a href="#" class="social_box google">
                            <span class="icon"><i class="fab fa-google-plus"></i></span>
                            <span class="icon_title">Continue with Google</span>
                        </a>
                    </div>

                    <div class="centeredText">
                        <span>Or sign in with your email</span>
                    </div>

                    <div class="action_btns">
                        <div class="one_half"><a href="#" id="login_form" class="btn">Sign In</a></div>
                        <div class="one_half last"><a href="#" id="register_form" class="btn">Create Account</a></div>
                    </div>
                </div>

                <!-- Username & Password Login form -->
                <div class="user_login">
                    <form>
                        <label>Email or Username</label>
                        <input type="text" />
                        <br />

                        <label>Password</label>
                        <input type="password" />
                        <br />

                        <div class="checkbox">
                            <input id="remember" type="checkbox" />
                            <label for="remember">Keep me signed in on this device</label>
                        </div>

                        <div class="action_btns">
                            <div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
                            <div class="one_half last"><a href="#" class="btn btn_red">Sign In</a></div>
                        </div>
                    </form>

                    <a href="#" class="forgot_password">Forgot your password?</a>
                </div>

                <!-- Register Form -->
                <div class="user_register">
                    <form>
                        <label>Full Name</label>
                        <input type="text" />
                        <br />

                        <label>Email Address</label>
                        <input type="email" />
                        <br />

                        <label>Password</label>
                        <input type="password" />
                        <br />

                        <div class="checkbox">
                            <input id="send_updates" type="checkbox" />
                            <label for="send_updates">Send me service updates and announcements</label>
                        </div>

                        <div class="action_btns">
                            <div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
                            <div class="one_half last"><a href="#" class="btn btn_red">Register</a></div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <!-- ***** Modal End ***** -->

        <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 align-self-center">
                                <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2>Digitize public services with clarity, speed, and trust</h2>
                                            <p>GovEase is a centralized e-services platform that helps citizens, government offices, and administrators manage requests securely, reduce delays, and deliver a more transparent service experience.</p>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="white-button first-button scroll-to-section">
                                            <a href="#services">Explore Services <i class="fab fa-apple"></i></a>
                                            </div>
                                            <div class="white-button scroll-to-section">
                                            <a href="#about">See How It Works <i class="fab fa-google-play"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-lg-6">
                                <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                                    <img src="assets/images/slider-dec.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="services" class="services section">
            <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                <div class="section-heading  wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
                    <h4>Essential <em>Services &amp; Features</em> for modern public service delivery</h4>
                    <img src="assets/images/heading-line-dec.png" alt="">
                    <p>The platform brings service requests, document handling, payments, appointments, and communication into one reliable workflow for citizens, offices, and administrators.</p>
                </div>
                </div>
            </div>
            </div>
            <div class="container">
            <div class="row">
                <div class="col-lg-3">
                <div class="service-item first-service">
                    <div class="icon"></div>
                    <h4>Service Request Management</h4>
                    <p>Submit, review, approve, and complete service requests through a structured process that keeps every step clear and accountable.</p>
                    <div class="text-button">
                    <a href="#">Learn More <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="service-item second-service">
                    <div class="icon"></div>
                    <h4>Fast and Secure Processing</h4>
                    <p>Support online payments, protected access, and real-time status updates so users can move forward with confidence.</p>
                    <div class="text-button">
                    <a href="#">Learn More <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="service-item third-service">
                    <div class="icon"></div>
                    <h4>Connected Office Workflows</h4>
                    <p>Help offices manage services, required documents, appointments, feedback, and follow-up from one organized dashboard.</p>
                    <div class="text-button">
                    <a href="#">Learn More <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="service-item fourth-service">
                    <div class="icon"></div>
                    <h4>Transparent Communication</h4>
                    <p>Keep citizens informed with notifications, request tracking, and timely responses that improve trust and service satisfaction.</p>
                    <div class="text-button">
                    <a href="#">Learn More <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div id="about" class="about-us section">
            <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                <div class="section-heading">
                    <h4>About <em>The Platform</em> &amp; How It Helps</h4>
                    <img src="assets/images/heading-line-dec.png" alt="">
                    <p>GovEase is built to simplify how public services are delivered by connecting citizens, municipal offices, and administrators in one secure digital environment.</p>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                    <div class="box-item">
                        <h4><a href="#">For Citizens</a></h4>
                        <p>Access services, upload documents, make payments, and track progress without unnecessary office visits.</p>
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="box-item">
                        <h4><a href="#">For Offices</a></h4>
                        <p>Manage requests, service categories, schedules, and official responses with greater efficiency.</p>
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="box-item">
                        <h4><a href="#">For Administrators</a></h4>
                        <p>Oversee offices, users, service activity, and reporting from a single point of control.</p>
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="box-item">
                        <h4><a href="#">Built for Trust</a></h4>
                        <p>Role-based access, secure authentication, and clear status updates support reliable public service delivery.</p>
                    </div>
                    </div>
                    <div class="col-lg-12">
                    <p>The result is a more responsive service model that improves operational efficiency while giving citizens a clearer, more convenient experience.</p>
                    <div class="gradient-button">
                        <a href="#">Request a Platform Demo</a>
                    </div>
                    <span>*Designed for public service transformation</span>
                    </div>
                </div>
                </div>
                <div class="col-lg-6">
                <div class="right-image">
                    <img src="assets/images/about-right-dec.png" alt="">
                </div>
                </div>
            </div>
            </div>
        </div>

        <div id="clients" class="the-clients">
            <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                <div class="section-heading">
                    <h4>See How <em>Users and Offices</em> benefit from GovEase</h4>
                    <img src="assets/images/heading-line-dec.png" alt="">
                    <p>From faster request handling to clearer communication, the platform is designed to improve both service quality and day-to-day efficiency.</p>
                </div>
                </div>
                <div class="col-lg-12">
                <div class="naccs">
                    <div class="grid">
                    <div class="row">
                        <div class="col-lg-7 align-self-center">
                        <div class="menu">
                            <div class="first-thumb active">
                            <div class="thumb">
                                <div class="row">
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <h4>Citizen Services Team</h4>
                                    <span class="date">Request Processing Insight</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                                    <span class="category">Citizen Experience</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="rating">Trusted</span>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div>
                            <div class="thumb">
                                <div class="row">
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <h4>Municipal Office Desk</h4>
                                    <span class="date">Operations Insight</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                                    <span class="category">Office Efficiency</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="rating">Reliable</span>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div>
                            <div class="thumb">
                                <div class="row">
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <h4>Administrative Control Unit</h4>
                                    <span class="date">Platform Oversight</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                                    <span class="category">System Visibility</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="rating">Efficient</span>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div>
                            <div class="thumb">
                                <div class="row">
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <h4>Appointments Team</h4>
                                    <span class="date">Scheduling Insight</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                                    <span class="category">Appointment Flow</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="rating">Organized</span>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="last-thumb">
                            <div class="thumb">
                                <div class="row">
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <h4>Support and Feedback Desk</h4>
                                    <span class="date">Communication Insight</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                                    <span class="category">Public Support</span>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="rating">Responsive</span>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div> 
                        <div class="col-lg-5">
                        <ul class="nacc">
                            <li class="active">
                            <div>
                                <div class="thumb">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <div class="client-content">
                                        <img src="assets/images/quote.png" alt="">
                                        <p>“Citizens can submit requests, upload documents, and follow progress in one place. The experience feels clearer, faster, and more dependable from start to finish.”</p>
                                    </div>
                                    <div class="down-content">
                                        <img src="assets/images/client-image.jpg" alt="">
                                        <div class="right-content">
                                        <h4>Citizen Perspective</h4>
                                        <span>Simple access to public services</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </li>
                            <li>
                            <div>
                                <div class="thumb">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <div class="client-content">
                                        <img src="assets/images/quote.png" alt="">
                                        <p>“Office teams can review requests, manage missing documents, and update statuses without relying on fragmented manual steps.”</p>
                                    </div>
                                    <div class="down-content">
                                        <img src="assets/images/client-image.jpg" alt="">
                                        <div class="right-content">
                                        <h4>Office Perspective</h4>
                                        <span>Streamlined daily operations</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </li>
                            <li>
                            <div>
                                <div class="thumb">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <div class="client-content">
                                        <img src="assets/images/quote.png" alt="">
                                        <p>“Administrators gain better visibility across offices, services, and performance, making oversight more accurate and decision-making more informed.”</p>
                                    </div>
                                    <div class="down-content">
                                        <img src="assets/images/client-image.jpg" alt="">
                                        <div class="right-content">
                                        <h4>Admin Perspective</h4>
                                        <span>Clear control across the platform</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </li>
                            <li>
                            <div>
                                <div class="thumb">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <div class="client-content">
                                        <img src="assets/images/quote.png" alt="">
                                        <p>“Scheduling tools and reminders reduce missed appointments and help both citizens and officers stay prepared.”</p>
                                    </div>
                                    <div class="down-content">
                                        <img src="assets/images/client-image.jpg" alt="">
                                        <div class="right-content">
                                        <h4>Scheduling Perspective</h4>
                                        <span>Better coordination for in-person visits</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </li>
                            <li>
                            <div>
                                <div class="thumb">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <div class="client-content">
                                        <img src="assets/images/quote.png" alt="">
                                        <p>“Notifications, feedback, and in-app communication help service teams respond faster and build stronger public confidence.”</p>
                                    </div>
                                    <div class="down-content">
                                        <img src="assets/images/client-image.jpg" alt="">
                                        <div class="right-content">
                                        <h4>Support Perspective</h4>
                                        <span>Faster communication, stronger trust</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </li>
                        </ul>
                        </div>          
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div id="pricing" class="pricing-tables">
            <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                <div class="section-heading">
                    <h4>Platform <em>Solutions</em> tailored to each user group</h4>
                    <img src="assets/images/heading-line-dec.png" alt="">
                    <p>Each area of the platform is designed around the practical needs of citizens, government offices, and administrators.</p>
                </div>
                </div>
                <div class="col-lg-4">
                <div class="pricing-item-regular">
                    <span class="price">01</span>
                    <h4>Citizen Access</h4>
                    <div class="icon">
                    <img src="assets/images/pricing-table-01.png" alt="">
                    </div>
                    <ul>
                    <li>Browse services by office and category</li>
                    <li>Upload required documents online</li>
                    <li>Track requests in real time</li>
                    <li>Book appointments when needed</li>
                    <li>Make secure online payments</li>
                    <li>Download completed documents</li>
                    </ul>
                    <div class="border-button">
                    <a href="#">Explore Citizen Features</a>
                    </div>
                </div>
                </div>
                <div class="col-lg-4">
                <div class="pricing-item-pro">
                    <span class="price">02</span>
                    <h4>Office Operations</h4>
                    <div class="icon">
                    <img src="assets/images/pricing-table-01.png" alt="">
                    </div>
                    <ul>
                    <li>Manage services and categories</li>
                    <li>Review and update request statuses</li>
                    <li>Handle appointments and time slots</li>
                    <li>Respond to citizen feedback</li>
                    <li>Share notifications and updates</li>
                    <li class="non-function">Upload official response documents</li>
                    </ul>
                    <div class="border-button">
                    <a href="#">See Office Capabilities</a>
                    </div>
                </div>
                </div>
                <div class="col-lg-4">
                <div class="pricing-item-regular">
                    <span class="price">03</span>
                    <h4>Admin Oversight</h4>
                    <div class="icon">
                    <img src="assets/images/pricing-table-01.png" alt="">
                    </div>
                    <ul>
                    <li>Manage offices across the platform</li>
                    <li>Control municipality and citizen accounts</li>
                    <li>Monitor service activity and requests</li>
                    <li>Review revenue and request reports</li>
                    <li>Support secure, role-based access</li>
                    <li>Strengthen accountability and visibility</li>
                    </ul>
                    <div class="border-button">
                    <a href="#">View Admin Benefits</a>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div> 

        <footer id="newsletter">
            <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                <div class="section-heading">
                    <h4>Stay informed about platform updates and service improvements</h4>
                </div>
                </div>
                <div class="col-lg-6 offset-lg-3">
                <form id="search" action="#" method="GET">
                    <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <fieldset>
                        <input type="address" name="address" class="email" placeholder="Enter your email address" autocomplete="on" required>
                        </fieldset>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <fieldset>
                        <button type="submit" class="main-button">Subscribe for Updates <i class="fa fa-angle-right"></i></button>
                        </fieldset>
                    </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                <div class="footer-widget">
                    <h4>Contact</h4>
                    <p>Digital public service support for citizens, offices, and administrators.</p>
                    <p><a href="#">Service assistance available online</a></p>
                    <p><a href="#">Support channels shared at launch</a></p>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="footer-widget">
                    <h4>Platform</h4>
                    <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Platform</a></li>
                    <li><a href="#">Outcomes</a></li>
                    <li><a href="#">Solutions</a></li>
                    </ul>
                    <ul>
                    <li><a href="#">Citizen Access</a></li>
                    <li><a href="#">Office Operations</a></li>
                    <li><a href="#">Admin Oversight</a></li>
                    </ul>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="footer-widget">
                    <h4>Key Benefits</h4>
                    <ul>
                    <li><a href="#">Secure authentication</a></li>
                    <li><a href="#">Document handling</a></li>
                    <li><a href="#">Appointment scheduling</a></li>
                    <li><a href="#">Payment processing</a></li>
                    <li><a href="#">Real-time tracking</a></li>
                    </ul>
                    <ul>
                    <li><a href="#">Notifications</a></li>
                    <li><a href="#">Feedback tools</a></li>
                    <li><a href="#">Reporting dashboard</a></li>
                    </ul>
                </div>
                </div>
                <div class="col-lg-3">
                <div class="footer-widget">
                    <h4>About GovEase</h4>
                    <div class="logo">
                    <img src="assets/images/white-logo.png" alt="">
                    </div>
                    <p>GovEase helps public institutions deliver services with greater efficiency, transparency, and confidence through a secure and accessible digital platform.</p>
                </div>
                </div>
                <div class="col-lg-12">
                <div class="copyright-text">
                    <p>Copyright © 2026 GovEase. All Rights Reserved.
                <br>Built for efficient and citizen-focused public services.</p>
                </div>
                </div>
            </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/owl-carousel.js') }}"></script>
        <script src="{{ asset('assets/js/animation.js') }}"></script>
        <script src="{{ asset('assets/js/imagesloaded.js') }}"></script>
        <script src="{{ asset('assets/js/popup.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
    </body>

</html>
