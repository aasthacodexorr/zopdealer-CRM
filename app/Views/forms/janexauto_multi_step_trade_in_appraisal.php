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
                                                    <li>
                                                        <div class="head-felid1">
                                                            <label>Vin</label>
                                                            <input type="text" class="form-control" name="vin" id="trade-vin" placeholder="Vehicle Identification Number (17 characters)">
                                                            <div id="vin_error" class="invalid-feedback"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none;">
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
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Do you own the vehicle outright?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="vehicle-outright-yes" name="vehicle_outright" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="vehicle-outright-financed" name="vehicle_outright" value="No, it's financed"><label>No, it's financed</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="vehicle-outright-leased" name="vehicle_outright" value="No, it's leased"><label>No, it's leased</label>
                                                            </div>
                                                            <div id="vehicle_outright_error" class="invalid-feedback"></div>
                                                        </li>
                                                        <label class="sec-lab remaining-loan d-none">What is the remaining loan balance?</label>
                                                        <ul class="form-list-desgine remaining-loan d-none">
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <input type="text" class="form-control" name="loan_balance" id="loan-balance" placeholder="">
                                                                    <div id="loan_balance_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Are you the sole owner of this vehicle?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-sole-owner-vehicle-yes" name="sole_owner_vehicle" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-sole-owner-vehicle-no" name="sole_owner_vehicle" value="No, I have a co-owner"><label>No, I have a co-owner</label>
                                                            </div>
                                                            <div id="sole_owner_vehicle_error" class="invalid-feedback"></div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Select Vehicle Colour</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <select id="trade-vehicle-colour" name="vehicle_colour" class="form-control">
                                                                    <option disabled="" selected="">Select Vehicle Colour</option>
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
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Are you selling or trading in?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-selling-trading-yes" name="selling_or_trading" value="Selling"><label>Selling</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-selling-trading-no" name="selling_or_trading" value="Trading in"><label>Trading in</label>
                                                            </div>
                                                            <div id="selling_or_trading_error" class="invalid-feedback"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none;">
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Additional Features</h3>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1 check-list-box">
                                                                <label class="sec-lab">Select any additional features your car has.</label>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-window_tint" name="additional_features[]" value="Window tint"><label>Window tint</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-second_set_of_tires" name="additional_features[]" value="Second set of tires"><label>Second set of tires</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-pickup_truck_bed_tonneau_cover" name="additional_features[]" value="Pickup truck bed tonneau cover"><label>Pickup truck bed tonneau cover</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-tow_hitch" name="additional_features[]" value="Tow hitch"><label>Tow hitch</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-roof_rack" name="additional_features[]" value="Roof rack"><label>Roof rack</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-remote_starter" name="additional_features[]" value="Remote starter"><label>Remote starter</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_rims" name="additional_features[]" value="Aftermarket rims"><label>Aftermarket rims</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_stereo" name="additional_features[]" value="Aftermarket stereo"><label>Aftermarket stereo</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_exhaust" name="additional_features[]" value="Aftermarket exhaust"><label>Aftermarket exhaust</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_suspension" name="additional_features[]" value="Aftermarket suspension"><label>Aftermarket suspension</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_spoiler" name="additional_features[]" value="Aftermarket spoiler"><label>Aftermarket spoiler</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_cosmetic_modifications" name="additional_features[]" value="Aftermarket cosmetic modifications (including splitters, diffuses, and side skirts)"><label>Aftermarket cosmetic modifications (including splitters, diffuses, and side skirts)</label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-aftermarket_performance_upgrades" name="additional_features[]" value="Aftermarket performance upgrades"><label>Aftermarket performance upgrades</label>
                                                                </div>
                                                                <div class="head-felid w-50 check-list-box">
                                                                    <input type="checkbox" class="seclect-ratio-btn" id="trade-additional-features-other" name="additional_features[]" value="Other"><label>Other</label>
                                                                </div>
                                                            </div>
                                                            <label class="sec-lab other-features d-none">What other features are there?</label>
                                                            <ul class="form-list-desgine other-features d-none">
                                                                <li>
                                                                    <div class="head-felid1">
                                                                        <textarea name="other_comment" rows="8" class="form-control" placeholder="" id="trade-other-comment"></textarea>
                                                                        <div id="other_comment_error" class="invalid-feedback"></div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div id="additional_features_error" class="invalid-feedback"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display:none ;">
                                    <div class="head-text-f">
                                        <div class="set-box">
                                            <div class="col-md-12">
                                                <div class="set-box">
                                                    <h3>Condition & History</h3>
                                                    <ul class="form-list-desgine">
                                                        <li>
                                                            <div class="head-felid1 check-list-box">
                                                                <label class="sec-lab">Does your vehicle have any exterior damage?</label>
                                                            </div>
                                                            <div class="head-felid check-list-box">
                                                                <input type="checkbox" class="seclect-ratio-btn" id="trade-exterior-damage-rust" name="exterior_damage[]" value="Rust"><label>Rust</label>
                                                            </div>
                                                            <div class="head-felid check-list-box">
                                                                <input type="checkbox" class="seclect-ratio-btn" id="trade-exterior-damage-chipped-cracked" name="exterior_damage[]" value="Chipped or cracked windshield"><label>Chipped or cracked windshield</label>
                                                            </div>
                                                            <div class="head-felid check-list-box">
                                                                <input type="checkbox" class="seclect-ratio-btn" id="trade-exterior-damage-minor-cosmetic" name="exterior_damage[]" value="Minor cosmetic damage smaller than a golf ball (dents, scratches, or paint chips)"><label>Minor cosmetic damage smaller than a golf ball (dents, scratches, or paint chips)</label>
                                                            </div>
                                                            <div class="head-felid check-list-box">
                                                                <input type="checkbox" class="seclect-ratio-btn" id="trade-exterior-damage-moderate-cosmetic-damages" name="exterior_damage[]" value="Moderate cosmetic damages larger than a golf ball (dents, scrapes, or paint chips)"><label>Moderate cosmetic damages larger than a golf ball (dents, scrapes, or paint chips)</label>
                                                            </div>
                                                            <div class="head-felid check-list-box">
                                                                <input type="checkbox" class="seclect-ratio-btn" id="trade-exterior-damage-cracked-bodywork" name="exterior_damage[]" value="Cracked Bodywork"><label>Cracked Bodywork</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="checkbox" class="seclect-ratio-btn" id="trade-exterior-damage-no-exterior-damage" name="exterior_damage[]" value="No Exterior Damage"><label>No Exterior Damage</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Select the number of keys you own for this vehicle</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <select id="trade-number-of-keys" name="number_of_keys" class="form-control">
                                                                    <option disabled="" selected="">Select Number of Keys</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2+">2+</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Do you smoke in this vehicle?</label>
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
                                                                <label class="sec-lab">Do you have the original factory rims?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-original-factory-rims-yes" name="original_factory_rims" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-original-factory-rims-no" name="original_factory_rims" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Have you replaced your tires in the past 12 months?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-tires-yes" name="tires" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-tires-no" name="tires" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Is there anything that would prevent us from driving your vehicle?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-prevent-driving-vehicle-yes" name="prevent_driving_vehicle" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-prevent-driving-vehicle-no" name="prevent_driving_vehicle" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Has the vehicle been involved in an accident or insurance claim?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="accident-insurance-vehicle-yes" name="accident_or_insurance" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="accident-insurance-vehicle-no" name="accident_or_insurance" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <label class="sec-lab total-amount d-none">What was the total cost of repairs for all accident and insurance claims?</label>
                                                        <ul class="form-list-desgine total-amount d-none">
                                                            <li>
                                                                <div class="head-felid1">
                                                                    <input type="text" class="form-control" name="cost_accident_insurance" id="cost_accident_insurance" placeholder="">
                                                                    <div id="cost_accident_insurance_error" class="invalid-feedback"></div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Does the vehicle have any mechanical issues or warning lights? </label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="mechanical-issue-yes" name="mechanical_issue" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="mechanical-issue-no" name="mechanical_issue" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <label class="sec-lab issue-mechanical d-none">Select all that apply.</label>
                                                        <ul class="form-list-desgine issue-mechanical d-none">
                                                            <li>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Engine"><label>Engine</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Transmission"><label>Transmission</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Brakes / ABS system"><label>Brakes / ABS system</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Exhaust"><label>Exhaust</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="A/C"><label>A/C</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Oil Leak"><label>Oil Leak</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Suspension"><label>Suspension</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Airbag"><label>Airbag</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Battery"><label>Battery</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="TPMS"><label>TPMS</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Traction Control"><label>Traction Control</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Powersteering system"><label>Powersteering system</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Infotainment System (stereo, navigation, car play, etc.)"><label>Infotainment System (stereo, navigation, car play, etc.)</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="mechanical_reason" name="mechanical_reason[]" value="Blindspot sensor / adaptive cruise control / AEB / back up camera"><label>Blindspot sensor / adaptive cruise control / AEB / back up camera</label>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Any additional disclosures?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="disclosures-yes" name="disclosures" value="Yes"><label>Yes</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="disclosures-no" name="disclosures" value="No"><label>No</label>
                                                            </div>
                                                        </li>
                                                        <label class="sec-lab other-disclosure d-none">Select all that apply.</label>
                                                        <ul class="form-list-desgine other-disclosure d-none">
                                                            <li>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="kind_of_disclosure" name="kind_of_disclosure[]" value="Salvage"><label>Salvage</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="kind_of_disclosure" name="kind_of_disclosure[]" value="Rebuild Title"><label>Rebuild Title</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="kind_of_disclosure" name="kind_of_disclosure[]" value="Fire"><label>Fire</label>
                                                                    </div>
                                                                    <div class="head-felid w-50 check-list-box">
                                                                        <input type="checkbox" class="seclect-ratio-btn" id="kind_of_disclosure" name="kind_of_disclosure[]" value="Flood"><label>Flood</label>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">Select the condition that best matches your car</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-condition-of-vehicle-Excellent" name="condition_of_vehicle" value="Excellent"><label><strong>Excellent</strong>
                                                                    Looks new with exceptional exterior and interior conditions
                                                                </label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-condition-of-vehicle-Good" name="condition_of_vehicle" value="Good"><label><strong>Good</strong>
                                                                    Some normal wear but no major cosmetic problems
                                                                </label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-condition-of-vehicle-Poor" name="condition_of_vehicle" value="Poor"><label><strong>Poor</strong>
                                                                    Several cosmetic problems that require repairing/replacing
                                                                </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="head-felid1">
                                                                <label class="sec-lab">When do you plan on selling your vehicle?</label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-plan-to-sell-Immediately" name="plan_to_sell" value="Immediately"><label><strong>Immediately</strong>
                                                                    I'm ready to sell my car and just comparing options
                                                                </label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-plan-to-sell-Almost-Ready" name="plan_to_sell" value="Almost Ready"><label><strong>Almost Ready</strong>
                                                                    I'm planning to sell my car in the next 3 months
                                                                </label>
                                                            </div>
                                                            <div class="head-felid">
                                                                <input type="radio" class="seclect-ratio-btn" id="trade-plan-to-sell-Not-Ready-Yet" name="plan_to_sell" value="Not Ready Yet"><label><strong>Not Ready Yet</strong>
                                                                    I'm curious about what my car is worth
                                                                </label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step well" style="display: none;">
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
                                                    <p>I have read and accept the <a href="https://www.janexauto.com/terms-conditions">Terms of Use</a> . I also acknowledge having read the <a href="https://www.janexauto.com/privacy-policy/">Privacy Policy</a> and agree to the terms herein.</p>
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
                vin: "Please Enter VIN",
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

        $('#vehicle-outright-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.remaining-loan').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-financed').change(function() {
            if ($(this).is(":checked")) {
                $('.remaining-loan').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#vehicle-outright-leased').change(function() {
            if ($(this).is(":checked")) {
                $('.remaining-loan').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-window_tint').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-second_set_of_tires').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-pickup_truck_bed_tonneau_cover').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-tow_hitch').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-roof_rack').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-remote_starter').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_rims').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_stereo').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_exhaust').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_suspension').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_spoiler').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_cosmetic_modifications').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-aftermarket_performance_upgrades').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#trade-additional-features-other').change(function() {
            if ($(this).is(":checked")) {
                $('.other-features').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#accident-insurance-vehicle-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.total-amount').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#accident-insurance-vehicle-no').change(function() {
            if ($(this).is(":checked")) {
                $('.total-amount').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#mechanical-issue-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.issue-mechanical').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#mechanical-issue-no').change(function() {
            if ($(this).is(":checked")) {
                $('.issue-mechanical').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#disclosures-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.other-disclosure').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");
        });

        $('#disclosures-no').change(function() {
            if ($(this).is(":checked")) {
                $('.other-disclosure').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
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

</body>