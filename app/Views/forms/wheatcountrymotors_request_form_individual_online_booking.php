<!DOCTYPE html>
<html>

<head>
    <title>Request Form for Individual Online Booking | Zop Dealer</title>
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
    <style>
        .weekend-note {
            font-size: 12px;
            color: #777;
        }
    </style>

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
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Appointment Date</strong></p>
                                    <input type="date" name="appointment_date" class="form-control" placeholder="Appointment Date" id="zop-appointment-date" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Appointment Time</strong></p>
                                    <input type="time" name="appointment_time" class="form-control" placeholder="Appointment Time" id="zop-appointment-time" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Return Date</strong></p>
                                    <input type="date" name="return_date" class="form-control" placeholder="Return Date" id="zop-return-date" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Return Time</strong></p>
                                    <input type="time" name="return_time" class="form-control" placeholder="Return Time" id="zop-return-time" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Destination</strong></p>
                                    <input type="text" name="destination" class="form-control" placeholder="" id="zop-destination" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Company Name</strong></p>
                                    <input type="text" name="company_name" class="form-control" placeholder="" id="zop-company-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>First Name</strong></p>
                                    <input type="text" name="first_name" class="form-control" placeholder="" id="zop-first-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last Name</strong></p>
                                    <input type="text" name="last_name" class="form-control" placeholder="" id="zop-last-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Address</strong></p>
                                    <input name="address" type="text" class="form-control" placeholder="" id="zop-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input name="city" type="text" class="form-control" placeholder="" id="zop-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="province" class="form-control" placeholder="" id="zop-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="postal_code" class="form-control" placeholder="" id="zop-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input type="email" name="email" class="form-control" placeholder="" id="zop-email" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="zop-phone-number" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Requested Vehicle Type</strong></p>
                                    <select id="zop-requested-vehicle-type" name="requested_vehicle_type" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Car">Car</option>
                                        <option value="Mini Van">Mini Van</option>
                                        <option value="Compact SUV">Compact SUV</option>
                                        <option value="Mid Size SUV">Mid Size SUV</option>
                                        <option value="Full Size SUV">Full Size SUV</option>
                                        <option value="Half Ton">Half Ton</option>
                                        <option value="12 Passenger Van">12 Passenger Van</option>
                                        <option value="15 Passenger Van">15 Passenger Van</option>
                                        <option value="Mid Roof">Mid Roof</option>
                                        <option value="12 Passenger High Roof">12 Passenger High Roof</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Provide Copy of Policy for Coverage</strong></p>
                                    <select id="zop-provide-copy-of-policy-for-coverage" name="provide_copy_of_policy_for_coverage" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Additional Driver Name</strong></p>
                                    <input type="text" name="additional_driver_name" class="form-control" placeholder="" id="zop-additional-driver-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Additional Driver Name</strong></p>
                                    <input type="text" name="additional_driver_name_2" class="form-control" placeholder="" id="zop-additional-driver-name-2" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Other Notes</strong></p>
                                    <textarea rows="5" class="form-control" name="other_notes" placeholder="" id="zop-other-notes"></textarea>
                                    <div class="invalid-feedback">
                                    </div>
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
                            <input type="hidden" name="source" value="<?php echo $source; ?>">
                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                            <input type="hidden" name="source_url" value="">
                            <div class="hero-btn p-top-20 p-bottom-50">
                                <button type="submit" class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;color:white !important;">Submit</a></button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>

    <script>
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


            function applyDateRules() {
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                // ✅ Helper: parse local date correctly
                function parseLocalDate(dateString) {
                    if (!dateString) return null;
                    const [year, month, day] = dateString.split("-").map(Number);
                    return new Date(year, month - 1, day);
                }

                // ✅ Helper: format date in local time (YYYY-MM-DD)
                function formatLocalDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, "0");
                    const day = String(date.getDate()).padStart(2, "0");
                    return `${year}-${month}-${day}`;
                }

                // ✅ Public Holidays (Closed)
                const closedDates = [
                    // 2025
                    "2025-11-11", // Remembrance Day
                    "2025-12-25", // Christmas Day
                    "2025-12-26", // Boxing Day

                    // 2026
                    "2026-01-01", // New Year's Day
                    "2026-02-16", // Family Day
                    "2026-04-03", // Good Friday
                    "2026-05-18", // Victoria Day
                    "2026-07-01", // Canada Day
                    "2026-08-03", // Saskatchewan Day
                    "2026-09-07", // Labour Day
                    "2026-10-12", // Thanksgiving
                    "2026-11-11", // Remembrance Day
                    "2026-12-25", // Christmas Day
                    "2026-12-26", // Boxing Day
                ];

                // ✅ Check if date is closed (weekend or holiday)
                function isClosed(date) {
                    const day = date.getDay(); // 0 = Sunday, 6 = Saturday
                    const ymd = formatLocalDate(date);
                    return day === 0 || day === 6 || closedDates.includes(ymd);
                }

                // ✅ Reusable closed popup
                function showClosedPopup(input) {
                    Swal.fire({
                        icon: "info",
                        title: "Closed",
                        text: "Sorry, we're closed on weekends and public holidays. Please select another date.",
                    });
                    input.val("");
                }

                // Add small note under calendar inputs
                const closedNote = `<small class="text-muted d-block mt-1 weekend-note">* Closed on weekends & public holidays</small>`;
                $("#zop-appointment-date, #zop-return-date").each(function() {
                    if (!$(this).next(".weekend-note").length) {
                        $(this).after(closedNote);
                    }
                });

                // === Appointment Date Validation ===
                $("#zop-appointment-date").on("change", function() {
                    const input = $(this);
                    const selected = parseLocalDate(input.val());
                    if (!selected) return;

                    // Block weekends & holidays
                    if (isClosed(selected)) {
                        showClosedPopup(input);
                        return;
                    }

                    // Call to book for same-day or next-day
                    const diffHours = (selected - today) / 36e5;
                    if (diffHours < 48) {
                        Swal.fire({
                            icon: "info",
                            title: "Call to Book",
                            html: `<p>Need a rental today?</p>
                       <strong>Call us directly at 306-545-5655 — we may be able to help!</strong>`,
                        });
                        input.val("");
                        return;
                    }
                });

                // === Return Date Validation ===
                $("#zop-return-date").on("change", function() {
                    const appointmentVal = $("#zop-appointment-date").val();
                    const returnInput = $(this);
                    const selectedReturn = parseLocalDate(returnInput.val());
                    if (!selectedReturn) return;

                    if (!appointmentVal) {
                        Swal.fire({
                            icon: "info",
                            title: "Select Appointment Date First",
                            text: "Please choose your appointment date before setting a return date.",
                        });
                        returnInput.val("");
                        return;
                    }

                    const appointmentDate = parseLocalDate(appointmentVal);

                    if (selectedReturn <= appointmentDate) {
                        Swal.fire({
                            icon: "warning",
                            title: "Invalid Return Date",
                            text: "Return date must be after your appointment date.",
                        });
                        returnInput.val("");
                        return;
                    }

                    // Block weekends & holidays
                    if (isClosed(selectedReturn)) {
                        showClosedPopup(returnInput);
                        return;
                    }

                    const diffHours = (selectedReturn - today) / 36e5;
                    if (diffHours < 48) {
                        Swal.fire({
                            icon: "info",
                            title: "Call to Book",
                            html: `<p>Need a rental today?</p>
                       <strong>Call us directly at 306-545-5655 — we may be able to help!</strong>`,
                        });
                        returnInput.val("");
                        return;
                    }
                });
            }

            applyDateRules();



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
                        number: true
                    },
                    appointment_date: "required",
                    appointment_time: "required",
                    return_date: "required",
                    return_time: "required",
                    destination: "required",
                    requested_vehicle_type: "required",
                    provide_copy_of_policy_for_coverage: "required",

                },
                messages: {
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
                    appointment_date: "Please Select an Appointment Date",

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

            setTimeout(() => {
                parent.postMessage({
                    type: "css",
                    value: $(document).outerHeight(true),
                    element_id: "contact_us"
                }, "*");
            }, 1000);
        });
    </script>

</body>

<!---- BODY END ---->

</html>