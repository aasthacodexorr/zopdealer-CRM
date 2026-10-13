<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="origin-trial" content="Az520Inasey3TAyqLyojQa8MnmCALSEU29yQFW8dePZ7xQTvSt73pHazLFTK5f7SyLUJSo2uKLesEtEa9aUYcgMAAACPeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZS5jb206NDQzIiwiZmVhdHVyZSI6IkRpc2FibGVUaGlyZFBhcnR5U3RvcmFnZVBhcnRpdGlvbmluZyIsImV4cGlyeSI6MTcyNTQwNzk5OSwiaXNTdWJkb21haW4iOnRydWUsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=">
    <title>Service Appointment | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="/custom_forms/style.css"> -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <!--  HEAD END  -->

    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
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
            <form class="needs-validation" id="lead_form" novalidate="novalidate">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <!--Financial Services Starts-->
                <div class="col-md-12">
                    <div class="form-layout">
                        <div class="list">
                            <div class="step well" style="display:block;">
                                <div class="head-text-f">
                                    <div class="col-md-12">
                                        <div class="set-box">
                                            <h3>Enter your vehicle information</h3>
                                            <div class="form-list-desgine">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-12">
                                                        <div class="head-felid1">
                                                            <label>Year</label>
                                                            <input min="1960" name="year" type="text" class="form-control" placeholder="Year" id="service-year">
                                                            <div id="year_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-12">
                                                        <div class="head-felid1">
                                                            <label>Make</label>
                                                            <input type="text" name="make" class="form-control" placeholder="Make" id="service-make" />
                                                            <div id="make_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-12">
                                                        <div class="head-felid1">
                                                            <label>Model</label>
                                                            <input type="text" name="model" class="form-control" placeholder="Model" id="service-model" />
                                                            <div id="model_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="head-felid1">
                                                            <label>Odometer</label>
                                                            <input type="text" class="form-control" name="odometer" id="service-odometer" placeholder="KM">
                                                            <div id="odometer_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step well" style="display:none;">
                                <div class="head-text-f">
                                    <div class="set-box">
                                        <div class="col-md-12">
                                            <div class="set-box">
                                                <h3 class="sb-t">Car Services</h3>
                                                <p class="sec-lab">Select any services your car needs.</label>
                                                <ul class="form-list-desgine">
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-new_tire" name="requested_service[]" value="New Tire/ Wheel Consultation"><label><strong> New Tire/ Wheel Consultation </strong> We'll help you find the right tires and/or wheels to fit your unique needs or preferences.</label>

                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-tire_balancing" name="requested_service[]" value="Tire Balancing"><label><strong> Tire Balancing </strong>Our tire balancing service encourages a smooth ride and even treadwear.</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-flat_repair" name="requested_service[]" value="Flat Repair"><label><strong> Flat Repair </strong>If you have a flat, punctured or low tire, we'll repair the damage for free or identify your replacement options if it can't be safely repaired.</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-tire_rotation_and_balance" name="requested_service[]" value="Tire Rotation and Balance"><label><strong> Tire Rotation and Balance </strong>Our tire rotation and balancing services promote longevity, safety, and ride comfort.</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-oil_change" name="requested_service[]" value="Oil Change"><label><strong> Oil Change </strong>Keep your engine running smoothly and extend its life with our quick and reliable oil change service.</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-brakes" name="requested_service[]" value="Brakes"><label><strong> Brakes </strong>Stay safe on the road with expert brake inspections, repairs, and replacements tailored to your vehicle's needs.</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid w-50 check-list-box">
                                                            <input type="checkbox" class="seclect-ratio-btn" id="service-requested-service-other_services" name="requested_service[]" value="Other Services"><label><strong> Other Services </strong>From alignments to battery checks and beyond, we offer a full range of services to keep you driving with confidence.</label>
                                                        </div>
                                                    </li>
                                                    <div id="requested_service_error" class="invalid-feedback"></div>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step well" style="display: none;">
                                <div class="head-text-f">
                                    <div class="set-box row">
                                        <h3 class="w-100">Personal Info & Appointment</h3>
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
                                        <div class="col-md-6 col-xs-12">
                                            <div class="head-felid1">
                                                <label class="preferred-appointment">Preferred Appointment Date</label>
                                                <input type="date" name="preferred_appointment_date" class="form-control" placeholder="Appointment Date" id="preferred_appointment_date" />
                                                <div id="preferred_appointment_date_error" class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="head-felid1">
                                                <label class="preferred-appointment">Preferred Appointment Time</label>
                                                <input type="time" name="preferred_appointment_time" class="form-control" placeholder="Appointment Time" id="preferred_appointment_time" />
                                                <div id="preferred_appointment_time_error" class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <input type="hidden" name="source" value="<?php echo $source; ?>">
                                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                            <input type="hidden" name="source_url" value="">
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
                            <div class="col-md-12 left-content-list">
                                <a type="submit" class="action back btn btn-info" style="display: none;">Back</a>
                                <a class="action next btn btn-info" style="display: block;">Continue</a>
                                <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                            </div>
                        </div>
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
                    "removeMaskOnSubmit": true,
                    "autoUnmask": true
                });
            });

            //Fetch Year from JSON
            $.get(window.location.origin + '/json/year.json', function(data) {
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var element = data[key];
                        var option = '<option value="' + element + '"> ' + element + '</option>';
                        $('#autofinder-year').append(option);

                    }
                }
            });

            //Fetch Make from JSON
            $.get(window.location.origin + '/json/make.json', function(data) {
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var element = data[key];
                        var option = '<option value="' + element + '"> ' + element + '</option>';
                        $('#autofinder-make').append(option);

                    }
                }
            });

            $.dobPicker({
                daySelector: '#dobday',
                monthSelector: '#dobmonth',
                yearSelector: '#dobyear'

            });

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "service_appointment"
            }, "*");

            var current = 1;

            widget = $(".step");
            //        btnskip     = $(".skip");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                year: {
                    required: true,
                    min: 1960,
                    max: new Date().getFullYear() + 2,
                    number: true
                },
                make: "required",
                model: "required",
                odometer: {
                    required: true,
                    number: true
                },
                "requested_service[]": "required",
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
            };
            var messages = {
                year: {
                    required: "Please Enter the Year",
                    number: "Year should be a Number"
                },
                make: "Please Enter the Make of the Vehicle",
                model: "Please Enter the Model",
                odometer: {
                    required: "Please Enter Odometer",
                    number: "Odometer should be a Number"
                },
                "requested_service[]": "Please Select Services",
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
                    let cleansed_id = element.attr("name");
                    cleansed_id = cleansed_id.replace("[]", "");
                    error.appendTo("#" + cleansed_id + "_error");
                    $("#" + cleansed_id + "_error").addClass("d-block");
                    $("#" + cleansed_id + "_error").find(".is-invalid").addClass("h5");

                    parent.postMessage({
                        type: "css",
                        value: $(".step.well:visible").outerHeight(true) + 200,
                        element_id: "trade_in_appraisal"
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
                        data: new FormData(document.getElementById('lead_form')),
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
                    value: $(".step.well:visible").outerHeight(true) + 200,
                    element_id: "service_appointment"
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
                    value: $(".step.well:visible").outerHeight(true) + 200,
                    element_id: "service_appointment"
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
    </script>

</body>