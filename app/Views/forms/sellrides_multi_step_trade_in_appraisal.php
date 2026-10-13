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
    <!-- <link rel="stylesheet" type="text/css" href="/custom_forms/style.css"> -->
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->

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
            <form class="needs-validation" id="lead_form">
                <!--Financial Services Starts-->
                <div class="col-md-10 col-md-offset-1">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display:block;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <!-- <h3>Get a Car Loan Fast With Any Credit Rating</h3>
                                    <p>Good credit, bad credit & no credit approved!</p> -->
                                    <h3>Vehicle Information</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <select id="autofinder-year" name="year" placeholder="Select" class="form-control">
                                                    <option disabled="disabled" selected value="">-- Select Year --</option>
                                                </select>
                                                <div id="year_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <select id="autofinder-make" name="make" placeholder="Select" class="form-control">
                                                    <option disabled="disabled" selected="" value="">-- Select Make --</option>
                                                </select>
                                                <div id="make_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="model" id="trade-model" placeholder="Model">
                                                <div id="model_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="trim" id="trade-trim" placeholder="Trim">
                                                <div id="trim_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>VIN</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="vin" id="trade-vin" placeholder="Vin">
                                                <div id="vin_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Odometer (KMS)</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="odometer" id="trade-odometer" placeholder="Odometer">
                                                <div id="odometer_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is the condition of your Vehicle?</h3>
                                    <p>Selecting an accurate condition will give you a more accurate estimate.</p>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Excellent" name="vehicle_condition" value="Excellent"><label>Excellent</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Good" name="vehicle_condition" value="Good"><label>Good</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Fair" name="vehicle_condition" value="Fair"><label>Fair</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Poor" name="vehicle_condition" value="Poor"><label>Poor</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="vehicle_condition_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Any Accidents? Accident Amount</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="accident_amount" id="accident_amount" placeholder="Amount">
                                                <div id="accident_amount_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Ownership Status</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Lease" name="financing_option" value="Lease"><label>Lease</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Finance" name="financing_option" value="Finance"><label>Finance</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="trade-Paid-off" name="financing_option" value="Paid Off"><label>Paid Off</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="financing_option_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Additional Notes (Ex. Add-ons, Scratches, Dents etc.)</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <textarea name="additional_notes" rows="8" class="form-control" placeholder="" id="multi-step-additional_notes"></textarea>
                                                <div id="additional_notes_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box row">
                                    <h3>Congratulations your Trade-in Appraisal is ready!</h3>
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
                                    <div class="col-md-12 col-xs-12">
                                        <input type="hidden" name="source" value="<?php echo $source; ?>">
                                        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                        <input type="hidden" name="source_url" value="">

                                        <div class="link-set">
                                            <p>I have read and accept the <a href="https://www.sellrides.ca/terms-conditions/">Terms of Use</a> . I also acknowledge having read the <a href="https://www.sellrides.ca/privacy-policy/">Privacy Policy</a> and agree to the terms herein.</p>
                                        </div>
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
                        <a class="action next btn btn-info" style="display:block;">Continue</a>
                        <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        <a type="submit" class="action back btn btn-info" style="display:block;">Back</a>

                    </div>
                    <div class="safe-and-secure text-center mt-3">
                        <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/icon-bb.png" style="width:25px;padding-right:5px; padding-top: 0px;" />Confidential, Safe and Secure</p>
                        <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/trust-badges-small.webp" class="bottom-img" /> </p>
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
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");


            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                year: {
                    required: true,
                    min: 1960,
                    max: 2023,
                    number: true
                },
                make: "required",
                model: "required",
                trim: "required",
                vin: "required",
                odometer: {
                    required: true,
                    number: true
                },
                vehicle_condition: "required",
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
                trim: "Please Enter the Trim",
                vin: "Please Enter VIN",
                odometer: {
                    required: "Please Enter Odometer",
                    number: "Odometer should be a Number"
                },
                vehicle_condition: "Please Select Your Vehicle's Condition",
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
                    error.appendTo("#" + element.attr("name") + "_error");
                    $("#" + element.attr("name") + "_error").addClass("d-block");
                    $("#" + element.attr("name") + "_error").find(".is-invalid").addClass("h5");

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
            })

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', false);
                }
                hideButtons(current);
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