<!DOCTYPE html>
<html>

<head>
    <title>Financing | Zop Dealer</title>
    <meta name="description" content="">
    <meta name="keyword" content="" />


    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/single_credit_application/style.css">
    <!-- STYLESHEET END -->

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

    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->


</head>

<body class="home-01">
    <section class="details-main form-new-set">
        <div class="container">
            <form class="needs-validation" id="lead_form" novalidate="novalidate">
                <!--Financial Services Starts-->
                <div class="col-md-10 col-md-offset-1 set-box-step">
                    <div class="step-count-list">
                        <ul class="step-list-b">
                            <li> Step 1</li>
                            <li> Step 2</li>
                            <li> Step 3</li>
                        </ul>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display:block;">
                            <div class="active-color-1"></div>
                            <div class="head-text-f">
                                <div class="set-box">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h4>What is your estimated credit rating?</h4>
                                    </div>
                                    <ul class="form-list-desgine finace-box">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn valid" id="excellent_over" name="credit_rating" value="Over 800"><label> Excellent (Over 800) </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn valid" id="very_good" name="credit_rating" value="Between 740 and 799"><label>Very Good (740 to 799)</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn valid" id="good" name="credit_rating" value="Between 670 and 739"><label> Good (670 to 739)</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn valid" id="fair" name="credit_rating" value="Between 580 and 669"><label>Fair (580 to 669) </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn valid" id="needs" name="credit_rating" value="Under 579"><label>Needs Work (Under 579)</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="credit_rating_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="active-color-2"></div>
                            <div class="head-text-f">
                                <div class="set-box">
                                    <div class="first-block-l border-bottom-ll">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h4>Personal information</h4>
                                            </div>
                                            <div class="form-list-desgine">
                                                <div class="col-md-12 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Date of birth*</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <div class="row">
                                                        <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day">
                                                        </select>
                                                        <div id="birth_date_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <div class="row">
                                                        <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                                        <div id="birth_month_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <div class="row">
                                                        <select class="form-control" name="birth_year" id="dobyear" placeholder="Year">
                                                        </select>
                                                        <div id="birth_year_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>SIN</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="sin" id="sin_zop" placeholder="Sin">
                                                        <div id="sin_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="first-block-l border-bottom-ll">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h4>Your present address</h4>
                                            </div>
                                            <div class="form-list-desgine">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Home Address*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="home_address" id="home_address" placeholder="Address">
                                                        <div id="home_address_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Apt/suite/other</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="apt" id="apt" placeholder="XXXX">
                                                        <div id="apt_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>City*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="city" id="city" placeholder=" City">
                                                        <div id="city_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Province*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                                        <div id="province_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Postal Code*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="postal_code" id="postal_code" placeholder="Postal Code">
                                                        <div id="postal_code_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Residence Type*</label>
                                                    </div>
                                                    <div class="row">
                                                        <select type="text" class="form-control " name="residence_status" placeholder=" Residence">
                                                            <option disabled="disabled" selected="" value="">Residence </option>
                                                            <option value="Rent">Rent</option>
                                                            <option value="Own">Own</option>
                                                            <option value="Own Free and clear">Own Free and clear</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        <div id="residence_status_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Monthly Rent / Mortgage*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="mortgage_rent_amount_monthly" id="mortgage_rent_amount_monthly" placeholder="$0.00">
                                                        <div id="mortgage_rent_amount_monthly_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>How long have you lived here?*</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="row">
                                                                <input type="text" min="0" max="100" class="form-control" name="residence_period_years" placeholder="Years" id="residence_period_years" />
                                                                <div id="residence_period_years_error" class="invalid-feedback"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="row">
                                                                <input type="text" min="0" max="11" name="residence_period_months" class="form-control" placeholder="Months" id="credit-application-coapplicant_residence_period_months" />
                                                                <div id="residence_period_months_error" class="invalid-feedback"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="first-block-l">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h4>Employment Information</h4>
                                            </div>
                                            <div class="form-list-desgine">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Present Employer Name*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" class="form-control " name="employer_name" id="employer_name" placeholder="Present Employer Name">
                                                        <div id="employer_name_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Present Job Title*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" name="job_title" class="form-control" placeholder="Present Job Title*" id="credit-application-employer-job" />
                                                        <div id="job_title_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Work Phone Number*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="tel" name="employer_work_phone" class="form-control" placeholder="" id="credit-application-employer-work-phone" />
                                                        <div id="employer_work_phone_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>How long have you worked here?*</label>
                                                    </div>
                                                    <div class="row row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <input type="text" min="0" name="employment_period_years" class="form-control" placeholder="Years" id="employment_period_years" />
                                                            <div id="employment_period_years_error" class="invalid-feedback"></div>

                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <input type="text" min="0" max="11" name="employment_period_months" class="form-control" placeholder="Months" id="credit-application-lengthmonth" />
                                                            <div id="employment_period_years_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="head-lable">
                                                        <label>Monthly Income*</label>
                                                    </div>
                                                    <div class="row">
                                                        <input type="text" name="gross_income" class="form-control" placeholder="Monthly Income*" id="credit-application-employer-gross-income" />
                                                        <div id="gross_income_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="active-color-3"></div>
                            <div class="head-text-f list-cont-n">
                                <div class="set-box">
                                    <h3>Congratulations your Pre-Approval is Ready!</h3>
                                    <div class="form-list-desgine" style="padding-top:30px;">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="head-lable">
                                                    <label>First Name</label>
                                                </div>
                                                <div class="row">
                                                    <input type="text" class="form-control " name="first_name" id="first-name" placeholder="First Name">
                                                    <div id="first_name_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="head-lable">
                                                    <label>Last Name</label>
                                                </div>
                                                <div class="row">
                                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                                    <div id="last_name_error" class="invalid-feedback"></div>

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="head-lable">
                                                    <label>Email</label>
                                                </div>
                                                <div class="row">
                                                    <input type="text" class="form-control " name="email" id="email" placeholder="Email">
                                                    <div id="email_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="head-lable">
                                                    <label>Mobile Phone</label>
                                                </div>
                                                <div class="row">
                                                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="Mobile Phone">
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
                                                <div id="recaptcha-error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="action next btn btn-info" style="display: block;">Next</a>
                        <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        <a type="submit" class="action back btn btn-info" style="display: none;">Back</a>

                    </div>
                </div>
            </form>
        </div>
    </section>


    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="<?php echo getenv('image.cdn') ?>/custom_forms/js/jquery-3.1.1.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="<?php echo getenv('image.cdn') ?>/dist/assets/js/dobpicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="<?php echo getenv('image.cdn') ?>/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>


    <script type="text/javascript">
        function isLocalStorageAvailable() {
            var test = 'test';
            try {
                localStorage.setItem(test, test);
                localStorage.removeItem(test);
                return true;
            } catch (e) {
                return false;
            }
        }

        function persistInput(input) {
            if (isLocalStorageAvailable()) {
                var key = input.name;
                var storedValue = localStorage.getItem(key);
                $(input).on('change', function() {
                    localStorage.setItem(key, $(input).val());

                });
            }
        }

        function loadInput(input) {
            if (isLocalStorageAvailable()) {
                var key = input.name;
                var storedValue = localStorage.getItem(key);
                if (storedValue) {
                    $(input).val(storedValue);
                }
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
            btnnext.click(function(event, blank_check_flag = false, check_validations = true) {

                var validator = $("#lead_form").validate();

                parent.postMessage({
                    type: "css",
                    value: $(".step.well:nth-child(" + current + ")").outerHeight(true) + 230,
                    element_id: "finance_form"
                }, "*");


                if (validator.form() === false && check_validations) {

                    const invalid_doms = document.querySelectorAll(".error");

                    if (invalid_doms.length > 0) {
                        invalid_doms[0].scrollIntoView({
                            behavior: 'smooth'
                        });
                    }

                    parent.postMessage({
                        type: "css",
                        value: $(".step.well:nth-child(" + current + ")").outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");

                    return false;
                }

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
                        toast: false,
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

                    parent.postMessage({
                        type: "css",
                        value: $(".step.well:nth-child(" + current + ")").outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");

                    parent.postMessage({
                        type: "scroll_top",
                        value: $(".step.well:nth-child(" + current + ")").outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");
                }
                hideButtons(current);
            })

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', [false, false, false]);
                }
                // hideButtons(current);
            })

            setTimeout(() => {
                parent.postMessage({
                    type: "css",
                    value: $(".step.well:nth-child(" + current + ")").outerHeight(true) + 230,
                    element_id: "finance_form"
                }, "*");
            }, 1000);
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

            if (isLocalStorageAvailable()) {

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
            }

            $('input[type="tel"]').each(function() {
                $(this).inputmask({
                    "mask": "(999) 999-9999",
                    "autoUnmask": true
                });
            });

            // START Form Validation
            $("#lead_form").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
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
                    gross_income: {
                        required: true,
                        min: 0,
                        number: true
                    },
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
                    employer_work_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    job_title: "required",
                    employer_name: "required",
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
                    mortgage_rent_amount_monthly: {
                        required: true,
                        min: 0,
                        number: true,
                    },
                    residence_status: "required",
                    postal_code: "required",
                    province: "required",
                    city: "required",
                    home_address: "required",
                    birth_year: "required",
                    birth_month: "required",
                    birth_date: "required",
                    credit_rating: "required"


                },
                messages: {
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
                    mobile_phone: {
                        required: "Please Enter Your Mobile Number",
                        number: "Mobile Number should be a Number",
                        minlength: "Your Mobile Number must be 10 characters long"
                    },
                    work_phone: {
                        required: "Please Enter Your Work Phone Number",
                        number: "Work Number should be a Number",
                        minlength: "Your Work Number must be 10 characters long"
                    },
                    sin: {
                        required: "Please Enter Your Sin Number",
                        number: "SIN should be a Number",
                        minlength: "Your Number must be 9 characters long"
                    },
                    date_of_birth: "Please Enter Your Date Of Birth",
                    marital_status: "Please Select Your Marital Status",
                    drivers_license_number: "Please Enter Your Drivers License Number",
                    drivers_license_expiry_date: "Please Enter Your Drivers License Expiry Date",
                    home_address: "Please Enter Your Home Address",
                    city: "Please Enter Your City",
                    province: "Please Enter Your Province",
                    postal_code: "Please Enter Your Postal Code",
                    residence_status: "Please Select Your Residence Status",

                    mortgage_rent_amount_monthly: {
                        required: "Mortgage / Rent Amount is Required",
                        number: "Mortgage / Rent Amount should be a Number"
                    },
                    mortgage_balance_amount: {
                        required: "Mortgage Balance Amount is Required",
                        number: "Mortgage Balance Amount should be a Number"
                    },
                    market_value: {
                        required: "Market Value is Required",
                        number: "Market Value should be a Number"
                    },
                    residence_period_years: {
                        required: "Please Enter the Residence Period Years",

                    },
                    residence_period_months: {
                        required: "Please Enter Residence Period Months",

                    },
                    previous_residence_address_period_years: {
                        required: "Please Enter Previous Residence Years",
                    },
                    previous_residence_period_months: {
                        required: "Please Enter Previous Residence Months",
                    },

                    employer_name: "Please Enter Your Employer Name",
                    self_employed: "Please Select Your Self Employed",
                    job_title: "Please Enter Your Job Title",
                    employer_work_phone: {
                        required: "Please Enter Your Employer Work Phone Number",
                        number: "Employer Work Phone should be a Number",
                        minlength: "Your Employer Work Phone must be 10 characters long"
                    },
                    gross_income: {
                        required: "Please Enter Your Gross Income",
                        number: "Gross Income should be a Number"

                    },
                    payment_frequency: "Please Select Your Income Frequency",
                    employment_period_years: {
                        required: "Please Enter Employment Period Years",

                    },
                    employment_period_months: {
                        required: "Please Enter Employment Period Months",

                    },

                    employer_address: "Please Enter Your Employer Address",
                    employer_city: "Please Enter Your Employer City",
                    employer_province: "Please Enter Your Employer Province",
                    employer_postal_code: "Please Enter Your Employer Postal Code",
                    previous_work_phone: {
                        required: "Please Enter Your Previous Employer Work Phone Number",
                        number: "Previous Employer Work Phone should be a Number",
                        minlength: "Your Previous Employer Work Phone must be 10 characters long"

                    },
                    previous_employment_period_years: {
                        required: "Please Enter Previous Employment Period Years",

                    },
                    previous_employment_period_months: {
                        required: "Please Enter Previous Employment Period Months",

                    },
                    other_gross_income: {
                        required: "Please Enter Other Gross Income",
                        number: "Other Gross Income should be a Number"

                    },
                    coapplicant_phone: {
                        required: "Please Enter Co-Applicant Phone Number",
                        number: "Co-Applicant Phone Number should be a Number",
                        minlength: "Co-Applicant Phone Number must be 10 characters long"

                    },
                    coapplicant_mobile_phone: {
                        required: "Please Enter Co-Applicant Mobile Number",
                        number: "Co-Applicant Mobile Number should be a Number",
                        minlength: "Co-Applicant Mobile Number must be 10 characters long"

                    },
                    coapplicant_work_phone: {
                        required: "Please Enter Co-Applicant Work Phone Number",
                        number: "Co-Applicant Work Phonae Number should be a Number",
                        minlength: "Co-Applicant Work Phone Number must be 10 characters long"

                    },
                    coapplicant_sin: {
                        minlength: "Co-Applicant SIN must be at least 9 characters long",
                        number: "Co-Applicant SIN should be a Number"
                    },
                    coapplicant_mortgage_rent_amount_monthly: {
                        required: "Co-Applicant Mortgage / Rent Amount is Required",
                        number: "Co-Applicant Mortgage / Rent Amount should be a Number"
                    },
                    coapplicant_mortgage_balance_amount: {
                        required: "Co-Applicant Mortgage Balance Amount is Required",
                        number: "Co-Applicant Mortgage Balance Amount should be a Number"
                    },
                    coapplicant_market_value: {
                        required: "Co-Applicant Market Value is Required",
                        number: "Co-Applicant Market Value should be a Number"
                    },
                    coapplicant_residence_period_years: {
                        required: "Please Enter the Co-Applicant Residence Period Years",

                    },
                    coapplicant_residence_period_months: {
                        required: "Please Enter the Co-Applicant Residence Period Months",

                    },
                    coapplicant_employer_work_phone: {
                        required: "Please Enter Co-Applicant Work Phone Number",
                        number: "Co-Applicant Work Phone Number should be a Number",
                        minlength: "Co-Applicant Work Phone Number must be 10 characters long"
                    },
                    coapplicant_gross_income: {
                        required: "Please Enter Your Co-Applicant Gross Income",
                        number: "Co-Applicant Gross Income should be a Number"

                    },
                    coapplicant_employment_period_years: {
                        required: "Please Enter Co-Applicant Employment Period Years",

                    },
                    coapplicant_employment_period_months: {
                        required: "Please Enter Co-Applicant Employment Period Months",

                    },

                    coapplicant: "Please Select Your Co-Applicant Information",
                    gender: "This field is required"
                },
                errorPlacement: function(error, element) {

                    if (element.is(":radio")) {
                        error.appendTo(element.parent().parent().parent().next(".invalid-feedback"));
                    } else { // This is the default behavior 
                        error.appendTo(element.next(".invalid-feedback"));
                    }
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
                            if (isLocalStorageAvailable()) {
                                localStorage.clear();
                            }

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

                                        $('input[name="' + key + '"]').prop('title', errors[key]).addClass('is-invalid').nextAll('.invalid-feedback').text(errors[key]);
                                        $('select[name="' + key + '"]').prop('title', errors[key]).addClass('is-invalid').nextAll('.invalid-feedback').text(errors[key]);
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
            // END Form Validation

            var $theForm = $("#lead_form"),
                $form = $theForm.validate();
            //changed to check every textbox type input inside #theForm:
            $theForm.on("blur", "input", "select", function(e) {
                //use element() to test only one element:
                if ($form.element(this)) {
                    console.log("is valid");
                }
            });
        });
    </script>