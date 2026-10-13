<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="origin-trial" content="Az520Inasey3TAyqLyojQa8MnmCALSEU29yQFW8dePZ7xQTvSt73pHazLFTK5f7SyLUJSo2uKLesEtEa9aUYcgMAAACPeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZS5jb206NDQzIiwiZmVhdHVyZSI6IkRpc2FibGVUaGlyZFBhcnR5U3RvcmFnZVBhcnRpdGlvbmluZyIsImV4cGlyeSI6MTcyNTQwNzk5OSwiaXNTdWJkb21haW4iOnRydWUsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=">
    <title>Trade In Appraisal | Zop Dealer</title>
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
                <!--Financial Services Starts-->
                <div class="col-md-12">
                    <div class="form-layout">
                        <div class="offset-md-1 col-md-10">
                            <div class="list">
                                <div class="step well" style="display:block;">
                                    <div class="head-text-f">
                                        <div class="col-md-12">
                                            <div class="set-box">
                                                <h3>Expected Value</h3>
                                                <div class="col-md-12 col-xs-12">
                                                    <div class="head-felid1">
                                                        <input type="text" class="form-control" name="expected_value" id="expected_value" placeholder="Expected Value">
                                                        <div id="expected_value_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-xs-12">
                                                    <input type="hidden" name="source" value="<?php echo $source; ?>">
                                                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                                    <input type="hidden" name="source_url" value="">
                                                    <input type="hidden" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                                    <input type="hidden" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                                    <input type="hidden" class="form-control" name="email" id="email" placeholder="Email">
                                                    <input type="hidden" class="form-control" name="phone" id="phone" placeholder="Phone">
                                                    <div class="link-set">
                                                    <p>Please provide an estimated value you believe your vehicle is worth. This information is used for preliminary evaluation purposes only and will assist our team in assessing your vehicle. Final appraisal is subject to in-person inspection and verification.</p>
                                                    </div>
                                                    <div class="link-set">
                                                        <p>I have read and accept the <a href="https://www.yvrautogroup.com/terms-conditions">Terms of Use</a> . I also acknowledge having read the <a href="https://www.yvrautogroup.com/privacy-policy/">Privacy Policy</a> and agree to the terms herein.</p>
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
                                </div>
                                <div class="col-md-12 left-content-list">
                                    <a type="submit" class="action back btn btn-info" style="display: none;">Back</a>
                                    <a class="action next btn btn-info" style="display: block;">Continue</a>
                                    <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                                </div>
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

    <script>
        var BrowserStorage = (function() {
            /**
             * Whether the current browser supports local storage as a way of storing data
             * @var {Boolean}
             */
            var _hasLocalStorageSupport = (function() {
                try {
                    return 'localStorage' in window && window['localStorage'] !== null;
                } catch (e) {
                    return false;
                }
            })();

            /**
             * @param {String} name The name of the property to read from this document's cookies
             * @return {?String} The specified cookie property's value (or null if it has not been set)
             */
            var _readCookie = function(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }

                return null;
            };

            /**
             * @param {String} name The name of the property to set by writing to a cookie
             * @param {String} value The value to use when setting the specified property
             * @param {int} [days] The number of days until the storage of this item expires
             */
            var _writeCookie = function(name, value, days) {
                var expiration = (function() {
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        return "; expires=" + date.toGMTString();
                    } else {
                        return "";
                    }
                })();

                document.cookie = name + "=" + value + expiration + "; path=/";
            };

            return {
                /**
                 * @param {String} name The name of the property to set
                 * @param {String} value The value to use when setting the specified property
                 * @param {int} [days] The number of days until the storage of this item expires (if storage of the provided item must fallback to using cookies)
                 */
                set: function(name, value, days) {
                    _hasLocalStorageSupport
                        ?
                        localStorage.setItem(name, value) :
                        _writeCookie(`localstorage_${name}`, value, days);
                },

                /**
                 * @param {String} name The name of the value to retrieve
                 * @return {?String} The value of the 
                 */
                get: function(name) {
                    return _hasLocalStorageSupport ?
                        localStorage.getItem(name) :
                        _readCookie(`localstorage_${name}`);
                },

                /**
                 * @param {String} name The name of the value to delete/remove from storage
                 */
                remove: function(name) {
                    _hasLocalStorageSupport
                        ?
                        localStorage.removeItem(name) :
                        this.set(`localstorage_${name}`, "", -1);
                },

                /**
                 *  Clears the localStorage if supported.
                 */
                clear: function() {
                    _hasLocalStorageSupport
                        ?
                        localStorage.clear() :
                        null;
                }
            };
        })();
    </script>

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
                element_id: "trade_in_appraisal"
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
                // trim: "required",
                vin: {
                    required: true,
                    minlength: 17,
                    maxlength: 17,
                },
                odometer: {
                    required: true,
                    number: true
                },
                transmission_type: "required",
                vehicle_outright: "required",
                sole_owner_vehicle: "required",
                vehicle_colour: "required",
                selling_or_trading: "required",
                "additional_features[]": "required",
                "exterior_damage[]": "required",
                number_of_keys: "required",
                smoke_in_vehicle: "required",
                original_factory_rims: "required",
                tires: "required",
                prevent_driving_vehicle: "required",
                accident_or_insurance: "required",
                mechanical_issue: "required",
                disclosures: "required",
                condition_of_vehicle: "required",
                plan_to_sell: "required",
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
                vin: {
                    required: "Please Enter VIN",
                    minlength: "VIN must be 17 characters long",
                },
                odometer: {
                    required: "Please Enter Odometer",
                    number: "Odometer should be a Number"
                },
                transmission_type: "Please Select Transmission Type",
                vehicle_outright: "Please Select Vehicle Outright",
                sole_owner_vehicle: "Please Select Sole Owner",
                vehicle_colour: "Please Select Vehicle Colour",
                selling_or_trading: "Please Select Selling or Trading",
                "additional_features[]": "Please Select Additional Features",
                "exterior_damage[]": "Please Select Exterior Damage",
                number_of_keys: "Please Select Number of Keys",
                smoke_in_vehicle: "Please Select whether you Smoke in Vehicle",
                original_factory_rims: "Please Select whether vehicle has Original Rims",
                tires: "Please Select whether Tires replaced in 12 Months",
                prevent_driving_vehicle: "Please Select whether anything preventing from driving vehicle",
                accident_or_insurance: "Please Select whether vehicle involved in Accident or Insurance Claim",
                mechanical_issue: "Please Select whether vehicle has Mechanical Issue",
                disclosures: "Please Select is there any Disclosure",
                condition_of_vehicle: "Please Select the Condition of Vehicle",
                plan_to_sell: "Please Select when planning to sell the Vehicle",
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
                        data: form.serialize(), // get all form variables
                        success: function(result) {
                            BrowserStorage.clear();

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");
                            parent.postMessage("alertClose", "*");

                            $('form#lead_form').each(function() {
                                this.reset();
                            });

                            parent.postMessage("redirectToDifferentPage", "*");
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
                    element_id: "trade_in_appraisal"
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
                    element_id: "trade_in_appraisal"
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

        $('#vehicle-outright-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.remaining-loan').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-financed').change(function() {
            if ($(this).is(":checked")) {
                $('.remaining-loan').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-leased').change(function() {
            if ($(this).is(":checked")) {
                $('.remaining-loan').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-window_tint').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-second_set_of_tires').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-pickup_truck_bed_tonneau_cover').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-tow_hitch').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-roof_rack').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-remote_starter').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_rims').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_stereo').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_exhaust').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_suspension').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_spoiler').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_cosmetic_modifications').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_performance_upgrades').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-other').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#accident-insurance-vehicle-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.total-amount').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#accident-insurance-vehicle-no').change(function() {
            if ($(this).is(":checked")) {
                $('.total-amount').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#mechanical-issue-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.issue-mechanical').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#mechanical-issue-no').change(function() {
            if ($(this).is(":checked")) {
                $('.issue-mechanical').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#disclosures-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.other-disclosure').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#disclosures-no').change(function() {
            if ($(this).is(":checked")) {
                $('.other-disclosure').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(".step.well:visible").outerHeight(true) + 200,
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $(`
        #vehicle-outright-yes,
        #vehicle-outright-financed,
        #vehicle-outright-leased
        `).trigger('change');

        $(`
        #trade-additional-features-window_tint,
        #trade-additional-features-second_set_of_tires,
        #trade-additional-features-pickup_truck_bed_tonneau_cover,
        #trade-additional-features-tow_hitch,
        #trade-additional-features-roof_rack,
        #trade-additional-features-remote_starter,
        #trade-additional-features-aftermarket_rims,
        #trade-additional-features-aftermarket_stereo,
        #trade-additional-features-aftermarket_exhaust,
        #trade-additional-features-aftermarket_suspension,
        #trade-additional-features-aftermarket_spoiler,
        #trade-additional-features-aftermarket_cosmetic_modifications,
        #trade-additional-features-aftermarket_performance_upgrades,
        #trade-additional-features-other
        `).trigger('change');

        $(`
        #accident-insurance-vehicle-yes,
        #accident-insurance-vehicle-no
        `).trigger('change');

        $(`
        #mechanical-issue-yes,
        #mechanical-issue-no
        `).trigger('change');

        $(`
        #disclosures-yes,
        #disclosures-no
        `).trigger('change');
    </script>
    <script src="/dist/assets/js/autoTradeInDropdown.js"></script>

</body>