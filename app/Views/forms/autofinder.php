<!DOCTYPE html>
<html>

<head>
    <title>Autofinder | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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

    <div class="container">
        <form class="needs-validation" id="lead_form">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="head-text">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-30">
                                <label><strong> Vehicle You Need </strong></label>
                                <div class="border-bottom"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Year <sup class="text-danger">*</sup></strong></p>
                                <select id="autofinder-year" name="year" placeholder="Select" class="form-control">
                                    <option disabled="disabled" selected value="">-- Select Year --</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Make <sup class="text-danger">*</sup></strong></p>
                                <select id="autofinder-make" name="make" placeholder="Select" class="form-control">
                                    <option disabled="disabled" selected="" value="">-- Select Make --</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Model <sup class="text-danger">*</sup></strong></p>
                                <input type="text" name="model" class="form-control" placeholder="" id="autofinder-model" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Purchase Time Frame</strong></p>
                                <input type="text" name="purchase_time_frame" class="form-control" placeholder="" id="autofinder-purchase_time_frame">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Price</strong></p>
                                <input type="text" name="price" class="form-control" placeholder="" id="autofinder-price">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="head-text">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-30">
                                <label><strong> Personal Information </strong></label>
                                <div class="border-bottom"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>First Name <sup class="text-danger">*</sup></strong></p>
                                <input type="text" class="form-control" name="first_name" placeholder="" id="autofinder-first-name" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Last Name <sup class="text-danger">*</sup></strong></p>
                                <input type="text" class="form-control" name="last_name" placeholder="" id="autofinder-last-name" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Email <sup class="text-danger">*</sup></strong></p>
                                <input type="email" class="form-control" name="email" placeholder="" id="autofinder-email" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Phone <sup class="text-danger">*</sup></strong></p>
                                <input type="tel" class="form-control" name="phone" placeholder="" id="autofinder-phone-number" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="p-top-20">
                                <p><strong>Comment</strong></p>
                                <textarea rows="2" class="form-control" placeholder="" id="comment"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                        <div class="invalid-feedback" id="recaptcha-error"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="p-top-20">
                            <?php echo $disclosure; ?>
                        </div>
                    </div>
                </div>
                <div class="checkbox-block">
                    <input type="checkbox" name="sms_consent" checked>
                    By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us.
                </div>
                <div class="hero-btn p-top-20 p-bottom-50">
                    <input type="hidden" name="source" value="<?php echo $source; ?>">
                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                    <input type="hidden" name="source_url" value="">

                    <button type="submit" class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;">Submit</a></button>
                </div>
            </div>
        </form>
    </div>

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
                element_id: "autofinder_form"
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

            // START Form Validation
            $("#lead_form").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    //                email: "required", 
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    year: {
                        required: true,
                        min: 1970,
                        max: 2024,
                        number: true
                    },
                    make: "required",
                    model: "required",
                    price: {
                        required: false,
                        min: 0,
                        number: true
                    },


                },
                messages: {
                    first_name: "Please Enter Your First Name",
                    last_name: "Please Enter Your Last Name",
                    //                email: "Please Enter Your Email",
                    email: {
                        required: "Please Enter Your Email",
                        email: "Please Enter a valid Email"
                    },
                    year: {
                        required: "Please Enter Year Of The Car",
                        number: "Year should be a Number",
                    },
                    phone: {
                        required: "Please Enter Your Phone Number",
                        number: "Phone Number should be a Number",
                        minlength: "Your Phone Number must be 10 characters long"
                    },
                    make: "Please Enter Make Of The Car",
                    model: "Please Enter Model Of The Car",
                    price: {
                        required: "Please Enter Price",
                        number: "Price should be Number"

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
                                        //var va = errors[key];
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