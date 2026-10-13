<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Inquiry | Zop Dealer</title>
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

        #zm_image {
            width: 300px;
        }



        @media (max-width: 576px) {
            #inventory_custom_block {
                flex-wrap: wrap;
            }
        }

        #inventory_custom_block div span {
            min-width: 50%;
            display: block;
        }

        #inventory_custom_block div span:nth-child(even) {
            font-weight: 800;
        }

        #inventory_custom_block>div:first-child {
            display: flex;
            flex-wrap: wrap;
        }

        #inventory_custom_block .owl-nav {
            position: absolute;
            top: 30%;
            width: 100%;
            z-index: 999999;
            left: 0px;
            text-align: left;
            display: none;
        }

        #inventory_custom_block .owl-nav button.owl-prev {
            position: relative;
            top: 30%;
            left: 0px;
            width: 40px;
            height: 40px;
            background: #000000db;
            border-radius: 50%;
        }

        #inventory_custom_block .owl-nav button.owl-next {
            position: relative;
            width: 40px;
            height: 40px;
            background: #000000db;
            border-radius: 50%;
            float: right;
        }

        #inventory_custom_block .owl-nav button.owl-prev span,
        #inventory_custom_block .owl-nav button.owl-next span {
            color: #fff;
            font-size: 38px;
            position: relative;
            top: -16px;
            z-index: 9999;
            left: 11px;
            right: 0px;
            text-align: center;
        }

        #inventoryImageCarousel:hover .owl-nav {
            display: block;
        }

        #inventory_custom_block .owl-nav button.owl-next span {
            left: 14px;
        }

        #inventory_custom_block .owl-nav button.owl-prev:focus:not(:focus-visible) {
            outline: 1px solid transparent;
            border: 0px;
        }

        #inventory_custom_block .owl-nav button.owl-next:focus:not(:focus-visible) {
            outline: 1px solid transparent;
            border: 0px;
        }
    </style>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <?php if (!empty($custom_less)) {
        $custom_less_urls = explode(',', $custom_less);
        foreach ($custom_less_urls as $each_url) { ?>
            <link rel="stylesheet/less" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>

    <!-- OWL DEPENDENCIES : START -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <!-- OWL DEPENDENCIES : END  -->
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
                                    <h3> Vehicle You Need</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <p><strong>Year <sup class="text-danger">*</sup></strong></p>
                                                <select id="autofinder-year" name="year" placeholder="Select" class="form-control">
                                                    <option disabled="disabled" selected value="">-- Select Year --</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <p><strong>Make <sup class="text-danger">*</sup></strong></p>
                                                <select id="autofinder-make" name="make" placeholder="Select" class="form-control">
                                                    <option disabled="disabled" selected="" value="">-- Select Make --</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <p><strong>Model <sup class="text-danger">*</sup></strong></p>
                                                <input type="text" name="model" class="form-control" placeholder="" id="autofinder-model" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3> Vehicle You Need</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <p><strong>Purchase Time Frame</strong></p>
                                                <input type="text" name="purchase_time_frame" class="form-control" placeholder="" id="autofinder-purchase_time_frame">
                                                <div id="purchase_time_frame_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <p><strong>Price</strong></p>
                                                <input type="text" name="price" class="form-control" placeholder="" id="autofinder-price">
                                                <div id="price_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">

                                    <h3> Personal Information </h3>
                                    <!-- <h3>Congratulations your Pre-Approval is Ready!</h3> -->
                                    <div class="row">
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
                                            <!-- <input type="hidden" name="hash" id="hash" value=""> -->
                                            <!-- <div class="link-set">
                                                <p>I have read and accept the <a href="https://www.janexauto.com/terms-conditions/">Terms of Use</a> . I also acknowledge having read the <a href="https://www.janexauto.com/privacy-policy/">Privacy Policy</a> and agree to the terms herein.</p>
                                            </div> -->
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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js'></script>

    <script type="text/javascript">
        $(document).ready(function() {
            function validURL(str) {
                regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
                if (regexp.test(str)) {
                    return true;
                } else {
                    return false;
                }
            }

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
                window.placeholder = '<?php echo getenv('image.cdn')
                                        ?>' + data.image_url;
            });

            //Fetch instock inventory

            function repopulateDropDown(url = window.location.origin + '/api/website/inventory?zm_status[]=Instock&paginate=false&column=year&order=DESC') {
                $.get(url, function(data) {
                    $('#inventory_id').append('<option value="" > -- Not Sure -- </option>');
                    for (var key in data.inventory) {
                        if (data.inventory.hasOwnProperty(key)) {
                            var element = data.inventory[key];
                            var option = '<option value="' + element.inventory_id + '"> ' + element.year + ' ' + element.make + ' ' + element.model + ' #' + element.stock_no + '-' + element.odometer + ' ' + element.km_miles + '</option>';
                            $('#inventory_id').append(option);

                        }
                    }
                }).done(function() {
                    if (window.params.hasOwnProperty('inventory_id')) {
                        $('#inventory_id').val(window.params.inventory_id);
                        single_inventory(window.params.inventory_id);
                    }
                });
            }

            repopulateDropDown();

            function resetInventoryData() {
                $('#single_stock_no_input').val('');
                $('#zm_stock_no').text('');

                $('#single_stock_zm_year').val('');
                $('#zm_year').text('');

                $('#single_stock_zm_make').val('');
                $('#zm_make').text('');

                $('#single_stock_zm_model').val('');
                $('#zm_model').text('');

                $('#single_stock_zm_trim').val('');
                $('#zm_trim').text('');

                $('#single_stock_zm_vin').val('');
                $('#zm_vin').text('');

                $("#zm_odometer").text(``);

                $('#single_stock_image').val('');
                $("#zm_image").attr('src', '');

                $('#inventory_custom_block').css({
                    'display': 'none',
                });
            }


            function single_inventory(id) {
                $.get(window.location.origin + '/api/website/inventory/show/' + id, function(data) {

                    if (data.stock_no.length > 0) {
                        $('#inventory_custom_block').css({
                            'display': 'flex',
                        });
                    } else {
                        $('#inventory_custom_block').css({
                            'display': 'none',
                        });
                    }

                    $('#single_stock_no_input').val(data.stock_no);
                    $('#zm_stock_no').text(data.stock_no);

                    $('#single_stock_zm_year').val(data.year);
                    $('#zm_year').text(data.year);

                    $('#single_stock_zm_make').val(data.make);
                    $('#zm_make').text(data.make);

                    $('#single_stock_zm_model').val(data.model);
                    $('#zm_model').text(data.model);

                    $('#single_stock_zm_trim').val(data.trim);
                    $('#zm_trim').text(data.trim);

                    $('#single_stock_zm_vin').val(data.vin);
                    $('#zm_vin').text(data.vin);

                    $("#zm_odometer").text(`${data.odometer} ${data.km_miles}`);
                });

                $.get(window.location.origin + '/api/website/inventory/images/' + id, function(data) {
                    //single_inventory_image
                    if (data['gallery'].hasOwnProperty('0')) {
                        var image_url = '';
                        if (validURL(data['gallery'][0]['image_url'])) {
                            image_url = data['gallery'][0]['image_url'];
                        } else {
                            image_url = '<?php echo getenv('image.cdn')
                                            ?>' + data['gallery'][0]['image_url'];
                        }

                        $('#single_stock_image').val(image_url);
                        $("#zm_image").attr('src', image_url);
                    } else {
                        $('#single_stock_image').val(window.placeholder);
                        $("#zm_image").attr('src', image_url);
                    }

                    $('#inventoryImageCarousel').owlCarousel('destroy');
                    $('#inventoryImageCarousel').removeClass('owl-drag owl-hidden owl-loaded');
                    document.getElementById("inventoryImageCarousel").innerHTML = '';

                    for (const key in data['gallery']) {
                        var image_url = '';
                        if (validURL(data['gallery'][key]['image_url'])) {
                            image_url = data['gallery'][key]['image_url'];
                        } else {
                            image_url = '<?php echo getenv('image.cdn')
                                            ?>' + data['gallery'][key]['image_url'];
                        }

                        jQuery('#inventoryImageCarousel').append(`<div class="item"><img style="width: 300px;" src="${image_url}"></div>`);
                    }



                    setTimeout(() => {
                        $("#inventoryImageCarousel").owlCarousel({
                            autoPlay: 3000, //Set AutoPlay to 3 seconds
                            items: 1,
                            nav: true
                        });
                    }, 1000);

                });

            }

            //Method for changing select 
            $('#inventory_id').change(function() {
                var data = $(this).val();
                resetInventoryData();
                single_inventory(data);
            });

            $('input[name="vehicle_type"]').change(function() {
                let refine_dropdown_url = window.location.origin + '/api/website/inventory?zm_status[]=Instock&paginate=false&column=year&order=DESC';
                let vehicle_type = $(this).val();

                $('#inventory_id').find('option').remove();
                if (vehicle_type) {
                    switch (vehicle_type) {
                        case 'Sedan':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Sedan&zm_body_type[]=Sedan%204%20Dr.`;
                            break;

                        case 'Coupe':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Coupe&zm_body_type[]=Coupes&zm_body_type[]=Coupe-2-Door`;
                            break;

                        case 'Hatchback':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Hatchback&zm_body_type[]=Hatchback%202%20Dr.`;
                            break;

                        case 'SUV':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Sport%20Utility%20Vehicle&zm_body_type[]=SUV-Crossover&zm_body_type[]=Sport%20Utility%204-Door&zm_body_type[]=Suv`;
                            break;

                        case 'Van':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Minivan&zm_body_type[]=Van&zm_body_type[]=Minivan-Van&zm_body_type[]=minivan`;
                            break;

                        case 'Truck':
                            refine_dropdown_url = `${refine_dropdown_url}&zm_body_type[]=Truck&zm_body_type[]=Pickup%20Truck&zm_body_type[]=Pickup-Truck&zm_body_type[]=Trucks&zm_body_type[]=Commercial%20Vehicle`;
                            break;

                        default:
                            break;
                    }
                }

                repopulateDropDown(refine_dropdown_url);

            });


        });

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

            $.dobPicker({
                daySelector: '#dobday',
                monthSelector: '#dobmonth',
                yearSelector: '#dobyear'

            });

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "autofinder_form"
            }, "*");


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

            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
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
                }
            };
            var messages = {
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
                    //$("#submit-button").prop("disabled", true);

                    //Fire an Alert.
                    parent.postMessage("alertFire", "*");

                    // var form = $(form);
                    // var disabled = form.find(':input:disabled').removeAttr('disabled');
                    // var serialized = form.serialize();
                    // disabled.attr('disabled', 'disabled');

                    var form = $(form);
                    $.ajax({
                        url: window.location.origin + '/api/leads/responses/create',
                        type: "POST",
                        data: form.serialize(), // get all form variables
                        success: function(result) {

                            //Fire A Success Update Alert
                            parent.postMessage("alertSuccessUpdate", "*");

                            // $('form#lead_form').each(function() {
                            //     this.reset();
                            // });

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

        $(".auto-click-next").on('click', function() {
            $(".next").trigger('click');
        });
    </script>


</body>