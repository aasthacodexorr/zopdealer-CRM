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
                        <div class="step well" style="display:block;">
                            <div class="head-text-f">
                                <div class="set-box row">
                                    <h3 class="w-100">Personal Information</h3>
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
                                            <input type="text" class="form-control" name="gender" id="gender" placeholder="Gender">
                                            <div id="gender_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="tel" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                            <div id="phone_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="Date of Birth">
                                            <div id="date_of_birth_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="sin" id="sin" placeholder="SIN">
                                            <div id="sin_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                            <div id="email_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid">
                                            <select id="marital_status" name="marital_status" class="form-control">
                                                <option disabled="" selected="">Select Marital Status</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Common Law">Common Law</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                        </div>
                                        <div id="marital_status_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box row">
                                    <h3 class="w-100">Applicants Address</h3>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                            <div id="street_address_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                            <div id="city_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                            <div id="province_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                            <div id="postal_code_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <h3 class="w-100">How Long at this address?</h3>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" min="0" max="100" class="form-control" name="residence_period_years" id="residence_period_years" placeholder="Years">
                                            <div id="residence_period_years_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" min="0" max="11" class="form-control" name="residence_period_months" id="residence_period_months" placeholder="Months">
                                            <div id="residence_period_months_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <h3 class="w-100">Rent/Mortgage</h3>
                                        <select id="residence_status" name="residence_status" class="form-control">
                                            <option disabled="" selected="">Select Residence Status</option>
                                            <option value="Rent">Rent</option>
                                            <option value="Mortgage">Mortgage</option>
                                        </select>
                                        <div id="residence_status_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <h3 class="w-100">How Much Rent/Mortgage?</h3>
                                            <input type="text" class="form-control " name="mortgage_rent_amount" id="mortgage_rent_amount" placeholder="Payments">
                                            <div id="mortgage_rent_amount_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <h3 class="w-100">Who do you Bank with?</h3>
                                            <input type="text" class="form-control " name="bank_name" id="bank_name" placeholder="Bank Name">
                                            <div id="bank_name_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <h3 class="w-100">Which Bank is Mortgage Holder?</h3>
                                            <input type="text" class="form-control " name="mortgage_holder" id="mortgage_holder" placeholder="Mortgage Holder">
                                            <div id="mortgage_holder_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <h3 class="w-100 residence_previous_address_section d-none">Applicants Previous Address</h3>
                                    <p class="w-100 residence_previous_address_section d-none">(If Current Address is shorter than 2 Years)</p>
                                    <div class="col-md-6 col-xs-12 residence_previous_address_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="previous_street_address" id="previous_street_address" placeholder="Previous Street Address">
                                            <div id="previous_street_address_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 residence_previous_address_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="previous_city" id="previous_city" placeholder="Previous City">
                                            <div id="previous_city_error" class="invalid-feedback"></div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 residence_previous_address_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="previous_province" id="previous_province" placeholder="Previous Province">
                                            <div id="previous_province_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 residence_previous_address_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="previous_postal_code" id="previous_postal_code" placeholder="Previous Postal Code">
                                            <div id="previous_postal_code_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <h3 class="w-100 residence_previous_address_section d-none">How Long at this address?</h3>
                                    <ul class="form-list-desgine residence_previous_address_section d-none">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" min="0" max="100" class="form-control" name="previous_residence_period_years" id="previous_residence_period_years" placeholder="Years">
                                                <div id="previous_residence_period_years_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" min="0" max="11" class="form-control" name="previous_residence_period_months" id="previous_residence_period_months" placeholder="Months">
                                                <div id="previous_residence_period_months_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box row">
                                    <h3 class="w-100">Employer Address</h3>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="employer_name" id="employer_name" placeholder="Employer Name">
                                            <div id="employer_name_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Job Title">
                                            <div id="job_title_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="tel" name="employer_phone" class="form-control" id="tel" placeholder="Employer Phone Number">
                                            <div id="employer_phone_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="employer_address" id="employer_address" placeholder="Employer Address">
                                            <div id="employer_address_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="employer_city" id="employer_city" placeholder="Employer City">
                                            <div id="employer_city_error" class="invalid-feedback"></div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="employer_province" id="employer_province" placeholder="Employer Province">
                                            <div id="employer_province_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="employer_postal_code" id="employer_postal_code" placeholder="Employer Postal Code">
                                            <div id="employer_postal_code_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <h3 class="w-100">How Long at this Employer?</h3>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" min="0" class="form-control" name="employment_period_years" id="employment_period_years" placeholder="Years">
                                            <div id="employment_period_years_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="text" min="0" max="11" class="form-control" name="employment_period_months" id="employment_period_months" placeholder="Months">
                                            <div id="employment_period_months_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <h3 class="w-100">Gross Monthly Income (Before or After Tax)</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="monthly_gross_income" id="monthly_gross_income" placeholder="Amount">
                                                <div id="monthly_gross_income_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <h3 class="w-100 previous_employer_section d-none">Previous Employer Address</h3>
                                    <p class="w-100 previous_employer_section d-none">(Only if shorter than 2 Years)</p>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="previous_employer_name" id="previous_employer_name" placeholder="Previous Employer Name">
                                            <div id="previous_employer_name_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="previous_job_title" id="previous_job_title" placeholder="Previous Job Title">
                                            <div id="previous_job_title_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="tel" name="previous_employer_phone" class="form-control" id="tel" placeholder="Previous Employer Phone Number">
                                            <div id="previous_employer_phone_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="previous_employer_address" id="previous_employer_address" placeholder="Previous Employer Address">
                                            <div id="previous_employer_address_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="previous_employer_city" id="previous_employer_city" placeholder="Previous Employer City">
                                            <div id="previous_employer_city_error" class="invalid-feedback"></div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control " name="previous_employer_province" id="previous_employer_province" placeholder="Previous Employer Province">
                                            <div id="previous_employer_province_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" class="form-control" name="previous_employer_postal_code" id="previous_employer_postal_code" placeholder="Previous Employer Postal Code">
                                            <div id="employer_postal_code_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <h3 class="w-100 previous_employer_section d-none">How Long at this Employer?</h3>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1 previous_employer_section d-none">
                                            <input type="text" min="0" class="form-control" name="previous_employment_period_years" id="previous_employment_period_years" placeholder="Years">
                                            <div id="previous_employment_period_years_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 previous_employer_section d-none">
                                        <div class="head-felid1">
                                            <input type="text" min="0" max="11" class="form-control" name="previous_employment_period_months" id="previous_employment_period_months" placeholder="Months">
                                            <div id="previous_employment_period_months_error" class="invalid-feedback"></div>
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
                date_of_birth: "required",
                residence_period_years: {
                    required: true,
                    min: 0,
                    max: 65,
                    number: true,
                },
                residence_period_months: {
                    required: true,
                    min: 0,
                    max: 11,
                    number: true,
                },
                residence_status: "required",
                mortgage_rent_amount: {
                    required: true,
                    min: 0,
                    number: true,
                },
                employer_name: "required",
                job_title: "required",
                employer_phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    number: true,
                },
                employer_address: "required",
                employer_city: "required",
                employer_province: "required",
                employer_postal_code: "required",
                employment_period_years: {
                    required: true,
                    min: 0,
                    max: 65,
                    number: true
                },
                employment_period_months: {
                    required: true,
                    min: 0,
                    max: 11,
                    number: true
                },
                monthly_gross_income: {
                    required: true,
                    min: 0,
                    number: true
                },

            };
            var messages = {
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
                date_of_birth: "Please Enter Your Date Of Birth",
                residence_period_years: {
                    required: "Please Enter the Residence Period Years",

                },
                residence_period_months: {
                    required: "Please Enter Residence Period Months",

                },
                residence_status: "Please Select Your Residence Status",
                mortgage_rent_amount: {
                    required: "Mortgage / Rent Amount is Required",
                    number: "Mortgage / Rent Amount should be a Number"
                },
                employer_name: "Please Enter Your Employer Name",
                job_title: "Please Enter Your Job Title",
                employer_phone: {
                    required: "Please Enter Your Employer Work Phone Number",
                    number: "Employer Work Phone should be a Number",
                    minlength: "Your Employer Work Phone must be 10 characters long"
                },
                employer_address: "Please Enter Your Employer Address",
                employer_city: "Please Enter Your Employer City",
                employer_province: "Please Enter Your Employer Province",
                employer_postal_code: "Please Enter Your Employer Postal Code",
                employment_period_years: {
                    required: "Please Enter Employment Period Years",
                },
                employment_period_months: {
                    required: "Please Enter Employment Period Months",
                },
                monthly_gross_income: {
                    required: "Please Enter Your Gross Income",
                    number: "Gross Income should be a Number"
                },

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



        $('#residence_period_years').change(function() {
            if (parseInt($(this).val()) < 2) {
                $('.residence_previous_address_section').removeClass('d-none');
            } else {
                $('.residence_previous_address_section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");

        });

        $('#employment_period_years').change(function() {
            if (parseInt($(this).val()) < 2) {
                $('.previous_employer_section').removeClass('d-none');
            } else {
                $('.previous_employer_section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");

        });



        $(`
        #employment_period_years,
        #residence_period_years
        `).trigger('change');
    </script>


</body>