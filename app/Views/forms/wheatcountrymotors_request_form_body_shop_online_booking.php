<!DOCTYPE html>
<html>

<head>
    <title>Request Form for Body Shop Online Booking | Zop Dealer</title>
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
            color: #888;
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
                                    <p><strong>Body Shop/Dealership Name</strong></p>
                                    <input type="text" name="dealership_name" class="form-control" placeholder="" id="zop-dealership-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Estimated Return Date</strong></p>
                                    <input type="date" name="estimated_return_date" class="form-control" placeholder="Estimated Return Date" id="zop-estimated-return-date" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Customer First Name</strong></p>
                                    <input type="text" name="customer_first_name" class="form-control" placeholder="" id="zop-customer-first-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Customer Last Name</strong></p>
                                    <input type="text" name="customer_last_name" class="form-control" placeholder="" id="zop-customer-last-name" />
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
                                    <p><strong>Current Vehicle</strong></p>
                                    <input name="current_vehicle" type="text" class="form-control" placeholder="" id="zop-current-vehicle" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Plate</strong></p>
                                    <input name="plate" type="text" class="form-control" placeholder="" id="zop-plate" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Claim#</strong></p>
                                    <input type="text" name="claim" class="form-control" placeholder="" id="zop-claim" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Adjuster/Team Email</strong></p>
                                    <input type="email" name="team_email" class="form-control" placeholder="" id="zop-team_email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Date of Loss</strong></p>
                                    <input type="date" name="date_of_loss" class="form-control" placeholder="Date of Loss" id="zop-date-of-loss" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Policy#</strong></p>
                                    <input type="text" name="policy" class="form-control" placeholder="" id="zop-policy" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>PO#</strong></p>
                                    <input type="text" name="po" class="form-control" placeholder="" id="zop-po" />
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
                // Utility: parse input date (YYYY-MM-DD) as local (not UTC)
                function parseLocalDate(dateString) {
                    if (!dateString) return null;
                    const [year, month, day] = dateString.split("-").map(Number);
                    return new Date(year, month - 1, day);
                }

                // Format date back to YYYY-MM-DD (for matching)
                function formatLocalDate(date) {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, "0");
                    const d = String(date.getDate()).padStart(2, "0");
                    return `${y}-${m}-${d}`;
                }

                // === CLOSED HOLIDAYS (add any future ones here) ===
                const closedDates = [
                    // 2025
                    "2025-11-11", // Remembrance Day (Tuesday)
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
                    "2026-12-26" // Boxing Day
                ];

                // Check if date is closed (holiday or weekend)
                function isClosed(date) {
                    const day = date.getDay(); // 0 = Sunday, 6 = Saturday
                    const formatted = formatLocalDate(date);
                    return day === 0 || day === 6 || closedDates.includes(formatted);
                }

                // Add “Closed on weekends & holidays” message
                const closedNote = `<small class="text-muted d-block mt-1 weekend-note">
        * Closed on weekends & public holidays
    </small>`;
                $("#zop-appointment-date, #zop-estimated-return-date").each(function() {
                    if (!$(this).next(".weekend-note").length) {
                        $(this).after(closedNote);
                    }
                });

                // === Appointment Date Validation ===
                $("#zop-appointment-date").on("change", function() {
                    const input = $(this);
                    const selected = parseLocalDate(input.val());
                    if (!selected) return;

                    const today = new Date();
                    const tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    const afterTomorrow = new Date();
                    afterTomorrow.setDate(today.getDate() + 2);

                    // --- Block weekends & public holidays ---
                    if (isClosed(selected)) {
                        Swal.fire({
                            icon: "info",
                            title: "Closed",
                            text: "Sorry, we're closed on weekends and public holidays. Please select another date.",
                        });
                        input.val("");
                        return;
                    }

                    // --- Same-day or next-day popup ---
                    if (
                        selected.toDateString() === today.toDateString() ||
                        selected.toDateString() === tomorrow.toDateString()
                    ) {
                        Swal.fire({
                            icon: "info",
                            title: "Call to Book",
                            html: `<p>Need a rental today?</p>
                       <strong>Call us directly at 306-545-5655 — we may be able to help!</strong>`,
                        });
                        input.val("");
                        return;
                    }

                    // --- Enforce 48-hour lead time ---
                    if (selected < afterTomorrow) {
                        Swal.fire({
                            icon: "warning",
                            title: "Unavailable",
                            text: "Please select a date at least 48 hours in advance.",
                        });
                        input.val("");
                        return;
                    }
                });

                // === Estimated Return Date Validation ===
                $("#zop-estimated-return-date").on("change", function() {
                    const returnInput = $(this);
                    const selectedReturn = parseLocalDate(returnInput.val());
                    if (!selectedReturn) return;

                    const appointmentVal = $("#zop-appointment-date").val();
                    if (!appointmentVal) {
                        Swal.fire({
                            icon: "info",
                            title: "Select Appointment Date First",
                            text: "Please choose your appointment date before setting an estimated return date.",
                        });
                        returnInput.val("");
                        return;
                    }

                    const appointmentDate = parseLocalDate(appointmentVal);
                    const today = new Date();
                    const tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    const afterTomorrow = new Date();
                    afterTomorrow.setDate(today.getDate() + 2);

                    // --- Return before appointment ---
                    if (selectedReturn <= appointmentDate) {
                        Swal.fire({
                            icon: "warning",
                            title: "Invalid Return Date",
                            text: "Return date must be after your appointment date.",
                        });
                        returnInput.val("");
                        return;
                    }

                    // --- Block weekends & public holidays ---
                    if (isClosed(selectedReturn)) {
                        Swal.fire({
                            icon: "info",
                            title: "Closed",
                            text: "Sorry, we're closed on weekends and public holidays. Please select another date.",
                        });
                        returnInput.val("");
                        return;
                    }

                    // --- Same-day / next-day popup ---
                    if (
                        selectedReturn.toDateString() === today.toDateString() ||
                        selectedReturn.toDateString() === tomorrow.toDateString()
                    ) {
                        Swal.fire({
                            icon: "info",
                            title: "Call to Book",
                            html: `<p>Need a rental today?</p>
                       <strong>Call us directly at 306-545-5655 — we may be able to help!</strong>`,
                        });
                        returnInput.val("");
                        return;
                    }

                    // --- Enforce 48-hour lead time ---
                    if (selectedReturn < afterTomorrow) {
                        Swal.fire({
                            icon: "warning",
                            title: "Unavailable",
                            text: "Please select a date at least 48 hours in advance.",
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
                    customer_first_name: "required",
                    customer_last_name: "required",

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
                    dealership_name: "required",
                    estimated_return_date: "required",
                    current_vehicle: "required",
                    plate: "required",
                    team_email: {
                        required: true,
                        email: true
                    },

                },
                messages: {
                    customer_first_name: "Please Enter Your First Name",
                    customer_last_name: "Please Enter Your Last Name",

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
                    dealership_name: "Please Enter Dealership Name",
                    estimated_return_date: "Please Select an Estimated Return Date",
                    current_vehicle: "Please Enter Current Vehicle"

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