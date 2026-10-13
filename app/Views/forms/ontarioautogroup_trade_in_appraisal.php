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
                                    <label>1. <strong>Personal Info</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>First Name</strong></p>
                                    <input name="first_name" type="text" class="form-control" placeholder="" id="trade-first-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last Name</strong></p>
                                    <input type="text" name="last_name" class="form-control" placeholder="" id="trade-last-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input type="email" name="email" class="form-control" placeholder="" id="trade-email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="trade-phone-number" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>2. <strong>Vehicle Description</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Year</strong></p>
                                    <input min="1960" name="year" type="text" class="form-control" placeholder="" id="trade-year" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Make</strong></p>
                                    <input type="text" name="make" class="form-control" placeholder="" id="trade-make" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Model</strong></p>
                                    <input type="text" name="model" class="form-control" placeholder="" id="trade-model" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Trim</strong></p>
                                    <input type="text" name="trim" class="form-control" placeholder="" id="trade-trim" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20" style="position: relative;">
                                    <p><strong>Odometer</strong></p>
                                    <span class="right-text" style="bottom:unset;"> Kms</span>
                                    <input type="text" name="odometer" class="form-control" placeholder="" id="trade-odometer" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Body Style</strong></p>
                                    <select id="trade-body_style" name="body_style" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Sedan">Sedan</option>
                                        <option value="Cargo Van">Cargo Van</option>
                                        <option value="Pickup Truck">Pickup Truck</option>
                                        <option value="VAN">VAN</option>
                                        <option value="Truck">Truck</option>
                                        <option value="Mini Truck">Mini Truck</option>
                                        <option value="Commercial">Commercial</option>
                                        <option value="Minivan">Minivan</option>
                                        <option value="Other">Other</option>
                                        <option value="Truck Crew Cab">Truck Crew Cab</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Transmission</strong></p>
                                    <select id="trade-transmission" name="transmission" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Manual">Manual</option>
                                        <option value="3 Speed Manual">3 Speed Manual</option>
                                        <option value="4 Speed Manual">4 Speed Manual</option>
                                        <option value="5 Speed Manual">5 Speed Manual</option>
                                        <option value="6 Speed Manual">6 Speed Manual</option>
                                        <option value="7 Speed Manual">7 Speed Manual</option>
                                        <option value="Automatic">Automatic</option>
                                        <option value="2 Speed Automatic">2 Speed Automatic</option>
                                        <option value="3 Speed Automatic">3 Speed Automatic</option>
                                        <option value="4 Speed Automatic">4 Speed Automatic</option>
                                        <option value="5 Speed Automatic">5 Speed Automatic</option>
                                        <option value="6 Speed Automatic">6 Speed Automatic</option>
                                        <option value="7 Speed Automatic">7 Speed Automatic</option>
                                        <option value="8 Speed Automatic">8 Speed Automatic</option>
                                        <option value="9 Speed Automatic">9 Speed Automatic</option>
                                        <option value="10 Speed Automatic">10 Speed Automatic</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Driveline</strong></p>
                                    <select id="trade-driveline" name="driveline" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="4x2">4x2</option>
                                        <option value="4x4">4x4</option>
                                        <option value="AWD">AWD</option>
                                        <option value="FWD">FWD</option>
                                        <option value="RWD">RWD</option>
                                        <option value="2WD">2WD</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Fuel Type</strong></p>
                                    <input type="text" name="fuel_type" class="form-control" placeholder="" id="trade-fuel_type" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Condition</strong></p>
                                    <select id="trade-condition" name="condition" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Fair">Fair</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>VIN</strong></p>
                                    <input type="text" name="vin" class="form-control" placeholder="" id="trade-vin" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Exterior Colour</strong></p>
                                    <select id="trade-exterior_colour" name="exterior_colour" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
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
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <p><strong>Option List</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="option_list_section">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="CD Stereo" /><span class="left-sp">CD Stereo</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Backup Camera" /><span class="left-sp">Backup Camera</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Bind-Spot Alert" /><span class="left-sp">Bind-Spot Alert</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="DVD System" /><span class="left-sp">DVD System</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Heated Seats" /><span class="left-sp">Heated Seats</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Fold Away Third Row" /><span class="left-sp">Fold Away Third Row</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Navigation System" /><span class="left-sp">Navigation System</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Moon Roof" /><span class="left-sp">Moon Roof</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Automated Tailgate" /><span class="left-sp">Automated Tailgate</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Power Seats" /><span class="left-sp">Power Seats</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Panorama Roof" /><span class="left-sp">Panorama Roof</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Rain sensing wipers" /><span class="left-sp">Rain sensing wipers</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Alarm System" /><span class="left-sp">Alarm System</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Canopy" /><span class="left-sp">Canopy</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Lane Departure Warning" /><span class="left-sp">Lane Departure Warning</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Keyless-Go" /><span class="left-sp">Keyless-Go</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Leather" /><span class="left-sp">Leather</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Reverse Sensors" /><span class="left-sp">Reverse Sensors</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Power Windows" /><span class="left-sp">Power Windows</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Tow Package" /><span class="left-sp">Tow Package</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Bluetooth Wireless" /><span class="left-sp">Bluetooth Wireless</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Cruise Control" /><span class="left-sp">Cruise Control</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Remote Start" /><span class="left-sp">Remote Start</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Air Conditioning" /><span class="left-sp">Air Conditioning</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Power Door Locks" /><span class="left-sp">Power Door Locks</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Apple Car Play" /><span class="left-sp">Apple Car Play</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Aftermarket Rims" /><span class="left-sp">Aftermarket Rims</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Modified Exhaust" /><span class="left-sp">Modified Exhaust</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Oversized Tires" /><span class="left-sp">Oversized Tires</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="360 Camera" /><span class="left-sp">360 Camera</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Tonneau Cover" /><span class="left-sp">Tonneau Cover</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="option_list[]" value="Lift Kit" /><span class="left-sp">Lift Kit</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Approximate amount owning (If financed/leased)</strong></p>
                                    <input type="text" name="approximate_amount_owning" class="form-control" placeholder="" id="trade-approximate_amount_owning" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Accident/Claim history</strong></p>
                                    <select id="trade-accident_claim_history" name="accident_claim_history" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Under 2000">Under 2000</option>
                                        <option value="2000-5000">2000-5000</option>
                                        <option value="Over 5000">Over 5000</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Any mechanical issues (If Yes please describe in info box below)</strong></p>
                                    <select id="trade-mechanical_issues" name="mechanical_issues" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Any damages beside minor scratches (If Yes please describe in info box below)</strong></p>
                                    <select id="trade-damage" name="damage" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Upload Car Image(Max Size : 1MB)</strong></p>
                                    <input type="file" name="car_image" class="form-control" placeholder="" id="trade-car_image" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
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
                                <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                <div class="invalid-feedback" id="recaptcha-error"></div>
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
                                <input type="hidden" name="source_url" value="">
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
                value: $(document).height(),
                element_id: "trade_in_appraisal"
            }, "*");

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
                    year: {
                        required: true,
                        min: 1960,
                        max: new Date().getFullYear() + 2,
                        number: true
                    },
                    make: "required",
                    model: "required",
                    odometer: {
                        number: true
                    },
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
                        data: new FormData(document.getElementById('lead_form')), // get all form variables
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
        });
    </script>

</body>

<!---- BODY END ---->

</html>