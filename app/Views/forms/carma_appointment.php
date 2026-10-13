<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="origin-trial" content="Az520Inasey3TAyqLyojQa8MnmCALSEU29yQFW8dePZ7xQTvSt73pHazLFTK5f7SyLUJSo2uKLesEtEa9aUYcgMAAACPeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZS5jb206NDQzIiwiZmVhdHVyZSI6IkRpc2FibGVUaGlyZFBhcnR5U3RvcmFnZVBhcnRpdGlvbmluZyIsImV4cGlyeSI6MTcyNTQwNzk5OSwiaXNTdWJkb21haW4iOnRydWUsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=">
    <title>Book An Appointment | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Flatpickr Calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

        .form-control.is-invalid,
        .was-validated .form-control:invalid {
            border-color: var(--bs-form-invalid-border-color);
            padding-right: calc(1.5em + .75rem);
            background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e);
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body class="service-form">
    <section class="get-sp-form">
        <div class="container py-5">
            <form class="needs-validation" id="lead_form" novalidate="novalidate">
                <!--Financial Services Starts-->
                <div class="col-md-12">
                    <div class="form-layout">
                        <div class="offset-md-1 col-md-10">
                            <div class="list">

                                <!-- STEP 1: START -->
                                <div class="step-section step active well" style="display: block;" id="step3">
                                    <h4 class="center-text mb-3">Select an available date and time.</h4>

                                    <!-- Calendar -->
                                    <div class="box-card cal">
                                        <div id="calendar"></div>
                                    </div>
                                    <input type="text" style="visibility: hidden;display:inline;width:0px;position:absolute;" value="" id="appointment_date_input" name="appointment_date">
                                    <div id="appointment_date_error" class="invalid-feedback text-center"></div>

                                    <!-- Time Slots -->
                                    <div class="box-card">
                                        <div class="time-header">
                                            <h4 id="selected_date_text">Select a date</h4>
                                            <span id="slot_count"></span>
                                        </div>
                                        <div id="time_slots" class="slot-grid" style="display:none;">
                                            <div class="slot-btn">8:00 AM</div>
                                            <div class="slot-btn">9:00 AM</div>
                                            <div class="slot-btn">10:00 AM</div>
                                            <div class="slot-btn">11:00 AM</div>
                                            <div class="slot-btn">12:00 PM</div>
                                            <div class="slot-btn">1:00 PM</div>
                                            <div class="slot-btn">2:00 PM</div>
                                            <div class="slot-btn">3:00 PM</div>
                                        </div>
                                    </div>
                                    <input type="text" style="visibility: hidden;display:none;width:0px;position:absolute;" value="" id="appointment_time_input" name="appointment_time">
                                    <div id="appointment_time_error" class="invalid-feedback text-center"></div>
                                </div>
                                <!-- STEP 1: END -->

                                <!-- STEP 2: START -->
                                <div class="step-section step well" style="display: none;" id="step4">
                                    <div class="about-car-content financical-d-content">
                                        <h4>Your Contact Information</h4>
                                        <div class="mt-3 form-info-com">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control mb-3" placeholder="First Name" name="first_name" id="first_name" value="">
                                                    <div id="first_name_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control mb-3" name="last_name" id="last_name" placeholder="Last Name" value="">
                                                    <div id="last_name_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="">
                                                    <div id="email_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="tel" class="form-control" placeholder="Phone Number" name="phone" id="tel" value="">
                                                    <div id="phone_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                            </div>
                                            <div class="checkbox-block link-set service-check-text">
                                                <input type="hidden" name="source" value="">
                                                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                                <input type="hidden" name="source" value="Book An Appointment">
                                                <input type="hidden" name="source_url" value="">
                                                <p><input type="checkbox" name="sms_consent" checked="">By providing my phone number, I consent to be contacted by Cardora and its affiliated service partners. Cardora may contact me via phone call, email, or text message (including automated systems) for appointment confirmations, service updates, and marketing communications. Standard message and data rates may apply. Consent is not a condition of purchase. You may opt out of text messages at any time by replying STOP. </p>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12 col-xs-12 text-center">
                                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                                    <div class="invalid-feedback" id="recaptcha-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- STEP 2: END -->
                            </div>
                            <div class="step-footer">
                                <a type="submit" class="action back btn btn-info text-uppercase" style="display: none;">← BACK</a>
                                <a class="action next btn btn-info text-uppercase" style="display: block;">SELECT</a>
                                <button type="submit" class="action submit btn btn-success text-uppercase" style="display: none;">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
            //        btnskip     = $(".skip");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
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
                },
                appointment_date: "required",
                appointment_time: "required"
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
                    number: "Phone Number should be a Number",
                    minlength: "Your Phone Number must be 10 characters long"
                },
                appointment_date: "Select the desired appointment date",
                appointment_time: "Select the desired appointment time"

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
                    let cleansed_id = element.attr("name");
                    cleansed_id = cleansed_id.replace("[]", "");
                    $("#" + cleansed_id + "_error").text("");
                    error.appendTo("#" + cleansed_id + "_error");
                    $("#" + cleansed_id + "_error").addClass("d-block");
                    $("#" + cleansed_id + "_error").find(".is-invalid").addClass("h5");

                    parent.postMessage({
                        type: "css",
                        value: $("body").outerHeight(true),
                        element_id: "book_an_appointment"
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
                        data: new FormData(document.getElementById('lead_form')),
                        contentType: false,
                        processData: false,
                        success: function(result) {
                            BrowserStorage.clear();

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");

                            setTimeout(() => {

                                // Fire A Close Alert and Than Navigation To ThankYou Page.
                                parent.postMessage("alertClose", "*");
                                parent.postMessage("redirectToAlternateThankYouPage", "*");

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
                    value: $("body").outerHeight(true),
                    element_id: "book_an_appointment"
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
                    element_id: "book_an_appointment"
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
    <script>
        /* Calendar Setup */
        flatpickr("#calendar", {
            inline: true,
            minDate: "today",
            maxDate: new Date().fp_incr(60),

            disable: [
                function(date) {
                    return date.getDay() === 0; // Sunday Disabled
                }
            ],

            onChange: function(selectedDates, dateStr) {
                console.log(dateStr);

                document.getElementById("selected_date_text").innerText =
                    new Date(dateStr).toDateString();

                document.getElementById("appointment_date_input").value = new Intl.DateTimeFormat('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                }).format(new Date(dateStr));

                document.getElementById("slot_count").innerText =
                    "8 available times";

                document.getElementById("time_slots").style.display = "grid";
                document.getElementById("appointment_time_input").style.display = "inline";

                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "book_an_appointment"
                }, "*");
            }
        });

        /* Time Slot Selection */
        document.querySelectorAll(".slot-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                document.querySelectorAll(".slot-btn").forEach(b =>
                    b.classList.remove("active")
                );
                this.classList.add("active");
                document.getElementById("appointment_time_input").value = this.innerText;

                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "book_an_appointment"
                }, "*");
            });
        });
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "book_an_appointment"
            }, "*");
        });
    </script>

</body>