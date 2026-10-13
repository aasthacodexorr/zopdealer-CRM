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
        #options_section p input {
            vertical-align: bottom;
        }
    </style>

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
                                    <p><strong>Last name</strong></p>
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
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Purpose</strong></p>
                                    <select id="trade-purpose" name="purpose" class="form-control">
                                        <option disabled selected>Select Purpose</option>
                                        <option value="Service Onatrio / Ministry Tax ($49.95)">Service Onatrio / Ministry Tax ($49.95)</option>
                                        <option value="Insurance: Collision / Comprehensive ($119.95)">Insurance: Collision / Comprehensive ($119.95)</option>
                                        <option value="Insurance: Antique / Vintage / Classic ($149.95)">Insurance: Antique / Vintage / Classic ($149.95)</option>
                                        <option value="Insurance: Modified / Custom ($149.95)">Insurance: Modified / Custom ($149.95)</option>
                                        <option value="Insurance: Acccident / Loss Dispute (+$199.95)">Insurance: Acccident / Loss Dispute (+$199.95)</option>
                                        <option value="Legal: Estate Law / Trust ($49.95)">Legal: Estate Law / Trust ($49.95)</option>
                                        <option value="Legal: Family Law / Divorce ($49.95)">Legal: Family Law / Divorce ($49.95)</option>
                                        <option value="Financing / Bank Loan ($49.95)">Financing / Bank Loan ($49.95)</option>
                                        <option value="Liquidation / Bankruptcy ($49.95)">Liquidation / Bankruptcy ($49.95)</option>
                                        <option value="Buy or Resale / Trade-in ($49.95)">Buy or Resale / Trade-in ($49.95)</option>
                                        <option value="Import / Export ($49.95)">Import / Export ($49.95)</option>
                                        <option value="Charity / Donation ($49.95)">Charity / Donation ($49.95)</option>
                                        <option value="Other? (Contact Us)">Other? (Contact Us)</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>2. <strong>Vehicle Information</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Purchase Date (If Available)</strong></p>
                                    <input name="purchase_date" type="date" class="form-control" placeholder="" id="trade-purchase-date" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Year</strong></p>
                                    <input min="1960" name="year" type="text" class="form-control" placeholder="Year" id="trade-year" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Make</strong></p>
                                    <input type="text" name="make" class="form-control" placeholder="Make" id="trade-make" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Model</strong></p>
                                    <input type="text" name="model" class="form-control" placeholder="Model" id="trade-model" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>VIN</strong></p>
                                    <input type="text" name="vin" class="form-control" placeholder="VIN" id="trade-vin" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
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
                                    <p><strong>Colour</strong></p>
                                    <input type="text" name="colour" class="form-control" placeholder="Colour" id="trade-colour" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong># of Cylinders</strong></p>
                                    <input type="text" name="cylinders" class="form-control" placeholder="Cylinders" id="trade-cylinders" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Transmission (Automatic / Manual)</strong></p>
                                    <input type="text" name="transmission" class="form-control" placeholder="Transmission" id="trade-transmission" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong># of Doors</strong></p>
                                    <input type="text" name="doors" class="form-control" placeholder="Doors" id="trade-doors" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Options</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="options_section">
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="None" /><span class="left-sp">None</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="Power Steering" /><span class="left-sp">Power Steering</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="Power Brakes" /><span class="left-sp">Power Brakes</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="Power Windows" /><span class="left-sp">Power Windows</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="Power Locks" /><span class="left-sp">Power Locks</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="Power Seats" /><span class="left-sp">Power Seats</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="Cruise Control" /><span class="left-sp">Cruise Control</span></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><input type="checkbox" name="optons[]" value="A/C" /><span class="left-sp">A/C</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>3. <strong>Vehicle Condition</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p>Include problems before repairs were done (excess wear & severe damage, not regular maintenance). </p>
                                    <p>MECHANICAL PROBLEMS (Part 1)</p>
                                    <p>EXAMPLES: <b>Engine</b> (seized, misfiring, knocking, oil leak), <b>Transmission</b> (seized, slipping gear, rough shifting/clunk, clutch worn, oil leak), <b>Power Train</b> (drive shaft, differential/awd, drive axle, transfer case fail/leak), <b>Electrical</b> (alternator, starter worn/failed), <b>Turbo/Super Charger</b> (fail), <b>Hybrid/Electric Battery</b> (fail), <b>Cooling</b> (radiator, condenser, fan, water pump failed/leak), </p>
                                    <p><strong>Comment [Point Form Notes]</strong></p>
                                    <textarea rows="8" name="mechanical_problems_1" class="form-control" placeholder="" id="trade-mechanical-problems-1"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p>MECHANICAL PROBLEMS (Part 2)</p>
                                    <p>EXAMPLE: <b>Exhaust</b> (manifold, O2 sensor, cat, resonator, muffler, pipe leak/failed), <b>Suspension</b> (strut/shocks, springs, control arm, sway bar, knuckle worn/leak), <b>Steering</b> (rack, gearbox, pump, tires worn/leak), <b>Braking</b> (pads/rotors, master cylinder, caliper, parking/hand brake, line worn/leak),<b>Fuel</b> (gas leak, pump failed).</p>
                                    <p><strong>Comment [Point Form Notes]</strong></p>
                                    <textarea rows="8" name="mechanical_problems_2" class="form-control" placeholder="" id="trade-mechanical-problems-2"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p>BODY PROBLEMS & EXACT LOCATION</p>
                                    <p>EXAMPLES: Bumper, Fender, Hood, Roof/Sunroof, Door, Wheel Well, Quarter Panel, Trunk/Liftgate, Rear Bumper, Rim, Window/Windshield, Light, Mirror, Grille [Rust, Scrape, Dent, Crack, Paint/Clear (faded/peeling/mismatch)]</p>
                                    <p><strong>Comment [Point Form Notes]</strong></p>
                                    <textarea rows="8" name="body_problems_exact_location" class="form-control" placeholder="" id="trade-body-problems-exact-location"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p>INTERIOR PROBLEMS & EXACT LOCATION</p>
                                    <p>EXAMPLES: Options/Features Inoperable (Power Windows, Power Locks, A/C, Entertainment System, Tachometer), Dash Lights (Check Engine Light, Airbag Light, ABS Light), Carpet/Seats/Roof Liner (Ripped, Stained), Panels/Trims (Cracked/Damaged) And Etc.</p>
                                    <p><strong>Comment [Point Form Notes]</strong></p>
                                    <textarea rows="8" name="interior_problems_exact_location" class="form-control" placeholder="" id="trade-interior-problems-exact-location"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Previously Out of State or Country?</strong></p>
                                    <select id="trade-previously-out-of-state-or-country" name="previously_out_of_state_or_country" class="form-control">
                                        <option disabled selected>Select</option>
                                        <option value="No">No</option>
                                        <option value="Not Sure">Not Sure</option>
                                        <option value="Yes, Out of Province">Yes, Out of Province</option>
                                        <option value="Yes, Out of Country">Yes, Out of Country</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Previous Rental or Commercial Use?</strong></p>
                                    <select id="trade-previously-rental-or-commercial-use" name="previously_rental_or_commercial_use" class="form-control">
                                        <option disabled selected>Select</option>
                                        <option value="No">No</option>
                                        <option value="Not Sure">Not Sure</option>
                                        <option value="Yes, Previous Rental Use">Yes, Previous Rental Use</option>
                                        <option value="Yes, Previous Commercial Use">Yes, Previous Commercial Use</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Previous Accident History?</strong></p>
                                    <select id="trade-previous-accident-history" name="previous_accident_history" class="form-control">
                                        <option disabled selected>Select</option>
                                        <option value="No">No</option>
                                        <option value="Not Sure">Not Sure</option>
                                        <option value="Yes, Out of Province">Yes, Out of Province</option>
                                        <option value="Yes, Out of Country">Yes, Out of Country</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>4. <strong>Vehicle Valuation</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Purchase Price</strong></p>
                                    <input type="text" name="purchase_price" class="form-control" placeholder="Purchase Price" id="trade-purchase-price" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Special Instructions/comments/notes</strong></p>
                                    <textarea rows="8" name="comments" class="form-control" placeholder="" id="trade-comments"></textarea>
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
        });
    </script>

</body>

<!---- BODY END ---->

</html>