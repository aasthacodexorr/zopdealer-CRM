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
    <link rel="stylesheet" type="text/css" href="/custom_forms/error-style.css">
    <style>
        .invalid-feedback.radio-error {
            display: block;
        }

        .invalid-feedback.radio-error>.error {
            font-size: 12px;
            color: red;
            margin: 0px;
        }
    </style>
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->
    <style>
        .fa,
        .fas {
            font-family: "FontAwesome", "Font Awesome 5 Free" !important;
        }
    </style>

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
                        <!-- <h3>Trade-In Estimate</h3>
                        <p>Find out how much your trade-in vehicle is worth. Our trade-in estimate tool uses data from industry leading partners, giving you relevant and robust vehicle valuations.</p> -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Vehicle Description</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Year*</strong></p>
                                    <input min="1960" name="year" type="text" class="form-control" placeholder="Year" id="trade-year" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Make*</strong></p>
                                    <input type="text" name="make" class="form-control" placeholder="Make" id="trade-make" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Model*</strong></p>
                                    <input type="text" name="model" class="form-control" placeholder="Model" id="trade-model" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>VIN</strong></p>
                                    <input type="text" name="vin" class="form-control" placeholder="VIN" id="trade-vin" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>2. <strong>Vehicle Condition</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>What is the condition of your Vehicle? </strong></p>
                                    <p>Selecting an accurate condition will give you a more accurate estimate.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-1" value="Fair" />
                                        <div class="select-box-text">
                                            <p><strong>Fair</strong></p>
                                            <!--                                            <p>11% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-2" value="Good" />
                                        <div class="select-box-text">
                                            <p><strong>Good</strong></p>
                                            <!--                                            <p>49% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-3" value="Very Good" />
                                        <div class="select-box-text">
                                            <p><strong>Very Good</strong></p>
                                            <!--                                            <p>33% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-20">
                                    <div class="select-box">
                                        <input type="radio" name="vehicle_condition" class="zop-condition" id="trade-condition-4" value="Excellent" />
                                        <div class="select-box-text">
                                            <p><strong>Excellent</strong></p>
                                            <!--                                            <p>3% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20" style="position: relative;">
                                    <p><strong>Odometer*</strong></p>
                                    <span class="right-text" style="bottom:unset;"> Kms</span>
                                    <input type="text" name="odometer" class="form-control" placeholder="" id="trade-odometer" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Accident or Insurance Claims?*</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="accident_or_insurance_claims" value="Yes" id="trade-accident-or-insurance-claims" />Yes</li>
                                        <li><input type="radio" placeholder="" name="accident_or_insurance_claims" value="No" id="trade-accident-or-insurance-claims" />No</li>
                                    </ul>
                                    <div class="invalid-feedback radio-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Lien on Vehicle?</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="lien_on_vehicle" value="Yes" id="trade-lien-on-vehicle-yes" />Yes</li>
                                        <li><input type="radio" placeholder="" name="lien_on_vehicle" value="No" id="trade-lien-on-vehicle-no" />No</li>
                                    </ul>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row lien-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Lien Amount</strong></p>
                                    <span style="float: left; margin-top: 12px;">$</span>
                                    <input style="width:96%" type="text" name="lien_amount" class="form-control" placeholder="" id="trade-lien-amount" />
                                    <div class="invalid-feedback"></div>
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
                                    <p><strong>First Name*</strong></p>
                                    <input name="first_name" type="text" class="form-control" placeholder="" id="trade-first-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last name*</strong></p>
                                    <input type="text" name="last_name" class="form-control" placeholder="" id="trade-last-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email*</strong></p>
                                    <input type="email" name="email" class="form-control" placeholder="" id="trade-email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone*</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="trade-phone-number" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Preferred Appointment Date</strong></p>
                                    <input type="date" name="preferred_appointment_date" class="form-control" placeholder="" id="trade-preferred-date" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Preferred Appointment Time</strong></p>
                                    <select id="trade-preferred-time" name="preferred_appointment_time" class="form-control">
                                        <option disabled selected>Select Preferred Time</option>
                                        <option value="Morning">Morning</option>
                                        <option value="Afternoon">Afternoon</option>
                                        <option value="Evening">Evening</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Images*</strong></p>
                                    <input type="file" name="image_1" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_2" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
                                    <input type="file" name="image_3" class="form-control" placeholder="" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row <?php if ($VOI != '1') {
                                            echo 'd-none';
                                        } ?>">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-30">
                                    <p><strong>VOI (Vehicle of interest)</strong></p>
                                    <select id="inventory_id" name="inventory_id" class="form-control">
                                        <option disabled selected>Choose...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Please share any additional details about your vehicle, such as any prior Insurance Claims? Damages? Service Records? and so on....</strong></p>
                                    <textarea rows="8" name="comments" class="form-control" placeholder="Message" id="trade-message"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="checkbox-block">
                            <input type="checkbox" name="sms_consent" checked>
                            By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us.
                        </div>
                        <div class="row">
                            <div class="col-md-12 p-top-20">
                                <img src="" id="captcha_image" alt="" srcset="">
                                <button id="request_captcha" class="btn btn-link">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="col-md-12 p-3">
                                <input type="text" style="width: auto;" name="captcha" class="form-control" placeholder="Type Image Characters" />
                                <div class="invalid-feedback" id="captcha-error"></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><input type="checkbox" id="condition-check" name="condition"/><span class="left-sp">By clicking Get Approved, I consent to the collection, use and disclosure of my personal information as described in this paragraph. I agree that the personal information provided above may be used and disclosed by Zop Demo and/or its agents or service providers (collectively, the "Dealer") as necessary to obtain credit, financial and related personal information (including a credit or consumer information report) about me from any credit bureau or credit reporting agency, and to advise me on credit availability in connection with product and/or service purchase financing. I further agree that the personal information provided above may be disclosed to the provider of Dealer’s website hosting or related services for the purpose of enabling Dealer to access my personal information for marketing purposes. Personal information I provide and credit information obtained may also be retained by Dealer and used to facilitate the application process should I subsequently choose to apply for credit through Dealer.</span></p>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="hero-btn p-top-20 p-bottom-50">
                                <input type="hidden" name="source" value="<?php echo $source; ?>">
                                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                <input type="hidden" id="single_stock_no_input" name="stock_no" value="">
                                <input type="hidden" id="single_stock_zm_year" name="zm_year" value="">
                                <input type="hidden" id="single_stock_zm_make" name="zm_make" value="">
                                <input type="hidden" id="single_stock_zm_model" name="zm_model" value="">
                                <input type="hidden" id="single_stock_zm_trim" name="zm_trim" value="">
                                <input type="hidden" id="single_stock_image" name="zm_image" value="">
                                <input type="hidden" id="single_stock_zm_vin" name="zm_vin" value="">
                                <input type="hidden" name="source_url" value="">
                                <input type="hidden" name="hash" id="hash" value="">
                                <button type="submit" class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;color:white !important;">Get My Trade-In Appraisal</a></button>
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
                value: $("#lead_form").outerHeight(true),
                element_id: "trade_in_appraisal"
            }, "*");

            function wait(ms) {
                var start = new Date().getTime();
                var end = start;
                while (end < start + ms) {
                    end = new Date().getTime();
                }
            }

            function validURL(str) {
                regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
                if (regexp.test(str)) {
                    return true;
                } else {
                    return false;
                }
            }

            $('input[type="tel"]').each(function() {
                $(this).inputmask({
                    "mask": "(999) 999-9999",
                    "removeMaskOnSubmit": true,
                    "autoUnmask": true
                });
            });

            function getSearchParameters() {
                var prmstr = window.location.search.substr(1);
                return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
            }

            function transformToAssocArray(prmstr) {
                var params = {};
                var prmarr = prmstr.split("&");
                for (var i = 0; i < prmarr.length; i++) {
                    var tmparr = prmarr[i].split("=");
                    params[tmparr[0]] = tmparr[1];
                }
                return params;
            }

            window.params = getSearchParameters();

            var placeholder = '';

            //Fetch placeholder
            $.get(window.location.origin + '/api/website/image/placeholder', function(data) {
                window.placeholder = '<?php //echo getenv('image.cdn') 
                                        ?>' + data.image_url;
            });


            //Fetch instock inventory
            $.get(window.location.origin + '/api/website/inventory?zm_status[]=Instock&paginate=false&column=year&order=DESC', function(data) {
                for (var key in data.inventory) {
                    if (data.inventory.hasOwnProperty(key)) {
                        var element = data.inventory[key];
                        var option = '<option value="' + element.inventory_id + '"> ' + element.year + ' ' + element.make + ' ' + element.model + ' #' + element.stock_no + '</option>';
                        $('#inventory_id').append(option);

                    }
                }
            }).done(function() {
                if (window.params.hasOwnProperty('inventory_id')) {
                    $('#inventory_id').val(window.params.inventory_id);
                    single_inventory(window.params.inventory_id);
                }
            });

            function single_inventory(id) {
                $.get(window.location.origin + '/api/website/inventory/show/' + id, function(data) {
                    $('#single_stock_no_input').val(data.stock_no);
                    $('#single_stock_zm_year').val(data.year);
                    $('#single_stock_zm_make').val(data.make);
                    $('#single_stock_zm_model').val(data.model);
                    $('#single_stock_zm_trim').val(data.trim);
                    $('#single_stock_zm_vin').val(data.vin);
                });

                $.get(window.location.origin + '/api/website/inventory/images/' + id, function(data) {
                    //single_inventory_image
                    if (data['gallery'].hasOwnProperty('0')) {
                        var image_url = '';
                        if (validURL(data['gallery'][0]['image_url'])) {
                            image_url = data['gallery'][0]['image_url'];
                        } else {
                            image_url = '<?php //echo getenv('image.cdn') 
                                            ?>' + data['gallery'][0]['image_url'];
                        }

                        $('#single_stock_image').val(image_url);
                    } else {
                        $('#single_stock_image').val(window.placeholder);
                    }
                });

            }

            //Method for changing select 
            $('#inventory_id').change(function() {
                var data = $(this).val();
                single_inventory(data);
            });

            // START Form Validation
            $("#lead_form").validate({
                rules: {
                    year: {
                        required: true,
                        min: 1960,
                        max: 2100,
                        number: true
                    },
                    make: "required",
                    model: "required",
                    odometer: {
                        number: true,
                        required: true
                    },
                    accident_or_insurance_claims: "required",
                    image_1: "required",
                    image_2: "required",
                    image_3: "required",
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
                    vin: "Please Enter VIN",
                    odometer: {
                        number: "Odometer should be a Number",
                        required: "Please Enter Odometer"
                    },
                    accident_or_insurance_claims: "Please select the Claims",
                    image_1: "Please Upload the Car Image 1",
                    image_2: "Please Upload the Car Image 2",
                    image_3: "Please Upload the Car Image 3",
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
                        // error.appendTo(element('.invalid-feedback'));
                        error.appendTo(element.parent().parent().next(".invalid-feedback"));
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
                        data: new FormData(document.getElementById('lead_form')),
                        contentType: false,
                        processData: false,
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

                                $("#captcha-error").text(errors);
                                document.getElementById("captcha-error").scrollIntoView({
                                    behavior: 'smooth'
                                });
                                $("input[name='captcha']").removeClass('valid').addClass('is-invalid');


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

                            if (window.params.hasOwnProperty('inventory_id')) {
                                $('#inventory_id').val(window.params.inventory_id);
                                single_inventory(window.params.inventory_id);
                            }

                            var inventory_value = $('#inventory_id').val();

                            if (inventory_value == null || inventory_value == '') {
                                $('#single_inventory').addClass('d-none');
                            }


                        }
                    });
                }
            });
            // END Form Validation
        });
        $('#trade-lien-on-vehicle-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.lien-section').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $("#lead_form").outerHeight(true),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-lien-on-vehicle-no').change(function() {
            if ($(this).is(":checked")) {
                $('.lien-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $("#lead_form").outerHeight(true),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $(`
        #trade-lien-on-vehicle-yes,
        #trade-lien-on-vehicle-no
        `).trigger('change');
    </script>

    <script>
        $("#request_captcha").on("click", (e) => {
            e.preventDefault();


            $.ajax({
                url: window.location.origin + '/api/services/captcha/request',
                type: "GET",
                success: (result) => {
                    $("#hash").val(result.hash);
                    $("#captcha_image").attr('src', result.captcha);
                },
                error: (error) => {}


            });
        });
        $("#request_captcha").trigger('click');
        $('input[name="captcha"]').keyup(function() {
            $('input[name="captcha"]').removeClass('is-invalid').removeClass('valid');
        });
    </script>

</body>

<!---- BODY END ---->

</html>