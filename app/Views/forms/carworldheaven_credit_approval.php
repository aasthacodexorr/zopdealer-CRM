<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multi-Step Form</title>

    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://rawcdn.githack.com/SochavaAG/example-mycode/master/pens/calculator/plugins/rangeslider/rangeslider.css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/single_credit_application/style.css">

    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>


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

        .if-employed {
            margin: 10px 0 0;
            background-color: #ddd;
            padding: 10px 4px 10px;
        }

        .if-own {
            margin: 10px 0 0;
            background-color: #ddd;
            padding: 10px 4px 10px;
        }

        .if-trade-in {
            margin: 10px 0 0;
            background-color: #ddd;
            padding: 10px 4px 10px;
        }

        .invalid-feedback {
            display: block;
            margin: auto;
        }

        .invalid-feedback .error {
            margin-top: .25rem;
            font-size: .875rem;
            color: #dc3545;
        }
    </style>

    <style>
        .fa,
        .fas {
            font-family: "FontAwesome", "Font Awesome 5 Free" !important;
        }

        .form_group_inner button#request_captcha {
            background: transparent;
            width: auto;
            padding: 15px 20px;
            color: #000;
        }

        .form_group_inner button#request_captcha i.fas.fa-sync-alt {
            color: #000;
            font-size: 25px;
            display: block;
        }
    </style>


    <script>
        function rangeSlide(value) {
            document.getElementById('rangeValue').innerHTML = value;
        }
    </script>
</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="form_header">
        <div class="logo_header">
            <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/brmotors.png">
        </div>
        <div class="form_head_bg">
            <h2>The Easiest way to buy a vehicle<br> Receive finance offers within minutes</h2>
        </div>
    </div>
    <div class="container">

        <div class="form_container">
            <h3 style="text-align: center;">Buy Your Vehicle 100% Online</h3>
            <div class="stepwizard col-md-offset-3">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="#step-1" type="button" class="btn btn-primary btn-circle"><span>Budget</span><br><span>1</span></a>
                    </div>
                    <div class="stepwizard-step">
                        <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled"><span>Vehicle Type</span><br><span>2</span></a>
                    </div>
                    <div class="stepwizard-step">
                        <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled"><span>Location</span><br><span>3</span></a>
                    </div>
                    <div class="stepwizard-step">
                        <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled"><span>Get Offers</span><br><span>4</span></a>
                    </div>
                </div>
            </div>

            <form class="needs-validation" id="lead_form" novalidate="novalidate">


                <div class="row setup-content step well" id="step-1" style="display: block;">
                    <div class="range_slider">
                        <h3>What's Your Budget?</h3>
                        <p>$<span id="rangeValue">0</span>/ Month</p>

                        <div class="range_input">
                            <div class="range-slider">
                                <Input class="range slider" value="600" id="budget_input" type="range" name="budget" value="600" min="300" max="1200"></Input>
                                <span style="opacity: 0;" class="range-slider__value">600</span>
                            </div>
                            <div class="values-placeholder">
                                <span>300</span>
                                <span>750</span>
                                <span>1200</span>
                            </div>

                        </div>

                        <div class="range_content">
                            <h4>Hundreds Of Vehicles In Ontario</h4>
                            <p class="same_line"><span>$0 Down</span><span>Low Rates</span><span>Low payments</span></p>
                            <ul class="highlights">
                                <li>Bad credit</li>
                                <li>No credit</li>
                                <li>Collections</li>
                                <li>Bankruptcy</li>
                                <li>Late payments</li>
                                <li>Reposessions</li>
                                <li>New To Canada</li>
                                <li>Good Credit</li>
                            </ul>
                        </div>

                    </div>
                    <div class="range_slider">
                        <h3>Preferred Downpayment</h3>
                        <p>$<span id="rangeValue1">0</span></p>
                        <div class="range_input">
                            <div class="range-slider1">
                                <Input class="range slider" value="1000" id="downpayment_input" type="range" name="downpayment" value="1000" min="0" max="50000" step="500"></Input>
                                <span style="opacity: 0;" class="range-slider1__value">1000</span>
                            </div>
                            <div class="values-placeholder">
                                <span>0</span>
                                <span>25000</span>
                                <span>50000</span>
                            </div>
                        </div>
                        <!--          <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">Pick your RV NOW</button>-->
                    </div>
                </div>


                <div class="row setup-content step well" id="step-2" style="display: none;">
                    <h2>What are you looking for?</h2>
                    <p class="small-text">Receive Approval Offers Now</p>
                    <div class="vehicles">
                        <div class="field">
                            <input id="want_car" name="vehicle_option" value="Car" type="radio" class="vehicle_option required">
                            <label for="want_car">
                                <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/car_logo.png">
                                <span>I Want a Car</span>
                            </label>
                        </div>

                        <div class="field">
                            <input id="want_truck" name="vehicle_option" value="Truck" type="radio" class="vehicle_option required">
                            <label for="want_truck">
                                <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/truck_logo.png">
                                <span>I Want a Truck</span>
                            </label>
                        </div>

                        <div class="field">
                            <input id="want_suv" name="vehicle_option" value="SUV" type="radio" class="vehicle_option required">
                            <label for="want_suv">
                                <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/suv_logo.png">
                                <span>I Want an SUV</span>
                            </label>
                        </div>

                        <div class="field">
                            <input id="want_van" name="vehicle_option" value="Van" type="radio" class="vehicle_option required">
                            <label for="want_van">
                                <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/van_logo.png">
                                <span>I Want a Van</span>
                            </label>
                        </div>



                    </div>
                    <div id="vehicle_option_error" class="invalid-feedback"></div>
                </div>

                <div class="row setup-content step well" id="step-3" style="display: none;">

                    <div class="col-md-12">
                        <h2>You’re Almost Done!</h2>
                        <p class="small-text">Where are you located?</p>
                        <p>Let us know your address so we can find the best vehicle's in your area that match your budget.</p>

                        <!-- <div class="form-group">
                            <input type="text" class="form-control" placeholder="Start typing your address">
                        </div>

                        <p class="small_text_link"><a href="#">I don't see my address. Let me type it in.</a></p> -->

                        <div class="form-group">
                            <div class="form_group_inner">
                                <label class="label">Street address</label>
                                <input name="street_address" type="text" class="form-control" placeholder="">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form_group_inner">
                                <label class="label">City</label>
                                <input name="city" type="text" class="form-control" placeholder="">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form_group_inner">
                                <label class="label">Province</label>
                                <input name="city" type="text" class="form-control" placeholder="" value="Ontario">
                            </div>
                            <div class="form_group_inner">
                                <label class="label">Postal Code</label>
                                <input name="postal_code" type="text" class="form-control" placeholder="">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form_group_inner">
                                <label class="label">Where should we send your approval offers?</label>
                                <input type="email" name="email" class="form-control" placeholder="Email address">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form_group_inner">
                                <label class="label">What Is Your Name?</label>
                                <div class="half_block">
                                    <div class="half_block_inner">
                                        <input class="one_half form-control" type="text" name="first_name" class="form-control" placeholder="First name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="half_block_inner">
                                        <input class="one_half form-control" type="text" name="middle_name" class="form-control" placeholder="Middle name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <div class="half_block">
                                    <div class="half_block">
                                        <input class="one_half form-control" type="text" name="last_name" class="form-control" placeholder="Last name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_group_inner">
                                <label class="label">Phone number</label>
                                <input type="tel" name="phone" id="Phone" class="form-control" placeholder="( —- ) —- ——">
                                <div class="invalid-feedback"></div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="row setup-content step well" id="step-4" style="display: none;">

                    <div class="col-md-12">
                        <h3>Just need a little more info</h3>
                        <h2>You're 30 Seconds away</h2>
                        <p>We will send your offers as soon as you complete this final step</p>

                        <div class="form-group">
                            <div class="form_group_inner">
                                <label class="">When is your birthday?</label>
                                <input name="date_of_birth" type="date" id="dateofbirth" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form_group_inner">
                                <label class="label">Do you rent or own</label>
                                <select class="select valid" name="residence_status" id="residence_status" aria-invalid="false">
                                    <option></option>
                                    <option value="RENT">Rent</option>
                                    <option class="if-own-yes" value="OWN">Own</option>
                                </select>
                                <div id="own-details" class="if-own row" style="display:none;">
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Mortgage Balance Amount</label>
                                        <input type="text" class="input" name="mortgage_balance_amount">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Market Value</label>
                                        <input type="text" class="input" name="market_value">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form_group_inner">
                                <div>
                                    <label class="">Do you rent or own</label>
                                    <div class="checkbox-values">
                                        <input id="residence_status" name="residence_status" value="RENT" type="radio" class="radio">
                                        <label for="residence_status" class="radio-label">Rent</label>

                                        <input id="residence_status" name="residence_status" value="OWN" type="radio" class="radio">
                                        <label for="residence_status" class="radio-label">Own</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                             -->
                            <div class="form_group_inner">
                                <label class="">Mortgage /Rent Amount (Monthly)</label>
                                <div class="half_block">
                                    <div class="half_block">
                                        <input class="one_half form-control" name="mortgage_rent_amount_monthly" type="text" placeholder="">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <label class="">How long have you lived there?</label>
                                <div class="half_block">
                                    <div class="half_block_inner">
                                        <input class="one_half form-control" name="residence_period_years" type="text" placeholder="Years">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="half_block_inner">
                                        <input class="one_half form-control" name="residence_period_months" type="text" placeholder="Months">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_group_inner">
                                <label class="label">Employment Type</label>
                                <select class="select valid" name="employment_type" id="primary_income_source" aria-invalid="false">
                                    <option></option>
                                    <option class="if-employed-yes" value="EMPLOYED FULL TIME">Employed Full time</option>
                                    <option class="if-employed-yes" value="EMPLOYED PART TIME">Employed Part time</option>
                                    <option value="SELF-EMPLOYED">Self Employed</option>
                                    <option value="STUDENT">Student not working</option>
                                    <option value="RETIRED">Retired or Disability</option>
                                    <option value="HOME MAKER">Homemaker or Unemployed</option>
                                    <option value="OTHER">Other</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <div id="employment-complete-details" class="if-employed row" style="display:none;">
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Employer Name</label>
                                        <input type="text" class="input" name="employer_name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Position</label>
                                        <input type="text" class="input" name="job_title">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Employer Address</label>
                                        <input type="text" class="input" name="employer_address">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">City</label>
                                        <input type="text" class="input" name="employer_city">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Province</label>
                                        <input type="text" class="input" name="employer_province">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Employer Phone Number</label>
                                        <input type="tel" name="employer_phone" id="Phone" class="input" placeholder="( —- ) —- ——">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="form_group_inner">
                                <label class="">What is your monthly income?</label>
                                <label class="sub_label">Include all sources before deductions</label>
                                <input class="form-control" name="gross_income" type="text" placeholder="">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form_group_inner">
                                <label class="">How long have you worked at your current job?</label>
                                <div class="half_block">
                                    <div class="half_block_inner">
                                        <input class="one_half form-control" name="employment_period_years" type="text" placeholder="Years">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="half_block_inner">
                                        <input class="one_half form-control" name="employment_period_months" type="text" placeholder="Months">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <label class="">Drivers License Image (Front) (Max Size : 16MB)</label>
                                <div class="half_block">
                                    <input class="one_half form-control" name="driver_licence_image" type="file" placeholder="" id="credit-application-driver_licence_image" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <label class="">Drivers License Image (Back) (Max Size : 16MB)</label>
                                <div class="half_block">
                                    <input class="one_half form-control" name="driver_licence_image2" type="file" placeholder="" id="credit-application-driver_licence_image2" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!-- <div class="form_group_inner">
                                <label class="">Pay Stubs (Max Size : 16MB)</label>
                                <input class="one_half form-control" name="pay_stubs_image" type="file" placeholder="" id="pay_stubs_image" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form_group_inner">
                                <label class="">Other Documents (Max Size : 16MB)</label>
                                <input class="one_half form-control" name="other_documents" type="file" placeholder="" id="other_documents" />
                                <div class="invalid-feedback"></div>
                            </div> -->
                            <div class="form_group_inner">
                                <label class="label">Trade In</label>
                                <select class="select valid" name="trade_in" id="trade_in_vehicle" aria-invalid="false">
                                    <option></option>
                                    <option class="if-trade-in-yes" value="YES">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <div id="trade-in-details" class="if-trade-in row" style="display:none;">
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Year</label>
                                        <input type="text" class="input" name="trade_in_year">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Make</label>
                                        <input type="text" class="input" name="trade_in_make">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Model</label>
                                        <input type="text" class="input" name="trade_in_model">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Trim</label>
                                        <input type="text" class="input" name="trade_in_trim">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">Odometer</label>
                                        <!-- <span class="right-text"> Kms</span> -->
                                        <input type="text" class="input" name="trade_in_odometer">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-5 col-md-5" style="display: inline-block;">
                                        <label class="label">VIN</label>
                                        <input type="text" class="input" name="trade_in_vin">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <label class="label">Current Loan Amount</label>
                                <div class="half_block">
                                    <input class="one_half form-control" type="text" name="current_loan_Amount" class="form-control" placeholder="Loan Amount">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <label class="label">Lien Holder Name</label>
                                <div class="half_block">
                                    <input class="one_half form-control" type="text" name="lien_holder_name" class="form-control" placeholder="Lien Holder Name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <div>
                                    <label class="">Current Payment Option</label>
                                    <div class="checkbox-values">
                                        <input id="current_payment_option_monthly" name="current_payment_option" value="Monthly" type="radio" class="radio">
                                        <label for="current_payment_option_monthly" class="radio-label">Monthly</label>

                                        <input id="current_payment_option_biweekly" name="current_payment_option" value="Bi-weekly" type="radio" class="radio">
                                        <label for="current_payment_option_biweekly" class="radio-label">Bi-weekly</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form_group_inner">
                                <div class="half_block">
                                    <div class="half_block">
                                        <label class="">VOI (Vehicle of interest)</label>
                                        <select id="inventory_id" name="inventory_id" class="form-control">
                                            <option disabled selected>Choose...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form_group_inner">
                                <label class="label">Looking for other vehicles? Provide details.</label>
                                <div class="half_block">
                                    <div class="half_block_inner">
                                        <label class="label">Year</label>
                                        <input class="one_half form-control" type="text" name="year" class="form-control" placeholder="Year">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="half_block_inner">
                                        <label class="label">Make</label>
                                        <input class="one_half form-control" type="text" name="make" class="form-control" placeholder="Make">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <div class="half_block">
                                    <div class="half_block_inner">
                                        <label class="label">Model</label>
                                        <input class="one_half form-control" type="text" name="model" class="form-control" placeholder="Model">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="half_block_inner">
                                        <label class="label">Trim</label>
                                        <input class="one_half form-control" type="text" name="trim" class="form-control" placeholder="Trim">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group_inner">
                                <div class="half_block">
                                    <div class="half_block_inner">
                                        <label class="label">Color</label>
                                        <input class="one_half form-control" type="text" name="color" class="form-control" placeholder="Color">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="half_block_inner">
                                        <label class="label">Odometer</label>
                                        <input class="one_half form-control" type="text" name="odometer" class="form-control" placeholder="Odometer">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_group_inner">
                                <label class="">Does the vehicle has any smells, pets inside, the smoked in or any other damage disclosures</label>
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
                                <textarea name="message" rows="8" class="form-control" placeholder="" id="credit-application-message"></textarea>
                                <div class="link-set">
                                    <p>By signing and submitting, I authorize CARWORLD HEAVEN INC O/A CARWORLD HEAVEN or any affiliate company Carworld Heaven does business with in order to facilitate my optimal credit approval and to run my credit check and submit my information through the Dealertrack portal to various lenders along with pulling my credit report from Equifax or Transunion credit reporting agencies to facilitate a credit approval for a vehicle.</p>
                                </div>

                            </div>
                            <div class="form_group_inner">
                                <div class="half_block">
                                    <div class="half_block">
                                        <label class="label">Signature (Full Name)</label>
                                        <input class="one_half form-control" type="text" name="full_name" class="form-control" placeholder="Full Name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_group_inner">
                                <img src="" id="captcha_image" alt="" srcset="">
                                <button id="request_captcha" class="btn btn-link">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </div>
                            <div class="form_group_inner">
                                <input type="text" style="width: auto;" name="captcha" class="form-control" placeholder="Type Image Characters" />
                                <div class="invalid-feedback" id="captcha-error"></div>
                            </div>

                        </div>

                    </div>
                    <!-- <div class="col-md-12">
                        <p class="disclaimer-form">Disclaimer: <?php echo $disclosure; ?></p>
                    </div> -->

                </div>
                <div class="btn-center-l">
                    <a type="submit" class="action back btn btn-info" style="display: none;">Back</a>
                    <a class="action next btn btn-info">Next</a>
                    <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                </div>

            </form>

        </div>

    </div>
    <!-- partial -->

    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js'></script>
    <script src='https://rawcdn.githack.com/SochavaAG/example-mycode/master/pens/calculator/plugins/rangeslider/rangeslider.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="<?php echo getenv('image.cdn') ?>/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>

    <script>
        const settings = {
            fill: '#06b651',
            background: '#d7dcdf'
        }

        const sliders = document.querySelectorAll('.range-slider');

        Array.prototype.forEach.call(sliders, (slider) => {

            slider.querySelector('input').addEventListener('input', (event) => {

                slider.querySelector('span').innerHTML = event.target.value;
                document.getElementById('rangeValue').innerHTML = event.target.value;

                applyFill(event.target);
            });
            applyFill(slider.querySelector('input'));
        });

        function applyFill(slider) {
            const percentage = 100 * (slider.value - slider.min) / (slider.max - slider.min);
            const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage+0.1}%)`;
            slider.style.background = bg;
        }

        document.getElementById("budget_input").dispatchEvent(new Event("input"));
    </script>
    <script>
        const settings1 = {
            fill: '#06b651',
            background: '#d7dcdf'
        }

        const sliders1 = document.querySelectorAll('.range-slider1');

        Array.prototype.forEach.call(sliders1, (slider) => {

            slider.querySelector('input').addEventListener('input', (event) => {

                slider.querySelector('span').innerHTML = event.target.value;
                document.getElementById('rangeValue1').innerHTML = event.target.value;

                applyFill(event.target);
            });
            applyFill(slider.querySelector('input'));
        });

        function applyFill(slider) {
            const percentage = 100 * (slider.value - slider.min) / (slider.max - slider.min);
            const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage+0.1}%)`;
            slider.style.background = bg;
        }

        document.getElementById("downpayment_input").dispatchEvent(new Event("input"));
    </script>



    <script>
        $(document).ready(function() {
            var source_url = (window.location != window.parent.location) ?
                document.referrer :
                document.location.href;

            document.querySelector(`input[name='source_url'`).value = source_url;

            var current = 1;

            widget = $(".step");
            //        btnskip     = $(".skip");
            btnnext = $(".next");
            btnback = $(".back");
            btnsubmit = $(".submit");

            // Init buttons and UI
            widget.not(':eq(0)').hide();
            hideButtons(current);
            setProgress(current);

            // Next button click action
            btnnext.click(function(event, blank_check_flag = false, check_validations = true) {

                var validator = $("#lead_form").validate();

                parent.postMessage({
                    type: "css",
                    value: $(document.body).outerHeight(true) + 230,
                    element_id: "finance_form"
                }, "*");

                if (validator.form() === false && check_validations) {

                    const invalid_doms = document.querySelectorAll(".error");

                    if (invalid_doms.length > 0) {
                        invalid_doms[0].scrollIntoView({
                            behavior: 'smooth'
                        });
                    }

                    parent.postMessage({
                        type: "css",
                        value: $(document.body).outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");

                    return false;
                }

                if (current < widget.length) {
                    widget.show();
                    widget.not(':eq(' + (current++) + ')').hide();
                    setProgress(current);

                    parent.postMessage({
                        type: "css",
                        value: $(document.body).outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");

                    parent.postMessage({
                        type: "scroll_top",
                        value: $(document.body).outerHeight(true) + 230,
                        element_id: "finance_form"
                    }, "*");
                }
                hideButtons(current);
            })
            // skip button click action
            // btnskip.click(function(){
            //			if(current < widget.length){
            //				widget.show();
            //				widget.not(':eq('+(current++)+')').hide();
            //				setProgress(current);
            //			}
            //			hideButtons(current);
            //		})

            // Back button click action
            btnback.click(function() {
                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', [false, false, false]);
                }
                hideButtons(current);
            });

            setTimeout(() => {
                parent.postMessage({
                    type: "css",
                    value: $(document.body).outerHeight(true) + 230,
                    element_id: "finance_form"
                }, "*");
            }, 1000);


        });

        // Change progress bar action
        setProgress = function(currstep) {
            console.log(currstep);
            var percent = parseFloat(100 / widget.length) * currstep;
            percent = percent.toFixed();
            $(".progress-bar").css("width", percent + "%").html("");
            // $(`div.stepwizard-step > a`).addClass("btn-default").removeClass("btn-primary");
            $(`.stepwizard-step:nth-child(${currstep})>a`).addClass("btn-primary");

        }

        // Hide buttons according to the current step
        hideButtons = function(current) {
            var limit = parseInt(widget.length);

            $(".action").hide();

            if (current < limit) btnnext.show();
            //if(current < limit) btnskip.show();
            if (current > 1) btnback.show();
            if (current == limit) {
                btnnext.hide();
                btnsubmit.show();
            }
        }

        $('#residence_status').on('change', function() {
            if ($(this).find(":checked").val() === "OWN") {
                document.getElementById("own-details").style.display = "block";
            } else {
                document.getElementById("own-details").style.display = "none";
            }

            parent.postMessage({
                    type: "css",
                    value: $(document.body).outerHeight(true),
                    element_id: "finance_form"
                }, "*");
        });

        $('#primary_income_source').on('change', function() {
            if ($(this).find(":checked").val() === "EMPLOYED FULL TIME") {
                document.getElementById("employment-complete-details").style.display = "block";
            } else if ($(this).find(":checked").val() === "EMPLOYED PART TIME") {
                document.getElementById("employment-complete-details").style.display = "block";
            } else {
                document.getElementById("employment-complete-details").style.display = "none";
            }
            parent.postMessage({
                    type: "css",
                    value: $(document.body).outerHeight(true),
                    element_id: "finance_form"
                }, "*");
        });

        $('#trade_in_vehicle').on('change', function() {
            if ($(this).find(":checked").val() === "YES") {
                document.getElementById("trade-in-details").style.display = "block";
            } else {
                document.getElementById("trade-in-details").style.display = "none";
            }
            parent.postMessage({
                    type: "css",
                    value: $(document.body).outerHeight(true),
                    element_id: "finance_form"
                }, "*");
        });



        $(`#residence_status,
        #primary_income_source,
            #trade_in_vehicle`).trigger("change");
    </script>
    <script>
        $(document).ready(function() {

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
                        var option = '<option value="' + element.inventory_id + '"> ' + element.year + ' ' + element.make + ' ' + element.model + ' ' + element.trim + ' #' + element.stock_no + ' ' + element.color + '</option>';
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

            setTimeout(() => {
                parent.postMessage({
                    type: "css",
                    value: $(document.body).outerHeight(true),
                    element_id: "finance_form"
                }, "*");

            }, 1000);



            // START Form Validation
            $("#lead_form").validate({
                rules: {
                    vehicle_option: "required",
                    street_address: "required",
                    first_name: "required",
                    last_name: "required",
                    employment_type: "required",
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
                    gross_income: {
                        required: true,
                        min: 0,
                        number: true
                    },
                    residence_status: "required",
                    postal_code: "required",
                    province: "required",
                    city: "required",
                    home_address: "required",
                    date_of_birth: "required",
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
                    employer_work_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    job_title: "required",
                    employer_name: "required",
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
                    mortgage_rent_amount_monthly: {
                        required: true,
                        min: 0,
                        number: true,
                    },
                    income_years: {
                        required: true,
                        min: 0,
                        max: 65,
                        number: true,
                    },
                    income_months: {
                        required: true,
                        min: 0,
                        max: 11,
                        number: true,
                    },
                    full_name: "required"
                },
                messages: {
                    vehicle_option: "Please select the vehicle",
                    street_address: "Please Enter Your Street Address",
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
                    },
                    mobile_phone: {
                        required: "Please Enter Your Mobile Number",
                        number: "Mobile Number should be a Number",
                        minlength: "Your Mobile Number must be 10 characters long"
                    },
                    work_phone: {
                        required: "Please Enter Your Work Phone Number",
                        number: "Work Number should be a Number",
                        minlength: "Your Work Number must be 10 characters long"
                    },
                    sin: {
                        required: "Please Enter Your Sin Number",
                        number: "SIN should be a Number",
                        minlength: "Your Number must be 9 characters long"
                    },
                    date_of_birth: "Please Enter Your Date Of Birth",
                    marital_status: "Please Select Your Marital Status",
                    drivers_license_number: "Please Enter Your Drivers License Number",
                    drivers_license_expiry_date: "Please Enter Your Drivers License Expiry Date",
                    home_address: "Please Enter Your Home Address",
                    city: "Please Enter Your City",
                    province: "Please Enter Your Province",
                    postal_code: "Please Enter Your Postal Code",
                    residence_status: "Please Select Your Residence Status",

                    mortgage_rent_amount_monthly: {
                        required: "Mortgage / Rent Amount is Required",
                        number: "Mortgage / Rent Amount should be a Number"
                    },
                    mortgage_balance_amount: {
                        required: "Mortgage Balance Amount is Required",
                        number: "Mortgage Balance Amount should be a Number"
                    },
                    market_value: {
                        required: "Market Value is Required",
                        number: "Market Value should be a Number"
                    },
                    residence_period_years: {
                        required: "Please Enter the Residence Period Years",

                    },
                    residence_period_months: {
                        required: "Please Enter Residence Period Months",

                    },
                    previous_residence_address_period_years: {
                        required: "Please Enter Previous Residence Years",
                    },
                    previous_residence_period_months: {
                        required: "Please Enter Previous Residence Months",
                    },
                    income_years: {
                        required: "Please Enter Number of Years",
                    },
                    income_months: {
                        required: "Please Enter Number of Months",
                    },

                    employer_name: "Please Enter Your Employer Name",
                    self_employed: "Please Select Your Self Employed",
                    job_title: "Please Enter Your Job Title",
                    employer_work_phone: {
                        required: "Please Enter Your Employer Work Phone Number",
                        number: "Employer Work Phone should be a Number",
                        minlength: "Your Employer Work Phone must be 10 characters long"
                    },
                    gross_income: {
                        required: "Please Enter Your Gross Income",
                        number: "Gross Income should be a Number"

                    },
                    payment_frequency: "Please Select Your Income Frequency",
                    employment_period_years: {
                        required: "Please Enter Employment Period Years",

                    },
                    employment_period_months: {
                        required: "Please Enter Employment Period Months",

                    },

                    employer_address: "Please Enter Your Employer Address",
                    employer_city: "Please Enter Your Employer City",
                    employer_province: "Please Enter Your Employer Province",
                    employer_postal_code: "Please Enter Your Employer Postal Code",
                    previous_work_phone: {
                        required: "Please Enter Your Previous Employer Work Phone Number",
                        number: "Previous Employer Work Phone should be a Number",
                        minlength: "Your Previous Employer Work Phone must be 10 characters long"

                    },
                    previous_employment_period_years: {
                        required: "Please Enter Previous Employment Period Years",

                    },
                    previous_employment_period_months: {
                        required: "Please Enter Previous Employment Period Months",

                    },
                    other_gross_income: {
                        required: "Please Enter Other Gross Income",
                        number: "Other Gross Income should be a Number"

                    },
                    full_name: "Please Enter Your Full Name"
                },
                errorPlacement: function(error, element) {

                    if (element.is(":radio")) {
                        error.appendTo(element.parent().parent().next(".invalid-feedback"));
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
                        data: new FormData(document.getElementById('lead_form')),
                        contentType: false,
                        processData: false,
                        success: function(result) {
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


                        }
                    });
                }
            });
            // END Form Validation

            var $theForm = $("#lead_form"),
                $form = $theForm.validate();
            //changed to check every textbox type input inside #theForm:
            $theForm.on("blur", "input", "select", function(e) {
                //use element() to test only one element:
                if ($form.element(this)) {
                    console.log("is valid");
                }
            });
        });
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

</html>