<!DOCTYPE html>
<html>

<head>
    <title>Details | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-…(check actual hash on cdnjs)…"
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!--  HEAD END  -->

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>

    <?php if (!empty($custom_less)) {
        $custom_less_urls = explode(',', $custom_less);
        foreach ($custom_less_urls as $each_url) { ?>
            <link rel="stylesheet/less" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>

<!--  BODY START  -->

<body>

    <!-- LAYER-1 START -->
    <section class="details-main get-sp-form">
        <!-- CONTAINER START -->
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="head-text">
                    <form class="needs-validation" id="lead_form">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" class="form-control mb-3" placeholder="First Name" name="first_name" id="first_name" value="" required>
                                    <div id="first_name_error" class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control mb-3" name="last_name" id="last_name" placeholder="Last Name" value="" required>
                                    <div id="last_name_error" class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="col-6">
                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="" required>
                                    <div id="email_error" class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="col-6">
                                    <input type="tel" class="form-control" placeholder="Phone Number" name="phone" id="tel" value="" required>
                                    <div id="phone_error" class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="col-12">
                                    <textarea rows="5" class="form-control" name="message" placeholder="Message" id="message"></textarea>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="checkbox-block">
                                        <input type="checkbox" name="sms_consent" checked>
                                        By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us.
                                    </div>
                                </div>
                                <div class="col-12 p-top-20">
                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                    <div class="invalid-feedback" id="recaptcha-error"></div>
                                </div>
                                <div class="col-lg-12 col-12 col-sm-12 col-xs-12 no-padding">
                                    <input type="hidden" name="source" value="<?php echo $source; ?>">
                                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                    <input type="hidden" name="source_url" value="">
                                    <input type="hidden" id="single_stock_no_input" name="stock_no" value="">
                                    <input type="hidden" id="single_stock_zm_year" name="zm_year" value="">
                                    <input type="hidden" id="single_stock_zm_make" name="zm_make" value="">
                                    <input type="hidden" id="single_stock_zm_model" name="zm_model" value="">
                                    <input type="hidden" id="single_stock_zm_trim" name="zm_trim" value="">
                                    <input type="hidden" id="single_stock_image" name="zm_image" value="">
                                    <input type="hidden" id="single_stock_zm_vin" name="zm_vin" value="">
                                    <div class="hero-btn p-top-20 p-bottom-50 pt-2">
                                        <button type="submit" class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;color:white !important;">Submit</a></button>
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="/dist/assets/js/dobpicker.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>

    <script>
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
                    console.log(element.attr("name"));
                    console.log(element);
                    console.log(error);
                    $('[name="' + element.attr("name") + '"]').addClass('is-invalid');
                    error.appendTo("#" + element.attr("name") + "_error");
                    $("#" + element.attr("name") + "_error").addClass("d-block");
                    $("#" + element.attr("name") + "_error").find(".is-invalid").addClass("h5");

                    parent.postMessage({
                        type: "css",
                        value: $(document).outerHeight(true),
                        element_id: "request_information"
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

                            if (window.params.hasOwnProperty('inventory_id')) {
                                $('#inventory_id').val(window.params.inventory_id);
                                single_inventory(window.params.inventory_id);
                            }

                            var inventory_value = $('#inventory_id').val();

                            if (inventory_value == null || inventory_value == '') {
                                $('#single_inventory').addClass('d-none');
                            }

                            grecaptcha.reset();
                        }
                    });
                }
            });
            // END Form Validation

            setTimeout(() => {
                parent.postMessage({
                    type: "css",
                    value: $(document).outerHeight(true),
                    element_id: "request_information"
                }, "*");
            }, 1000);
        });
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "request_information"
            }, "*");

        });
    </script>
    <script>
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
                window.placeholder = '<?php echo getenv('image.cdn') ?>' + data.image_url;
            });


            if (window.params.hasOwnProperty('inventory_id')) {
                $('#inventory_id').val(window.params.inventory_id);
                single_inventory(window.params.inventory_id);
            }

            function single_inventory(id) {
                $.get(window.location.origin + '/api/website/inventory/show/' + id, function(data) {
                    $('#single_stock_no_input').val(data.stock_no);

                    $('#single_stock_zm_year').val(data.year);
                    $('#single_stock_zm_make').val(data.make);
                    $('#single_stock_zm_model').val(data.model);
                    $('#single_stock_zm_trim').val(data.trim);
                    $('#single_stock_zm_vin').val(data.vin);
                    var message = "Hi, I'm interested in the " + data.year + " " + data.make + " " + data.model + " . Please contact me as soon as you can.";
                    $('#message').val(message);
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

            $('#inventory_id').change(function() {
                var data = $(this).val();
                single_inventory(data);
            });
        });
    </script>

</body>

<!---- BODY END ---->

</html>