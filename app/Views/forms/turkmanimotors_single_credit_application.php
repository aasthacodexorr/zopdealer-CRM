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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/single_credit_application/style.css">
    <!--  HEAD END  -->
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
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
            <form class="needs-validation" id="lead_form" action="" method="post" novalidate="novalidate">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="head-text">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>1. <strong>Applicant Personal Information</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Salutation</strong></p>
                                    <select id="credit-application-salutation" name="salutation" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Mrs.">Mrs.</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Gender</strong></p>
                                    <select id="credit-application-gender" name="gender" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>First Name</strong></p>
                                    <input name="first_name" type="text" class="form-control" placeholder="" id="credit-application-first-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last Name</strong></p>
                                    <input type="text" name="last_name" class="form-control" placeholder="" id="credit-application-last-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="credit-application-phone-number" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input name="email" type="email" class="form-control" placeholder="" id="credit-application-email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Marital Status</strong></p>
                                    <select id="credit-application-marital-status" name="marital_status" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Date of Birth</strong></p>
                                    <input name="date_of_birth" type="date" class="form-control" placeholder="" id="credit-application-date-birth" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>SIN</strong></p>
                                    <input type="text" name="sin" class="form-control" placeholder="" id="credit-application-SIN" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>2. <strong>Applicants Address</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Home Address</strong></p>
                                    <input name="home_address" type="text" class="form-control" placeholder="" id="credit-application-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input name="city" type="text" class="form-control" placeholder="" id="credit-application-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="province" class="form-control" placeholder="" id="credit-application-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="postal_code" class="form-control" placeholder="" id="credit-application-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>How Long at this address?</strong></p>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="100" class="form-control" name="residence_period_years" placeholder="Years" id="residence_period_years" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="residence_period_months" class="form-control" placeholder="Months" id="credit-application-month" />
                                <div class="invalid-feedback"></div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>3. <strong>Home Rent/Mortgage Information</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Residence Status</strong></p>
                                    <select id="credit-application-residence-status" name="residence_status" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Own With Mortgage">Own With Mortgage</option>
                                        <option value="With Parent">With Parent</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mortgage /Rent Amount (Monthly)</strong></p>
                                    <input type="text" class="form-control" name="mortgage_rent_amount_monthly" placeholder="" id="credit-application-mortgage" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>4. <strong>Applicants Employer</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Type</strong></p>
                                    <select id="credit-application-type" name="type" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                        <option value="Contract">Contract</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Employer Name</strong></p>
                                    <input name="employer_name" type="text" class="form-control" placeholder="" id="credit-application-employer-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Occupation</strong></p>
                                    <input name="occupation" type="text" class="form-control" placeholder="" id="credit-application-occupation" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Address</strong></p>
                                    <input name="employer_address" type="text" class="form-control" placeholder="" id="credit-application-employer-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input type="text" name="employer_city" class="form-control" placeholder="" id="credit-application-employer-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="employer_province" class="form-control" placeholder="" id="credit-application-employer-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="employer_postal_code" class="form-control" placeholder="" id="credit-application-employer-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="employer_work_phone" class="form-control" placeholder="" id="credit-application-employer-work-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employment Length</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" name="employment_period_years" class="form-control" placeholder="Years" id="employment_period_years" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="employment_period_months" class="form-control" placeholder="Months" id="credit-application-lengthmonth" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Gross Income (Monthly)</strong></p>
                                    <input type="text" name="gross_income" class="form-control" placeholder="" id="credit-application-employer-gross-income" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>5.<strong>Previous Employer</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Previous Employer Name</strong></p>
                                    <input type="text" name="previous_employer_name" class="form-control" placeholder="" id="credit-application-previous-employer-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="previous_work_phone" class="form-control" placeholder="" id="credit-application-employer-previous-work-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employment Length</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" name="previous_employment_period_years" class="form-control" placeholder="Years" id="credit-application-lengthyears" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="previous_employment_period_months" class="form-control" placeholder="Months" id="credit-application-previous_employment_period_months" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>6.<strong>Other Information</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Previous Bankruptcy</strong></p>
                                    <select id="credit-application-previous-bankruptcy" name="previous_bankruptcy" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Bankruptcy</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Previous Repossession</strong></p>
                                    <select id="credit-application-previous-repossession" name="previous-repossession" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Repossession</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Is Co-signer Available?</strong></p>
                                    <select id="credit-application-coapplicant" name="coapplicant" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Is Co-signer Available?</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Please rate your credit</strong></p>
                                    <select id="rate_your_credit" name="rate_your_credit" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Rate Your Credit</option>
                                        <option value="Good Credit">Good Credit</option>
                                        <option value="Average">Average</option>
                                        <option value="Bad Credit">Bad Credit</option>
                                        <option value="No Credit">No Credit</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>7.<strong>Desired Vehicle</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Make</strong></p>
                                    <input type="text" name="make" class="form-control" placeholder="" id="credit-application-make" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Model</strong></p>
                                    <input type="text" name="model" class="form-control" placeholder="" id="credit-application-model" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Year</strong></p>
                                    <input min="1960" name="year" type="text" class="form-control" placeholder="" id="credit-application-year" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>VIN</strong></p>
                                    <input type="text" name="vin" class="form-control" placeholder="" id="credit-application-vin" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>8.<strong>Trade In</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Trade-In Make</strong></p>
                                    <input type="text" name="trade_in_make" class="form-control" placeholder="" id="credit-application-trade-in-make" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Trade-In Model</strong></p>
                                    <input type="text" name="trade_in_model" class="form-control" placeholder="" id="credit-application-trade-in-model" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Trade-In Year</strong></p>
                                    <input min="1960" name="trade_in_year" type="text" class="form-control" placeholder="" id="credit-application-trade-in-year" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Trade-In Kilometers</strong></p>
                                    <input type="text" name="trade_in_kilometers" class="form-control" placeholder="" id="credit-application-trade-in-kilometers" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row <?php if ($VOI != '1') {
                                        echo 'd-none';
                                    } ?>">
                        <div class="col-md-12 col-sm-12">
                            <div class="p-top-30">
                                <p><strong>VOI (Vehicle of interest)</strong></p>
                                <select id="inventory_id" name="inventory_id" class="form-control">
                                    <option disabled selected>Choose...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="p-top-20">
                                <p><strong>Message</strong></p>
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
                                <textarea name="message" rows="8" class="form-control" placeholder="" id="credit-application-message"></textarea>
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
                    <!--
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>6. <strong>Vehicle Info</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
-->

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="p-top-20">
                                <?php echo $disclosure; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="hero-btn p-top-20 p-bottom-50">
                            <!--                                <button class="serach-btn" style="padding:0px;"><a style="padding:16px 60px;">Get Approved</a></button>-->
                            <input class="btn btn-primary serach-btn" value="Get Approved" type="submit" name="submit">
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
    <script type="text/javascript">
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
            setProgress(0);

            // Next button click action
            btnnext.click(function() {
                if (current < widget.length) {
                    widget.show();
                    widget.not(':eq(' + (current) ++ + ')').hide();
                    setProgress(current - 1);
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
                    btnnext.trigger('click');
                }
                hideButtons(current);
            })
        });

        // Change progress bar action
        setProgress = function(currstep) {
            var percent = parseFloat(100 / 5) * currstep;
            percent = percent.toFixed();
            $(".progress-bar").css("width", percent + "%").html("");
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

        $('#credit-application-trade_other').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').addClass('hide-feild-show');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-trade_promotions').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').removeClass('hide-feild-show');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-previous-self-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').addClass('hide-feild-show');
            }
            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-previous-self-no').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').removeClass('hide-feild-show');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#coapplicant-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.coapplicant-section').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#coapplicant-no').change(function() {
            if ($(this).is(":checked")) {
                $('.coapplicant-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");

        });


        $('#residence_period_years').change(function() {
            if (parseInt($(this).val()) < 2) {
                $('.residence_previous_address_section').removeClass('d-none');
            } else {
                $('.residence_previous_address_section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");

        });

        $('#employment_period_years').change(function() {
            if (parseInt($(this).val()) < 2) {
                $('.previous_employer_section').removeClass('d-none');
            } else {
                $('.previous_employer_section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#lein-on-amount-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.lien-on-amount-section').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");

        });

        $('#lein-on-amount-no').change(function() {
            if ($(this).is(":checked")) {
                $('.lien-on-amount-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-residence-status').change(function() {
            if ($(this).val() == "Own") {
                $('.mortgage-section').removeClass('d-none');
            } else {
                $('.mortgage-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-coapplicant-residence-status').change(function() {
            if ($(this).val() == "Own") {
                $('.coapplicant-mortgage-section').removeClass('d-none');
            } else {
                $('.coapplicant-mortgage-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });

        $(`
        #credit-application-coapplicant-residence-status,
        #credit-application-residence-status,
        #lein-on-amount-no,
        #lein-on-amount-yes,
        #employment_period_years,
        #residence_period_years,
        #coapplicant-no,
        #coapplicant-yes,
        #credit-application-previous-self-no,
        #credit-application-previous-self-yes,
        #credit-application-trade_promotions,
        #credit-application-trade_other
        `).trigger('change');
    </script>
    <script>
        $(document).ready(function() {

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
                window.placeholder = '<?php //echo getenv('image.cdn') 
                                        ?>' + data.image_url;
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
                    value: $(document).outerHeight(true),
                    element_id: "finance_form"
                }, "*");

            }, 1000);

        });

        $(document).ready(function($) {
            $("#lead_form").validate({
                rules: {
                    salutation: "required",
                    gender: "required",
                    first_name: "required",
                    last_name: "required",
                    //                email: "required", 
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    marital_status: "required",
                    date_of_birth: "required",
                    drivers_license_number: "required",
                    drivers_license_expiry_date: "required",
                    sin: {
                        required: false,
                        minlength: 9,
                        maxlength: 9,
                        number: true,
                    },

                    home_address: "required",
                    city: "required",
                    province: "required",
                    postal_code: "required",
                    residence_status: "required",
                    mortgage_rent_amount_monthly: {
                        required: true,
                        min: 0,
                        number: true,
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
                    type: "required",
                    occupation: "required",
                    employer_name: "required",
                    employer_address: "required",
                    employer_city: "required",
                    employer_province: "required",
                    employer_postal_code: "required",
                    employer_work_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
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
                    gross_income: {
                        required: true,
                        min: 0,
                        number: true
                    },
                    previous_work_phone: {
                        required: false,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    previous_employment_period_years: {
                        required: false,
                        min: 0,
                        max: 65,
                        number: false
                    },
                    previous_employment_period_months: {
                        required: false,
                        min: 0,
                        max: 11,
                        number: true
                    }

                },
                messages: {
                    salutation: "Please Select Salutation",
                    gender: "Please Select your Gender",
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
                    residence_period_years: {
                        required: "Please Enter the Residence Period Years",

                    },
                    residence_period_months: {
                        required: "Please Enter Residence Period Months",

                    },
                    type: "Please Select the Type of Job",
                    occupation: "Please Enter Your Occupation",
                    employer_name: "Please Enter Your Employer Name",
                    employer_work_phone: {
                        required: "Please Enter Your Employer Work Phone Number",
                        number: "Employer Work Phone should be a Number",
                        minlength: "Your Employer Work Phone must be 10 characters long"
                    },
                    gross_income: {
                        required: "Please Enter Your Gross Income",
                        number: "Gross Income should be a Number"

                    },
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
                                        //var va = errors[key];
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
</body>

<!---- BODY END ---->

</html>