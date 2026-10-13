<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Nation - Used Car Dealership</title>
    <!-- STYLESHEET -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        a {
            color: #000;
            font-family: "Roboto Flex", serif;
        }

        a,
        a:hover {
            color: #00af66;
            text-decoration: none;
        }

        .hero-form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .small-size {
            font-size: 12px;
        }

        p.time-table {
            margin-bottom: 2px;
            color: #000;
        }

        .contact-info {
            max-width: 470px;
        }

        p.time-table strong {
            color: #000;
        }

        .contact-form .form-control {
            padding: 0.5rem .75rem;
        }

        .btn.btn-primary {
            color: #f5f5f5;
            background-color: #00af66;
            border-color: #00af66;
        }

        .btn.btn-primary:hover {
            opacity: 0.8;
        }
    </style>

    <style>
        .form-control.error {
            border-color: red;
        }

        .form-control.error~label {
            font-size: 12px;
            color: red;
            margin: 0px;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

    <div class="hero-form-container">
        <form class="needs-validation" id="lead_form">
            <div class="contact-form">
                <h3 class="mb-1">Apply Today!</h3>
                <p class="mt-0">Write your personal information below.</p>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" name="first_name" id="firstName" placeholder="First Name">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" name="last_name" id="lastName" placeholder="Last Name">
                        <div class="invalid-feedback"></div>

                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        <div class="invalid-feedback"></div>

                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone Number">
                        <div class="invalid-feedback"></div>

                    </div>
                    <div class="col-md-12 mb-3">
                        <select class="form-control" name="vehicle_interested" id="vehicle-interested">
                            <option value="" selected disabled>Vehicle You Are Interested In</option>
                            <option value="Car">Car</option>
                            <option value="SUV">SUV</option>
                            <option value="Van">Van</option>
                            <option value="Truck">Truck</option>
                        </select>
                        <div class="invalid-feedback"></div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 p-top-20">
                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                        <div class="invalid-feedback" id="recaptcha-error"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input"
                            id="resident_status" name="resident_status" value="I am a Canadian citizen or a permanent resident."
                            type="checkbox">
                        <label class="form-check-label small-size" for="resident_status">
                            I am a Canadian citizen or a permanent resident.
                        </label>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input"
                            id="terms_and_conditions" name="terms_and_conditions" value="I agree to terms and conditions."
                            type="checkbox">
                        <label class="form-check-label small-size" for="terms_and_conditions">
                            I agree to the <a href="#">Terms & Conditions</a>
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            id="consent_for_communication" name="consent_for_communication" value="I provide my consent for communication."
                            type="checkbox">
                        <label class="form-check-label small-size" for="consent_for_communication">
                            I agree to receive SMS communications at the phone number provided.
                        </label>
                    </div>
                </div>
                <input type="hidden" name="source" value="<?php echo $source; ?>">
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                <input type="hidden" name="source_url" value="">
                <button type="submit" class="btn btn-primary w-100">Get Approved</button>
            </div>
        </form>
    </div>


    <!---- JAVA SCRIPT FILE ----->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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

            parent.postMessage({
                type: "css",
                value: $("#lead_form").outerHeight(true) + 20,
                element_id: "credit_application"
            }, "*");

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
                    vehicle_interested: {
                        required: true
                    },
                    resident_status: {
                        required: true
                    },
                    terms_and_conditions: {
                        required: true
                    },
                    consent_for_communication: {
                        required: true
                    }
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
                    }
                },
                errorPlacement: function(error, element) {
                    console.log(element);
                    if (element.is(":radio") || element.is(":checkbox")) {
                        $('[name="' + element.attr("name") + '"]').addClass('is-invalid');
                    } else { // This is the default behavior 
                        error.insertAfter(element);
                    }

                    parent.postMessage({
                        type: "css",
                        value: $("#lead_form").outerHeight(true) + 20,
                        element_id: "credit_application"
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

                            parent.postMessage({
                                type: "css",
                                value: $("#lead_form").outerHeight(true) + 20,
                                element_id: "credit_application"
                            }, "*");
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

                            parent.postMessage({
                                type: "css",
                                value: $("#lead_form").outerHeight(true) + 20,
                                element_id: "credit_application"
                            }, "*");
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

            parent.postMessage({
                type: "css",
                value: $("#lead_form").outerHeight(true) + 20,
                element_id: "credit_application"
            }, "*");
        });
    </script>
</body>

<!---- BODY END ---->

</html>