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
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 8%;"></div>
                            </div>
                            <div class="list">
                                <div class="step well" style="display:block;">
                                    <p class="step-count-num">1/9</p>
                                    <div class="head-text-f">
                                        <div class="col-md-12">
                                            <div class="set-box">
                                                <h3>Selling your car just got easier</h3>
                                                <p>Tell us about your ride to get a firm offer in minutes.</p>
                                                <ul class="form-list-desgine">
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>Year</label>
                                                            <input min="1960" name="year" type="text" class="form-control" placeholder="Year" id="trade-year">
                                                            <div id="year_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>Make</label>
                                                            <input type="text" name="make" class="form-control" placeholder="Make" id="trade-make" />
                                                            <div id="make_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>Model</label>
                                                            <input type="text" name="model" class="form-control" placeholder="Model" id="trade-model" />
                                                            <div id="model_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:block;">
                                    <p class="step-count-num">2/9</p>
                                    <div class="head-text-f">
                                        <div class="col-md-12">
                                            <div class="set-box">
                                                <h3>Selling your car just got easier</h3>
                                                <p>Tell us about your ride to get a firm offer in minutes.</p>
                                                <ul class="form-list-desgine">
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>Vin</label>
                                                            <input type="text" class="form-control" name="vin" id="trade-vin" placeholder="Vehicle Identification Number (17 characters)">
                                                            <div id="vin_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>City</label>
                                                            <input type="text" class="form-control" name="city" id="trade-city" placeholder="City">
                                                            <div id="city_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>Province</label>
                                                            <input type="text" class="form-control" name="province" id="trade-province" placeholder="Province">
                                                            <div id="province_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none;">
                                    <p class="step-count-num">3/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Tell us about your vehicle</h3>
                                                    <p>Tell us about your ride to get a firm offer in minutes.</p>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label>Body Trim</label>
                                                                <input type="text" class="form-control" name="body_trim" id="trade-body_trim" placeholder="Body Trim">
                                                                <div id="vin_error" class="invalid-feedback"></div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label>Style</label>
                                                                <input type="text" class="form-control" name="style" id="trade-style" placeholder="Style">
                                                                <div id="vin_error" class="invalid-feedback"></div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Select Exterior Colour</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <select id="trade-exterior-colour" name="exterior_colour" class="form-control">
                                                                    <option disabled="" selected="">Select Exterior Colour</option>
                                                                    <option value="Maroon">Maroon</option>
                                                                    <option value="Black">Black</option>
                                                                    <option value="Brown">Brown</option>
                                                                    <option value="Yellow">Yellow</option>
                                                                    <option value="Blue">Blue</option>
                                                                    <option value="Gold">Gold</option>
                                                                    <option value="Bronze">Bronze</option>
                                                                    <option value="Green">Green</option>
                                                                    <option value="Silver">Silver</option>
                                                                    <option value="Orange">Orange</option>
                                                                    <option value="Tan">Tan</option>
                                                                    <option value="White">White</option>
                                                                    <option value="Grey">Grey</option>
                                                                    <option value="Black">Black</option>
                                                                    <option value="Purple">Purple</option>
                                                                    <option value="Beige">Beige</option>
                                                                    <option value="Other">Other</option>
                                                                    <option value="Copper">Copper</option>
                                                                    <option value="Champagne">Champagne</option>
                                                                    <option value="Sand">Sand</option>
                                                                    <option value="Pink">Pink</option>
                                                                </select>
                                                            </div>
                                                            <div id="exterior_colour_error" class="invalid-feedback"></div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Select Interior Colour</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <select id="trade-interior-colour" name="interior_colour" class="form-control">
                                                                    <option disabled="" selected="">Select Interior Colour</option>
                                                                    <option value="Maroon">Maroon</option>
                                                                    <option value="Black">Black</option>
                                                                    <option value="Brown">Brown</option>
                                                                    <option value="Yellow">Yellow</option>
                                                                    <option value="Blue">Blue</option>
                                                                    <option value="Gold">Gold</option>
                                                                    <option value="Bronze">Bronze</option>
                                                                    <option value="Green">Green</option>
                                                                    <option value="Silver">Silver</option>
                                                                    <option value="Orange">Orange</option>
                                                                    <option value="Tan">Tan</option>
                                                                    <option value="White">White</option>
                                                                    <option value="Grey">Grey</option>
                                                                    <option value="Black">Black</option>
                                                                    <option value="Purple">Purple</option>
                                                                    <option value="Beige">Beige</option>
                                                                    <option value="Other">Other</option>
                                                                    <option value="Copper">Copper</option>
                                                                    <option value="Champagne">Champagne</option>
                                                                    <option value="Sand">Sand</option>
                                                                    <option value="Pink">Pink</option>
                                                                </select>
                                                            </div>
                                                            <div id="vehicle_colour_error" class="invalid-feedback"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none;">
                                    <p class="step-count-num">4/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Tell us about your vehicle</h3>
                                                    <p>Tell us about your ride to get a firm offer in minutes.</p>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">What's the approximate mileage on your car?</label>
                                                                <input type="text" class="form-control" name="odometer" id="trade-odometer" placeholder="KM">
                                                                <div id="odometer_error" class="invalid-feedback"></div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Select your transmission type</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-automatic" name="transmission_type" value="Automatic"><label>Automatic</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-manual" name="transmission_type" value="Manual"><label>Manual</label>
                                                            </div>
                                                            <div id="transmission_type_error" class="invalid-feedback"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none ;">
                                    <p class="step-count-num">5/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Condition & History</h3>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1 check-list-box">
                                                                <label class="sec-lab">Does your vehicle have any existing exterior damage?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-exterior-damage-yes" name="exterior_damage" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-exterior-damage-no" name="exterior_damage" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1 check-list-box">
                                                                <label class="sec-lab">Does your vehicle have any existing interior damage?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-interior-damage-yes" name="interior_damage" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-interior-damage-no" name="interior_damage" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Does your vehicle have any accidents or claims in its history?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="accident-insurance-vehicle-yes" name="accident_or_insurance" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="accident-insurance-vehicle-no" name="accident_or_insurance" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none ;">
                                    <p class="step-count-num">6/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Condition & History</h3>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Has your vehicle ever been smoked in?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-smoke-in-vehicle-yes" name="smoke_in_vehicle" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-smoke-in-vehicle-no" name="smoke_in_vehicle" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Are there any chips or cracks in the windshield?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-chips-cracks-windshield-yes" name="chips_or_cracks_windshield" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-chips-cracks-windshield-no" name="chips_or_cracks_windshield" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">How many key fobs do you have for your vehicle?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <select id="trade-number-of-keys" name="number_of_keys" class="form-control">
                                                                    <option disabled="" selected="">Select Number of Keys</option>
                                                                    <option value="None">None</option>
                                                                    <option value="1 Key Fob">1 Key Fob</option>
                                                                    <option value="2+  Key Fobs">2+ Key Fobs</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none ;">
                                    <p class="step-count-num">7/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Condition & History</h3>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">When did you last replace your tires?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-tires-less-than-2-years-ago" name="tires" value="Less than 2 Years Ago"><label>Less than 2 Years Ago</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-tires-2-to-4-years-ago" name="tires" value="2 to 4 Years Ago"><label>2 to 4 Years Ago</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-tires-more-than-4-years-ago" name="tires" value="More than 4 Years Ago or Unsure"><label>More than 4 Years Ago or Unsure</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Have you had any indication your brakes may be worn, including unusual sounds or pulsation while braking?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-brakes-yes" name="brakes" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-brakes-no" name="brakes" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Does your vehicle have any mechanical issues or dashboard warning lights?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="mechanical-issue-yes" name="mechanical_issue" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="mechanical-issue-no" name="mechanical_issue" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none ;">
                                    <p class="step-count-num">8/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Condition & History</h3>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Does your vehicle have a rebuilt or salvage status?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="mechanical-rebuilt-salvage-yes" name="rebuilt_salvage" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="mechanical-rebuilt-salvage-no" name="rebuilt_salvage" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Do you own your vehicle outright or is it leased or financed?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="vehicle-outright-owned" name="vehicle_outright" value="Owned"><label>Owned</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="vehicle-outright-leased" name="vehicle_outright" value="Leased"><label>Leased</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="vehicle-outright-financed" name="vehicle_outright" value="Financed"><label>Financed</label>
                                                            </div>
                                                            <div id="vehicle_outright_error" class="invalid-feedback"></div>
                                                        </li>
                                                        <!-- <label class="sec-lab leased d-none"></label> -->
                                                        <ul class="form-list-desgine leased d-none">
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <label class="sec-lab">How much do you still owe on your vehicle?</label>
                                                                    <input type="text" class="form-control" name="pending_amount_lease" id="trade-pending-amount-lease" placeholder="">
                                                                    <div id="pending_amount_lease_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <label class="sec-lab">How much do you pay every month?</label>
                                                                    <input type="text" class="form-control" name="monthly_payment_lease" id="trade-monthly-payment-lease" placeholder="">
                                                                    <div id="monthly_payment_lease_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <label class="sec-lab">Who is your vehicle leased with?</label>
                                                                    <input type="text" class="form-control" name="leased_with" id="trade-leased-with" placeholder="">
                                                                    <div id="leased_with_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <!-- <label class="sec-lab financed d-none"></label> -->
                                                        <ul class="form-list-desgine financed d-none">
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <label class="sec-lab">How much do you still owe on your vehicle?</label>
                                                                    <input type="text" class="form-control" name="pending_amount_finance" id="trade-pending-amount-finance" placeholder="">
                                                                    <div id="pending_amount_finance_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <label class="sec-lab">How much do you pay every month?</label>
                                                                    <input type="text" class="form-control" name="monthly_payment_finance" id="trade-monthly-payment-finance" placeholder="">
                                                                    <div id="monthly_payment_finance_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <label class="sec-lab">Who is your vehicle financed with?</label>
                                                                    <input type="text" class="form-control" name="financed_with" id="trade-financed-with" placeholder="">
                                                                    <div id="financed_with_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Would you like to sell your car or trade it in? You can change your mind later. Your selection will not influence the offer you receive.</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-in-sell" name="sell_car_or_trade_in" value="Sell"><label>Sell</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-in-trade-in" name="sell_car_or_trade_in" value="Trade In"><label>Trade In</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-in-unsure" name="sell_car_or_trade_in" value="Unsure"><label>Unsure</label>
                                                            </div>
                                                            <div id="sell_car_or_trade_in_error" class="invalid-feedback"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display: none;">
                                    <p class="step-count-num">9/9</p>
                                    <div class="head-text-f">
                                        <div class="set-box row">
                                            <h3>Congratulations your Pre-Approval is Ready!</h3>
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
                                                    <p>I have read and accept the <a href="https://www.canadianautonapa.ca/terms-conditions">Terms of Use</a> . I also acknowledge having read the <a href="https://www.canadianautonapa.ca/privacy-policy/">Privacy Policy</a> and agree to the terms herein.</p>
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
                    "removeMaskOnSubmit": true,
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
                trim: "required",
                vin: "required",
                city: "required",
                province: "required",
                odometer: {
                    required: true,
                    number: true
                },
                current_km: {
                    required: true,
                    number: true
                },
                body_trim: "required",
                style: "required",
                exterior_colour: "required",
                interior_colour: "required",
                transmission_type: "required",
                exterior_damage: "required",
                interior_damage: "required",
                accident_or_insurance: "required",
                smoke_in_vehicle: "required",
                chips_or_cracks_windshield: "required",
                number_of_keys: "required",
                tires: "required",
                brakes: "required",
                mechanical_issue: "required",
                rebuilt_salvage: "required",
                vehicle_outright: "required",
                sell_car_or_trade_in: "required",
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
                city: "Please Enter City",
                province: "Please Enter Province",
                odometer: {
                    required: "Please Enter Odometer",
                    number: "Odometer should be a Number"
                },
                current_km: {
                    required: "Please Enter Current KM",
                    number: "Current KM should be a Number"
                },
                body_trim: "Please Enter the Body Trim",
                style: "Please Enter the Style",
                exterior_colour: "Please Select the Exterior Colour",
                interior_colour: "Please Select the Interior Colour",
                transmission_type: "Please Select Transmission Type",
                number_of_keys: "Please Select Number of Keys",
                smoke_in_vehicle: "Please Select whether you Smoke in Vehicle",
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

        $('#vehicle-outright-owned').change(function() {
            if ($(this).is(":checked")) {
                $('.leased').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-leased').change(function() {
            if ($(this).is(":checked")) {
                $('.leased').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-financed').change(function() {
            if ($(this).is(":checked")) {
                $('.leased').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-owned').change(function() {
            if ($(this).is(":checked")) {
                $('.financed').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-leased').change(function() {
            if ($(this).is(":checked")) {
                $('.financed').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-financed').change(function() {
            if ($(this).is(":checked")) {
                $('.financed').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });


        $(`
        #vehicle-outright-owned,
        #vehicle-outright-leased,
        #vehicle-outright-financed
        
        `).trigger('change');
    </script>

</body>