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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="/custom_forms/style.css"> -->
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->


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
    <main class="finance-page">
        <section class="hero-intro container">
            <div class="eyebrow">AUTO FINANCING QUIZ</div>
            <h1>Find Your Options in <span>2 Minutes.</span></h1>
            <p>A quick, secure form to see what you may qualify for. No impact to your credit score.</p>
            <div class="benefits row g-0">
                <div class="col-md-4 benefit"><i class="far fa-clock"></i><strong>Takes 2 minutes</strong></div>
                <div class="col-md-4 benefit"><i class="fa-solid fa-credit-card"></i><strong>No credit score impact</strong></div>
                <div class="col-md-4 benefit"><i class="fa-solid fa-lock"></i><strong>100% secure &amp; confidential</strong></div>
            </div>
        </section>

        <section class="form-shell">

            <div class="stepper" aria-label="Form progress">
                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <div>Your Info</div>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <div>Financial Details</div>
                </div>
                <div class="step-line"></div>
                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <div>Vehicle<br class="mobile-only"> Preferences</div>
                </div>
                <!-- <div class="step-line"></div>
                <div class="step-item" data-step="4">
                    <div class="step-circle">4</div>
                    <div>Review &amp; Submit</div>
                </div> -->
            </div>

            <div class="form-content">
                <!-- STEP 1 -->
                <div class="form-step active" id="step-1">

                    <div class="step-label">Step 1 of 3</div>
                    <h2>Your Information</h2>
                    <form id="form1" novalidate>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label>First Name <em>*</em></label>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                <div id="first_name_error" class="invalid-feedback"></div>
                            </div>
                            <div class="col-lg-6">
                                <label>Last Name <em>*</em></label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                <div id="last_name_error" class="invalid-feedback"></div>
                            </div>
                            <div class="col-lg-6">
                                <label>Email Address <em>*</em></label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                <div id="email_error" class="invalid-feedback"></div>
                            </div>
                            <div class="col-lg-6">
                                <label>Phone Number <em>*</em></label>
                                <input type="tel" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                <div id="phone_error" class="invalid-feedback"></div>
                            </div>
                            <div class="col-lg-6">
                                <label>Date of Birth <em>*</em></label>
                                <div class="dob-row">
                                    <div>
                                        <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day">
                                        </select>
                                        <div id="birth_date_error" class="invalid-feedback"></div>
                                    </div>
                                    <div>
                                        <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                        <div id="birth_month_error" class="invalid-feedback"></div>
                                    </div>
                                    <div>
                                        <select class="form-control" name="birth_year" id="dobyear" placeholder="Year">
                                        </select>
                                        <div id="birth_year_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>Postal Code <em>*</em></label>
                                <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                <div id="postal_code_error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                    <div class="action-row single step-navigation">
                        <button type="button" class="btn-primary-custom next-btn" data-next="2">Continue to Financial Details <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="form-step" id="step-2">
                    <div class="step-label">Step 2 of 3</div>
                    <h2>Your Financial Details</h2>
                    <p class="subtitle">Tell us a bit about your financial situation so we can<br class="desktop-only"> find the best options for you.</p>
                    <form id="form2" novalidate>
                        <div class="mb-4">
                            <label>Employment Status <em>*</em></label>
                            <select id="employment-status" class="form-select" name="employment_status" placeholder="Select">
                                <option disabled="disabled" selected value="">Select</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Self-employed">Self-employed</option>
                                <option value="Retired">Retired</option>
                                <option value="Student">Student</option>
                                <option value="Unemployed">Unemployed</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label>Monthly Income (Before Tax) <em>*</em></label>
                            <select id="monthly_income" class="form-select" name="monthly_income" placeholder="Select">
                                <option disabled="disabled" selected value="">Select</option>
                                <option value="$0 - $2,499">$0 - $2,499</option>
                                <option value="$2,500 - $3,999">$2,500 - $3,999</option>
                                <option value="$4,000 - $4,999">$4,000 - $4,999</option>
                                <option value="$5,000 - $6,999">$5,000 - $6,999</option>
                                <option value="$7,000 - $9,999">$7,000 - $9,999</option>
                                <option value="$10,000+">$10,000+</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label>Time at Current Job <em>*</em></label>
                            <select id="time_at_current_job" class="form-select" name="time_at_current_job" placeholder="Select">
                                <option disabled="disabled" selected value="">Select</option>
                                <option value="Less than 6 months">Less than 6 months</option>
                                <option value="6 months - 1 year">6 months - 1 year</option>
                                <option value="1 - 2 years">1 - 2 years</option>
                                <option value="2+ years">2+ years</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label>Housing Status <em>*</em></label>
                            <div class="choice-grid three">
                                <label class="choice-card"><input type="radio" name="housing" id="housing-own" value="Own" required><span><i class="fa-regular fa-house"></i><b>Own</b></span></label>
                                <label class="choice-card"><input type="radio" name="housing" id="housing-rent" value="Rent"><span><i class="fa-regular fa-building"></i><b>Rent</b></span></label>
                                <label class="choice-card"><input type="radio" name="housing" id="housing-live-with-family" value="Live with Family"><span><i class="fa-solid fa-people-roof"></i><b>Live with Family</b></span></label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label>Monthly Housing Payment <em>*</em></label>
                            <select id="monthly_housing_payment" class="form-select" name="monthly_housing_payment" placeholder="Select">
                                <option disabled="disabled" selected value="">Select</option>
                                <option value="$0 - $499">$0 - $499</option>
                                <option value="$500 - $999">$500 - $999</option>
                                <option value="$1,000 - $1,499">$1,000 - $1,499</option>
                                <option value="$1,500 - $1,999">$1,500 - $1,999</option>
                                <option value="$2,000+">$2,000+</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label>Other Monthly Payments <small>(Optional)</small></label>
                            <div class="helper">E.g. loans, credit cards, etc.</div>
                            <select id="other_monthly_payments" class="form-select" name="other_monthly_payments" placeholder="Select">
                                <option disabled="disabled" selected value="">Select</option>
                                <option value="$0">$0</option>
                                <option value="$1 - $299">$1 - $299</option>
                                <option value="$300 - $499">$300 - $499</option>
                                <option value="$500 - $999">$500 - $999</option>
                                <option value="$1,000+">$1,000+</option>
                            </select>
                        </div>
                    </form>
                    <div class="action-row step-navigation">
                        <button type="button" class="btn-back back-btn" data-back="1"><i class="fa-solid fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn-primary-custom next-btn" data-next="3">Continue to Vehicle Preferences <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="form-step" id="step-3">
                    <div class="step-label">Step 3 of 3</div>
                    <h2>Your Vehicle Preferences</h2>
                    <p class="subtitle">Let us know what you're looking for so we can match<br class="desktop-only"> you with the right options.</p>
                    <form id="form3" novalidate>
                        <div class="mb-4">
                            <label>What type of vehicle are you interested in? <em>*</em></label>
                            <div class="choice-grid four">
                                <label class="choice-card compact"><input type="radio" name="vehicle_type" id="vehicleType-car" value="Car" required><span><i class="fa-solid fa-car-rear"></i></i><b>Car</b></span></label>
                                <label class="choice-card compact"><input type="radio" name="vehicle_type" id="vehicleType-suv" value="SUV"><span><i class="fa-solid fa-car-side"></i><b>SUV</b></span></label>
                                <label class="choice-card compact"><input type="radio" name="vehicle_type" id="vehicleType-truck" value="Truck"><span><i class="fa-solid fa-truck"></i><b>Truck</b></span></label>
                                <label class="choice-card compact"><input type="radio" name="vehicle_type" id="vehicleType-van" value="Van/Minivan"><span><i class="fa-solid fa-truck-front"></i><b>Van/Minivan</b></span></label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label>New or Used? <em>*</em></label>
                            <div class="choice-grid two">
                                <label class="choice-card wide"><input type="radio" name="condition" id="condition-new" value="New" required><span><i class="fa-solid fa-car"></i><b>New</b></span></label>
                                <label class="choice-card wide"><input type="radio" name="condition" id="condition-used" value="Used"><span><i class="fa-solid fa-car-on"></i><b>Used</b></span></label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label>Price Range <em>*</em></label>
                            <select id="price_range" class="form-select" name="price_range" placeholder="Select">
                                <option disabled="disabled" selected value="">Select</option>
                                <option value="$10,000 - $20,000">$10,000 - $20,000</option>
                                <option value="$20,000 - $30,000">$20,000 - $30,000</option>
                                <option value="$30,000 - $40,000">$30,000 - $40,000</option>
                                <option value="$40,000 - $50,000">$40,000 - $50,000</option>
                                <option value="$50,000+">$50,000+</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label>When are you looking to get a vehicle? <em>*</em></label>
                            <div class="choice-grid three">
                                <label class="choice-card timeline"><input type="radio" name="timeline" id="timeline-immediately" value="Immediately" required><span><i class="fa-regular fa-calendar-days"></i><b>Immediately</b></span></label>
                                <label class="choice-card timeline"><input type="radio" name="timeline" id="timeline-1-3-months" value="In 1-3 months"><span><i class="fa-regular fa-calendar-days"></i><b>In 1-3 months</b></span></label>
                                <label class="choice-card timeline"><input type="radio" name="timeline" id="timeline-just-exploring" value="Just exploring"><span><i class="fa-regular fa-calendar-days"></i><b>Just exploring</b></span></label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label>Do you have a trade-in? <em>*</em></label>
                            <div class="choice-grid two">
                                <label class="choice-card wide"><input type="radio" name="trade_in" id="trade_in-yes" value="Yes" required><span><i class="fa-solid fa-circle-check"></i><b>Yes</b></span></label>
                                <label class="choice-card wide"><input type="radio" name="trade_in" id="trade_in-no" value="No"><span><i class="fa-regular fa-circle-xmark"></i><b>No</b></span></label>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12">
                            <input type="hidden" name="source" value="<?php echo $source; ?>">
                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

                            <input type="hidden" name="source_url" value="">

                            <div class="consent">
                                <label class="check-wrap"><input type="checkbox" id="consent"><span class="fake-check"><i class="fa-solid fa-check"></i></span></label>
                                <p>I agree to be contacted by Carma Credit and its lending partners via phone, email and SMS regarding my auto financing options. I understand this is not a credit application and there is no impact to my credit score.</p>
                            </div>
                        </div>
                    </form>
                    <div class="mb-4">
                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                        <div id="recaptcha-error" class="invalid-feedback d-block"></div>
                    </div>
                    <div class="action-row step-navigation">
                        <button type="button" class="btn-back back-btn" data-back="2"><i class="fa-solid fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn-primary-custom final-submit-btn">
                            Submit My Information <i class="fa-solid fa-check"></i>
                        </button>
                    </div>
                </div>


                <!-- STEP 4 -->
                <!-- <div class="form-step" id="step-4">
                    <div class="step-label">Step 4 of 4</div>
                    <h2>Review &amp; Submit</h2>
                    <p class="subtitle">Please review your information and complete the verification before submitting.</p>

                    <div id="review-content" class="mb-4"></div> -->

                <!-- reCAPTCHA on Step 4 -->
                <!-- <div class="mb-4">
                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                        <div id="recaptcha-error" class="invalid-feedback d-block"></div>
                    </div>

                    <div class="action-row step-navigation">
                        <button type="button" class="btn-back back-btn" data-back="3">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn-primary-custom final-submit-btn">
                            Submit My Information <i class="fa-solid fa-check"></i>
                        </button>
                    </div>
                </div> -->

            </div>

            <div class="safe-and-secure text-center mt-3">
                <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/icon-bb.png" style="width:25px;padding-right:5px; padding-top: 0px;" />Confidential, Safe and Secure</p>
                <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/trust-badges-small.webp" class="bottom-img" /> </p>
            </div>
        </section>
    </main>

    <!---- JAVA SCRIPT FILE ----->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            var current = 1;
            var totalSteps = 3;
            var $steps = $(".form-step");

            // Source URL required by the existing PHP/software.
            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            $("input[name='source_url']").val(source_url);

            // DOB dropdowns: exact carma_finance names and IDs.
            function initDob() {
                var $day = $("#dobday");
                var $month = $("#dobmonth");
                var $year = $("#dobyear");

                if (!$day.length || !$month.length || !$year.length) return;

                if ($day.children().length === 0) {
                    $day.append('<option value="">Day</option>');
                    for (var d = 1; d <= 31; d++) {
                        var day = String(d).padStart(2, '0');
                        $day.append('<option value="' + day + '">' + day + '</option>');
                    }
                }

                if ($month.children().length === 0) {
                    $month.append('<option value="">Month</option>');
                    var months = [
                        "January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];

                    $.each(months, function(i, month) {
                        var monthNumber = String(i + 1).padStart(2, '0');
                        $month.append('<option value="' + monthNumber + '">' + month + '</option>');
                    });
                }

                if ($year.children().length === 0) {
                    $year.append('<option value="">Year</option>');
                    var currentYear = new Date().getFullYear();
                    for (var y = 2008; y >= 1940; y--) {
                        $year.append('<option value="' + y + '">' + y + '</option>');
                    }
                }

                function updateDays() {
                    var month = parseInt($month.val(), 10);
                    var year = parseInt($year.val(), 10) || 2000;
                    var max = month ? new Date(year, month, 0).getDate() : 31;
                    var selected = $day.val();

                    $day.find("option").each(function() {
                        var v = parseInt($(this).val(), 10);
                        if (v) $(this).prop("disabled", v > max).toggle(v <= max);
                    });

                    if (selected && parseInt(selected, 10) > max) {
                        $day.val("");
                    }
                }

                $month.off("change.carmaDob").on("change.carmaDob", updateDays);
                $year.off("change.carmaDob").on("change.carmaDob", updateDays);
                updateDays();
            }

            initDob();

            // Phone mask.
            if ($.fn.inputmask) {
                $('input[type="tel"]').inputmask({
                    mask: "(999) 999-9999",
                    autoUnmask: true
                });
            }

            var rules = {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
                },
                birth_date: {
                    required: true,
                    digits: true
                },
                birth_month: {
                    required: true,
                    digits: true
                },
                birth_year: {
                    required: true,
                    digits: true
                },
                postal_code: {
                    required: true
                },

                employment_status: {
                    required: true
                },
                monthly_income: {
                    required: true
                },
                time_at_current_job: {
                    required: true
                },
                housing: {
                    required: true
                },
                monthly_housing_payment: {
                    required: true
                },

                vehicle_type: {
                    required: true
                },
                condition: {
                    required: true
                },
                price_range: {
                    required: true
                },
                timeline: {
                    required: true
                },
                trade_in: {
                    required: true
                }
            };

            var messages = {
                first_name: "Please Enter Your First Name",
                last_name: "Please Enter Your Last Name",
                email: {
                    required: "Please Enter Your Email",
                    email: "Please Enter a valid Email"
                },
                phone: {
                    required: "Please Enter Your Phone Number",
                    minlength: "Your Phone Number must be 10 digits long",
                    maxlength: "Your Phone Number must be 10 digits long",
                    digits: "Phone Number should be a Number"
                },
                birth_date: {
                    required: "Please Enter Day of Birth",
                    digits: "Day of Birth should be a number"
                },
                birth_month: {
                    required: "Please Enter Month of Birth",
                    digits: "Month of Birth should be a number"
                },
                birth_year: {
                    required: "Please Enter Year of Birth",
                    digits: "Year of Birth should be a number"
                },
                postal_code: "Please Enter Postal Code",

                employment_status: "Employment Status is required",
                monthly_income: "Monthly Income is required",
                time_at_current_job: "Employment Period is required",
                housing: "Housing Status is required",
                monthly_housing_payment: "Monthly Housing Payment is required",

                vehicle_type: "Vehicle Type is required",
                condition: "Please Select New or Used",
                price_range: "Price Range is required",
                timeline: "Please Select When You Need a Vehicle",
                trade_in: "Trade In is required"
            };

            // Ensure every error is rendered BELOW the corresponding field.
            function errorBox(name) {
                return $("#" + name + "_error");
            }

            var $forms = $("form");

            $forms.each(function() {
                $(this).validate({
                    rules: rules,
                    messages: messages,
                    errorElement: "div",
                    errorClass: "field-error-bottom",
                    ignore: [],

                    errorPlacement: function(error, element) {
                        var name = element.attr("name");
                        var $box = errorBox(name);

                        if (element.is(":radio")) {
                            element.closest(".choice-grid").addClass("validation-invalid");

                            if ($box.length) {
                                $box.text(error.text()).show();
                            } else {
                                error.insertAfter(element.closest(".choice-grid"));
                            }
                            return;
                        }

                        if ($box.length) {
                            $box.text(error.text()).show();
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    highlight: function(element) {
                        $(element).addClass("is-invalid");

                        if ($(element).is(":radio")) {
                            $(element).closest(".choice-grid").addClass("validation-invalid");
                        }
                    },

                    unhighlight: function(element) {
                        $(element).removeClass("is-invalid");

                        if ($(element).is(":radio")) {
                            $(element).closest(".choice-grid").removeClass("validation-invalid");
                        }
                    },

                    success: function(label, element) {
                        var name = $(element).attr("name");
                        errorBox(name).empty().hide();
                        $(element).removeClass("is-invalid");

                        if ($(element).is(":radio")) {
                            $(element).closest(".choice-grid").removeClass("validation-invalid");
                        }
                    },

                    submitHandler: function() {
                        return false;
                    }
                });
            });

            // Ask the parent page to scroll to a field instead of scrolling this
            // document. iOS Safari keeps a stuck offset when an embedded
            // scrolling="no" iframe scrolls itself.
            function scrollParentTo($el) {
                if (!$el || !$el.length) return;
                try {
                    parent.postMessage({
                        type: "scrollToField",
                        value: Math.round($el.offset().top),
                        element_id: "finance_form"
                    }, "*");

                    parent.postMessage({
                        type: "css",
                        value: $("body").outerHeight(true),
                        element_id: "finance_form"
                    }, "*");
                } catch (e) {}
            }

            function scrollToStepTop(step) {
                var $target = $("#step-" + step);
                if (!$target.length) return;

                setTimeout(function() {
                    scrollParentTo($target);
                }, 80);
            }

            function validateStep(step) {
                var $form = $("#form" + step);

                if (!$form.length) return true;

                if (!$form.valid()) {
                    var $first = $form.find(".is-invalid").first();

                    if (!$first.length) {
                        $first = $form.find(".validation-invalid").first();
                    }

                    if ($first.length) {
                        // Keep the user on the current step and reveal the first error.
                        setTimeout(function() {
                            scrollParentTo($first);
                            if ($first.is("input, select, textarea")) {
                                try {
                                    $first[0].focus({
                                        preventScroll: true
                                    });
                                } catch (e) {}
                            }
                        }, 50);
                    }

                    return false;
                }

                return true;
            }

            function updateStepper(step) {
                $(".step-item").each(function() {
                    var n = parseInt($(this).attr("data-step"), 10);
                    $(this).removeClass("active completed");

                    if (n === step) $(this).addClass("active");
                    if (n < step) $(this).addClass("completed");
                });
            }

            function showStep(step) {
                if (step < 1 || step > totalSteps) return;

                current = step;

                $steps.hide().removeClass("active");
                $("#step-" + step).show().addClass("active");

                updateStepper(step);

                if (step === 3) {
                    buildReview();
                }

                try {
                    parent.postMessage({
                        type: "css",
                        value: $("body").outerHeight(true),
                        element_id: "finance_form"
                    }, "*");
                } catch (e) {}

                // Scroll to the top of the form whenever the step changes
                // setTimeout(function() {
                //     var $formShell = $(".form-shell");

                //     if ($formShell.length) {
                //         $("html, body").stop(true, true).animate({
                //             scrollTop: 0
                //         }, 400);
                //     }
                // }, 100);
            }

            // Multi-step NEXT.
            $(".next-btn").off("click").on("click", function(e) {
                e.preventDefault();

                if (!validateStep(current)) {
                    return false;
                }

                var nextStep = parseInt($(this).data("next"), 10);
                showStep(nextStep);
                scrollToStepTop(nextStep);
                return false;
            });

            // Multi-step BACK.
            $(".back-btn").off("click").on("click", function(e) {
                e.preventDefault();
                var backStep = parseInt($(this).data("back"), 10);
                showStep(backStep);
                scrollToStepTop(backStep);
                return false;
            });

            // Remove radio error as soon as the user chooses an option.
            $(document).on("change", 'input[type="radio"]', function() {
                var name = $(this).attr("name");
                errorBox(name).empty().hide();
                $(this).closest(".choice-grid").removeClass("validation-invalid");
                $(this).closest("form").validate().element(this);
            });

            function collectFormData() {
                var data = {};

                $("form").each(function() {
                    $(this).serializeArray().forEach(function(item) {
                        data[item.name] = item.value;
                    });
                });

                data.consent = $("#consent").is(":checked") ? "1" : "0";

                // reCAPTCHA token is included on Step 4.
                if (typeof grecaptcha !== "undefined") {
                    data["g-recaptcha-response"] = grecaptcha.getResponse();
                }

                return data;
            }

            function buildReview() {
                var data = collectFormData();

                var labels = {
                    first_name: "First Name",
                    last_name: "Last Name",
                    email: "Email Address",
                    phone: "Phone Number",
                    birth_date: "Birth Day",
                    birth_month: "Birth Month",
                    birth_year: "Birth Year",
                    postal_code: "Postal Code",
                    employment_status: "Employment Status",
                    monthly_income: "Monthly Income",
                    time_at_current_job: "Time at Current Job",
                    housing: "Housing Status",
                    monthly_housing_payment: "Monthly Housing Payment",
                    other_monthly_payments: "Other Monthly Payments",
                    vehicle_type: "Vehicle Type",
                    condition: "New or Used",
                    price_range: "Price Range",
                    timeline: "Vehicle Timeline",
                    trade_in: "Trade-In"
                };

                var output = '<div class="review-grid">';

                $.each(labels, function(key, label) {
                    if (data[key] !== undefined && data[key] !== "") {
                        output +=
                            '<div class="review-item">' +
                            '<span>' + label + '</span>' +
                            '<strong>' + $("<div>").text(data[key]).html() + '</strong>' +
                            '</div>';
                    }
                });

                output += "</div>";
                $("#review-content").html(output);
            }

            function submitApplication() {
                // Validate all three input steps.
                for (var step = 1; step <= 3; step++) {
                    if (!validateStep(step)) {
                        showStep(step);
                        return false;
                    }
                }

                // Step 4 reCAPTCHA validation.
                if (typeof grecaptcha === "undefined" || !grecaptcha.getResponse()) {
                    $("#recaptcha-error")
                        .text("Please verify that you are not a robot.")
                        .show();

                    $(".g-recaptcha").addClass("validation-invalid");

                    scrollParentTo($(".g-recaptcha"));

                    return false;
                }

                $("#recaptcha-error").empty().hide();
                $(".g-recaptcha").removeClass("validation-invalid");

                try {
                    parent.postMessage("alertFire", "*");
                } catch (e) {}

                $.ajax({
                    url: window.location.origin + "/api/leads/responses/create",
                    type: "POST",
                    data: collectFormData(),

                    success: function() {
                        try {
                            parent.postMessage("alertSuccessUpdate", "*");
                        } catch (e) {}

                        setTimeout(function() {
                            try {
                                parent.postMessage("alertClose", "*");
                                parent.postMessage("redirectToThankYouPage", "*");
                            } catch (e) {}

                            window.location.href =
                                window.location.origin + "/thankyou.html";
                        }, 1000);
                    },

                    error: function(error) {
                        var errors = error.responseJSON.errors;
                        var firstError = true;

                        if (typeof errors === "object") {

                            $.each(errors, function(key, message) {

                                var $field = $('[name="' + key + '"]');
                                var $box = errorBox(key);

                                // Add invalid class
                                $field.addClass("is-invalid");

                                // Handle radio buttons
                                if ($field.is(":radio")) {
                                    $field.closest(".choice-grid")
                                        .addClass("validation-invalid");
                                }

                                // Show error message
                                if ($box.length) {
                                    $box.text(message).show();
                                } else {
                                    $field.next().text(message);
                                }

                                // Scroll to first error and move to its step
                                if (firstError && $field.length) {
                                    firstError = false;

                                    var stepId = $field.closest(".form-step").attr("id");

                                    if (stepId) {
                                        var errorStep = parseInt(
                                            stepId.replace("step-", ""),
                                            10
                                        );

                                        // Show the step containing the error
                                        showStep(errorStep);

                                        // Scroll to the first invalid field after the step is visible.
                                        setTimeout(function() {
                                            scrollParentTo($field);
                                        }, 150);
                                    } else {
                                        // Scroll the parent page to the invalid field.
                                        scrollParentTo($field);
                                    }
                                }
                            });

                            // Fire Error Update Alert
                            try {
                                parent.postMessage("alertErrorUpdate", "*");
                            } catch (e) {}

                            // Close Error Alert
                            setTimeout(function() {
                                try {
                                    parent.postMessage("alertClose", "*");
                                } catch (e) {}
                            }, 1000);

                        } else {

                            // Fire Error Update Alert
                            try {
                                parent.postMessage("alertErrorUpdate", "*");
                            } catch (e) {}

                            $("#recaptcha-error")
                                .text(errors)
                                .show();

                            var recaptchaError = document.getElementById("recaptcha-error");

                            if (recaptchaError) {
                                scrollParentTo($(recaptchaError));
                            }

                            $(".g-recaptcha").addClass("is-invalid");

                            // Close Error Alert
                            setTimeout(function() {
                                try {
                                    parent.postMessage("alertClose", "*");
                                } catch (e) {}
                            }, 1000);
                        }
                    },
                    complete: function() {
                        if ($.fn.inputmask) {
                            $('input[type="tel"]').inputmask({
                                mask: "(999) 999-9999",
                                autoUnmask: true
                            });
                        }
                    }
                });

                return false;
            }

            $(".final-submit-btn").off("click").on("click", function(e) {
                e.preventDefault();
                submitApplication();
                return false;
            });

            // Never allow an Enter key to submit the wrong step.
            $forms.on("submit", function(e) {
                e.preventDefault();
                return false;
            });

            showStep(1);
        });
    </script>


</body>