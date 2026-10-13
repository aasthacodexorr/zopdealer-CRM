<!DOCTYPE html>
<html>

<head>
    <title>Trade-In Appraisal | Zop Dealer</title>
    <meta name="description" content="">
    <meta name="keyword" content="" />


    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/single_credit_application/style.css">
    <!-- STYLESHEET END -->

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .g-recaptcha div {
            margin: auto;
        }

        .swal2-icon {
            font-size: 2em !important;
        }

        .swal2-title {
            font-size: 2em !important;
        }
    </style>

    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->


</head>

<body class="home-01">
    <section class="details-main form-new-set">
        <div class="container">
            <div class="form-layout" id="kbb-estimator" style="margin-top:1em;display:none;">
                <div class="row">
                    <h2 class="kbb-vehicle-title col-xs-12 text-center"></h2>
                    <h5 class="col-xs-12"><b>Mileage <span class="kbb-odometer"></span></b></h5>
                    <p class="col-xs-12">Hello <span class="kbb-customer-name"></span>, <br>
                        We have received your request.<br>
                        Following is the usual price range for a used <span class="kbb-vehicle-title"></span>
                        We may be able to offer you a higher price once we inspect your vehicle. <br>
                    </p>
                    <div class="col-xs-12 col-md-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="kbb-price-block" style="max-width: 300px;margin:auto;">
                                </div>

                            </div>
                        </div>
                    </div>
                    <p class="col-xs-12" style="padding-top:1em">
                        The valuation was generated on <?php echo date('m/d/Y'); ?> and is valued for the next 14 days. Terms & Conditions Apply.
                    </p>
                </div>
            </div>
            <form class="needs-validation" id="lead_form" novalidate="novalidate">
                <!--Financial Services Starts-->
                <div class="col-md-10 col-md-offset-1">
                    <div class="step-count-list">
                        <ul class="step-list-b">
                            <li> Step 1</li>
                            <li> Step 2</li>
                        </ul>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display:block;">
                            <div class="active-color-1"></div>
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Tell us more about your vehicle</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-lable">
                                                <label>Year</label>
                                            </div>
                                            <div class="row">
                                                <select type="text" class="form-control " name="year" id="trade-year" placeholder="Year">
                                                    <option value="" selected>-- Select --</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <input type="hidden" name="make" value="">
                                        <input type="hidden" name="model" value="">
                                        <input type="hidden" name="trim" value="">
                                        <input type="hidden" name="kbb_id" value="">
                                        <li>
                                            <div class="head-lable">
                                                <label>Make</label>
                                            </div>
                                            <div class="row">
                                                <select readonly type="text" class="form-control " name="make_id" id="trade-make" placeholder="Make">
                                                    <option value="" selected>-- Select --</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-lable">
                                                <label>Model</label>
                                            </div>
                                            <div class="row">
                                                <select readonly type="text" class="form-control " name="model_id" id="trade-model" placeholder="Model">
                                                    <option value="" selected>-- Select --</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-lable">
                                                <label>Trim</label>
                                            </div>
                                            <div class="row">
                                                <select readonly type="text" class="form-control " name="trim_id" id="trade-trim" placeholder="Trim">
                                                    <option value="" selected>-- Select --</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="row">
                                                <div class="head-lable">
                                                    <label>Odometer</label>
                                                </div>
                                                <div class="row">
                                                    <div style="position: relative;">
                                                        <span class="right-text" style="bottom:unset;top:1px;"> Kms</span>
                                                        <input type="text" name="odometer" class="form-control" placeholder="" id="trade-odometer" />
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-lable">
                                                <label>Vehicle Condition</label>
                                            </div>
                                            <div class="row">
                                                <select type="text" class="form-control " name="vehicle_condition_id" id="condition-name" placeholder="Vehicle Condition">
                                                    <option value="5">Excellent</option>
                                                    <option value="4">Very Good</option>
                                                    <option selected value="3">Good</option>
                                                    <option value="2">Fair</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="step well" style="display: none;">
                            <div class="active-color-2"></div>
                            <div class="head-text-f list-cont-n">
                                <div class="set-box">
                                    <h3>Let us know where to send your offer</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-lable">
                                                <label>First Name</label>
                                            </div>
                                            <div class="row">
                                                <input name="first_name" type="text" class="form-control" placeholder="" id="trade-first-name" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-lable">
                                                <label>Last Name</label>
                                            </div>
                                            <div class="row">
                                                <input type="text" name="last_name" class="form-control" placeholder="" id="trade-last-name" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-lable">
                                                <label>Email</label>
                                            </div>
                                            <div class="row">
                                                <input type="email" name="email" class="form-control" placeholder="" id="trade-email" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-lable">
                                                <label>Mobile Phone</label>
                                            </div>
                                            <div class="row">
                                                <input type="tel" name="phone" class="form-control" placeholder="" id="trade-phone-number" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li class="col-md-12 col-xs-12">
                                            <input type="hidden" name="source" value="<?php echo $source; ?>">
                                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                            <input type="hidden" name="source_url" value="">
                                            <div class="link-set">
                                                <p>I have read and accept the <a href="#">Terms of Use</a> . I also acknowledge having read the <a href="#">Privacy Policy</a> and agree to the terms herein.</p>
                                            </div>
                                        </li>
                                        <li class="col-md-12 col-xs-12 p-top-20 text-center">
                                            <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                            <div id="recaptcha-error"></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a class="action next btn btn-info" style="display: block;">Next</a>
                        <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        <a type="submit" class="action back btn btn-info" style="display: none;">Back</a>

                    </div>
                </div>
            </form>
        </div>
    </section>


    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="<?php echo getenv('image.cdn') ?>/custom_forms/js/jquery-3.1.1.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="<?php echo getenv('image.cdn') ?>/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>


    <script type="text/javascript">
        function persistInput(input) {
            var key = input.name;
            var storedValue = localStorage.getItem(key);
            $(input).on('change', function() {
                localStorage.setItem(key, $(input).val());

            });
        }

        function loadInput(input) {
            var key = input.name;
            var storedValue = localStorage.getItem(key);
            if (storedValue) {
                $(input).val(storedValue);
            }
        }

        $(document).ready(function() {

            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            document.querySelector(`input[name='source_url'`).value = source_url;

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");

            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");

            // Init buttons and UI
            widget.not(':eq(0)').hide();
            hideButtons(current);
            setProgress(current);

            // Next button click action
            // history.pushState(current, "title", "#"+current)
            btnnext.click(function(event, blank_check_flag = false, check_validations = true) {

                parent.postMessage({
                    type: "css",
                    value: $(".step.well:nth-child(" + current + ")").height() + 400,
                    element_id: "trade_in_appraisal"
                }, "*");

                var validator = $("#lead_form").validate();
                if (validator.form() === false && check_validations) {

                    parent.postMessage({
                        type: "css",
                        value: $(".step.well:nth-child(" + current + ")").height() + 400,
                        element_id: "trade_in_appraisal"
                    }, "*");

                    return false;
                }

                var blank_input = false;
                $(".step.well:nth-child(" + current + ") :input").each(function() {
                    var element_value = undefined;
                    if (this.type == 'radio') {
                        element_value = $("input[name=" + this.name + "]:checked").val();
                    } else {
                        element_value = $(this).val();
                    }

                    // alert(element_value);

                    element_value == undefined || element_value == "" ? blank_input = true : null;
                });

                if (blank_input && blank_check_flag) {
                    Swal.fire({
                        toast: false,
                        position: "center",
                        title: 'Please fill all the inputs.',
                        icon: 'error',
                        timer: 1000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });

                    return false;
                }

                var check_val = 0;

                if (current < widget.length) {
                    widget.show();
                    widget.not(':eq(' + (current++) + ')').hide();
                    setProgress(current);

                    parent.postMessage({
                        type: "css",
                        value: $(".step.well:nth-child(" + current + ")").height() + 400,
                        element_id: "trade_in_appraisal"
                    }, "*");

                    parent.postMessage({
                        type: "scroll_top",
                        value: $(".step.well:nth-child(" + current + ")").height() + 400,
                        element_id: "trade_in_appraisal"
                    }, "*");
                }
                hideButtons(current);
            })

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', [false, false, false]);
                }
                // hideButtons(current);
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
        $(document).ready(function() {

            $('form#lead_form :input').each(function() {
                // loadInput(this); this.name

                var storedValue = localStorage.getItem(this.name);
                if (storedValue) {
                    // input.value = storedValue;
                    // $(this).val(storedValue);
                    if (this.type == 'radio') {
                        $(this).val([storedValue]);
                    } else {
                        $(this).val(storedValue);
                    }

                }

                persistInput(this);
            });

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
                        max: 2023,
                        number: true
                    },
                    make_id: "required",
                    model_id: "required",
                    trim_id: "required",
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
                    odometer: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    year: {
                        required: "Please Enter the Year",
                        number: "Year should be a Number"
                    },
                    make_id: "Please Enter the Make of the Vehicle",
                    model_id: "Please Enter the Model",
                    trim_id: "Please Enter the Trim",
                    vin: "Please Enter VIN",
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
                        error.appendTo(element.parent().parent().parent().next(".invalid-feedback"));
                    } else { // This is the default behavior 
                        error.appendTo(element.next(".invalid-feedback"));
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
                            console.log(result);

                            if (result.hasOwnProperty('price_advisory')) {
                                $("#lead_form").css("display", "none");
                                $("#kbb-price-block").html(result['price_advisory']);
                                $("#kbb-estimator").css("display", "");
                                $(".kbb-customer-name").text($("input[name='first_name']").val());
                                let kbb_year = document.querySelector(`select[name='year']`).value;
                                let kbb_make = document.querySelector(`input[name='make']`).value;
                                let kbb_model = document.querySelector(`input[name='model']`).value;
                                let kbb_trim = document.querySelector(`input[name='trim']`).value;
                                let kbb_odometer = document.querySelector(`input[name='odometer']`).value;
                                $(".kbb-vehicle-title").text(`${kbb_year} ${kbb_make} ${kbb_model} ${kbb_trim}`);
                                $(".kbb-odometer").text(`${kbb_odometer}`);

                            }

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");
                            localStorage.clear();

                            setTimeout(() => {

                                // Fire A Close Alert and Than Navigation To ThankYou Page.
                                parent.postMessage("alertClose", "*");

                                if (!result.hasOwnProperty('price_advisory')) {
                                    parent.postMessage("redirectToThankYouPage", "*");
                                    window.location.href = window.location.origin + '/thankyou.html';
                                }

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

            var $theForm = $("#lead_form"),
                $form = $theForm.validate();
            //changed to check every textbox type input inside #theForm:
            $theForm.on("blur", "keydown", "input", "select", function(e) {
                //use element() to test only one element:
                if ($form.element(this)) {
                    console.log("is valid");
                }
            });
        });
    </script>

    <script>
        /**
         * triggerDropdownOnChange Accepts three arguments column : current selector, filters: initial filters to use and reset: columns to reset
         *
         * @return void
         */
        function triggerDropdownOnChange(column, filters, reset) {
            let domain = window.location.hostname.match(/\w*\.\w*$/gi)[0];

            if (reset.length !== 0) {
                for (const reset_column of reset) {
                    document.querySelectorAll(`select[name='${reset_column}'] option`).forEach(option => option.remove());
                    let reset_dom = document.createElement("option");
                    reset_dom.innerText = `-- Select --`;
                    reset_dom.value = "";
                    document.querySelector(`select[name='${reset_column}']`).appendChild(reset_dom);
                    localStorage.setItem(reset_column, '');
                    reset_dom = null;
                }
            }

            if (column == 'year') {
                $.get(`https://${domain}/api/service/kbb/all/years`, function(data) {

                    if (!data.hasOwnProperty('errors')) {
                        for (const option of data) {
                            let optional_dom = document.createElement("option");
                            optional_dom = document.createElement("option");
                            optional_dom.innerText = option.label;
                            optional_dom.value = option.value;
                            document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                        }

                    }
                });
            }

            if (column == 'make_id') {
                let selected_year = document.querySelector(`select[name='year']`).value;
                $.get(`https://${domain}/api/service/kbb/all/makes/year/${selected_year}`, function(data) {
                    console.log(data);

                    if (!data.hasOwnProperty('errors')) {
                        for (const option of data) {
                            let optional_dom = document.createElement("option");
                            optional_dom = document.createElement("option");
                            optional_dom.innerText = option.label;
                            optional_dom.value = option.value;
                            document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                        }

                    }
                });
            }

            if (column == 'model_id') {
                let selected_year = document.querySelector(`select[name='year']`).value;
                let selected_make = document.querySelector(`select[name='make_id']`).value;
                $.get(`https://${domain}/api/service/kbb/all/models/year/${selected_year}/makeID/${selected_make}`, function(data) {
                    console.log(data);

                    if (!data.hasOwnProperty('errors')) {
                        for (const option of data) {
                            let optional_dom = document.createElement("option");
                            optional_dom = document.createElement("option");
                            optional_dom.innerText = option.label;
                            optional_dom.value = option.value;
                            document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                        }

                    }
                });
            }

            if (column == 'trim_id') {
                let selected_year = document.querySelector(`select[name='year']`).value;
                let selected_model = document.querySelector(`select[name='model_id']`).value;
                $.get(`https://${domain}/api/service/kbb/all/trims/year/${selected_year}/modelID/${selected_model}`, function(data) {
                    console.log(data);

                    if (!data.hasOwnProperty('errors')) {
                        for (const option of data) {
                            let optional_dom = document.createElement("option");
                            optional_dom = document.createElement("option");
                            optional_dom.innerText = option.label;
                            optional_dom.value = option.value;
                            document.querySelector(`select[name='${column}']`).appendChild(optional_dom);
                        }

                    }
                });
            }

            document.querySelector(`select[name='${column}']`).removeAttribute("readonly");
        }

        triggerDropdownOnChange('year', [], ['year', 'make_id', 'model_id', 'trim_id']);
        document.querySelector(`select[name='year']`).addEventListener('change', () => {
            triggerDropdownOnChange('make_id', ['year'], ['make_id', 'model_id', 'trim_id']);
        });

        document.querySelector(`select[name='make_id']`).addEventListener('change', () => {
            triggerDropdownOnChange('model_id', ['year', 'make_id'], ['model_id', 'trim_id']);
            $("input[name='make']").val($('select[name=make_id] option:selected').text());
        });

        document.querySelector(`select[name='model_id']`).addEventListener('change', () => {
            triggerDropdownOnChange('trim_id', ['year', 'make_id', 'model_id'], ['trim_id']);
            $("input[name='model']").val($('select[name=model_id] option:selected').text());
        });

        document.querySelector(`select[name='trim_id']`).addEventListener('change', () => {
            $("input[name='trim']").val($('select[name=trim_id] option:selected').text());
            $("input[name='kbb_id']").val($('select[name=trim_id]').val());
        });
    </script>