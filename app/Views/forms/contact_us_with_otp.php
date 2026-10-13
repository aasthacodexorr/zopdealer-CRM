<!DOCTYPE html>
<html>

<head>
    <title>Contact Us | Zop Dealer</title>
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

    <?php if (!empty($custom_less)) {
        $custom_less_urls = explode(',', $custom_less);
        foreach ($custom_less_urls as $each_url) { ?>
            <link rel="stylesheet/less" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>

    <style>
        button[type="submit"]:disabled {
            cursor: not-allowed;
        }

        input[name="otp"]:disabled {
            cursor: not-allowed;
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
                                    <p><strong>Email</strong></p>
                                    <input type="email" name="email" class="form-control" placeholder="" id="zop-email" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mobile</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="zop-phone-number" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Message</strong></p>
                                    <textarea rows="5" class="form-control" name="message" placeholder="" id="zop-message"></textarea>
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
                            <div class="col-md-3">
                                <div class="p-top-20">
                                    <p><strong>OTP (One Time Passcode)</strong></p>
                                    <input type="text" disabled name="otp" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                    <div id="request_otp_message"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding d-none">
                            <input type="hidden" name="source" value="<?php echo $source; ?>">
                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                            <input type="hidden" name="source_url" value="">
                            <input type="hidden" name="hash" id="hash" value="">
                            <div class="hero-btn p-top-20 p-bottom-50">
                                <button type="hidden" style="padding:0px;"></button>
                            </div>
                        </div>
                    </form>
                    <button type="submit" id="request_otp" disabled class="btn btn-primary mt-1">Request OTP</button>
                    <br>
                    <button type="submit" id="submit-button" class="serach-btn d-none mt-1">
                        <a style="padding:16px 60px;color:white !important;">Submit</a></button>
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
                element_id: "contact_us"
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
                    otp: {
                        required: true,
                        minlength: 4,
                        maxlength: 4,
                        number: true
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
                    if (element.is(":radio")) {
                        error.appendTo(element('.invalid-feedback'));
                    } else { // This is the default behavior 
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    $("input").removeClass("is-invalid");
                    $(".is-invalid").removeClass("is-invalid");
                    $("#submit-button").prop("disabled", true);

                    //Fire an Alert.
                    parent.postMessage("alertFire", "*");

                    var form = $(form);
                    var disabled = form.find(':input:disabled').removeAttr('disabled');
                    var serialized = form.serialize();
                    disabled.attr('disabled', 'disabled');

                    $.ajax({
                        url: window.location.origin + '/api/leads/responses/create',
                        type: "POST",
                        data: serialized, // get all form variables
                        success: function(result) {

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");

                            $('form#lead_form').each(function() {
                                this.reset();
                            });

                            setTimeout(() => {

                                // Fire A Close Alert and Than Navigation To ThankYou Page.
                                parent.postMessage("alertClose", "*");
                                parent.postMessage("redirectToThankYouPage", "*");
                                window.location.href = window.location.origin + '/thankyou.html';

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

                            $("#submit-button").prop("disabled", false);
                        }
                    });
                }
            });
            // END Form Validation
        });

        function formatPhoneNumber(phoneNumberString) {
            var cleaned = ('' + phoneNumberString).replace(/\D/g, '');
            var match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                return '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
            return null;
        }

        function OTPFunctionality() {
            const phone_no = $("#zop-phone-number").val().replace(/\D+/g, '');
            const request_button = $("#request_otp");
            const otp_input = $("input[name='otp']");
            if (phone_no.length === 10) {
                request_button.prop("disabled", false);
                otp_input.prop("disabled", false);
                $("#hash").val("");
                $("#submit-button").addClass("d-none");
                //Enable OTP Fields
            } else {
                //Disable OTP Fields
                request_button.prop("disabled", true);
                otp_input.prop("disabled", true);
                $("#hash").val("");
                $("#submit-button").addClass("d-none");
            }
        }

        $("#zop-phone-number").on("keyup", () => {
            OTPFunctionality();
        });

        OTPFunctionality();

        $("#submit-button").on("click", () => {
            $("#lead_form").trigger("submit");
        });

        $("#request_otp").on("click", () => {

            const phone_dom = $("#zop-phone-number");
            const phone = phone_dom.val().replace(/\D+/g, '');
            const masked_phone_no = formatPhoneNumber(phone);
            phone_dom.prop("disabled", true);
            $("#request_otp_message").addClass("alert").addClass("alert-primary");
            $("#request_otp_message").html(`Sending OTP to <b> ${masked_phone_no} </b> ...`);

            $.ajax({
                url: window.location.origin + '/api/services/one-time-password/request',
                type: "POST",
                data: {
                    "phone": phone
                },
                success: (result) => {
                    $("#hash").val(result.hash);
                    $("#request_otp_message").addClass("mt-1").addClass("alert").removeClass("alert-info").addClass("alert-success");
                    $("#request_otp_message").html(`OTP (One Time Passcode) has been sent to <b> +1 ${masked_phone_no} </b>. Please enter the OTP in the field above.`);
                    $("#submit-button").removeClass("d-none");
                },
                error: (error) => {
                    $("#request_otp_message").addClass("mt-1").addClass("alert").removeClass("alert-info").addClass("alert-success");
                    $("#request_otp_message").html(`OTP (One Time Passcode) already sent to <b> +1 ${masked_phone_no} </b>. Please wait for 3 minutes to send another request.`);
                    $("#submit-button").removeClass("d-none");
                }

            });
        });
    </script>

</body>

<!---- BODY END ---->

</html>