<!DOCTYPE html>
<html>

<head>
    <title>Trade In Appraisal | Zop Dealer</title>
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
                        <!-- <h3>Trade-In Estimate</h3>
                        <p>Find out how much your trade-in vehicle is worth. Our trade-in estimate tool uses data from industry leading partners, giving you relevant and robust vehicle valuations.</p> -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Vehicle Description</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <p><strong>Year <sup class="text-danger">*</sup></strong></p>
                                    <select class="form-control" name="year" id="year">
                                        <option disabled selected> Select Year</option>
                                    </select>
                                    <div id="year_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <p><strong>Make <sup class="text-danger">*</sup></strong></p>
                                    <select class="form-control" name="make" id="make">
                                        <option disabled selected> Select Make</option>
                                    </select>
                                    <div id="make_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <p><strong>Model <sup class="text-danger">*</sup></strong></p>
                                    <select class="form-control" name="model" id="model">
                                        <option disabled selected> Select Model</option>
                                    </select>
                                    <div id="model_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <p><strong>Trim</strong></p>
                                    <select class="form-control" name="trim" id="trim">
                                        <option disabled selected> Select Trim</option>
                                    </select>
                                    <div id="trim_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <input type="text" name="vin" class="form-control" placeholder="VIN" id="trade-vin" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>2. <strong>Vehicle Condition & Odometer </strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>What is the condition of your Vehicle? </strong></p>
                                    <p>Selecting an accurate condition will give you a more accurate estimate.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-1" value="Fair" />
                                        <div class="select-box-text">
                                            <p><strong>Fair</strong></p>
                                            <!--                                            <p>11% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-2" value="Good" />
                                        <div class="select-box-text">
                                            <p><strong>Good</strong></p>
                                            <!--                                            <p>49% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-3" value="Very Good" />
                                        <div class="select-box-text">
                                            <p><strong>Very Good</strong></p>
                                            <!--                                            <p>33% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-4" value="Excellent" />
                                        <div class="select-box-text">
                                            <p><strong>Excellent</strong></p>
                                            <!--                                            <p>3% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20" style="position: relative;">
                                    <p><strong>Odometer</strong></p>
                                    <span class="right-text" style="bottom:unset;"> Kms</span>
                                    <input type="text" name="odometer" class="form-control" placeholder="" id="trade-odometer" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>3. <strong>Vehicle Images (Upto 5 images)</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_1" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_2" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_3" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_4" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_5" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
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
                                    <input name="first_name" type="text" class="form-control" placeholder="" id="trade-first-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last name</strong></p>
                                    <input type="text" name="last_name" class="form-control" placeholder="" id="trade-last-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input type="email" name="email" class="form-control" placeholder="" id="trade-email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="trade-phone-number" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Preferred Appointment Date</strong></p>
                                    <input type="date" name="preferred_appointment_date" class="form-control" placeholder="" id="trade-preferred-date" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Preferred Appointment Time</strong></p>
                                    <select id="trade-preferred-time" name="preferred_appointment_time" class="form-control">
                                        <option disabled selected>Select Preferred Time</option>
                                        <option value="Morning">Morning</option>
                                        <option value="Afternoon">Afternoon</option>
                                        <option value="Evening">Evening</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <textarea rows="8" name="comments" class="form-control" placeholder="Message" id="trade-message"></textarea>
                                    <div class="invalid-feedback"></div>
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
                        <!-- <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><input type="checkbox" id="condition-check" name="condition"/><span class="left-sp">By clicking Get Approved, I consent to the collection, use and disclosure of my personal information as described in this paragraph. I agree that the personal information provided above may be used and disclosed by Zop Demo and/or its agents or service providers (collectively, the "Dealer") as necessary to obtain credit, financial and related personal information (including a credit or consumer information report) about me from any credit bureau or credit reporting agency, and to advise me on credit availability in connection with product and/or service purchase financing. I further agree that the personal information provided above may be disclosed to the provider of Dealer’s website hosting or related services for the purpose of enabling Dealer to access my personal information for marketing purposes. Personal information I provide and credit information obtained may also be retained by Dealer and used to facilitate the application process should I subsequently choose to apply for credit through Dealer.</span></p>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="hero-btn p-top-20 p-bottom-50">
                                <input type="hidden" name="source" value="<?php echo $source; ?>">
                                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                <input type="hidden" name="source_url" value="">
                                <button type="submit" class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;color:white !important;">Get My Trade-In Appraisal</a></button>
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

            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            document.querySelector(`input[name='source_url'`).value = source_url;

            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "trade_in_appraisal"
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
                    "removeMaskOnSubmit": true,
                    "autoUnmask": true
                });
            });

            // START Form Validation
            $("#lead_form").validate({
                rules: {
                    year: {
                        required: true,
                        min: 1942,
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
                    vin: "Please Enter VIN",
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
                        data: new FormData(document.getElementById('lead_form')), // get all form variables
                        contentType: false,
                        processData: false,
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

                                parent.postMessage({
                                    type: "css",
                                    value: $("body").outerHeight(true),
                                    element_id: "trade_in_appraisal"
                                }, "*");

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
                                    "removeMaskOnSubmit": true,
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
    <script>
        $(document).ready(function() {

            /**
             * triggerDropdownOnChange Accepts three arguments column : current selector, filters: initial filters to use and reset: columns to reset
             *
             * @return void
             */
            function triggerDropdownOnChange(column, filters, reset) {

                const trade_in_api_domain = "https://zoptradein.zopsoftware.com";

                if (reset.length !== 0) {
                    for (const reset_column of reset) {
                        document.querySelectorAll(`select[name='${reset_column}'] option`).forEach(option => option.remove());
                        let reset_dom = document.createElement("option");
                        reset_dom.innerText = `Select ${reset_column.charAt(0).toUpperCase() + String(reset_column).slice(1)}`;
                        reset_dom.value = "";
                        reset_dom.setAttribute("selected", true);
                        reset_dom.setAttribute("disabled", true);
                        document.querySelector(`select[name='${reset_column}']`).appendChild(reset_dom);
                        reset_dom = null;
                    }
                }

                if (column == 'year') {
                    $.get(`${trade_in_api_domain}/api/website/inventory/filters`, function(data) {
                        console.log(data);

                        if (!data.hasOwnProperty('errors')) {
                            if (data.hasOwnProperty('year')) {
                                for (const {
                                        name
                                    }
                                    of data.year.sort((a, b) => parseInt(b.name, 10) - parseInt(a.name))) {
                                    let optional_dom = document.createElement("option");
                                    optional_dom = document.createElement("option");
                                    optional_dom.innerText = name;
                                    optional_dom.value = name;
                                    document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                                }
                            }
                        }
                    });
                }

                if (column == 'make') {
                    let selected_year = document.querySelector(`select[name='year']`).value;
                    $.get(`${trade_in_api_domain}/api/website/inventory/filters?zm_year[]=${selected_year}`, function(data) {

                        if (!data.hasOwnProperty('errors')) {
                            if (data.hasOwnProperty('make')) {
                                for (const {
                                        name
                                    }
                                    of data.make) {
                                    let optional_dom = document.createElement("option");
                                    optional_dom = document.createElement("option");
                                    optional_dom.innerText = name;
                                    optional_dom.value = name;
                                    document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                                }
                            }
                        }
                    });
                }

                if (column == 'model') {
                    let selected_year = document.querySelector(`select[name='year']`).value;
                    let selected_make = document.querySelector(`select[name='make']`).value;
                    $.get(`${trade_in_api_domain}/api/website/inventory/filters?zm_year[]=${selected_year}&zm_make[]=${selected_make}`, function(data) {

                        if (!data.hasOwnProperty('errors')) {
                            if (data.hasOwnProperty('model')) {
                                for (const {
                                        name
                                    }
                                    of data.model) {
                                    let optional_dom = document.createElement("option");
                                    optional_dom = document.createElement("option");
                                    optional_dom.innerText = name;
                                    optional_dom.value = name;
                                    document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                                }
                            }
                        }
                    });
                }

                if (column == 'trim') {
                    let selected_year = document.querySelector(`select[name='year']`).value;
                    let selected_make = document.querySelector(`select[name='make']`).value;
                    let selected_model = document.querySelector(`select[name='model']`).value;
                    $.get(`${trade_in_api_domain}/api/website/inventory/filters?zm_year[]=${selected_year}&zm_make[]=${selected_make}&zm_model[]=${selected_model}`, function(data) {

                        if (!data.hasOwnProperty('errors')) {
                            if (data.hasOwnProperty('trim')) {
                                for (const {
                                        name
                                    }
                                    of data.trim) {
                                    let optional_dom = document.createElement("option");
                                    optional_dom = document.createElement("option");
                                    optional_dom.innerText = name;
                                    optional_dom.value = name;
                                    document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                                }
                            }
                        }
                    });
                }


            }

            triggerDropdownOnChange('year', [], ['year', 'make', 'model', 'trim']);
            document.querySelector(`select[name='year']`).addEventListener('change', () => {
                triggerDropdownOnChange('make', ['year'], ['make', 'model', 'trim']);
            });

            document.querySelector(`select[name='make']`).addEventListener('change', () => {
                triggerDropdownOnChange('model', ['year', 'make'], ['model', 'trim']);
            });

            document.querySelector(`select[name='model']`).addEventListener('change', () => {
                triggerDropdownOnChange('trim', ['year', 'make', 'model'], ['trim']);
            });

            document.querySelector(`select[name='trim']`).addEventListener('change', () => {

            });
        });
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "trade_in_appraisal"
            }, "*");

        });
    </script>

</body>

<!---- BODY END ---->

</html>