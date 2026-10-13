<!DOCTYPE html>
<html>

<head>
    <title>Book A Test Drive | Zop Dealer</title>
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
    <section class="section-3 p-bottom-50">
        <!-- CONTAINER START -->
        <div class="container">
            <form class="needs-validation" id="lead_form">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="head-text">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Vehicle Info</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <select id="inventory_id" name="inventory_id" class="form-control">
                                        <option disabled selected>Choose...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20 text-center">
                                    <img id="single_inventory_image" style="max-width: 150px;border-radius: 5px;" class="img-responsive img-thumbnail d-none" src="" alt="" srcset="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>2. <strong>Contact Info</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" id="bookdrive-first-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" id="bookdrive-last-name" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <input type="email" name="email" class="form-control" placeholder="Email" id="bookdrive-email" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <span style="float: left; margin-top: 12px;">+1</span>
                                    <input style="width:96%" type="tel" name="phone" class="form-control" placeholder="Phone" id="bookdrive-phone-number" />
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>3. <strong>Appointment Date & Time</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <input type="date" name="preferred_appointment_date" class="form-control" placeholder="Appointment Date" id="bookdrive-preferred-date" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>What time would you like to schedule a test drive</strong></p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-0">
                                    <div class="select-box">
                                        <input type="radio" name="preferred_appointment_time" class="zop-condition" id="bookdrive-condition" value="Morning" />
                                        <div class="select-box-text">
                                            <p><strong>Morning</strong></p>
                                            <!--                                            <p>11% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-0">
                                    <div class="select-box">
                                        <input type="radio" name="preferred_appointment_time" class="zop-condition" id="bookdrive-condition" value="Afternoon" />
                                        <div class="select-box-text">
                                            <p><strong>Afternoon</strong></p>
                                            <!--                                            <p>49% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="p-top-0">
                                    <div class="select-box">
                                        <input type="radio" name="preferred_appointment_time" class="zop-condition" id="bookdrive-condition" value="Evening " />
                                        <div class="select-box-text">
                                            <p><strong>Evening </strong></p>
                                            <!--                                            <p>33% of estimates <br> we provide.</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>How did you hear about us?</strong></p>
                                    <select id="credit-application-hear-about-us" name="hear_about_us" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="AutoTrader">AutoTrader</option>
                                        <option value="Kijiji">Kijiji</option>
                                        <option value="CarGurus">CarGurus</option>
                                        <option value="CarPages">CarPages</option>
                                        <option value="Driving By">Driving By</option>
                                        <option value="Direct Mail">Direct Mail</option>
                                        <option value="Previous Customer">Previous Customer</option>
                                        <option value="Friend/Word Of Mouth">Friend/Word Of Mouth</option>
                                        <option value="Web">Web</option>
                                        <option value="Facebook">Facebook</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="Google">Google</option>
                                        <option value="Yahoo">Yahoo</option>
                                        <option value="MSN">MSN</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
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
                                    <textarea rows="8" class="form-control" name="comments" placeholder="Message" id="bookdrive-message"></textarea>
                                    <div class="invalid-feedback"> </div>
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
                                <div class="invalid-feedback" id="recaptcha-error"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="hero-btn p-top-20 p-bottom-50">
                                <button class="serach-btn" style="padding:0px;"><a style="padding:13px 50px;">Send</a></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                element_id: "book_test_drive"
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
                window.placeholder = '<?php echo getenv('image.cdn') ?>' + data.image_url;
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
                    hear_about_us: "required"
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
                    },
                    hear_about_us: "Please let us know how did you hear about us"
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

            function single_inventory(id) {
                $.get(window.location.origin + '/api/website/inventory/show/' + id, function(data) {
                    // $('#single_inventory').removeClass('d-none');
                    // $('#single_vin').text(data.vin);
                    // $('#single_year').text(data.year);
                    // $('#single_make').text(data.make);
                    // $('#single_model').text(data.model);
                    // $('#single_trim').text(data.trim);
                    $('#single_stock_no_input').val(data.stock_no);
                    // $('#single_stock_no_input').val(data.stock_no);

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
                            image_url = '<?php echo getenv('image.cdn') ?>' + data['gallery'][0]['image_url'];
                        }

                        $('#single_inventory_image').attr("src", image_url);
                        $('#single_stock_image').val(image_url);
                        $('#single_inventory_image').removeClass("d-none");
                    } else {
                        $('#single_inventory_image').attr("src", window.placeholder);
                        $('#single_stock_image').val(window.placeholder);
                        $('#single_inventory_image').removeClass("d-none");
                    }
                });

            }

            //Method for changing select 
            $('#inventory_id').change(function() {
                var data = $(this).val();
                single_inventory(data);
            });
        });
    </script>

</body>

<!---- BODY END ---->

</html>