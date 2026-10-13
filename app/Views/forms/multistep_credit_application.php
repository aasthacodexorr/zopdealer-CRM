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
    <!-- <link rel="stylesheet" type="text/css" href="/custom_forms/style.css"> -->
    <link rel="stylesheet" type="text/css" href="style.css">
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
                        <div class="step well step1" style="display:block;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Get a Car Loan Fast With Any Credit Rating</h3>
                                    <p>Good credit, bad credit & no credit approved!</p>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_under-month" name="vehicle_type" value="Car"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/cargrey.png" class="img-resposive" /><strong>Car</strong></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_250-month" name="vehicle_type" value="SUV"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/suvgrey.png" class="img-resposive" /><strong>SUV</strong></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_374-month" name="vehicle_type" value="Truck"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/truckgrey.png" class="img-resposive" /><strong>Truck</strong></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="vehicle_type_Over-month" name="vehicle_type" value="Van"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/vangrey.png" class="img-resposive" /><strong>Van</strong></label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="vehicle_type_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your monthly budget?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="budget_under-month" name="budget" value="Under 250 per month"><label> Under $250 / month</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="budget_250-month" name="budget" value="Between 250 and 374 per month"><label>$250 - 374 / month</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="budget_374-month" name="budget" value="Between 375 and 500 per month"><label>$375 - 500 / month</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="budget_Over-month" name="budget" value="Over 500 per month"><label>Over $500 / month</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="budget_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your current employment status?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <select id="employment-status" name="employment_status" placeholder="Select" class="form-control">
                                                    <option disabled="disabled" selected value="">Select</option>
                                                    <option value="Employed">Employed</option>
                                                    <option value="Self Employed">Self Employed</option>
                                                    <option value="Student">Student</option>
                                                    <option value="Retired">Retired</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <ul class="form-list-desgine employed-section d-none">
                                            <li>
                                                <div class="head-felid1">
                                                    <input type="text" class="form-control " name="company_name" id="company_name" placeholder="Company Name">
                                                    <div id="company_name_error" class="invalid-feedback"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid1">
                                                    <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Job Title">
                                                    <div id="job_title_error" class="invalid-feedback"></div>
                                                </div>
                                            </li>
                                        </ul>
                                        <ul class="form-list-desgine self-employed-section d-none">
                                            <li>
                                                <div class="head-felid1">
                                                    <input type="text" class="form-control " name="company_name" id="company_name" placeholder="Company Name">
                                                    <div id="company_name_error" class="invalid-feedback"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid1">
                                                    <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                                                    <div id="title_error" class="invalid-feedback"></div>
                                                </div>
                                            </li>
                                        </ul>
                                        <h3 class="student-section d-none">Are you working while in school?</h3>
                                        <ul class="form-list-desgine student-section d-none">
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="student_status-yes" name="student_status" value="Yes"><label>Yes</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="student_status-no" name="student_status" value="No"><label>No</label>
                                                </div>
                                            </li>
                                        </ul>
                                        <h3 class="other-section d-none">What is Your Income Source?</h3>
                                        <ul class="form-list-desgine other-section d-none">
                                            <li>
                                                <div class="head-felid1">
                                                    <textarea name="other_status" rows="8" class="form-control" placeholder="" id="other_status"></textarea>
                                                    <div id="reason_error" class="invalid-feedback"></div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div id="other_status_error" class="invalid-feedback"></div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your current monthly gross income?</h3>
                                    <h3>Before taxes and deductions.</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="monthly_gross_income" id="monthly_gross_income" placeholder="Monthly Income">
                                                <div id="monthly_gross_income_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <h3>How long have you been earning this income?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_3_to_6" name="employment_period_with_this_salary" value="Between 3 and 6 Months"><label>3 - 6 Months</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_6_to_12" name="employment_period_with_this_salary" value="Between 6 and 12 Months"><label>6 - 12 Months</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_1_to_2" name="employment_period_with_this_salary" value="Between 12 Months And 2 Years"><label>12 Months - 2 Years</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_above_2" name="employment_period_with_this_salary" value="Above 2 Years"><label>2 Years +</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employment_period_less_than_3" name="employment_period_with_this_salary" value="Less Than 3 Months"><label>Less than 3 Months</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="employment_period_with_this_salary_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Do you own or rent your home?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="own" name="residence_status" value="Own"><label>Own</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="rent" name="residence_status" value="Rent"><label>Rent</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="with_family" name="residence_status" value="With Family"><label>With Family</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="other_residence_status" name="residence_status" value="Other"><label>Other</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="residence_status_error" class="invalid-feedback"></div>
                                    <h3>What Are Your Monthly (Rent Or Mortgage) Payments?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
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
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                                <div id="street_address_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                                <div id="city_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                                <div id="province_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                                <div id="postal_code_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                        <h3>How Long Have You Lived at Your Current Address?</h3>
                                        <ul class="form-list-desgine">
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="current_ad_less_than_3" name="residence_period" value="Less than 3 Months"><label>Less than 3 Months</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="current_ad_bet_3_to_6" name="residence_period" value="Between 3 and 6 Months"><label>3 - 6 Months</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="current_ad_1_to_2" name="residence_period" value="Between 6 and 12 Months"><label>6 - 12 Months</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="current_ad_1_to_2" name="residence_period" value="Between 12 Months And 2 Years"><label>12 Months - 2 Years</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="head-felid">
                                                    <input type="radio" class="auto-click-next seclect-ratio-btn" id="current_ad_above_2" name="residence_period" value="Above 2 Years"><label>2 Years +</label>
                                                </div>
                                            </li>
                                        </ul>
                                        <div id="residence_period_error" class="invalid-feedback"></div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Do You Have a Trade In?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-No" name="trade_in" value="No"><label>No</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="Trade-In-Yes" name="trade_in" value="Yes"><label>Yes, I want to trade it in</label>
                                            </div>
                                        </li>
                                        <!-- <li>
                                            <div class="head-felid">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-Unsure" name="trade_in" value="Unsure"><label>Yes, I want to keep it</label>
                                            </div>
                                        </li> -->
                                    </ul>
                                    <div id="trade_in_error" class="invalid-feedback"></div>
                                    <h3 class="trade_in-section d-none">Vehicle Details</h3>
                                    <ul class="form-list-desgine trade_in-section d-none">
                                        <li>
                                            <div class="head-felid1">
                                                <input min="1960" name="year" type="text" class="form-control" placeholder="Year" id="trade-year" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" name="make" class="form-control" placeholder="Make" id="trade-make" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" name="model" class="form-control" placeholder="Model" id="trade-model" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" name="vin" class="form-control" placeholder="VIN" id="trade-vin" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box row">
                                    <h3>Congratulations your Pre-Approval is Ready!</h3>
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
                                    <div class="col-md-6 col-sm-6">
                                        <div class="p-top-20">
                                            <p>Date of Birth</p>
                                            <input name="date_of_birth" type="date" class="form-control" placeholder="" id="credit-application-date-birth" />
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <input type="hidden" name="source" value="<?php echo $source; ?>">
                                        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                        <input type="hidden" name="source_url" value="">

                                        <!-- <div class="link-set">
                                            <p>I have read and accept the <a href="#">Terms of Use</a> . I also acknowledge having read the <a href="#">Privacy Policy</a> and agree to the terms herein.</p>
                                        </div> -->
                                        <div class="checkbox-block link-set">
                                            <p style="padding-top: 15px;padding-bottom: 15px;"><input type="checkbox" name="sms_consent" checked>
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

    <script type="text/javascript">
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
                    "autoUnmask": true
                });
            });

            $.dobPicker({
                daySelector: '#dobday',
                monthSelector: '#dobmonth',
                yearSelector: '#dobyear'

            });

            parent.postMessage({
                type: "css",
                value: $(document).height(),
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
                budget: "required",
                trade_in: "required",
                date_of_birth: "required",
                employment_status: "required",
                employment_period_with_this_salary: "required",
                monthly_gross_income: "required",
                residence_status: "required",
                mortgage_rent_amount_monthly: {
                    required: true,
                    number: true,
                    min: 0,
                },
                residence_period: "required",
                street_address: "required",
                city: "required",
                province: "required",
                postal_code: "required",
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
                },
                income_status: "required"
            };
            var messages = {
                vehicle_type: "Vehicle Type is required",
                budget: "Budget is required",
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
                monthly_gross_income: "Monthly Gross Income is required",
                employment_period_with_this_salary: "Employment Period is required",
                residence_status: "Residence Status is required",
                mortgage_rent_amount_monthly: {
                    required: "Please Enter Rent or Mortgage amount",
                    number: "Rent or Mortgage amount should be numeric"
                },
                residence_period: "Please Enter Residence Period",
                street_address: "Please Enter Street Address",
                city: "Please Enter City",
                province: "Please Enter Province",
                postal_code: "Please Enter Postal Code",
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
                },
                income_status: "Please Select Income Status"

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

                            setTimeout(() => {

                                // Fire A Close Alert and Than Navigation To ThankYou Page.
                                parent.postMessage("alertClose", "*");
                                parent.postMessage("redirectToThankYouPage", "*");
                                window.location.href = window.location.origin + '/thankyou.html';

                            }, 1000);

                            $('form#lead_form').each(function() {
                                this.reset();
                            });
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
            })

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', false);
                }
                hideButtons(current);
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

        $('#employment-status').change(function() {
            if ($(this).val() == "Employed") {
                $('.employed-section').removeClass('d-none');
            } else {
                $('.employed-section').addClass('d-none');
            }

            if ($(this).val() == "Self Employed") {
                $('.self-employed-section').removeClass('d-none');
            } else {
                $('.self-employed-section').addClass('d-none');
            }

            if ($(this).val() == "Student") {
                $('.student-section').removeClass('d-none');
            } else {
                $('.student-section').addClass('d-none');
            }

            if ($(this).val() == "Other") {
                $('.other-section').removeClass('d-none');
            } else {
                $('.other-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#Trade-In-Yes').change(function() {
            if ($(this).is(":checked")) {
                $('.trade_in-section').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");

        });

        $('#Trade-In-No').change(function() {
            if ($(this).is(":checked")) {
                $('.trade_in-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $(`
        #employment-status,
        #Trade-In-No,
        #Trade-In-Yes
        `).trigger('change');
    </script>


</body>