<!DOCTYPE html>
<html>

<head>
    <title>Trade | Zop Dealer</title>
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

    <style>
        .color-dropdown .color-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            min-width: 220px;
            justify-content: space-between;
        }

        .color-dropdown .color-preview {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .color-dropdown .swatch {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, .15);
            flex: 0 0 auto;
        }

        .color-dropdown .dropdown-item.color-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
        }

        .color-dropdown .dropdown-menu {
            min-width: 240px;
        }

        /* Nice checkmark alignment */
        .color-dropdown .check {
            margin-left: auto;
            opacity: 0;
        }

        .color-dropdown .dropdown-item.active .check {
            opacity: 1;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>
    <section class="details-main get-sp-form trade-in-my-car">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form class="needs-validation" id="lead_form" novalidate="novalidate">
                        <div class="step well">
                            <div class="about-car-content financical-d-content">
                                <div class="row g-3">
                                    <h4 class="mb-2 mt-4" id="vehicle-title"></h4>

                                    <?php $available_colors = [
                                        [
                                            "label" => "Black",
                                            "color" => "#000"
                                        ],
                                        [
                                            "label" => "Blue",
                                            "color" => "#0d6efd"
                                        ],
                                        [
                                            "label" => "Brown",
                                            "color" => "#8B5A2B"
                                        ],
                                        [
                                            "label" => "Gold",
                                            "color" => "#D4AF37"
                                        ],
                                        [
                                            "label" => "Gray",
                                            "color" => "#6C757D"
                                        ],
                                        [
                                            "label" => "Green",
                                            "color" => "#198754"
                                        ],
                                        [
                                            "label" => "Orange",
                                            "color" => "#FD7E14"
                                        ],
                                        [
                                            "label" => "Pink",
                                            "color" => "#D63384"
                                        ],
                                        [
                                            "label" => "Red",
                                            "color" => "#DC3545"
                                        ],
                                        [
                                            "label" => "Silver",
                                            "color" => "#C0C0C0"
                                        ],
                                        [
                                            "label" => "Teal",
                                            "color" => "#20C997"
                                        ],
                                        [
                                            "label" => "White",
                                            "color" => "#FFFFFF"
                                        ],
                                        [
                                            "label" => "Yellow",
                                            "color" => "#FFC107"
                                        ],
                                        [
                                            "label" => "Purple",
                                            "color" => "#6F42C1"
                                        ],
                                    ]; ?>


                                    <!-- Exterior Color: START -->
                                    <div class="col-md-12 col-md-12 col-xs-12">
                                        <label class="form-label"><strong>What is the exterior color of your vehicle?</strong></label>
                                        <div class="dropdown color-dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle color-btn" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false" id="exterior_colour_btn">
                                                <span class="color-preview">
                                                    <span class="swatch" style="background:none;"></span>
                                                    <span class="label">Select A Colour</span>
                                                </span>
                                            </button>

                                            <ul class="dropdown-menu" aria-labelledby="exterior_colour_btn" id="exterior_colour_dropdown">

                                                <?php foreach ($available_colors as $color) { ?>
                                                    <li><button class="dropdown-item color-item" type="button" data-color="<?php echo $color['label'] ?>" data-hex="<?php echo $color['color'] ?>">
                                                            <span class="swatch" style="background:<?php echo $color['color'] ?>;"></span> <?php echo $color['label'] ?> <span class="check">✓</span>
                                                        </button></li>
                                                <?php   } ?>


                                                <!-- Unknown (gradient swatch) -->
                                                <li><button class="dropdown-item color-item" type="button" data-color="Unknown" data-hex="gradient">
                                                        <span class="swatch" style="background:radial-gradient(circle at 30% 30%, 
                                                    #6A1B9A,
                                                    #AD1457,
                                                    #EF6C00,
                                                    #FBC02D,
                                                    #00897B,
                                                    #1565C0
                                                    );"></span>
                                                        Unknown <span class="check">✓</span>
                                                    </button></li>
                                            </ul>

                                            <input style="visibility: hidden; width:0px; position:absolute;" name="exterior_colour" value="">
                                            <div id="exterior_colour_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                    </div>

                                    <!-- Exterior Color: END -->

                                    <!-- Interior Color: START -->
                                    <div class="col-md-12 col-md-12 col-xs-12">
                                        <label class="form-label"><strong>What is the interior color of your vehicle?</strong></label>
                                        <div class="dropdown color-dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle color-btn" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false" id="interior_colour_btn">
                                                <span class="color-preview">
                                                    <span class="swatch" style="background:none;"></span>
                                                    <span class="label">Select A Colour</span>
                                                </span>
                                            </button>

                                            <ul class="dropdown-menu" aria-labelledby="interior_colour_btn" id="interior_colour_dropdown">

                                                <?php foreach ($available_colors as $color) { ?>
                                                    <li><button class="dropdown-item color-item" type="button" data-color="<?php echo $color['label'] ?>" data-hex="<?php echo $color['color'] ?>">
                                                            <span class="swatch" style="background:<?php echo $color['color'] ?>;"></span> <?php echo $color['label'] ?> <span class="check">✓</span>
                                                        </button></li>
                                                <?php   } ?>


                                                <!-- Unknown (gradient swatch) -->
                                                <li><button class="dropdown-item color-item" type="button" data-color="Unknown" data-hex="gradient">
                                                        <span class="swatch" style="background:radial-gradient(circle at 30% 30%, 
                                                    #6A1B9A,
                                                    #AD1457,
                                                    #EF6C00,
                                                    #FBC02D,
                                                    #00897B,
                                                    #1565C0
                                                    );"></span>
                                                        Unknown <span class="check">✓</span>
                                                    </button></li>
                                            </ul>

                                            <input style="visibility: hidden; width:0px; position:absolute;" name="interior_colour" value="">
                                            <div id="interior_colour_error" class="invalid-feedback text-danger"></div>
                                        </div>
                                    </div>

                                    <!-- Interior Color: END -->

                                    <div class="col-md-12 col-md-12 col-xs-12">
                                        <label class="form-label"><strong>Odometer</strong></label>
                                        <input type="text" class="form-control" name="odometer" id="odometer" placeholder="Odometer">
                                        <div id="odometer_error" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Transmission</strong></label>
                                        <div class="d-flex flex-wrap radio-group mb-3">
                                            <input type="radio" class="seclect-ratio-btn" id="automatic-trans" name="transmission" value="Automatic">
                                            <label for="automatic-trans">Automatic</label>

                                            <input type="radio" class="seclect-ratio-btn" id="manual-trans" name="transmission" value="Manual">
                                            <label for="manual-trans">Manual</label>

                                            <input type="radio" class="seclect-ratio-btn" id="cvt-trans" name="transmission" value="CVT">
                                            <label for="cvt-trans">CVT</label>

                                            <input type="radio" class="seclect-ratio-btn" id="other-trans" name="transmission" value="Other">
                                            <label for="other-trans">Other</label>

                                            <input type="radio" class="seclect-ratio-btn" id="triptronic-trans" name="transmission" value="triptronic">
                                            <label for="triptronic-trans">Triptronic</label>
                                        </div>
                                        <input type="radio" class="seclect-ratio-btn" style="visibility: none; position:absolute;display:inline;width:0px;" id="triptronic-trans" name="transmission">
                                        <div id="transmission_error" class="invalid-feedback text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well vehicle-d-second" style="display:none;">
                            <div class="row g-3">
                                <h4 class="mb-2 mt-4">Your offer is ready!</h4>
                                <p class="sb-title-f">Provide your contact details to get your personalized offer.</p>
                                <div class="col-md-6 col-md-6 col-xs-6 no-space">
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                    <div id="first_name_error" class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 col-md-6 col-xs-6 no-space">
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                    <div id="last_name_error" class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-12 col-md-12 col-xs-12">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                    <div id="email_error" class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-12 col-md-12 col-xs-12">
                                    <input type="tel" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                    <div id="phone_error" class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <input type="hidden" name="source" value="<?php echo $source; ?>">
                                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                    <input type="hidden" name="source_url" value="">
                                    <input type="hidden" class="form-control" name="year" id="year" placeholder="Year">
                                    <input type="hidden" class="form-control" name="make" id="make" placeholder="Make">
                                    <input type="hidden" class="form-control" name="model" id="model" placeholder="Model">
                                    <input type="hidden" class="form-control" name="city" id="city" placeholder="City">
                                    <input type="hidden" class="form-control" name="province" id="province" placeholder="Province">


                                    <div class="col-md-12 col-sm-12">
                                        <div class="checkbox-block">
                                            <input type="checkbox" name="sms_consent" checked>
                                            By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by texting STOP. Messaging frequency may vary. Message and data rates may apply. For assistance, text HELP or visit our website at <a href="https://www.cardora.ca/">Cardora</a>. Visit <a href="https://www.cardora.ca/privacy-policy/">Privacy Policy</a> for privacy policy and <a href="https://www.cardora.ca/terms-conditions/">Terms and Conditions</a> for Terms of Service.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 p-top-20 text-center">
                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                    <div class="invalid-feedback" id="recaptcha-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="trade-btn-list">
                            <a type="submit" class="action back btn btn-info" style="display:block;">Back</a>
                            <a class="action next btn btn-info" style="display:block;">Continue</a>
                            <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        </div>
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
    <script src="/dist/assets/js/browserStorage.js"></script>
    <script src="/dist/assets/js/colorDropdown.js"></script>

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


            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                vin: "required",
                odometer: {
                    required: true,
                    number: true
                },
                exterior_colour: "required",
                interior_colour: "required",
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
                transmission: "required"
            };
            var messages = {
                vin: "Please Enter the VIN",
                odometer: {
                    required: "Please Enter Odometer",
                    number: "Odometer should be a Number"
                },
                exterior_colour: "Exterior Colour is required",
                interior_colour: "Interior Colour is required",
                vehicle_type: "Vehicle Type is required",
                budget: "Budget is required",
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
                postal_code: "Please Enter Postal Code",
                residence_period: "Please Enter Residence Period",
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
                transmission: "Transmission is required"

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
                        element_id: "trade_form_with_vehicle"
                    }, "*");

                },
                submitHandler: function(form, event) {
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

                            parent.postMessage("redirectToThankYouPageForTradeInPage", "*");
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

                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "trade_form_with_vehicle"
                }, "*");
            })

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', false);
                } else {
                    parent.postMessage("redirectToTradeInPage", "*");
                }
                hideButtons(current);

                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "trade_form_with_vehicle"
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
            btnback.show();

            if (current < limit) btnnext.show();
            // if (current > 1) btnback.show();
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
        let interior_color_dropdown = document.getElementById("interior_colour_dropdown");
        let exterior_color_dropdown = document.getElementById("exterior_colour_dropdown");

        interior_color_dropdown.addEventListener("click", colorDropdownSelection.bind(this, 'interior_colour_btn', 'interior_colour'));
        exterior_color_dropdown.addEventListener("click", colorDropdownSelection.bind(this, 'exterior_colour_btn', 'exterior_colour'));
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "trade_form_with_vehicle"
            }, "*");

            setTimeout(() => {
                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "trade_form_with_vin"
                }, "*");
            }, 1000);

            var current_vehicle_year = $("input[name='year']").val();
            var current_vehicle_make = $("input[name='make']").val();
            var current_vehicle_model = $("input[name='model']").val();
            var current_vehicle_trim = $("input[name='trim']").val();

            $("#vehicle-title").text(`Your ${current_vehicle_year} ${current_vehicle_make} ${current_vehicle_model}`);
        });
    </script>


</body>