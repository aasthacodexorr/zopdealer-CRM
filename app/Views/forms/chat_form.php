<!DOCTYPE html>
<html>

<head>
    <title>SMS Request | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
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
    <style>
        /*-- FIX CHAT BOX START --*/
        .zd-container .q-sb-form {
            background-color: #fff;
            padding: 35px;
            bottom: 130px;
            right: 40px;
            max-width: 340px;
            box-shadow: 0 0px 9px rgb(0 0 0 / 20%);
            z-index: 99999;
            border-radius: 5px;
        }

        .zd-container .q-sb-form .q-title-box h2 {
            color: #000;
            font-weight: 900;
            margin-top: 10px;
            text-align: center;
            font-size: 18px;
        }

        .zd-container .q-sb-form .q-title-box p {
            margin-top: 10px;
            color: #5c5c5c;
            font-size: 16px;
            text-align: center;
            max-width: 300px;
        }

        .zd-container .q-form-feild input.form-control {
            border: none;
            border-radius: 50px;
            background-color: #ecf0f1;
            width: 100%;
            height: 40px;
            color: #636e72;
            padding-left: 15px;
            font-size: 15px;
            box-sizing: border-box;
            outline: 0;
            box-shadow: none;
        }

        .zd-container .q-form-feild .first-feild {
            padding-bottom: 15px;
        }

        .zd-container .q-form-feild .first-feild p {
            margin-top: 40px;
            color: #5c5c5c;
            font-size: 11px;
            text-align: center;
            line-height: 1.5;
            max-width: 300px;
        }

        .zd-container .q-form-feild .q-sub-btn input.sub-btn {
            border: 2px solid #60a9ff;
            border-radius: 50px;
            background-color: #60a9ff;
            color: #fff;
            width: 100%;
            height: 40px;
            font-size: 15px;
        }

        .zd-container .q-sb-form {}

        .zd-container .q-sb-form.main {
            display: block;
        }

        /*-- FIX CHAT BOX END --*/

        /** ERROR SECTION : START */
        .zd-container .q-sb-form .error {
            font-size: 80%;
            color: #FF0039;

        }

        .zd-container .q-sb-form .invalid-feedback {
            font-size: 80%;
            color: #FF0039;
        }

        /** ERROR SECTION : END  */
    </style>
</head>

<!--  BODY START  -->

<body class="zd-container">

    <form class="q-sb-form" class="needs-validation" id="lead_form">
        <div class="q-title-box">
            <h2>Let's chat over text!</h2>
            <p>Please provide your details below and we'll be in touch via sms soon.</p>
        </div>
        <div class="q-form-feild">
            <div class="first-feild">
                <input type="text" name="first_name" placeholder="Name *" class="form-control" />
                <div class="invalid-feedback">
                </div>
            </div>
            <div class="first-feild">
                <input type="tel" name="phone" placeholder="Cell Phone (10 Digits)*" class="form-control" />
                <div class="invalid-feedback">
                </div>
            </div>
            <div class="first-feild">
                <input type="text" placeholder="Message" class="form-control" />
                <div class="invalid-feedback">
                </div>
            </div>
            <div class="first-feild">
                <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                <div class="invalid-feedback" id="recaptcha-error"></div>
            </div>
            <div class="first-feild">
                <p>By submitting your details, you agree to receive text messages at the provided phone number.
                    Message & data rates may apply.</p>
            </div>
            <div class="q-sub-btn">
                <input type="hidden" name="email" value="noname@zopdealer.com">
                <input type="hidden" name="source" value="<?php echo $source; ?>">
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                <input type="hidden" name="source_url" value="">
                <input type="submit" value="Submit" class="sub-btn" style="cursor: pointer;" />
            </div>
        </div>
    </form>
    </div>
    </div>
    </div>

    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/custom_forms/js/bootstrap.min.js"></script>
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
                element_id: "chat_form"
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
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    }
                },
                messages: {
                    first_name: "Please Enter Your Name",

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

                    //Fire an Alert.
                    var alert = Swal.mixin({
                        showConfirmButton: false,
                        timerProgressBar: true,
                        confirmButtonText: "",
                        title: "Sending your message...",
                        onBeforeOpen: () => {
                            alert.showLoading();
                        },
                    });

                    alert.fire();

                    var form = $(form);
                    $.ajax({
                        url: window.location.origin + '/api/leads/responses/create',
                        type: "POST",
                        data: form.serialize(), // get all form variables
                        success: function(result) {

                            alert.update({
                                title: 'Thank You! \n Your message has been sent.',
                                icon: "success",
                            });

                            setTimeout(() => {
                                alert.close()
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


                                alert.update({
                                    title: 'There were errors in submitting the form.',
                                    icon: "error",
                                });

                                setTimeout(() => {
                                    alert.close()
                                }, 1000);

                            } else {

                                //Fire An Error Update Alert

                                $("#recaptcha-error").text(errors);
                                document.getElementById("recaptcha-error").scrollIntoView({
                                    behavior: 'smooth'
                                });
                                $(".g-recaptcha").addClass('is-invalid');


                                alert.update({
                                    title: errors,
                                    icon: "error",
                                });

                                setTimeout(() => {
                                    alert.close()
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