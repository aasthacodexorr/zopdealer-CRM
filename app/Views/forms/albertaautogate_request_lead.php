<!DOCTYPE html>
<html>

<head>
    <title>Detail | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/cosmo/bootstrap.min.css">
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

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>

    <!--  HEAD END  -->
</head>
<!--  BODY START  -->

<body>

    <div class="col-xs-12 col-sm-12 col-md-4 maincontent" style="padding:0px;">
        <div class="used-veh-basic-info">
            <div id="request-info-form" class="col-xs-12 col-sm-12 col-md-12 col-mobile-3 col-mobile-padding-10 no-padding">
                <form class="needs-validation" id="lead_form">
                    <div class="summary-information2">
                        <h4 class="reqinformation-used req-info-icon">Request Information</h4>
                        <div id="div_rmi" class="form-content-used req-info-form">
                            <div class="input-group">
                                <input required type="text" name="first_name" title="First Name" id="first_name" class="firstname-used stat-input-field" data-loc="request info" placeholder="First Name*" onfocus="inputFocus(this)" onblur="inputBlur(this)" maxlength="50">
                                <div class="invalid-feedback">
                                </div>
                                <div class="input-group">
                                    <input required type="text" name="last_name" title="Last Name" id="last_name" class="lastname-used stat-input-field" data-loc="request info" placeholder="Last Name*" onfocus="inputFocus(this)" onblur="inputBlur(this)" maxlength="50">
                                    <div class="invalid-feedback">
                                    </div>
                                    <div class="input-group">
                                        <input required type="email" name="email" title="Email Address" id="email" class="emailaddress-used stat-input-field" data-loc="request info" placeholder="Email Address*" onfocus="inputFocus(this)" onblur="inputBlur(this)" maxlength="70">
                                        <div class="invalid-feedback">
                                        </div>
                                        <div class="input-group">
                                            <input type="tel" name="phone" id="phone" title="Phone Number" class="phonenumber-used stat-input-field" data-loc="request info" placeholder="Phone Number" onfocus="inputFocus(this)" onblur="inputBlur(this)">
                                            <div class="invalid-feedback">
                                            </div>
                                            <div class="input-group">
                                                <textarea name="message" title="Comments" id="message" placeholder="Comments" class="comments1-used stat-input-field" data-loc="request info" onfocus="inputFocus(this)" onblur="inputBlur(this)" maxlength="1000" style="height: 5em;"></textarea>
                                            </div>
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
                                            <!-- <div class="input-group" style="margin-bottom: 8px;">
                                <label for="casl_agree_rmi" id="casl-consent" style="
                                 font-size: 10px;
                                 line-height: 10px;
                                 width: 100%; ">
                                    <input type="checkbox" required name="agree_to_terms_and_conditions" id="casl_agree_rmi" class="stat-checkbox" data-loc="request info" style="margin: 0;float: left;display: inline;">
                                    <p style="float: left;
                                    display: inline;
                                    width: 90%;
                                    margin-left: 7px;
                                    font-size: 13px;
                                    line-height: 24px;
                                    font-weight: 400;
                                    color:#fff;
                                    margin-top:-5px;">
                                        Send me exclusive offers and promotions.
                                        <a id="casl-consent-a-rmi" class="stat-disclaim" data-loc="request info" data-toggle="modal" href="#disclaimer-modal" style="text-decoration: underline;color:#ee4424;">
                                            View disclaimer.
                                        </a>
                                    </p>
                                </label>
                            </div> -->
                                            <div class="checkbox-block">
                                                <input type="checkbox" name="sms_consent" checked>
                                                By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us.
                                            </div>
                                            <div class="row">
                                                <ul class="form-sub-list">
                                                    <li>"By clicking SUBMIT you consent to receiving SMS messages."</li>
                                                    <li>"Messages and Data rates may apply. Message frequency will vary."</li>
                                                    <li>"Reply Stop to Opt-out of messaging."</li>
                                                    <li>"Reply Help for Customer Care Contact Information."</li>
                                                    <li>"I have read and acknowledged the Privacy Policy."</li>
                                                </ul>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 p-top-20">
                                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                                </div>
                                            </div>

                                            <button type="submit" id="link_contact_seller" class="btn-orange-send-new stat-button-valid" data-loc="request info">Submit</button>
                                        </div>
                                    </div>
                                    <!-- summary-info -->
                </form>
            </div>
        </div>
    </div>



    <!---- JAVA SCRIPT FILE ----->
    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/custom_forms/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>

    <script>
        $(document).ready(function() {

            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            document.querySelector(`input[name='source_url'`).value = source_url;

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

            //Method for saving lead response
            $("form#lead_form").on("submit", function(event) {
                event.preventDefault();
                $('input[type="tel"]').each(function() {
                    $(this).inputmask('remove');
                });
                $("input").removeClass("is-invalid");
                var form = $(this);
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
                        if (typeof(errors) == 'object') {
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    //var va = errors[key];
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
                grecaptcha.reset();
            });

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