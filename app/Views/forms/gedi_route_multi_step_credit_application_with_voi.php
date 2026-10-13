<!DOCTYPE html>
<html>

<head>
    <title>Credit Application | Zop Dealer</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css?ver=1.0.3">
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
        ul#credit-score-selection li,
        ul#trade-in-selection li,
        ul#date-of-birth-selection-options li {
            display: block;
        }

        ul#credit-score-selection li label,
        ul#trade-in-selection li label,
        ul#date-of-birth-selection-options li label {
            width: 100%;
        }

        ul.form-list-desgine li {
            vertical-align: top;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>
    <section class="details-main finance-form-set">
        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <div class="finance-car-detail">
                    <div class="finance-car-detail-box">
                        <div class="mobile-left-b">
                            <div class="finance-car-detai-img">
                                <img class="img-responsive" width="100%" id="zm_image" src="" />
                            </div>
                            <div class="mobile-right-b">
                                <h2 class="finance-car-title" id="zd_inventory_title"></h2>
                            </div>
                        </div>
                        <div class="mobile-right-btn-b">
                            <a class="btn btn-link" id="changeVehicleBtn">Edit</a>
                        </div>
                    </div>
                </div>
                <form class="needs-validation" id="lead_form" novalidate="novalidate">
                    <!--Financial Services Starts-->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display: block;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your estimated credit score?</h3>
                                    <ul class="form-list-desgine" id="credit-score-selection">
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="good_over_700" name="credit_rating" value="Over 700"><label> Good (Over 700)</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="fair_600_700" name="credit_rating" value="Between 600 And 700"><label>Fair (600-700)</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="poor_under_600" name="credit_rating" value="Under 600"><label>Poor (Under 600)</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="credit_rating_error" class="invalid-feedback text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Would you like to trade in a car?</h3>
                                    <ul class="form-list-desgine" id="trade-in-selection">
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-Yes" name="trade_in" value="I want to trade in a car"><label>I want to trade in a car</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="Trade-In-No" name="trade_in" value="I dont want to trade in a car"><label>I don’t want to trade in a car</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="trade_in_error" class="invalid-feedback text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What Is Your Employment Status?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="employed" name="employment_status" value="Employed"><label>Employed</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="self-employed" name="employment_status" value="Self Employed"><label>Self-Employed</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="student" name="employment_status" value="Retired"><label>Retired</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="retired-pension" name="employment_status" value="Disability"><label>Disability</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="unemployed" name="employment_status" value="Unemployed"><label>Unemployed</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f">
                                                <input type="radio" class="auto-click-next seclect-ratio-btn" id="other" name="employment_status" value="Other"><label>Other</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="employment_status_error" class="invalid-feedback text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Where do you work?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control " name="company_name" id="company_name" placeholder="Company Name">
                                                <div id="company_name_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Title">
                                                <div id="job_title_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <h4 class="sec-title">How long have you worked here?</h4>
                                    <ul class="form-list-desgine sec-box-n">
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" min="0" class="form-control" name="employment_period_years" id="employment_period_years" placeholder="Years">
                                                <div id="employment_period_years_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" min="0" max="11" class="form-control" name="employment_period_months" id="employment_period_months" placeholder="Months">
                                                <div id="employment_period_months_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Total gross annual income?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="finance-w-100">
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control" name="gross_income" id="gross_income" placeholder="Gross Income">
                                                <div id="gross_income_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your date of birth?</h3>
                                    <ul class="form-list-desgine" id="date-of-birth-selection-options">
                                        <li>
                                            <div class="head-felid-f1">
                                                <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day"></select>
                                                <div id="birth_date_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                                <div id="birth_month_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <select class="form-control" name="birth_year" id="dobyear" placeholder="Year"></select>
                                                <div id="birth_year_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Monthly rent or mortgage payment?</h3>
                                    <ul class="form-list-desgine">
                                        <li class="finance-w-100">
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control " name="mortgage_rent_amount_monthly" id="mortgage_rent_amount_monthly" placeholder="Payments">
                                                <div id="mortgage_rent_amount_monthly_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Where do you live?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                                <div id="street_address_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                                <div id="city_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                                <div id="province_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                                <div id="postal_code_error" class="invalid-feedback text-danger"></div>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>How long have you lived here </h3>
                                    <ul class="form-list-desgine sec-box-n">
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" min="0" class="form-control" name="residence_period_years" id="residence_period_years" placeholder="Years">
                                                <div id="residence_period_years_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" min="0" max="11" class="form-control" name="residence_period_months" id="residence_period_months" placeholder="Months">
                                                <div id="residence_period_months_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Congratulations your Pre-Approval is Ready!</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                                <div id="first_name_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                                <div id="last_name_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                                <div id="email_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid-f1">
                                                <input type="tel" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                                <div id="phone_error" class="invalid-feedback text-danger"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="checkbox-block link-set">
                                        <p style="padding-top: 15px;padding-bottom: 15px;"><input type="checkbox" name="sms_consent" checked="">
                                            By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us. </p>
                                    </div>
                                    <div class="col-md-12 col-xs-12 p-top-20 text-center">
                                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                        <div class="invalid-feedback text-danger" id="recaptcha-error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="single_stock_no_input" name="stock_no" value="">
                        <input type="hidden" id="single_stock_zm_year" name="zm_year" value="">
                        <input type="hidden" id="single_stock_zm_make" name="zm_make" value="">
                        <input type="hidden" id="single_stock_zm_model" name="zm_model" value="">
                        <input type="hidden" id="single_stock_zm_trim" name="zm_trim" value="">
                        <input type="hidden" id="single_stock_image" name="zm_image" value="">
                        <input type="hidden" id="single_stock_zm_vin" name="zm_vin" value="">
                        <input type="hidden" id="inventory_id" name="inventory_id">
                        <input type="hidden" name="source" value="<?php echo $source; ?>">
                        <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                        <input type="hidden" name="source_url" value="">
                        <a class="action next btn btn-info" style="display:block;">Continue</a>
                        <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                        <a type="submit" class="action back btn btn-info" style="display:block;"><i class="fas fa-arrow-left"></i>Back</a>
                    </div>
                </form>
            </div>
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

            function resetInventoryData() {
                $('#single_stock_no_input').val('');
                $('#single_stock_zm_year').val('');
                $('#single_stock_zm_make').val('');
                $('#single_stock_zm_model').val('');
                $('#single_stock_zm_trim').val('');
                $('#single_stock_zm_vin').val('');
                $('#single_stock_image').val('');
                $('#zd_inventory_title').text('NO VEHICLE SELECTED');
                $("#zm_image").attr('src', window.placeholder);
            }

            function single_inventory(id) {
                $.get(window.location.origin + '/api/v2/website/inventory/show/' + id, function(data) {

                    $('#single_stock_no_input').val(data.stock_no);
                    $('#single_stock_zm_year').val(data.year);
                    $('#single_stock_zm_make').val(data.make);
                    $('#single_stock_zm_model').val(data.model);
                    $('#single_stock_zm_trim').val(data.trim);
                    $('#single_stock_zm_vin').val(data.vin);
                    $('#zd_inventory_title').html(`<span>${data.year}</span> ${data.make} ${data.model} <span>${data.trim}</span> - <span><b>#${data.stock_no}</b></span>`);


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

                });

            }

            if (window.params.hasOwnProperty('inventory_id')) {
                $('#inventory_id').val(window.params.inventory_id);
                single_inventory(window.params.inventory_id);
            }
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
                element_id: "finance_form"
            }, "*");


            var current = 1;

            widget = $(".step");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");
            var partial_form = $("form");

            var rules = {
                vehicle_type: "required",
                credit_rating: "required",
                trade_in: "required",
                birth_date: {
                    required: true,
                    number: true
                },
                birth_month: {
                    required: true,
                    number: true
                },
                birth_year: {
                    required: true,
                    number: true
                },
                employment_status: {
                    required: true,
                },
                gross_income: "required",
                employment_period_years: {
                    required: true,
                    min: 0,
                    max: 65,
                    number: true
                },
                employment_period_months: {
                    required: true,
                    min: 0,
                    max: 11,
                    number: true
                },
                residence_period_years: {
                    required: true,
                    min: 0,
                    max: 65,
                    number: true,
                },
                residence_period_months: {
                    required: true,
                    min: 0,
                    max: 11,
                    number: true,
                },
                employment_period_with_this_salary: "required",
                company_name: "required",
                job_title: "required",
                residence_status: "required",
                mortgage_rent_amount_monthly: {
                    required: true,
                    number: true,
                    min: 0,
                },
                street_address: "required",
                city: "required",
                province: "required",
                postal_code: "required",
                residence_period: "required",
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
                    number: true,
                }
            };
            var messages = {
                vehicle_type: "Vehicle Type is required",
                budget: "Budget is required",
                credit_rating: "Credit Rating is required",
                trade_in: "Trade In is required",
                birth_date: {
                    required: "Please Enter Day of Birth",
                    number: "Day of Birth should be a number",
                },
                birth_month: {
                    required: "Please Enter Month of Birth",
                    number: "Month of Birth should be a number",
                },
                birth_year: {
                    required: "Please Enter Year of Birth",
                    number: "Year of Birth should be a number",
                },
                employment_status: "Employment Status is required",
                residence_period_years: {
                    required: "Please Enter the Residence Period Years",

                },
                residence_period_months: {
                    required: "Please Enter Residence Period Months",

                },
                employment_period_years: {
                    required: "Please Enter Employment Period Years",

                },
                employment_period_months: {
                    required: "Please Enter Employment Period Months",

                },
                gross_income: "Gross Income is required",
                employment_period_with_this_salary: "Employment Period is required",
                company_name: "Please Enter Company Name",
                job_title: "Please Enter Job Title",
                residence_status: "Residence Status is required",
                mortgage_rent_amount_monthly: {
                    required: "Please Enter Rent or Mortgage amount",
                    number: "Rent or Mortgage amount should be numeric"
                },
                street_address: "Please Enter Street Address",
                city: "Please Enter City",
                province: "Please Enter Province",
                postal_code: "Please Enter Postal Code",
                residence_period: "Please Enter Residence Period",
                first_name: "Please Enter Your First Name",
                last_name: "Please Enter Your Last Name",
                //                email: "Please Enter Your Email",
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
                debug: false,
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

                    parent.postMessage({
                        type: "css",
                        value: $(document).height(),
                        element_id: "finance_form"
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

                parent.postMessage({
                    type: "css",
                    value: $(document).height(),
                    element_id: "finance_form"
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
                    value: $(document).height(),
                    element_id: "finance_form"
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

        $(".auto-click-next").on('click', function() {
            $(".next").trigger('click');
        });

        $("#changeVehicleBtn").on('click', function() {
            parent.postMessage("redirectToInventoryListing", "*");
        });
    </script>


</body>