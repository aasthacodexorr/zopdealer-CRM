<!DOCTYPE html>
<html>

<head>
    <title>Service Appointment | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/custom_forms/error-style.css">
    <style>
        #request_service_section p input {
            vertical-align: bottom;
        }
    </style>
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

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>

    <!-- LAYER-1 START -->
    <section class="section-3">
        <!-- CONTAINER START -->
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="head-text">
                    <form class="needs-validation" id="lead_form">
                        <div class="row" id="pak1">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Package Options</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="pak">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Package</strong></p>
                                    <input type="text" id="package-option" name="package_option" class="form-control" placeholder="" readonly />

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Package Price</strong></p>
                                    <input type="text" name="package_price" id="package-price" class="form-control" placeholder="" readonly />
                                </div>
                            </div>

                        </div>
                        <div class="row" id="special-hading">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Special Offer</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="special">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Special</strong></p>
                                    <input type="text" id="special-option" name="special_option" class="form-control" value="special" placeholder="" readonly />

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Offer</strong></p>
                                    <input type="text" name="offer_price" id="offer-price" class="form-control" placeholder="" readonly />
                                </div>
                            </div>
                        </div>
                        <!-- <h3>Schedule Service</h3> -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><span id="vehicle_section_list_indicator">1.</span> <strong>Vehicle Info</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Year</strong></p>
                                    <input min="1960" name="year" type="text" class="form-control" placeholder="" id="service-year" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Make</strong></p>
                                    <input type="text" name="make" class="form-control" placeholder="" id="service-make" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Model</strong></p>
                                    <input type="text" name="model" class="form-control" placeholder="" id="service-model" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20" style="position: relative;">
                                    <p><strong>Odometer</strong></p>
                                    <span class="right-text" style="bottom:unset;"> Kms</span>
                                    <input type="text" name="odometer" class="form-control" placeholder="" id="trade-odometer" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>3. <strong>Requested Service(s)</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="request_service_section">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldExterior" value="Wash" /><span class="left-sp">Wash</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Oil Change" /><span class="left-sp">Oil Change</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium" value="Engine Shampoo" /><span class="left-sp">Engine Shampoo</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldExterior" value="Clean Door Jams" /><span class="left-sp">Clean Door Jams</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldExterior" value="Clay Bar" /><span class="left-sp">Clay Bar</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="Vacuum" /><span class="left-sp">Vacuum</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="Seat Shampoo" /><span class="left-sp">Seat Shampoo</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="Carpet shampoo" /><span class="left-sp">Carpet shampoo</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="All plastic vents & moldings cleaning" /><span class="left-sp">All plastic vents & moldings cleaning</span></p>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="Vinyl clean/shine" /><span class="left-sp">Vinyl clean/shine</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="Full trunk detail + removal of spare tire" /><span class="left-sp">Full trunk detail + removal of spare tire</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldExterior" value="Wheels (degrease and clean)" /><span class="left-sp">Wheels (degrease and clean)</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldExterior" value="Tire shine/conditioner" /><span class="left-sp">Tire shine/conditioner</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldExterior" value="Exterior Wax Polish" /><span class="left-sp">Exterior Wax Polish</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="GoldExterior" value="Windows" /><span class="left-sp">Windows</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" class="Titanium GoldInterior" value="Clean interior and exterior windows" /><span class="left-sp">Clean interior and exterior windows</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Tire Rotation" /><span class="left-sp">Tire Rotation</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Coolant Flush" /><span class="left-sp">Coolant Flush</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Transmission Flush" /><span class="left-sp">Transmission Flush</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Tire Balance" /><span class="left-sp">Tire Balance</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Air Conditioner Service" /><span class="left-sp">Air Conditioner Service</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Filter Replacement" /><span class="left-sp">Filter Replacement</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Front End Alignment" /><span class="left-sp">Front End Alignment</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Replace / Adjust Brakes" /><span class="left-sp">Replace / Adjust Brakes</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Muffler / Exhaust Repair" /><span class="left-sp">Muffler / Exhaust Repair</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Vehicle Checkup / Inspection" /><span class="left-sp">Vehicle Checkup / Inspection</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Electrical Service" /><span class="left-sp">Electrical Service</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Maintenance" /><span class="left-sp">Maintenance (Wipers, Headlights, Etc)</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Other" /><span class="left-sp">Other (Please Describe Below)</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Comments</strong></p>
                                    <textarea rows="8" name="comments" class="form-control" placeholder="" id="service-comments"></textarea>
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>4. <strong>Personal Info & Appointment</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>First Name</strong></p>
                                    <input type="text" class="form-control" name="first_name" placeholder="" id="service-first-name" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last Name</strong></p>
                                    <input type="text" class="form-control" name="last_name" placeholder="" id="service-last-name" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input type="email" class="form-control" name="email" placeholder="" id="service-email" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" class="form-control" name="phone" placeholder="" id="service-phone-number" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Preferred Appointment Date</strong></p>
                                    <input type="date" name="preferred_appointment_date" class="form-control" placeholder="" id="service-preferred-date" />
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Preferred Appointment Time</strong></p>
                                    <select id="service-preferred-time" name="preferred_appointment_time" class="form-control">
                                        <option disabled selected>Select Preferred Time</option>
                                        <option value="Morning">Morning</option>
                                        <option value="Afternoon">Afternoon</option>
                                        <option value="Evening">Evening</option>
                                    </select>
                                    <div class="text-danger invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="checkbox-block">
                            <input type="checkbox" name="sms_consent" checked>
                            By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us.
                        </div>
                        <div class="row">
                            <div class="col-md-12 p-top-20">
                                <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                <div class="invalid-feedback" id="recaptcha-error"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="hero-btn p-top-20 p-bottom-50">
                                <input type="hidden" name="source" value="<?php echo $source; ?>">
                                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                <input type="hidden" name="source_url" value="">
                                <button type="submit" class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;color:white !important;">Book Service Now</a></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONTAINER END -->
    </section>

    <!-- LAYER 1 END -->


    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/custom_forms/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>

    <script>
        $(document).ready(function() {

            $(document).ready(function() {
                let url_str = window.location.href;

                let url = new URL(url_str);
                let search_params = url.searchParams;
                var package_id = search_params.get('packag_id');
                var price = search_params.get('price');
                var special = search_params.get('special');
                var offer = search_params.get('offer');


                if (package_id) {
                    $('#pak').show();
                    $('#pak1').show();
                    $('#package-price').val(price);
                    $('#package-option').val(package_id);
                    document.getElementById("vehicle_section_list_indicator").innerHTML = "2.";
                    if (package_id == "Titanium") {
                        $('.Titanium').attr('checked', true);
                    } else if (package_id == "Gold Exterior") {
                        $('.GoldExterior').attr('checked', true);
                    } else if (package_id == "Gold Interior") {
                        $('.GoldInterior').attr('checked', true);
                    }


                } else {
                    $('#pak').hide();
                    $('#pak1').hide();
                }
                if (special) {
                    $('#special').show();
                    $('#special-hading').show();
                    $('#offer-price').val(offer);
                    document.getElementById("vehicle_section_list_indicator").innerHTML = "2.";
                } else {
                    $('#special').hide();
                    $('#special-hading').hide();
                }

            });

            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            document.querySelector(`input[name='source_url'`).value = source_url;

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "service_appointment"
            }, "*");

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

            // START Form Validation
            $("#lead_form").validate({
                rules: {
                    year: {
                        required: true,
                        min: 1960,
                        max: new Date().getFullYear() + 2,
                        number: true
                    },
                    make: "required",
                    model: "required",
                    odometer: {
                        number: true
                    },
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
                        number: true
                    }
                },
                messages: {
                    year: {
                        required: "Please Enter the Year",
                        number: "Year should be a Number"
                    },
                    make: "Please Enter the Make of the Vehicle",
                    model: "Please Enter the Model",
                    odometer: {
                        number: "Odometer should be a Number"
                    },
                    first_name: "Please Enter Your First Name",
                    last_name: "Please Enter Your Last Name",

                    email: {
                        required: "Please Enter Your Email",
                        email: "Please Enter a valid Email"
                    },
                    phone: {
                        required: "Please Enter Your Phone Number",
                        number: "Phone Number should be a Number",
                        minlength: "Your Phone Number must be 10 characters long"
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.is(":radio")) {
                        error.appendTo(element('.invalid-feedback'));
                    } else { // This is the default behavior 
                        error.insertAfter(element);
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
        });
    </script>

</body>

<!---- BODY END ---->

</html>