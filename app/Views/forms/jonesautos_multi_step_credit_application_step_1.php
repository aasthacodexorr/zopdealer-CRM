<!DOCTYPE html>
<html>

<head>
    <title>Credit Application | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->

    <style>
        .g-recaptcha>div {
            margin: auto;
        }

        #zm_image {
            width: 300px;
        }



        @media (max-width: 576px) {
            #inventory_custom_block {
                flex-wrap: wrap;
            }
        }

        #inventory_custom_block div span {
            min-width: 50%;
            display: block;
        }

        #inventory_custom_block div span:nth-child(even) {
            font-weight: 800;
        }

        #inventory_custom_block>div:first-child {
            display: flex;
            flex-wrap: wrap;
        }

        #inventory_custom_block .owl-nav {
            position: absolute;
            top: 30%;
            width: 100%;
            z-index: 999999;
            left: 0px;
            text-align: left;
            display: none;
        }

        #inventory_custom_block .owl-nav button.owl-prev {
            position: relative;
            top: 30%;
            left: 0px;
            width: 40px;
            height: 40px;
            background: #000000db;
            border-radius: 50%;
        }

        #inventory_custom_block .owl-nav button.owl-next {
            position: relative;
            width: 40px;
            height: 40px;
            background: #000000db;
            border-radius: 50%;
            float: right;
        }

        #inventory_custom_block .owl-nav button.owl-prev span,
        #inventory_custom_block .owl-nav button.owl-next span {
            color: #fff;
            font-size: 38px;
            position: relative;
            top: -16px;
            z-index: 9999;
            left: 11px;
            right: 0px;
            text-align: center;
        }

        #inventoryImageCarousel:hover .owl-nav {
            display: block;
        }

        #inventory_custom_block .owl-nav button.owl-next span {
            left: 14px;
        }

        #inventory_custom_block .owl-nav button.owl-prev:focus:not(:focus-visible) {
            outline: 1px solid transparent;
            border: 0px;
        }

        #inventory_custom_block .owl-nav button.owl-next:focus:not(:focus-visible) {
            outline: 1px solid transparent;
            border: 0px;
        }
    </style>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <?php if (!empty($custom_less)) {
        $custom_less_urls = explode(',', $custom_less);
        foreach ($custom_less_urls as $each_url) { ?>
            <link rel="stylesheet/less" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>

    <style>
        ul.form-list-desgine li {
            vertical-align: top;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>

    <!-- OWL DEPENDENCIES : START -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <!-- OWL DEPENDENCIES : END  -->
</head>
<!--  BODY START  -->

<body>
    <!--Financial Services Starts-->
    <section class="details-main form-new-set">
        <div class="container">
            <form class="needs-validation" id="lead_form">
                <!--Financial Services Starts-->
                <div class="col-md-10 col-md-offset-1">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display:block;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3><strong class="green-text">Get Pre-Approved</strong> for financing your new vehicle</h3>
                                    <p>Good credit, bad credit & no credit approved!</p>
                                    <ul class="form-list-desgine">
                                        <li class="vehicle-types">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_under-month" name="vehicle_type" value="Car"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/car_logo.png" class="img-resposive" /><strong>Car</strong></label>
                                            </div>
                                        </li>
                                        <li class="vehicle-types">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_250-month" name="vehicle_type" value="SUV"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/suv_logo.png" class="img-resposive" /><strong>SUV</strong></label>
                                            </div>
                                        </li>
                                        <li class="vehicle-types">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_374-month" name="vehicle_type" value="Truck"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/truck_logo.png" class="img-resposive" /><strong>Truck</strong></label>
                                            </div>
                                        </li>
                                        <li class="vehicle-types">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_Over-month" name="vehicle_type" value="Van"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/van_logo.png" class="img-resposive" /><strong>Van</strong></label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="vehicle_type_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Please select a preferred inventory</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <select type="text" class="form-control " id="inventory_id" name="inventory_id" placeholder="Inventory">
                                                </select>
                                                <div id="inventory_id_error" class="invalid-feedback"></div>
                                            </div>
                                            <div class="head-felid1" id="inventory_custom_block" style="display:none;">
                                                <div><span>YEAR : </span><span id="zm_year"></span>
                                                    <span>MAKE : </span><span id="zm_make"></span>
                                                    <span>MODEL : </span><span id="zm_model"></span>
                                                    <span style="display: none;">TRIM : </span><span style="display: none;" id="zm_trim"></span>
                                                    <span>VIN : </span><span id="zm_vin"></span>
                                                    <span>STOCK NO : </span><span id="zm_stock_no"></span>
                                                    <span>ODOMETER : </span><span id="zm_odometer"></span>
                                                </div>
                                                <div class="inventoryImageCarouselWrapper">
                                                    <!-- <img src="" class="img-responsive" id="zm_image" alt=""> -->


                                                    <div id="inventoryImageCarousel" class="owl-carousel owl-theme" style="width: 300px;">
                                                    </div>


                                                </div>
                                            </div>
                                            <input type="hidden" id="single_stock_no_input" name="stock_no" value="">
                                            <input type="hidden" id="single_stock_zm_year" name="zm_year" value="">
                                            <input type="hidden" id="single_stock_zm_make" name="zm_make" value="">
                                            <input type="hidden" id="single_stock_zm_model" name="zm_model" value="">
                                            <input type="hidden" id="single_stock_zm_trim" name="zm_trim" value="">
                                            <input type="hidden" id="single_stock_image" name="zm_image" value="">
                                            <input type="hidden" id="single_stock_zm_vin" name="zm_vin" value="">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your Estimated Credit Rating?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="credit-rating">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="good_over_650" name="credit_rating" value="Over 650"><label> Good (Over 650)</label>
                                            </div>
                                        </li>
                                        <li class="credit-rating">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="fair_550_650" name="credit_rating" value="Between 550 And 650"><label>Fair (550-650)</label>
                                            </div>
                                        </li>
                                        <li class="credit-rating">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="current_bankruptcy" name="credit_rating" value="Current Bankruptcy"><label>Current Bankruptcy</label>
                                            </div>
                                        </li>
                                        <li class="credit-rating">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="no_credit_unsure" name="credit_rating" value="Unsure"><label>No Credit/Unsure</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="credit_rating_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Do You Have a Trade In?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="trade-in">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-No" name="trade_in" value="No"><label>No</label>
                                            </div>
                                        </li>
                                        <li class="trade-in">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-Yes" name="trade_in" value="Yes"><label>Yes, I want to trade</label>
                                            </div>
                                        </li>
                                        <li class="trade-in">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-Unsure" name="trade_in" value="Unsure"><label>Yes, I'm keeping it</label>
                                            </div>
                                        </li>

                                    </ul>
                                    <div id="trade_in_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your date of birth?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="date-of-birth">
                                            <div class="head-felid1">
                                                <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day">
                                                </select>
                                                <div id="birth_date_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="date-of-birth">
                                            <div class="head-felid1">
                                                <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                                <div id="birth_month_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="date-of-birth">
                                            <div class="head-felid1">
                                                <select class="form-control" name="birth_year" id="dobyear" placeholder="Year">
                                                </select>
                                                <div id="birth_year_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What Is Your Employment Status?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="employment-status">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employed" name="employment_status" value="Employed"><label>Employed</label>
                                            </div>
                                        </li>
                                        <li class="employment-status">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="self-employed" name="employment_status" value="Self Employed"><label>Self-Employed</label>
                                            </div>
                                        </li>
                                        <li class="employment-status">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="student" name="employment_status" value="Retired"><label>
                                                    Retired</label>
                                            </div>
                                        </li>
                                        <li class="employment-status">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="retired-pension" name="employment_status" value="Disability"><label>Disability</label>
                                            </div>
                                        </li>
                                        <li class="employment-status">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="other" name="employment_status" value="Other"><label>Other</label>
                                            </div>
                                        </li>
                                        <li class="employment-status">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="unemployed" name="employment_status" value="Unemployed"><label>Unemployed</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="employment_status_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your gross income?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="monthly-gross-income">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="gross_income" id="gross_income" placeholder="Gross Income">
                                                <div id="gross_income_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>How long have you been earning this income?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="income-period">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_0_to_3" name="employment_period_with_this_salary" value="Between 0 and 3 Months"><label>0 - 3 Months</label>
                                            </div>
                                        </li>
                                        <li class="income-period">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_3_to_6" name="employment_period_with_this_salary" value="Between 3 and 6 Months"><label>3 - 6 Months</label>
                                            </div>
                                        </li>
                                        <li class="income-period">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_6_to_12" name="employment_period_with_this_salary" value="Between 6 and 12 Months"><label>6 - 12 Months</label>
                                            </div>
                                        </li>
                                        <li class="income-period">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_1_to_2" name="employment_period_with_this_salary" value="Between 12 Months And 2 Years"><label>12 Months - 2 Years</label>
                                            </div>
                                        </li>
                                        <li class="income-period">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_above_2" name="employment_period_with_this_salary" value="Above 2 Years"><label>2 Years +</label>
                                            </div>
                                        </li>

                                    </ul>
                                    <div id="employment_period_with_this_salary_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Where are you working?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="work-info">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="company_name" id="company_name" placeholder="Company Name">
                                                <div id="company_name_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="work-info">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Title">
                                                <div id="job_title_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is Employer address?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="address">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="employer_address" id="employer_address" placeholder="Employer Address">
                                                <div id="employer_address_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="address">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="employer_city" id="employer_city" placeholder="Employer City">
                                                <div id="employer_city_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                        <li class="address">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="employer_province" id="employer_province" placeholder="Employer Province">
                                                <div id="employer_province_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Do you own or rent your home?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="home-info">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="own" name="residence_status" value="Own"><label>Own</label>
                                            </div>
                                        </li>
                                        <li class="home-info">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="rent" name="residence_status" value="Rent"><label>Rent</label>
                                            </div>
                                        </li>
                                        <li class="home-info">
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="other_residence_status" name="residence_status" value="Other"><label>Other</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="residence_status_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What Are Your Monthly (Rent Or Mortgage) Payments?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="payments">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="mortgage_rent_amount_monthly" id="mortgage_rent_amount_monthly" placeholder="Payments">
                                                <div id="mortgage_rent_amount_monthly_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your address?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="address">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                                <div id="street_address_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="address">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                                <div id="city_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                        <li class="address">
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                                <div id="province_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>How Long Have You Lived at Your Current Address?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="long-you-lived">
                                            <div class="head-felid1">
                                                <input type="text" min="0" max="100" class="form-control" name="residence_period_years" placeholder="Years" id="residence_period_years" />
                                                <div id="residence_period_years_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="long-you-lived">
                                            <div class="head-felid1">
                                                <input type="text" min="0" max="11" name="residence_period_months" class="form-control" placeholder="Months" id="credit-application-month" />
                                                <div id="residence_period_months_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box row">
                                    <h3 class="w-100"><strong>Congratulations your Pre-Approval is Ready!</strong></h3>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                            <div id="first_name_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                            <div id="last_name_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                            <div id="email_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="tel" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                            <div id="phone_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <input type="hidden" name="source" value="<?php echo $source; ?>">
                                        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                        <input type="hidden" name="source_url" value="">
                                        <div class="link-set">
                                            <p><input type="checkbox" name="terms_condition[]" value="I have read and accept the Terms of Use. I also acknowledge having read the Privacy Policy and agree to the terms herein." /><span class="left-sp">I have read and accept the Terms of Use. I also acknowledge having read the Privacy Policy and agree to the terms herein.</span></p>
                                        </div>
                                        <div class="checkbox-block link-set">
                                            <p style="padding-top: 15px;padding-bottom: 15px;"><input type="checkbox" name="sms_consent" checked="">
                                                By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us. </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 p-top-20 text-center">
                                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                        <div class="invalid-feedback" id="recaptcha-error"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <a class="action next btn btn-info" style="display:block;">Continue</a>
                        <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        <a type="submit" class="action back btn btn-info" style="display:block;">Back</a>

                    </div>
                    <div class="safe-and-secure text-center mt-3">
                        <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/icon-bb.png" style="width:25px;padding-right:5px; padding-top: 0px;" />Confidential, Safe and Secure</p>
                        <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/trust-badges-small.webp" class="bottom-img" /> </p>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/custom_forms/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="/dist/assets/js/dobpicker.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js'></script>

    <script>
        var BrowserStorage = (function() {
            /**
             * Whether the current browser supports local storage as a way of storing data
             * @var {Boolean}
             */
            var _hasLocalStorageSupport = (function() {
                try {
                    return 'localStorage' in window && window['localStorage'] !== null;
                } catch (e) {
                    return false;
                }
            })();

            /**
             * @param {String} name The name of the property to read from this document's cookies
             * @return {?String} The specified cookie property's value (or null if it has not been set)
             */
            var _readCookie = function(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }

                return null;
            };

            /**
             * @param {String} name The name of the property to set by writing to a cookie
             * @param {String} value The value to use when setting the specified property
             * @param {int} [days] The number of days until the storage of this item expires
             */
            var _writeCookie = function(name, value, days) {
                var expiration = (function() {
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        return "; expires=" + date.toGMTString();
                    } else {
                        return "";
                    }
                })();

                document.cookie = name + "=" + value + expiration + "; path=/";
            };

            return {
                /**
                 * @param {String} name The name of the property to set
                 * @param {String} value The value to use when setting the specified property
                 * @param {int} [days] The number of days until the storage of this item expires (if storage of the provided item must fallback to using cookies)
                 */
                set: function(name, value, days) {
                    _hasLocalStorageSupport
                        ?
                        localStorage.setItem(name, value) :
                        _writeCookie(`localstorage_${name}`, value, days);
                },

                /**
                 * @param {String} name The name of the value to retrieve
                 * @return {?String} The value of the 
                 */
                get: function(name) {
                    return _hasLocalStorageSupport ?
                        localStorage.getItem(name) :
                        _readCookie(`localstorage_${name}`);
                },

                /**
                 * @param {String} name The name of the value to delete/remove from storage
                 */
                remove: function(name) {
                    _hasLocalStorageSupport
                        ?
                        localStorage.removeItem(name) :
                        this.set(`localstorage_${name}`, "", -1);
                },

                /**
                 *  Clears the localStorage if supported.
                 */
                clear: function() {
                    _hasLocalStorageSupport
                        ?
                        localStorage.clear() :
                        null;
                }
            };
        })();
    </script>

    <script type="text/javascript">
        function persistInput(input) {
            var key = input.name;
            var storedValue = BrowserStorage.get(key);
            $(input).on('change', function() {
                BrowserStorage.set(key, $(input).val());
            });
        }


        $(document).ready(function() {
            function validURL(str) {
                regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
                if (regexp.test(str)) {
                    return true;
                } else {
                    return false;
                }
            }

            function getSearchParameters() {
                var prmstr = window.location.search.substr(1);
                return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
            }

            function transformToAssocArray(prmstr) {
                var params = {};
                var prmarr = prmstr.split("&");
                for (var i = 0; i < prmarr.length; i++) {
                    var tmparr = prmarr[i].split("=");
                    params[tmparr[0]] = tmparr[1];
                }
                return params;
            }

            window.params = getSearchParameters();


            var placeholder = '';

            //Fetch placeholder
            $.get(window.location.origin + '/api/website/image/placeholder', function(data) {
                window.placeholder = '<?php echo getenv('image.cdn')
                                        ?>' + data.image_url;
            });

            //Fetch instock inventory

            function repopulateDropDown(url = window.location.origin + '/api/website/inventory?zm_status[]=Instock&paginate=false&column=year&order=DESC') {
                $.get(url, function(data) {
                    $('#inventory_id').append('<option value="" > -- Not Sure -- </option>');
                    for (var key in data.inventory) {
                        if (data.inventory.hasOwnProperty(key)) {
                            var element = data.inventory[key];
                            var option = '<option value="' + element.inventory_id + '"> ' + element.year + ' ' + element.make + ' ' + element.model + ' #' + element.stock_no + '-' + element.odometer + ' ' + element.km_miles + '</option>';
                            $('#inventory_id').append(option);

                        }
                    }
                }).done(function() {
                    if (window.params.hasOwnProperty('inventory_id')) {
                        $('#inventory_id').val(window.params.inventory_id);
                        single_inventory(window.params.inventory_id);
                    }
                });
            }

            repopulateDropDown();

            function resetInventoryData() {
                $('#single_stock_no_input').val('');
                $('#zm_stock_no').text('');

                $('#single_stock_zm_year').val('');
                $('#zm_year').text('');

                $('#single_stock_zm_make').val('');
                $('#zm_make').text('');

                $('#single_stock_zm_model').val('');
                $('#zm_model').text('');

                $('#single_stock_zm_trim').val('');
                $('#zm_trim').text('');

                $('#single_stock_zm_vin').val('');
                $('#zm_vin').text('');

                $("#zm_odometer").text(``);

                $('#single_stock_image').val('');
                $("#zm_image").attr('src', '');

                $('#inventory_custom_block').css({
                    'display': 'none',
                });
            }


            function single_inventory(id) {
                $.get(window.location.origin + '/api/website/inventory/show/' + id, function(data) {

                    if (data.stock_no.length > 0) {
                        $('#inventory_custom_block').css({
                            'display': 'flex',
                        });
                    } else {
                        $('#inventory_custom_block').css({
                            'display': 'none',
                        });
                    }

                    $('#single_stock_no_input').val(data.stock_no);
                    $('#zm_stock_no').text(data.stock_no);

                    $('#single_stock_zm_year').val(data.year);
                    $('#zm_year').text(data.year);

                    $('#single_stock_zm_make').val(data.make);
                    $('#zm_make').text(data.make);

                    $('#single_stock_zm_model').val(data.model);
                    $('#zm_model').text(data.model);

                    $('#single_stock_zm_trim').val(data.trim);
                    $('#zm_trim').text(data.trim);

                    $('#single_stock_zm_vin').val(data.vin);
                    $('#zm_vin').text(data.vin);

                    $("#zm_odometer").text(`${data.odometer} ${data.km_miles}`);
                });

                $.get(window.location.origin + '/api/website/inventory/images/' + id, function(data) {
                    //single_inventory_image
                    if (data['gallery'].hasOwnProperty('0')) {
                        var image_url = '';
                        if (validURL(data['gallery'][0]['image_url'])) {
                            image_url = data['gallery'][0]['image_url'];
                        } else {
                            image_url = '<?php echo getenv('image.cdn')
                                            ?>' + data['gallery'][0]['image_url'];
                        }

                        $('#single_stock_image').val(image_url);
                        $("#zm_image").attr('src', image_url);
                    } else {
                        $('#single_stock_image').val(window.placeholder);
                        $("#zm_image").attr('src', image_url);
                    }

                    $('#inventoryImageCarousel').owlCarousel('destroy');
                    $('#inventoryImageCarousel').removeClass('owl-drag owl-hidden owl-loaded');
                    document.getElementById("inventoryImageCarousel").innerHTML = '';

                    for (const key in data['gallery']) {
                        var image_url = '';
                        if (validURL(data['gallery'][key]['image_url'])) {
                            image_url = data['gallery'][key]['image_url'];
                        } else {
                            image_url = '<?php echo getenv('image.cdn')
                                            ?>' + data['gallery'][key]['image_url'];
                        }

                        jQuery('#inventoryImageCarousel').append(`<div class="item"><img style="width: 300px;" src="${image_url}"></div>`);
                    }



                    setTimeout(() => {
                        $("#inventoryImageCarousel").owlCarousel({
                            autoPlay: 3000, //Set AutoPlay to 3 seconds
                            items: 1,
                            nav: true
                        });
                    }, 1000);

                });

            }

            //Method for changing select 
            $('#inventory_id').change(function() {
                var data = $(this).val();
                resetInventoryData();
                single_inventory(data);
            });

            $('input[name="vehicle_type"]').change(function() {
                let refine_dropdown_url = window.location.origin + '/api/website/inventory?zm_status[]=Instock&paginate=false&column=year&order=DESC';
                let vehicle_type = $(this).val();

                $('#inventory_id').find('option').remove();
                if (vehicle_type) {
                    switch (vehicle_type) {
                        case 'Sedan':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Sedan&zm_body_type[]=Sedan%204%20Dr.`;
                            break;

                        case 'Coupe':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Coupe&zm_body_type[]=Coupes&zm_body_type[]=Coupe-2-Door`;
                            break;

                        case 'Hatchback':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Hatchback&zm_body_type[]=Hatchback%202%20Dr.`;
                            break;

                        case 'SUV':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Sport%20Utility%20Vehicle&zm_body_type[]=SUV-Crossover&zm_body_type[]=Sport%20Utility%204-Door&zm_body_type[]=Suv`;
                            break;

                        case 'Van':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Minivan&zm_body_type[]=Van&zm_body_type[]=Minivan-Van&zm_body_type[]=minivan`;
                            break;

                        case 'Truck':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Truck&zm_body_type[]=Pickup%20Truck&zm_body_type[]=Pickup-Truck&zm_body_type[]=Trucks&zm_body_type[]=Commercial%20Vehicle`;
                            break;

                        default:
                            break;
                    }
                }

                repopulateDropDown(refine_dropdown_url);

            });


        });

        $(document).ready(function() {

            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            document.querySelector(`input[name='source_url'`).value = source_url;

            function wait(ms) {
                var start = new Date().getTime();
                var end = start;
                while (end < start + ms) {
                    end = new Date().getTime();
                }
            }

            $('input[type="tel"]').each(function() {
                $(this).inputmask({
                    "mask": "(999) 999-9999",
                    "removeMaskOnSubmit": true,
                    "autoUnmask": true
                });
            });

            $('form#lead_form :input').each(function() {
                var storedValue = BrowserStorage.get(this.name);
                if (storedValue) {
                    if (this.type == 'radio') {
                        $(this).val([storedValue]);
                    } else if (this.type !== 'file') {
                        $(this).val(storedValue);
                    }
                }

                persistInput(this);
            });

            $.dobPicker({
                daySelector: '#dobday',
                monthSelector: '#dobmonth',
                yearSelector: '#dobyear'

            });

            parent.postMessage({
                type: "css",
                value: $("#lead_form").outerHeight(true) + 230,
                element_id: "finance_form"
            }, "*");


            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                vehicle_type: "required",
                credit_rating: "required",
                trade_in: "required",
                birth_date: {
                    required: true,
                    number: true
                },
                birth_month: {
                    required: true,
                    number: true
                },
                birth_year: {
                    required: true,
                    number: true
                },
                employment_status: {
                    required: true,
                },
                employer_address: "required",
                employer_city: "required",
                employer_province: "required",
                gross_income: "required",
                employment_period_with_this_salary: "required",
                company_name: "required",
                job_title: "required",
                residence_status: "required",
                mortgage_rent_amount_monthly: {
                    required: true,
                    number: true,
                    min: 0,
                },
                street_address: "required",
                city: "required",
                province: "required",
                // postal_code: "required",
                residence_period_years: "required",
                residence_period_months: "required",
                first_name: "required",
                last_name: "required",
                //                email: "required", 
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    number: true,
                }
            };
            var messages = {
                vehicle_type: "Vehicle Type is required",
                credit_rating: "Credit Rating is required",
                trade_in: "Trade In is required",
                birth_date: {
                    required: "Please Enter Day of Birth",
                    number: "Day of Birth should be a number",
                },
                birth_month: {
                    required: "Please Enter Month of Birth",
                    number: "Month of Birth should be a number",
                },
                birth_year: {
                    required: "Please Enter Year of Birth",
                    number: "Year of Birth should be a number",
                },
                employment_status: "Employment Status is required",
                employer_address: "Please Enter Your Employer Address",
                employer_city: "Please Enter Your Employer City",
                employer_province: "Please Enter Your Employer Province",
                gross_income: "Gross Income is required",
                employment_period_with_this_salary: "Employment Period is required",
                company_name: "Please Enter Company Name",
                job_title: "Please Enter Job Title",
                residence_status: "Residence Status is required",
                mortgage_rent_amount_monthly: {
                    required: "Please Enter Rent or Mortgage amount",
                    number: "Rent or Mortgage amount should be numeric"
                },
                street_address: "Please Enter Street Address",
                city: "Please Enter City",
                province: "Please Enter Province",
                // postal_code: "Please Enter Postal Code",
                residence_period_years: "Please Enter Residence Period Years",
                residence_period_months: "Please Enter Residence Period Months",
                first_name: "Please Enter Your First Name",
                last_name: "Please Enter Your Last Name",
                //                email: "Please Enter Your Email",
                email: {
                    required: "Please Enter Your Email",
                    email: "Please Enter a valid Email"
                },
                phone: {
                    required: "Please Enter Your Phone Number",
                    number: "Phone Number should be a Number",
                    minlength: "Your Phone Number must be 10 characters long"
                }

            };

            partial_form.validate({
                debug: true,
                rules: rules,
                messages: messages,
                errorClass: "is-invalid",
                errorPlacement: function(error, element) {
                    console.log(element.attr("name"));
                    console.log(element);
                    console.log(error);
                    $('[name="' + element.attr("name") + '"]').addClass('is-invalid');
                    error.appendTo("#" + element.attr("name") + "_error");
                    $("#" + element.attr("name") + "_error").addClass("d-block");
                    $("#" + element.attr("name") + "_error").find(".is-invalid").addClass("h5");

                    parent.postMessage({
                        type: "css",
                        value: $("#lead_form").outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");


                },
                submitHandler: function(form, event) {
                    //form.submit();
                    /* console.log(form);
                    console.log(event); */
                    event.preventDefault();
                    $("input").removeClass("is-invalid");
                    $(".is-invalid").removeClass("is-invalid");

                    //Fire an Alert.
                    parent.postMessage("alertFire", "*");

                    var form = $(form);
                    $.ajax({
                        url: window.location.origin + '/api/leads/responses/create',
                        type: "POST",
                        data: form.serialize(), // get all form variables
                        success: function(result) {

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");
                            parent.postMessage("alertClose", "*");

                            $('form#lead_form').each(function() {
                                this.reset();
                            });

                            parent.postMessage("redirectToDocumentPage", "*");

                        },
                        error: function(error) {
                            var errors = error.responseJSON.errors;
                            var first_error = true;
                            if (typeof(errors) == 'object') {
                                for (var key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        if (first_error) {
                                            var x = document.getElementsByName(key)[0];
                                            x.scrollIntoView({
                                                behavior: 'smooth'
                                            });

                                            first_error = false;
                                        }
                                        //var va = errors[key];
                                        $('input[name="' + key + '"]').addClass('is-invalid').next().text(errors[key]);
                                        $('select[name="' + key + '"]').addClass('is-invalid').next().text(errors[key]);
                                    }
                                }


                                //Fire An Error Update Alert
                                parent.postMessage("alertErrorUpdate", "*");

                                setTimeout(() => {
                                    //Close The Alert
                                    parent.postMessage("alertClose", "*");
                                }, 1000);

                            } else {

                                //Fire An Error Update Alert
                                parent.postMessage("alertErrorUpdate", "*");

                                $("#recaptcha-error").text(errors);
                                document.getElementById("recaptcha-error").scrollIntoView({
                                    behavior: 'smooth'
                                });
                                $(".g-recaptcha").addClass('is-invalid');

                                //  wait(2500);

                                setTimeout(() => {
                                    //Fire Event to Close The Alert
                                    parent.postMessage("alertClose", "*");
                                }, 1000);

                            }
                        },
                        complete: function() {
                            $('input[type="tel"]').each(function() {
                                $(this).inputmask({
                                    "mask": "(999) 999-9999",
                                    "removeMaskOnSubmit": true,
                                    "autoUnmask": true
                                });
                            });

                            grecaptcha.reset();

                        }
                    });

                }
            });

            // Init buttons and UI
            widget.not(':eq(0)').hide();
            hideButtons(current);
            setProgress(current);

            // Next button click action
            // history.pushState(current, "title", "#"+current)
            btnnext.on("click", function(e, v_flag = true) {
                console.log(v_flag);
                var check_val = 0;
                if (!partial_form.valid() && v_flag) {
                    return false;
                }


                if (current < widget.length) {
                    widget.show();
                    widget.not(':eq(' + (current++) + ')').hide();
                    setProgress(current);
                }
                hideButtons(current);

                parent.postMessage({
                    type: "css",
                    value: $("#lead_form").outerHeight(true) + 230,
                    element_id: "finance_form"
                }, "*");
            })

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', false);
                }
                hideButtons(current);

                parent.postMessage({
                    type: "css",
                    value: $("#lead_form").outerHeight(true) + 230,
                    element_id: "finance_form"
                }, "*");
            })
        });

        // Change progress bar action
        setProgress = function(currstep) {
            var percent = parseFloat(100 / widget.length) * currstep;
            percent = percent.toFixed();
            $(".progress-bar").css("width", percent + "%").html("");
        }

        // Hide buttons according to the current step
        hideButtons = function(current) {
            var limit = parseInt(widget.length);

            $(".action").hide();

            if (current < limit) btnnext.show();
            if (current > 1) btnback.show();
            if (current == limit) {
                btnnext.hide();
                btnsubmit.show();
            }
        }

        $(".auto-click-next").on('click', function() {
            $(".next").trigger('click');
        });
    </script>


</body>