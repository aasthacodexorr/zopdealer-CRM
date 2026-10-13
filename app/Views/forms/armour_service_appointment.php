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
    <!-- <link rel="stylesheet" type="text/css" href="/custom_forms/style.css"> -->
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
                        <!-- <h3>Schedule Service</h3> -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Vehicle Info</strong></label>
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
                                    <label>2. <strong>Requested Service(s)</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="request_service_section">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Oil Change" /><span class="left-sp">Oil Change</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Tire Rotation" /><span class="left-sp">Tire Rotation</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="requested_service[]" value="Tire Balance" /><span class="left-sp">Tire Balance</span></p>
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
                                    <p><input type="checkbox" name="requested_service[]" value="Vehicle Checkup / Inspection" /><span class="left-sp">Vehicle Checkup / Inspection</span></p>
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
                                    <label>3. <strong>Personal Info & Appointment</strong></label>
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
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><input type="checkbox" id="service-check" name="condition"/><span class="left-sp">By clicking Get Approved, I consent to the collection, use and disclosure of my personal information as described in this paragraph. I agree that the personal information provided above may be used and disclosed by Zop Demo and/or its agents or service providers (collectively, the "Dealer") as necessary to obtain credit, financial and related personal information (including a credit or consumer information report) about me from any credit bureau or credit reporting agency, and to advise me on credit availability in connection with product and/or service purchase financing. I further agree that the personal information provided above may be disclosed to the provider of Dealer’s website hosting or related services for the purpose of enabling Dealer to access my personal information for marketing purposes. Personal information I provide and credit information obtained may also be retained by Dealer and used to facilitate the application process should I subsequently choose to apply for credit through Dealer.</span></p>
                                </div>
                            </div>
                        </div> -->
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