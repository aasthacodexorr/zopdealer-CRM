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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-…(check actual hash on cdnjs)…"
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        ul#credit-score-selection li,
        ul#trade-in-selection li,
        ul#date-of-birth-selection-options li {
            display: block;
        }

        ul#credit-score-selection li label,
        ul#trade-in-selection li label,
        ul#date-of-birth-selection-options li label {
            width: 100%;
        }

        ul.form-list-desgine li {
            vertical-align: top;
        }

        .fa,
        .fas {
            font-family: "Font Awesome 5 Free" !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>
    <section class="details-main get-sp-form">
        <div class="container">
            <div class="row multistep-finance">
                <div class="col-md-10 order-xs-1">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <form class="needs-validation" id="lead_form" novalidate="novalidate">
                        <!-- STEP 1 -->
                        <div class="step well">
                            <div class="radio-group">
                                <h4 class="mb-2 mt-4">What type of loan do you want?</h4>
                                <p>Choose the loan purpose to begin — this won't affect your credit score.</p>
                                <div class="option-box-same-l">
                                    <div class="option-box" id="finance-in-selection">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input seclect-ratio-btn" id="loan_type_personal" name="finance_or_cash" value="Personal use">
                                            <label class="form-check-label fw-bold" for="loan_type_personal">Personal use<div class="muted-small">You're buying for yourself</div></label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input seclect-ratio-btn" id="loan_type_business" name="finance_or_cash" value="Business Use">
                                            <label class="form-check-label fw-bold" for="loan_type_business">Business use <div class="muted-small">You're buying on behalf of a business</div></label>
                                        </div>
                                    </div>
                                    <input type="radio" class="form-check-input seclect-ratio-btn" style="visibility: hidden; width:0px; position:absolute" name="finance_or_cash">
                                    <div id="finance_or_cash_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="vehile-delivery about-car-content financical-d-content">
                                <div class="row g-3 mt-2 mb-4">

                                    <div class="col-md-12">
                                        <label class="form-label"><strong>What is your Budget?</strong></label>
                                        <select id="budget" name="budget" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="Under $400 per Month">Under $400 /Month</option>
                                            <option value="$400 - $599 per Month">$400 - $599 / Month</option>
                                            <option value="$600 - $800 per Month">$600 - $800 / Month</option>
                                            <option value="Over $800 per Month">Over $800 / Month</option>
                                        </select>
                                        <div id="budget_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Do you have a Trade-in?</strong></label>
                                        <select id="trade_in" name="trade_in" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="Unsure">Unsure</option>
                                        </select>
                                        <div id="trade_in_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>What is your estimated credit rating?</strong></label>
                                        <div class="d-flex flex-wrap radio-group mb-3">
                                            <input type="radio" class="seclect-ratio-btn" id="bad" name="credit_rating" value="Poor (300-599)">
                                            <label for="bad">Poor <small>(300-599)</small></label>

                                            <input type="radio" class="seclect-ratio-btn" id="fair" name="credit_rating" value="Fair (600-659)">
                                            <label for="fair">Fair <small>(600-659)</small></label>

                                            <input type="radio" class="seclect-ratio-btn" id="good" name="credit_rating" value="Good (660-724)">
                                            <label for="good">Good <small>(660-724)</small></label>

                                            <input type="radio" class="seclect-ratio-btn" id="great" name="credit_rating" value="Very Good (725-759)">
                                            <label for="great">Very Good <small>(725-759)</small></label>

                                            <input type="radio" class="seclect-ratio-btn" id="excellent" name="credit_rating" value="Excellent (760-900)">
                                            <label for="excellent">Excellent <small>(760-900)</small></label>

                                            <input type="radio" class="seclect-ratio-btn" id="notsure" name="credit_rating" value="No Credit (Unsure)">
                                            <label for="notsure">No Credit <small>Unsure</small> </label>
                                        </div>
                                        <input type="radio" style="visibility: hidden; width:0px; position:absolute" name="credit_rating">
                                        <div id="credit_rating_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="vehile-delivery">
                                <h4 class="b-sp-0">Employment & Citizenship</h4>
                                <div class="row g-3 mt-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Select Employment Status</strong></label>
                                        <select id="employment-status" name="employment_status" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="Full-time">Full-time</option>
                                            <option value="Part-time">Part-time</option>
                                            <option value="Casual">Casual</option>
                                            <option value="Self-employed">Self-employed</option>
                                            <option value="Unemployed">Unemployed</option>
                                        </select>
                                        <div id="employment_status_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Canada Residency Status</strong></label>
                                        <select id="canada-residency-status" name="canada_residency_status" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="Canada Citizen">Canada Citizen</option>
                                            <option value="Permanent Resident">Permanent Resident</option>
                                            <option value="Work Visa">Work Visa</option>
                                            <option value="Student Visa">Student Visa</option>
                                        </select>
                                        <div id="canada_residency_status_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company Name">
                                        <div id="company_name_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Title">
                                        <div id="job_title_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>What is your monthly income before taxes & deductions?</strong></label>
                                    </div>
                                    <div class="col-md-6 dis-top-m">
                                        <div class="dis-top-mobile">
                                            <input type="number" class="form-control" name="income_before_tax" id="income_before_tax" placeholder="$ Monthly Income">
                                            <span class="residence-year">$</span>
                                        </div>
                                        <div id="income_before_tax_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>How long have you been receiving this income?</strong></label>
                                    </div>
                                    <div class="col-md-6 dis-top-m">
                                        <div class="dis-top-mobile">
                                            <input type="number" min="0" name="employment_period_years" class="form-control" placeholder="" id="employment_period_years" />
                                            <span class="residence-year">Years</span>
                                        </div>
                                        <div id="employment_period_years_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 dis-top-m sec">
                                        <div class="dis-top-mobile">
                                            <input type="number" min="0" max="11" name="employment_period_months" class="form-control" placeholder="" id="employment_period_months" />
                                            <span class="residence-year">Months</span>
                                        </div>
                                        <div id="employment_period_months_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="about-car-content financical-d-content">
                                <h4>Home & Family</h4>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Marital Status</strong></label>
                                        <select id="family-status" name="marital_status" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Common Law">Common Law</option>
                                            <option value="Divorced">Divorced</option>
                                        </select>
                                        <div id="marital_status_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Select Residential Status</strong></label>
                                        <select id="residential-status" name="residence_status" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="I am renting">I'm renting</option>
                                            <option value="Own home">Own home</option>
                                            <option value="Living with family">Living with family</option>
                                        </select>
                                        <div id="residence_status_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label"><strong>Home Address</strong></label>
                                    <div class="row g-3">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="input-group home-address">
                                                <span class="input-group-prepend">
                                                    <div class="input-group-text bg-white border-right-0 form-control border-end-0 rounded-0">
                                                        <i class="fa fa-search"></i>
                                                    </div>
                                                </span>
                                                <input class="input-group-text bg-white border-right-0 form-control border-start-0 rounded-end-0 w-auto text-start" type="search" list="locations_list" id="search_location" placeholder="Search for a location" />
                                                <datalist id="locations_list">
                                                </datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                            <div id="street_address_error" class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                            <div id="city_error" class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                            <div id="province_error" class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                            <div id="postal_code_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 dis-top-m">
                                        <div class="dis-top-mobile">
                                            <label class="form-label"><strong>Monthly Payment</strong></label>
                                            <input type="text" class="form-control" name="mortgage_rent_amount_monthly" id="mortgage_rent_amount_monthly" placeholder="$ Monthly Payment">
                                            <span class="residence-year">$</span>
                                        </div>
                                        <div id="mortgage_rent_amount_monthly_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>How long have you lived here</strong></label>
                                    </div>
                                    <div class="col-md-6 dis-top-m">
                                        <div class="dis-top-mobile">
                                            <input type="text" min="0" max="100" class="form-control" id="residence_period_years" name="residence_period_years" placeholder="Years" />
                                            <span class="residence-year">Years</span>
                                        </div>
                                        <div id="residence_period_years_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 dis-top-m sec">
                                        <div class="dis-top-mobile">
                                            <input type="text" min="0" max="100" class="form-control" name="residence_period_months" placeholder="Months" />
                                            <span class="residence-year">Months</span>
                                        </div>
                                        <div id="residence_period_months_error" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="about-car-content financical-d-content">
                                <h4 class="mb-2 mt-4">Tell us about yourself</h4>
                                <p>We'll use these details to provide your personalised rates through a soft credit check. This won't affect your credit score.</p>
                                <div class="mt-4">
                                    <div class="form-t-block">
                                        <div class="head-felid-s">
                                            <input type="text" class="form-control mb-3" placeholder="First Name" name="first_name" id="first_name" value="" required>
                                            <div id="first_name_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                        <div class="head-felid-s">
                                            <input type="text" class="form-control mb-3" name="last_name" id="last_name" placeholder="Last Name" value="" required>
                                            <div id="last_name_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="form-t-block">
                                        <div class="head-felid-s">
                                            <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="" required>
                                            <div id="email_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                        <div class="head-felid-s">
                                            <input type="tel" class="form-control" placeholder="Phone Number" name="phone" id="tel" value="" required>
                                            <div id="phone_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="vehile-driver mt-3">
                                        <h4 class="b-sp-0">Terms & Conditions </h4>
                                        <p class="b-sp-10">Before we provide your personalised quote please read the below carefully.</p>
                                        <div class="border rounded-3 p-4 mb-4 border">
                                            <h5 class="fw-semibold mb-4">I have read and accept the Terms of Use. I also acknowledge having read the Privacy Policy and agree to the terms herein.</h5>

                                            <div class="row g-4">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="checkbox-block">
                                                        <input type="checkbox" name="sms_consent" checked>
                                                        By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by texting STOP. Messaging frequency may vary. Message and data rates may apply. For assistance, text HELP or visit our website at <a href="https://www.cardora.ca/">Cardora</a>. Visit <a href="https://www.cardora.ca/privacy-policy/">Privacy Policy</a> for privacy policy and <a href="https://www.cardora.ca/terms-conditions/">Terms and Conditions</a> for Terms of Service.
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xs-12 p-top-20 text-center">
                                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                                    <div class="invalid-feedback" id="recaptcha-error"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="trade-btn-list">
                            <a type="submit" class="action back btn btn-info" style="display:block;">Previous</a>
                            <a class="action next btn btn-info" style="display:block;">Save & Continue</a>
                            <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        </div>
                        <input type="hidden" id="single_stock_no_input" name="stock_no" value="">
                        <input type="hidden" id="single_stock_zm_year" name="zm_year" value="">
                        <input type="hidden" id="single_stock_zm_make" name="zm_make" value="">
                        <input type="hidden" id="single_stock_zm_model" name="zm_model" value="">
                        <input type="hidden" id="single_stock_zm_trim" name="zm_trim" value="">
                        <input type="hidden" id="single_stock_image" name="zm_image" value="">
                        <input type="hidden" id="single_stock_zm_vin" name="zm_vin" value="">
                        <input type="hidden" id="inventory_id" name="inventory_id">
                        <input type="hidden" name="source" value="<?php echo $source; ?>">
                        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                        <input type="hidden" name="source_url" value="">
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="/dist/assets/js/suggestLocation.js"></script>
    <script src="/dist/assets/js/browserStorage.js"></script>

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

            function resetInventoryData() {
                $('#single_stock_no_input').val('');
                $('#single_stock_zm_year').val('');
                $('#single_stock_zm_make').val('');
                $('#single_stock_zm_model').val('');
                $('#single_stock_zm_trim').val('');
                $('#single_stock_zm_vin').val('');
                $('#single_stock_image').val('');
                $('#zd_inventory_title').text('NO VEHICLE SELECTED');
                $("#zm_image").attr('src', window.placeholder);
            }

            function single_inventory(id) {
                $.get(window.location.origin + '/api/v2/website/inventory/show/' + id, function(data) {

                    $('#single_stock_no_input').val(data.stock_no);
                    $('#single_stock_zm_year').val(data.year);
                    $('#single_stock_zm_make').val(data.make);
                    $('#single_stock_zm_model').val(data.model);
                    $('#single_stock_zm_trim').val(data.trim);
                    $('#single_stock_zm_vin').val(data.vin);
                    $('#zd_inventory_title').html(`<span>${data.year}</span> ${data.make} ${data.model} <span>${data.trim}</span> - <span><b>#${data.stock_no}</b></span>`);


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

                });

            }

            if (window.params.hasOwnProperty('inventory_id')) {
                $('#inventory_id').val(window.params.inventory_id);
                single_inventory(window.params.inventory_id);
            }
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


            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                vehicle_type: "required",
                finance_or_cash: "required",
                budget: "required",
                credit_rating: "required",
                trade_in: "required",
                marital_status: "required",
                employment_status: {
                    required: true,
                },
                gross_income: "required",
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
                employment_period_with_this_salary: "required",
                company_name: "required",
                canada_residency_status: "required",
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
                postal_code: "required",
                residence_period: "required",
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
                income_before_tax: {
                    required: true,
                    number: true
                },
            };
            var messages = {
                finance_or_cash: "Type of loan is required",
                vehicle_type: "Vehicle type is required",
                budget: "Budget is required",
                credit_rating: "Credit rating is required",
                trade_in: "Trade-In is required",
                marital_status: "Marital status is required",
                canada_residency_status: "Canada residency status is required",
                employment_status: "Employment status is required",
                residence_period_years: {
                    required: "Please enter the residence period years",

                },
                residence_period_months: {
                    required: "Please enter residence period months",

                },
                employment_period_years: {
                    required: "Please enter employment period years",

                },
                employment_period_months: {
                    required: "Please enter employment period months",

                },
                gross_income: "Gross income is required",
                employment_period_with_this_salary: "Employment period is required",
                company_name: "Please enter company name",
                job_title: "Please enter job title",
                residence_status: "Residence status is required",
                mortgage_rent_amount_monthly: {
                    required: "Please enter rent or mortgage amount",
                    number: "Rent or mortgage amount should be numeric"
                },
                street_address: "Please enter street address",
                city: "Please enter city",
                province: "Please enter province",
                postal_code: "Please enter postal code",
                residence_period: "Please enter residence period",
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email"
                },
                phone: {
                    required: "Please enter your phone number",
                    number: "Phone number should be a number",
                    minlength: "Your phone number should be 10 digit",
                    maxlength: "Your phone number should be 10 digit"
                },

            };

            partial_form.validate({
                debug: false,
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
                        value: $("body").outerHeight(true),
                        element_id: "financing_form"
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
                        data: new FormData(document.getElementById('lead_form')), // get all form variables
                        contentType: false,
                        processData: false,
                        success: function(result) {

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");

                            setTimeout(() => {

                                // Fire A Close Alert and Than Navigation To ThankYou Page.
                                parent.postMessage("alertClose", "*");
                                parent.postMessage("redirectToThankYouPageForFinance", "*");

                            }, 1000);
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
                    value: $("body").outerHeight(true),
                    element_id: "financing_form"
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
                    value: $("body").outerHeight(true),
                    element_id: "financing_form"
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

        $("#changeVehicleBtn").on('click', function() {
            parent.postMessage("redirectToInventoryListing", "*");
        });
    </script>
    <script>
        const searchLocationInput = document.getElementById("search_location");

        searchLocationInput.addEventListener("keyup", searchLocation);
        searchLocationInput.addEventListener("change", isPlaceSelected.bind(this,
            'street_address',
            'city',
            'province',
            'postal_code'));
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "financing_form"
            }, "*");

        });
    </script>

</body>