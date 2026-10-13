<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .g-recaptcha div {
            margin: auto;
        }

        .swal2-icon {
            font-size: 2em !important;
        }

        .swal2-title {
            font-size: 2em !important;
        }
    </style>

    <?php if (!empty($custom_less)) {
        $custom_less_urls = explode(',', $custom_less);
        foreach ($custom_less_urls as $each_url) { ?>
            <link rel="stylesheet/less" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--
<div class="page-header-area">
   <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <div class="header-page">
				<div class="col-md-6 col-sm-6">
                    <h1> Apply for Financing  </h1> 
					</div>					
					<div class="col-md-6 col-sm-6">
                    <ul class="list-bl">
                        <li> <a href="/"> Home </a> </li> | 
                        <li> Apply for Financing </li>
                    </ul>
						</div>					
                </div>
            </div>
        </div>
    </div>
</div>
-->

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
                                <p class="time-list-f">2-3 minutes from finish</p>
                                <h3>Get a Car Loan Fast With Any Credit Rating</h3>
                                <p>Good credit, bad credit & no credit approved!</p>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="under-month" name="vehicle_type" value="Car"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/cardora_car.png" class="img-resposive" /><strong>Car</strong></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="250-month" name="vehicle_type" value="SUV"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/cardora_suv.png" class="img-resposive" /><strong>SUV</strong></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="374-month" name="vehicle_type" value="Truck"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/cardora_truck.png" class="img-resposive" /><strong>Truck</strong></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="Over-month" name="vehicle_type" value="Van"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/cardora_van.png" class="img-resposive" /><strong>Van</strong></label>
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
                                <p class="time-list-f">2-3 minutes from finish</p>
                                <h3>What is your monthly budget?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="under-month" name="budget" value="Under 250 per month"><label> Under $250 / month</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="250-month" name="budget" value="Between 250 and 374 per month"><label>$250 - 374 / month</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="374-month" name="budget" value="Between 375 and 500 per month"><label>$375 - 500 / month</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="Over-month" name="budget" value="Over 500 per month"><label>Over $500 / month</label>
                                        </div>
                                    </li>
                                </ul>
                                <div id="budget_error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="step well" style="display:none ;">
                        <div class="head-text-f">
                            <div class="set-box">
                                <p class="time-list-f">2-3 minutes from finish</p>
                                <h3>Do You Have a Trade In?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="No" name="trade_in" value="No"><label>No</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="Yes" name="trade_in" value="Yes"><label>Yes, I want to trade</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="Unsure" name="trade_in" value="Unsure"><label>Yes, I'm keeping it</label>
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
                                <p class="time-list-f">2-3 minutes from finish</p>
                                <h3>What is your date of birth?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid1">
                                            <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day">
                                            </select>
                                            <div id="birth_date_error" class="invalid-feedback"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid1">
                                            <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                            <div id="birth_month_error" class="invalid-feedback"></div>
                                        </div>
                                    </li>
                                    <li>
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
                                <p class="time-list-f">2-3 minutes from finish</p>
                                <h3>What Is Your Employment Status?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="employed" name="employment_status" value="Employed"><label>Employed</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="self-employed" name="employment_status" value="Self Employed"><label>Self-Employed</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="student" name="employment_status" value="Retired"><label>
                                                Retired</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="retired-pension" name="employment_status" value="Disability"><label>Disability</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="other" name="employment_status" value="Other"><label>Other</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="other" name="employment_status" value="Unemployed"><label>Unemployed</label>
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
                                <p class="time-list-f">2-3 minutes from finish</p>
                                <h3>What is your current monthly gross income?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="employed" name="monthly_gross_income" value="Between 1800 to 2200"><label>$1800 to $2200</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="self-employed" name="monthly_gross_income" value="Between 2200 to 3000"><label>$2200 to $3000</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="student" name="monthly_gross_income" value="Between 3000 to 4500"><label>
                                                $3000 to $4500</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="retired-pension" name="monthly_gross_income" value="Over 4500"><label>Over $4500</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="other" name="monthly_gross_income" value="Under 1800"><label>Under $1800</label>
                                        </div>
                                    </li>
                                </ul>
                                <div id="monthly_gross_income_error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="step well" style="display: none;">
                        <div class="head-text-f">
                            <div class="set-box">
                                <p class="time-list-f">1-2 minutes from finish</p>
                                <h3>How long have you been earning this income?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="employed" name="employment_period_with_this_salary" value="Between 3 and 6 Months"><label>3 - 6 Months</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="self-employed" name="employment_period_with_this_salary" value="Between 6 and 12 Months"><label>6 - 12 Months</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="student" name="employment_period_with_this_salary" value="Between 12 Months And 2 Years"><label>12 Months - 2 Years</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="retired-pension" name="employment_period_with_this_salary" value="Above 2 Years"><label>2 Years +</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="other" name="employment_period_with_this_salary" value="Less Than 3 Months"><label>Less than 3 Months</label>
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
                                <p class="time-list-f">1-2 minutes from finish</p>
                                <h3>Where are you working?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="company_name" id="lead-company_name" placeholder="Company Name">
                                            <div id="company_name_error" class="invalid-feedback"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="job_title" id="lead-job_title" placeholder="Title">
                                            <div id="job_title_error" class="invalid-feedback"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="step well" style="display:none;">
                        <div class="head-text-f">
                            <div class="set-box">
                                <p class="time-list-f">1-2 minutes from finish</p>
                                <h3>Do you own or rent your home?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="employed" name="residence_status" value="Own"><label>Own</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="self-employed" name="residence_status" value="Rent"><label>Rent</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="student" name="residence_status" value="Other"><label>Other</label>
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
                                <p class="time-list-f">1-2 minutes from finish</p>
                                <h3>What Are Your Monthly (Rent Or Mortgage) Payments?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="mortgage_rent_amount_monthly" id="lead-rend" placeholder="Payments">
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
                                <p class="time-list-f">1-2 minutes from finish</p>
                                <h3>What is your address?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="street_address" id="lead-company_name" placeholder="Street Address">
                                            <div id="street_address_error" class="invalid-feedback"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="city" id="lead-job_title" placeholder="City">
                                            <div id="city_error" class="invalid-feedback"></div>

                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="province" id="lead-company_name" placeholder="Province">
                                            <div id="province_error" class="invalid-feedback"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="postal_code" id="lead-job_title" placeholder="Postal Code">
                                            <div id="postal_code_error" class="invalid-feedback"></div>

                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="step well" style="display:none ;">
                        <div class="head-text-f">
                            <div class="set-box">
                                <p class="time-list-f">1-2 minutes from finish</p>
                                <h3>How Long Have You Lived at Your Current Address?</h3>
                                <ul class="form-list-desgine">
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="other" name="residence_period" value="Less than 3 Months"><label>Less than 3 Months</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="employed" name="residence_period" value="Between 3 and 6 Months"><label>3 - 6 Months</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="self-employed" name="residence_period" value="Between 6 and 12 Months"><label>6 - 12 Months</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="student" name="residence_period" value="Between 12 Months And 2 Years"><label>12 Months - 2 Years</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="head-felid">
                                            <input type="radio" class="seclect-ratio-btn" id="retired-pension" name="residence_period" value="Above 2 Years"><label>2 Years +</label>
                                        </div>
                                    </li>
                                </ul>
                                <div id="residence_period_error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="step well" style="display: none;">
                        <div class="head-text-f">
                            <div class="set-box">
                                <p class="time-list-f">1 minutes from finish</p>
                                <h3>Congratulations your Pre-Approval is Ready!</h3>
                                <div class="col-md-6 col-xs-12">
                                    <div class="head-felid1">
                                        <input type="input" class="form-control" name="first_name" id="first-name" placeholder="First Name">
                                        <div id="first_name_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="head-felid1">
                                        <input type="input" class="form-control" name="last_name" id="last-name" placeholder="Last Name">
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
                                        <p>I have read and accept the <a href="#">Terms of Use</a> . I also acknowledge having read the <a href="#">Privacy Policy</a> and agree to the terms herein.</p>
                                    </div>
                                    <div class="checkbox-block link-set">
                                        <p style="padding-top: 15px;padding-bottom: 15px;"><input type="checkbox" name="sms_consent" checked="">
                                            By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us. </p>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 p-top-20 text-center">
                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <a class="action next btn btn-info" style="display:block;">Continue</a>
                    <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                    <a class="action back btn btn-info" style="display:block;">Back</a>

                </div>
                <div class="safe-and-secure text-center mt-3">
                    <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/icon-bb.png" style="width:25px;padding-right:5px; padding-top: 0px;" />Confidential, Safe and Secure</p>
                    <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/trust-badges-small.webp" class="bottom-img" /> </p>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="text/javascript" src="<?php echo getenv('image.cdn') ?>/custom_forms/js/jquery-3.1.1.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="<?php echo getenv('image.cdn') ?>/dist/assets/js/dobpicker.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="<?php echo getenv('image.cdn') ?>/dist/assets/js/jquery.inputmask.min.js"></script>
<!--Financial Services Ends-->
<script type="text/javascript">
    function persistInput(input) {
        var key = input.name;

        var storedValue = localStorage.getItem(key);



        $(input).on('change', function() {
            localStorage.setItem(key, $(input).val());

        });

    }

    function loadInput(input) {
        var key = input.name;

        var storedValue = localStorage.getItem(key);
        if (storedValue) {
            // input.value = storedValue;
            $(input).val(storedValue);
        }

    }

    $(document).ready(function() {
        $.dobPicker({
            daySelector: '#dobday',
            monthSelector: '#dobmonth',
            yearSelector: '#dobyear'

        });
    });


    $(document).ready(function() {
        var source_url = (window.location != window.parent.location) ?
            document.referrer :
            document.location.href;

        document.querySelector(`input[name='source_url'`).value = source_url;

        var current = 1;

        widget = $(".step");
        btnnext = $(".next");
        btnback = $(".back");
        btnsubmit = $(".submit");

        // Init buttons and UI
        widget.not(':eq(0)').hide();
        hideButtons(current);
        setProgress(current);

        // Next button click action
        // history.pushState(current, "title", "#"+current)
        btnnext.click(function(event, blank_check_flag = true) {
            var blank_input = false;
            $(".step.well:nth-child(" + current + ") :input").each(function() {
                var element_value = undefined;
                if (this.type == 'radio') {
                    element_value = $("input[name=" + this.name + "]:checked").val();
                } else {
                    element_value = $(this).val();
                }

                // alert(element_value);

                element_value == undefined || element_value == "" ? blank_input = true : null;
            });

            if (blank_input && blank_check_flag) {
                Swal.fire({
                    toast: true,
                    position: "center",
                    title: 'Please fill all the inputs.',
                    icon: 'error',
                    timer: 1000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });

                return false;
            }

            var check_val = 0;


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
                btnnext.trigger('click', [false]);
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
</script>
<script>
    $(document).ready(function() {

        $('form#lead_form :input').each(function() {
            // loadInput(this); this.name

            var storedValue = localStorage.getItem(this.name);
            if (storedValue) {
                // input.value = storedValue;
                // $(this).val(storedValue);
                if (this.type == 'radio') {
                    $(this).val([storedValue]);
                } else {
                    $(this).val(storedValue);
                }

            }

            persistInput(this);
        });

        $('input[type="tel"]').each(function() {
            $(this).inputmask({
                "mask": "(999) 999-9999",
                "autoUnmask": true
            });
        });

        //Method for saving lead response
        $("form#lead_form").on("submit", function(event) {
            event.preventDefault();
            $('input[type="tel"]').each(function() {
                $(this).inputmask('remove');
            });
            $("input").removeClass("is-invalid");
            $('.invalid-feedback').text("");
            var form = $(this);
            var alert = Swal.mixin({
                showConfirmButton: false,
                position: "center",
                timerProgressBar: true,
                confirmButtonText: "",
                title: "Sending your message...",
                onBeforeOpen: () => {
                    alert.showLoading();
                },
            });
            alert.fire();
            $.ajax({
                url: window.location.origin + '/api/leads/responses/create',
                type: "POST",
                data: form.serialize(), // get all form variables
                success: function(result) {
                    localStorage.clear();
                    alert.update({
                        title: 'Thank You! \n Your message has been sent.',
                        icon: "success",
                    });

                    setTimeout(() => {
                        // Fire A Close Alert and Than Navigation To ThankYou Page.
                        parent.postMessage("alertClose", "*");
                        parent.postMessage("redirectToThankYouPage", "*");
                        window.location.href = window.location.origin + '/thankyou.html';
                    }, 3000);

                    $('form#lead_form').each(function() {
                        this.reset();
                    });
                },
                error: function(error) {
                    var errors = error.responseJSON.errors;
                    if (typeof(errors) == 'object') {
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                //var va = errors[key];

                                $('input[name="' + key + '"]').addClass('is-invalid');
                                $('#' + key + '_error').text(errors[key]);

                            }
                        }

                        alert.update({
                            toast: true,
                            icon: 'error',
                            timer: 1000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            position: 'center',
                            title: 'There were errors in submitting the form.',
                            icon: "error",
                        });

                        setTimeout(() => {
                            alert.close()
                        }, 1000);

                    } else {
                        alert.update({
                            toast: true,
                            icon: 'error',
                            timer: 1000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            position: 'center',
                            title: errors,
                            icon: "error",
                        });

                        setTimeout(() => {
                            alert.close()
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

                }
            });
            grecaptcha.reset();
        });
    });
</script>