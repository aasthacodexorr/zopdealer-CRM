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
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!--  HEAD END  -->

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!-- <style>
        .g-recaptcha>div {
            margin: auto;
        }
    </style> -->

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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>
    <section class="details-main get-sp-form">
        <div class="container">
            <div class="row finance-step-form">
                <div class="col-md-4 order-xs-2">
                    <div class="car-card">
                        <div class="car-image">
                            <div class="car-main-image"><img class="img-responsive" width="100%" id="zm_image" src="" /></div>
                            <div class="car-main-title">
                                <h2 class="finance-car-title" id="zd_inventory_title"></h2>
                                <p div class="odometer" id="zm_odometer"></p>
                            </div>
                        </div>

                        <div class="car-info">

                            <hr>
                            <ul class="features">
                                <li>
                                    <i class="fa-solid fa-car-on icon"></i>
                                    <span>6-month warranty</span>
                                    <span class="status">Included</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-shield-halved icon"></i>
                                    <span>150-point inspection</span>
                                    <span class="status">Included</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-triangle-exclamation icon"></i>
                                    <span>Roadside assistance</span>
                                    <span class="status">Included</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-medal icon"></i>
                                    <span>10-day exchange</span>
                                    <span class="status">Included</span>
                                </li>
                            </ul>

                            <hr>

                            <div class="total">
                                <h3>Total</h3>
                                <div class="price" id="zm_selling_price_numeric"></div>
                            </div>
                            <a href="#" class="gov-link">Excluding HST & Licensing.</a>

                            <div class="tooltip-box">
                                <div class="terms"> <span>View terms</span><button class="tooltip-btn"><i class="fa-solid fa-circle-exclamation"></i></button></div>
                                <div class="tooltip-content"><strong>Buy with confidence.</strong><br> Every Cardora vehicle includes a 6-month powertrain warranty,<br> 10-day exchange policy, 150-point inspection,<br> complimentary CARFAX® vehicle history report, <br>inspection report, and roadside assistance. Certain conditions, limitations, <br>and exclusions may apply. Please visit the dealership for complete details.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 order-xs-1">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <form class="needs-validation" id="lead_form" novalidate="novalidate">
                        <!-- STEP 1 -->
                        <div class="step well">
                            <div class="radio-group">
                                <h5 class="mb-2 mt-4">Are you looking to Finance or by Cash?</h5>
                                <!-- <p>Get instant quotes from our lending partners or provide your financer details and we'll take it from there.</p> -->
                                <div class="option-box-same-l">
                                    <div class="option-box" id="trade-in-selection">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input seclect-ratio-btn" id="Trade-In-finance" name="finance_or_cash" value="Finance">
                                            <label class="form-check-label fw-bold" for="Trade-In-finance">Finance</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input seclect-ratio-btn" id="Trade-In-cash" name="finance_or_cash" value="Cash">
                                            <label class="form-check-label fw-bold" for="Trade-In-cash">Cash</label>
                                        </div>
                                    </div>
                                    <input type="radio" class="form-check-input seclect-ratio-btn" style="visibility: hidden; width:0px; position:absolute" name="finance_or_cash">
                                    <div id="finance_or_cash_error" class="invalid-feedback text-danger"></div>
                                </div>
                            </div>

                            <div class="radio-group">
                                <h5 class="mb-2 mt-4">Do you have a vehicle to trade in?</h5>
                                <div class="option-box-same-l">
                                    <div class="option-box" id="trade-in-selection">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="Yes" name="trade_in" id="Trade-In-Yes">
                                            <label class="form-check-label fw-bold" for="Trade-In-Yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="No" name="trade_in" id="Trade-In-No">
                                            <label class="form-check-label fw-bold" for="Trade-In-No">No</label>
                                        </div>
                                    </div>
                                    <input type="radio" class="form-check-input seclect-ratio-btn" style="visibility: hidden; width:0px; position:absolute" name="trade_in">
                                    <div id="trade_in_error" class="invalid-feedback text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="about-car-content financical-d-content">
                                <h4>Let's Complete Your Profile</h4>
                                <div class="mt-4 form-info-com">
                                    <label class="form-label"><strong>Contact Information</strong></label>
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
                                </div>

                                <div class="mt-4 form-info-addr">
                                    <label class="form-label"><strong>Home Address</strong></label>
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
                                    <input type="text" class="form-control mb-2" name="street_address" id="street_address" placeholder="Address">
                                    <div id="street_address_error" class="invalid-feedback text-danger"></div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                            <div id="city_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                            <div id="province_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                            <div id="postal_code_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="about-car-content financical-d-content">
                                <h4>Financial Details</h4>
                                <div class="mb-4">
                                    <label class="form-label"><strong>Date of Birth</strong></label>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-2">
                                            <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day"></select>
                                            <div id="birth_date_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                            <div id="birth_month_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="birth_year" id="dobyear" placeholder="Year"></select>
                                            <div id="birth_year_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                    </div>
                                </div>

                                <label class="form-label"><strong>Income & Employment </strong><small class="text-muted">(Before Taxes & Deductions)</small></label>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <select id="employment-status" name="employment_status" placeholder="Select" class="form-control">
                                            <option disabled="disabled" selected value="">Select</option>
                                            <option value="Full-time">Full-time</option>
                                            <option value="Part-time">Part-time</option>
                                            <option value="Self-employed">Self-employed</option>
                                        </select>
                                        <div id="employment_status_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="monthly_income" id="monthly_income" placeholder="Monthly Income">
                                        <div id="monthly_income_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="employer" id="employer" placeholder="Employer" placeholder="Employer">
                                        <div id="employer_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Title" placeholder="Job Title">
                                        <div id="job_title_error" class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <label class="form-label"><strong>Credit Score</strong></label>
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

                        <div class="step well" style="display:none ;">
                            <div class="vehile-delivery">
                                <h4 class="b-sp-0">Delivery Options</h4>
                                <p class="b-sp-10">Choose how you want to receive your vehicle</p>
                                <div class="option-box" id="financeBox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delivery_option" id="delivery_option_delivered">
                                        <label class="form-check-label fw-bold" for="delivery_option_delivered"> Have it delivered</label>
                                        <p>We bring your vehicle directly to you</p>
                                    </div>
                                </div>

                                <div class="option-box" id="cashBox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="pick_it_up" name="delivery_option" value="Pick it up">
                                        <label class="form-check-label fw-bold" for="pick_it_up"> Pick it up</label>
                                        <p>Pick up the vehicle at one of our location in Ontario</p>
                                    </div>
                                </div>
                                <input type="radio" style="visibility: hidden; width:0px; position:absolute" name="delivery_option">
                                <div id="delivery_option_error" class="invalid-feedback text-danger"></div>
                            </div>
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
                        <input type="hidden" name="source_params" value="">
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
    <script src="/dist/assets/js/dobpicker.js"></script>
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
                $("#zm_odometer").text(``);
                $("#zm_selling_price_numeric").text(``);
                $('#zd_inventory_title').text('NO VEHICLE SELECTED');
                $("#zm_image").attr('src', window.placeholder);
            }

            function single_inventory(id) {
                $.get(window.location.origin + '/api/v2/website/inventory/show/' + id, function(data) {

                    const USDollar = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                    });

                    let {
                        special_price_on,
                        price,
                        special_price
                    } = data;

                    const has_special_price =
                        special_price_on === "1" &&
                        Number(special_price) > 0;

                    const current_selling_price = has_special_price ? special_price : price;


                    let formattedAmount = USDollar.format(current_selling_price);

                    const NumberFormat = new Intl.NumberFormat('en-US');
                    let formattedOdometer = NumberFormat.format(data.odometer);

                    $('#single_stock_no_input').val(data.stock_no);
                    $('#single_stock_zm_year').val(data.year);
                    $('#single_stock_zm_make').val(data.make);
                    $('#single_stock_zm_model').val(data.model);
                    $('#single_stock_zm_trim').val(data.trim);
                    $('#single_stock_zm_vin').val(data.vin);
                    $('#zd_inventory_title').html(`<span>${data.year}</span> ${data.make} ${data.model} <span>${data.trim}</span> - <span><b>#${data.stock_no}</b></span>`);
                    $("#zm_odometer").text(`${formattedOdometer} ${data.km_miles}`);
                    $("#zm_selling_price_numeric").text(formattedAmount);

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

            document.querySelector(`input[name='source_url']`).value = source_url;

            let parentURLParams = new URLSearchParams(window.location.search);
            if (parentURLParams.size > 0) {
                document.querySelector(`input[name='source_params']`).value = window.location.search.substr(1);
            }

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

            $.dobPicker({
                daySelector: '#dobday',
                monthSelector: '#dobmonth',
                yearSelector: '#dobyear'

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

            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "financing_form"
            }, "*");


            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                vehicle_type: "required",
                finance_or_cash: "required",
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
                monthly_income: {
                    required: true,
                    number: true
                },
                employer: "required",
                delivery_option: "required",
            };
            var messages = {
                finance_or_cash: "Type of loan is required",
                vehicle_type: "Vehicle type is required",
                budget: "Budget is required",
                credit_rating: "Credit rating is required",
                trade_in: "Trade-In is required",
                birth_date: {
                    required: "Please enter date of birth",
                    number: "Day of birth should be a number",
                },
                birth_month: {
                    required: "Please enter month of birth",
                    number: "Month of birth should be a number",
                },
                birth_year: {
                    required: "Please enter year of birth",
                    number: "Year of birth should be a number",
                },
                employment_status: "Employment status is required",
                residence_period_years: {
                    required: "Please Enter the Residence Period Years",

                },
                residence_period_months: {
                    required: "Please Enter Residence Period Months",

                },
                employment_period_years: {
                    required: "Please Enter Employment Period Years",

                },
                employment_period_months: {
                    required: "Please Enter Employment Period Months",

                },
                gross_income: "Gross Income is required",
                employment_period_with_this_salary: "Employment Period is required",
                job_title: "Please enter job title",
                residence_status: "Residence Status is required",
                mortgage_rent_amount_monthly: {
                    required: "Please Enter Rent or Mortgage amount",
                    number: "Rent or Mortgage amount should be numeric"
                },
                street_address: "Please enter street address",
                city: "Please enter city",
                province: "Please enter province",
                postal_code: "Please enter postal code",
                residence_period: "Please Enter Residence Period",
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
                monthly_income: {
                    required: "Monthly income is required",
                    number: "Monthly income should be a number"
                },
                employer: "Employer name is required",
                delivery_option: "Delivery option is required"

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


        // tooltip code start

        document.querySelectorAll('.tooltip-box').forEach(box => {
            const tooltip = box.querySelector('.tooltip-content');

            box.addEventListener('mouseenter', () => {
                tooltip.classList.add('show');
            });

            box.addEventListener('mouseleave', () => {
                tooltip.classList.remove('show');
            });
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