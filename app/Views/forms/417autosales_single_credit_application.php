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
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>First Name</strong></p>
                                    <input name="first_name" type="text" class="form-control" placeholder="" id="credit-application-first-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Last Name</strong></p>
                                    <input type="text" name="last_name" class="form-control" placeholder="" id="credit-application-last-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input name="email" type="email" class="form-control" placeholder="" id="credit-application-email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" name="phone" class="form-control" placeholder="" id="credit-application-phone-number" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Mobile Phone</strong></p>
                                    <input type="tel" name="mobile_phone" class="form-control" placeholder="" id="credit-application-mobile-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="work_phone" class="form-control" placeholder="" id="credit-application-work-phone" />
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
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Date of Birth</strong></p>
                                    <input name="date_of_birth" type="date" class="form-control" placeholder="" id="credit-application-date-birth" />
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
                                        <option value="Common Law">Common Law</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Drivers License Number</strong></p>
                                    <input type="text" name="drivers_license_number" class="form-control" placeholder="" id="credit-application-drivers_license" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Drivers License Expiry Date</strong></p>
                                    <input type="date" name="drivers_license_expiry_date" class="form-control" placeholder="" id="credit-application-drivers_license_expiry_date" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>License Class</strong></p>
                                    <input type="text" name="drivers_license_class" class="form-control" placeholder="" id="credit-application-drivers_license_class" />
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
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Home Address</strong></p>
                                    <input name="home_address" type="text" class="form-control" placeholder="" id="credit-application-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input name="city" type="text" class="form-control" placeholder="" id="credit-application-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="province" class="form-control" placeholder="" id="credit-application-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="postal_code" class="form-control" placeholder="" id="credit-application-postal-code" />
                                    <div class="invalid-feedback"></div>
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
                                        <option value="Own">Own</option>
                                        <option value="Own Free Clear">Own Free Clear</option>
                                        <option value="With Family">With Family</option>
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
                        <div class="row mortgage-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mortgage Balance Amount</strong></p>
                                    <input type="text" name="mortgage_balance_amount" class="form-control" id="mortgage_balance_amount">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Market Value</strong></p>
                                    <input type="text" name="market_value" class="form-control" id="market_value">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mortgage Holder</strong></p>
                                    <input type="text" name="mortgage_holder" class="form-control" id="mortgage_holder">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row rent-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Landlord Name</strong></p>
                                    <input type="text" name="landlord_name" class="form-control" id="landlord_name">
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
                        <div class="row residence_previous_address_section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Previous Address (If less than 2 years at Current Residence)</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row  residence_previous_address_section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Address</strong></p>
                                    <input type="text" name="previous_address" class="form-control" placeholder="" id="credit-application-previous-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input type="text" name="previous_address_city" class="form-control" placeholder="" id="credit-application-previous-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="previous_address_province" class="form-control" placeholder="" id="credit-application-previous-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="previous_address_postal_code" class="form-control" placeholder="" id="credit-application-previous-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row residence_previous_address_section d-none">

                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>How Long at this address?</strong></p>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" class="form-control" name="previous_residence_address_period_years" placeholder="Years" id="previous_address_residence_period_years" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="previous_residence_period_months" class="form-control" placeholder="Months" id="previous_residence_period_months" />
                                <div class="invalid-feedback"></div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>3. <strong>Applicants Employer</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employer Name</strong></p>
                                    <input name="employer_name" type="text" class="form-control" placeholder="" id="credit-application-employer-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Self Employed</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="self_employed" value="Yes" id="credit-application-self" />Yes</li>
                                        <li><input type="radio" checked placeholder="" name="self_employed" value="No" id="credit-application-self" />No</li>
                                    </ul>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Job Title</strong></p>
                                    <input type="text" name="job_title" class="form-control" placeholder="" id="credit-application-employer-job" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="employer_work_phone" class="form-control" placeholder="" id="credit-application-employer-work-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Gross Income (Monthly)</strong></p>
                                    <input type="text" name="gross_income" class="form-control" placeholder="" id="credit-application-employer-gross-income" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Income Frequency</strong></p>
                                    <select id="credit-application-employer-frequency" name="payment_frequency" placeholder="Select" class="form-control">
                                        <option disabled="disabled" value="">Select</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Bi-Weekly">Bi-Weekly</option>
                                        <option selected value="Monthly">Monthly</option>
                                        <option value="Annually">Annually</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Down Payment</strong></p>
                                    <input type="text" name="downpayment" class="form-control" placeholder="" id="credit-application-employer-downpayment" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Employer Type</strong></p>
                                    <select id="credit-application-employer-type" name="employer_type" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                        <option value="Seasonal">Seasonal</option>
                                        <option value="Retired">Retired</option>
                                    </select>
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
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Employment Address</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Address</strong></p>
                                    <input name="employer_address" type="text" class="form-control" placeholder="" id="credit-application-employer-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input type="text" name="employer_city" class="form-control" placeholder="" id="credit-application-employer-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="employer_province" class="form-control" placeholder="" id="credit-application-employer-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="employer_postal_code" class="form-control" placeholder="" id="credit-application-employer-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row previous_employer_section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Previous Employer (If less than 2 Years at Current Employer)</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row previous_employer_section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employer Name</strong></p>
                                    <input type="text" name="previous_employer_name" class="form-control" placeholder="" id="credit-application-previous-employer-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Self Employed</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="previous_self_employed" value="Yes" id="credit-application-previous-self" />Yes</li>
                                        <li><input type="radio" placeholder="" name="previous_self_employed" value="No" id="credit-application-previous-self" />No</li>
                                    </ul>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Employer Type</strong></p>
                                    <select id="credit-application-previous-employer-type" name="previous_employer_type" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                        <option value="Seasonal">Seasonal</option>
                                        <option value="Self Employed">Self Employed</option>
                                        <option value="Retired">Retired</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Job Title</strong></p>
                                    <input type="text" name="previous_job_title" class="form-control" placeholder="" id="credit-application-previous-employer-job" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="previous_work_phone" class="form-control" placeholder="" id="credit-application-employer-previous-work-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row previous_employer_section d-none">

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
                                    <label><strong>Other Income</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Source</strong></p>
                                    <input type="text" name="other_income_source" class="form-control" placeholder="" id="credit-application-employer-source" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Gross Income (Monthly)</strong></p>
                                    <input type="text" name="other_gross_income" class="form-control" placeholder="" id="credit-application-other_gross_income" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Income Frequency</strong></p>
                                    <select id="credit-application-employer-frequency" name="other_income_frequency" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected="" value="">Select</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Bi-Weekly">Bi-Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Annually">Annually</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>4. <strong>Co-Applicant Information</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Is there a Co-Applicant?</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="coapplicant" value="Yes" id="coapplicant-yes">Yes</li>
                                        <li><input type="radio" placeholder="" checked name="coapplicant" value="No" id="coapplicant-no">No</li>
                                    </ul>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>First Name</strong></p>
                                    <input type="text" name="coapplicant_first_name" class="form-control" placeholder="" id="credit-application-information-first-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Last Name</strong></p>
                                    <input type="text" name="coapplicant_last_name" class="form-control" placeholder="" id="credit-application-information-last-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Email</strong></p>
                                    <input type="email" name="coapplicant_email" class="form-control" placeholder="" id="credit-application-information-email" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Phone</strong></p>
                                    <input type="tel" name="coapplicant_phone" class="form-control" placeholder="" id="credit-application-information-phone-number" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mobile Phone</strong></p>
                                    <input type="tel" name="coapplicant_mobile_phone" class="form-control" placeholder="" id="credit-application-information-mobile-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="coapplicant_work_phone" class="form-control" placeholder="" id="credit-application-information-work-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>SIN</strong></p>
                                    <input type="text" name="coapplicant_sin" class="form-control" placeholder="" id="credit-application-information-SIN" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Date of Birth</strong></p>
                                    <input type="date" name="coapplicant_date_of_birth" class="form-control" placeholder="" id="credit-application-information-date-birth" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Marital Status</strong></p>
                                    <select id="credit-application-information-marital-status" name="coapplicant_marital_status" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Common Law">Common Law</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Drivers License Number</strong></p>
                                    <input type="text" name="coapplicant_drivers_license_number" class="form-control" placeholder="" id="credit-application-information-date-birth" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Drivers License Expiry Date</strong></p>
                                    <input type="date" name="coapplicant_drivers_license_expiry_date" class="form-control" placeholder="" id="credit-application-drivers_license_expiry_date" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>License Class</strong></p>
                                    <input type="text" name="coapplicant_drivers_license_class" class="form-control" placeholder="" id="credit-application-coapplicant_drivers_license_class" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Relation With Applicant</strong></p>
                                    <input type="text" name="relation_with_applicant" class="form-control" placeholder="" id="credit-application-relation_with_applicant" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>


                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Co-Applicant Address </strong></label>
                                    <p>Check if Address is the same as above <input type="checkbox" id="addresscheckbox" /></p>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Home Address</strong></p>
                                    <input type="text" name="coapplicant_home_address" class="form-control" placeholder="" id="credit-application-coapplicant-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input type="text" name="coapplicant_city" class="form-control" placeholder="" id="credit-application-coapplicant-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="coapplicant_province" class="form-control" placeholder="" id="credit-application-coapplicant-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="coapplicant_postal_code" class="form-control" placeholder="" id="credit-application-coapplicant-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Residence Status</strong></p>
                                    <select id="credit-application-coapplicant-residence-status" name="coapplicant_residence_status" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Own">Own</option>
                                        <option value="Own Free Clear">Own Free Clear</option>
                                        <option value="With Family">With Family</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mortgage /Rent Amount (Monthly)</strong></p>
                                    <input type="text" name="coapplicant_mortgage_rent_amount_monthly" class="form-control" placeholder="" id="credit-application-coapplicant-mortgage" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-mortgage-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mortgage Balance Amount</strong></p>
                                    <input type="text" name="coapplicant_mortgage_balance_amount" class="form-control" id="coapplicant_mortgage_balance_amount">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Market Value</strong></p>
                                    <input type="text" name="coapplicant_market_value" class="form-control" id="coapplicant_market_value">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Mortgage Holder</strong></p>
                                    <input type="text" name="coapplicant_mortgage_holder" class="form-control" id="coapplicant_mortgage_holder">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">

                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>How Long at this address?</strong></p>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" class="form-control" name="coapplicant_residence_period_years" placeholder="Years" id="credit-application-years" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="coapplicant_residence_period_months" class="form-control" placeholder="Months" id="credit-application-coapplicant_residence_period_months" />
                                <div class="invalid-feedback"></div>

                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Co-Applicant Employer</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employer Name</strong></p>
                                    <input type="text" name="coapplicant_employer_name" class="form-control" placeholder="" id="credit-application-employer-name" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Self Employed</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="coapplicant_self_employed" value="yes" id="credit-application-self" />Yes</li>
                                        <li><input type="radio" placeholder="" name="coapplicant_self_employed" value="no" id="credit-application-self" />No</li>
                                    </ul>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Job Title</strong></p>
                                    <input type="text" name="coapplicant_job_title" class="form-control" placeholder="" id="credit-application-employer-job" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="coapplicant_employer_work_phone" class="form-control" placeholder="" id="credit-application_co_employer-work-phone" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Gross Income (Monthly)</strong></p>
                                    <input type="text" name="coapplicant_gross_income" class="form-control" placeholder="" id="co-applicant-credit-application-employer-gross-income" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Income Frequency</strong></p>
                                    <select id="credit-application-employer-frequency" name="coapplicant_payment_frequency" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Bi-Weekly">Bi-Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Annually">Annually</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>Employer Type</strong></p>
                                    <select id="credit-application-employer-type" name="coapplicant_employer_type" placeholder="Select" class="form-control">
                                        <option disabled="disabled" selected value="">Select</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                        <option value="Seasonal">Seasonal</option>
                                        <option value="Self Employed">Self Employed</option>
                                        <option value="Retired">Retired</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row coapplicant-section d-none">

                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employment Length</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" name="coapplicant_employment_period_years" class="form-control" placeholder="Years" id="credit-application-coapplicant_employment_period_years" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="coapplicant_employment_period_months" class="form-control" placeholder="Months" id="credit-application-coapplicant_employment_period_months" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Co-Applicant Employer Address</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Address</strong></p>
                                    <input type="text" name="coapplicant_employer_address" class="form-control" placeholder="" id="credit-application-coapplicant-employer-address" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>City</strong></p>
                                    <input type="text" name="coapplicant_employer_city" class="form-control" placeholder="" id="credit-application-coapplicant-employer-city" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Province</strong></p>
                                    <input type="text" name="coapplicant_employer_province" class="form-control" placeholder="" id="credit-application-coapplicant-employer-province" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Postal Code</strong></p>
                                    <input type="text" name="coapplicant_employer_postal_code" class="form-control" placeholder="" id="credit-application-coapplicant-employer-postal-code" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row coapplicant-section d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Previous Bankruptcy</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="coapplicant_previous_bankruptcy" value="Yes" id="credit-application-bankruptcy" />Yes</li>
                                        <li><input type="radio" placeholder="" name="coapplicant_previous_bankruptcy" value="No" id="credit-application-bankruptcy" />No</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row previous_employer_section_co_applicant d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label><strong>Previous Employer (If less than 2 Years at Current Employer) for Co-Applicant</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row previous_employer_section_co_applicant d-none">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employer Name</strong></p>
                                    <input type="text" name="previous_employer_name_co_applicant" class="form-control" placeholder="" id="credit-application-previous-employer-name-co-applicant" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Self Employed</strong></p>
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="previous_self_employed_co_applicant" value="Yes" id="credit-application-previous-self-co-applicant" />Yes</li>
                                        <li><input type="radio" placeholder="" name="previous_self_employed_co_applicant" value="No" id="credit-application-previous-self-co-applicant" />No</li>
                                    </ul>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Job Title</strong></p>
                                    <input type="text" name="previous_job_title_co_applicant" class="form-control" placeholder="" id="credit-application-previous-employer-job-co-applicant" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="p-top-20">
                                    <p><strong>Work Phone</strong></p>
                                    <input type="tel" name="previous_work_phone_co_applicant" class="form-control" placeholder="" id="credit-application-employer-previous-work-phone-co-applicant" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row previous_employer_section_co_applicant d-none">

                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <p><strong>Employment Length</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" name="previous_employment_period_years_co_applicant" class="form-control" placeholder="Years" id="credit-application-lengthyears-co-applicant" />
                                <div class="invalid-feedback"></div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" min="0" max="11" name="previous_employment_period_months_co_applicant" class="form-control" placeholder="Months" id="credit-application-previous_employment_period_months_co_applicant" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-30">
                                    <label>5. <strong>Trade In</strong></label>
                                    <div class="border-bottom"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="p-top-20">
                                    <!--                                        <p><strong>Trade In</strong></p>-->
                                    <ul class="radio-btn-self">
                                        <li><input type="radio" placeholder="" name="trade_in" value="Yes" id="credit-application-trade_other" class="editable-icon-2" /><span class="editable-icon2">Yes</span></li>
                                        <li><input type="radio" placeholder="" name="trade_in" checked value="No" class="editable-icon-3" id="credit-application-trade_promotions" /><span class="editable-icon3">No</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="hide-feild">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="p-top-20">
                                        <p><strong>Year</strong></p>
                                        <input type="text" name="trade_in_year" class="form-control" placeholder="" id="credit-application-year" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="p-top-20">
                                        <p><strong>Make</strong></p>
                                        <input type="text" name="trade_in_make" class="form-control" placeholder="" id="credit-application-make" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="p-top-20">
                                        <p><strong>Model</strong></p>
                                        <input type="text" name="trade_in_model" class="form-control" placeholder="" id="credit-application-model" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="p-top-20">
                                        <p><strong>VIN</strong></p>
                                        <input type="text" name="trade_in_vin" class="form-control" placeholder="" id="credit-application-vin" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="p-top-20" style="position: relative;">
                                        <p><strong>Odometer</strong></p>
                                        <span class="right-text"> Kms</span>
                                        <input type="text" name="trade_in_odometer" class="form-control" placeholder="" id="trade-odometer" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="p-top-20">
                                        <p><strong>Colour</strong></p>
                                        <input type="text" class="form-control" name="trade_in_colour" placeholder="" id="credit-application-colour" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="p-top-20" style="position: relative;">
                                        <p><strong>Lien on Vehicle</strong></p>
                                        <ul class="radio-btn-self">
                                            <li><input type="radio" placeholder="" name="lien_on_vehicle" value="Yes" id="lein-on-amount-yes" />Yes</li>
                                            <li><input type="radio" placeholder="" name="lien_on_vehicle" value="No" id="lein-on-amount-no" />No</li>
                                        </ul>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 lien-on-amount-section d-none">
                                    <div class="p-top-20">
                                        <p><strong>Lien Payoff Amount</strong></p>
                                        <input type="text" name="lien_payoff_amount" class="form-control" placeholder="" id="credit-application-lien-amount" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="p-top-20" style="position: relative;">
                                        <p><strong>Vehicle Title</strong></p>
                                        <input type="text" name="trade_in_vehicle_title" class="form-control" placeholder="Branded / Salvage / Clean" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row <?php if ($VOI != '1') {
                                            echo 'd-none';
                                        } ?>">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-30">
                                    <p><strong>VOI (Vehicle of interest)</strong></p>
                                    <select id="inventory_id" name="inventory_id" class="form-control">
                                        <option disabled selected>Choose...</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="p-top-20">
                                    <p><strong>How did you hear about us?</strong></p>
                                    <select id="credit-application-hear-about-us" name="hear_about_us" placeholder="Select" class="form-control">
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

        $('#addresscheckbox').change(function() {
            if (this.checked) {
                $('#credit-application-coapplicant-address').val($('#credit-application-address').val());
                $('#credit-application-coapplicant-address').attr('disabled', 'disabled');

                $('#credit-application-coapplicant-city').val($('#credit-application-city').val());
                $('#credit-application-coapplicant-city').attr('disabled', 'disabled');

                $('#credit-application-coapplicant-province').val($('#credit-application-province').val());
                $('#credit-application-coapplicant-province').attr('disabled', 'disabled');

                $('#credit-application-coapplicant-postal-code').val($('#credit-application-postal-code').val());
                $('#credit-application-coapplicant-postal-code').attr('disabled', 'disabled');

                $('#credit-application-coapplicant-mortgage').val($('#credit-application-mortgage').val());
                $('#credit-application-coapplicant-mortgage').attr('disabled', 'disabled');

                $('#credit-application-years').val($('#residence_period_years').val());
                $('#credit-application-years').attr('disabled', 'disabled');

                $('#credit-application-coapplicant_residence_period_months').val($('#credit-application-month').val());
                $('#credit-application-coapplicant_residence_period_months').attr('disabled', 'disabled');

                $('#credit-application-coapplicant-residence-status').val($('#credit-application-residence-status').val());
                $('#credit-application-coapplicant-residence-status').attr('disabled', 'disabled');

            } else {
                $('#credit-application-coapplicant-address').val('');
                $('#credit-application-coapplicant-address').removeAttr('disabled');

                $('#credit-application-coapplicant-city').val('');
                $('#credit-application-coapplicant-city').removeAttr('disabled');

                $('#credit-application-coapplicant-province').val('');
                $('#credit-application-coapplicant-province').removeAttr('disabled');

                $('#credit-application-coapplicant-postal-code').val('');
                $('#credit-application-coapplicant-postal-code').removeAttr('disabled');

                $('#credit-application-coapplicant-mortgage').val('');
                $('#credit-application-coapplicant-mortgage').removeAttr('disabled');

                $('#credit-application-years').val('');
                $('#credit-application-years').removeAttr('disabled');

                $('#credit-application-coapplicant_residence_period_months').val('');
                $('#credit-application-coapplicant_residence_period_months').removeAttr('disabled');

                $('#credit-application-coapplicant-residence-status').val('');
                $('#credit-application-coapplicant-residence-status').removeAttr('disabled');
            }
        });
        $('#credit-application-trade_other').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').addClass('hide-feild-show');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-trade_promotions').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').removeClass('hide-feild-show');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-previous-self-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').addClass('hide-feild-show');
            }
            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-previous-self-no').change(function() {
            if ($(this).is(":checked")) {
                $('.hide-feild').removeClass('hide-feild-show');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#coapplicant-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.coapplicant-section').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#coapplicant-no').change(function() {
            if ($(this).is(":checked")) {
                $('.coapplicant-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
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
                value: $(document).height(),
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
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-coapplicant_employment_period_years').change(function() {
            if (parseInt($(this).val()) < 2) {
                $('.previous_employer_section_co_applicant').removeClass('d-none');
            } else {
                $('.previous_employer_section_co_applicant').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#lein-on-amount-yes').change(function() {
            if ($(this).is(":checked")) {
                $('.lien-on-amount-section').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");

        });

        $('#lein-on-amount-no').change(function() {
            if ($(this).is(":checked")) {
                $('.lien-on-amount-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#credit-application-residence-status').change(function() {
            if ($(this).val() == "Own") {
                $('.mortgage-section').removeClass('d-none');
            } else {
                $('.mortgage-section').addClass('d-none');
            }

            if ($(this).val() == "Rent") {
                $('.rent-section').removeClass('d-none');
            } else {
                $('.rent-section').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
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
                value: $(document).height(),
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

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
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



        });

        $(document).ready(function($) {
            $("#lead_form").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    //                email: "required", 
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
                    mobile_phone: {
                        required: false,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    work_phone: {
                        required: false,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    sin: {
                        required: false,
                        minlength: 9,
                        maxlength: 9,
                        number: true,
                    },
                    mortgage_holder: "required",
                    date_of_birth: "required",
                    marital_status: "required",
                    drivers_license_number: "required",
                    drivers_license_expiry_date: "required",
                    home_address: "required",
                    city: "required",
                    province: "required",
                    postal_code: "required",
                    residence_status: "required",
                    landlord_name: "required",
                    mortgage_rent_amount_monthly: {
                        required: true,
                        min: 0,
                        number: true,
                    },
                    mortgage_balance_amount: {
                        required: false,
                        min: 0,
                        number: true,
                    },
                    market_value: {
                        required: false,
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
                    previous_address: "required",
                    previous_address_city: "required",
                    previous_address_province: "required",
                    previous_address_postal_code: "required",
                    previous_residence_address_period_years: {
                        required: true,
                        min: 0,
                        max: 65,
                        number: true,
                    },
                    previous_residence_period_months: {
                        required: true,
                        min: 0,
                        max: 11,
                        number: true,
                    },

                    employer_name: "required",
                    self_employed: "required",
                    job_title: "required",
                    employer_work_phone: {
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
                    payment_frequency: "required",
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

                    employer_address: "required",
                    employer_city: "required",
                    employer_province: "required",
                    employer_postal_code: "required",
                    previous_employer_name: "required",
                    previous_employer_type: "required",
                    previous_job_title: "required",
                    previous_work_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    previous_employment_period_years: {
                        required: true,
                        min: 0,
                        max: 65,
                        number: false
                    },
                    previous_employment_period_months: {
                        required: true,
                        min: 0,
                        max: 11,
                        number: true
                    },
                    previous_employer_name_co_applicant: "required",
                    previous_job_title_co_applicant: "required",
                    previous_work_phone_co_applicant: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    previous_employment_period_years_co_applicant: {
                        required: true,
                        min: 0,
                        max: 65,
                        number: false
                    },
                    previous_employment_period_months_co_applicant: {
                        required: true,
                        min: 0,
                        max: 11,
                        number: true
                    },
                    other_gross_income: {
                        required: false,
                        min: 0,
                        number: true
                    },
                    coapplicant_first_name: {
                        required: true,
                    },
                    coapplicant_last_name: {
                        required: true,
                    },
                    coapplicant_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    coapplicant_mobile_phone: {
                        required: false,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    coapplicant_work_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    coapplicant_sin: {
                        required: false,
                        minlength: 9,
                        maxlength: 9,
                        number: true,
                    },
                    coapplicant_drivers_license_expiry_date: "required",
                    coapplicant_home_address: {
                        required: true,
                    },
                    coapplicant_city: {
                        required: true,
                    },
                    coapplicant_province: {
                        required: true,
                    },
                    coapplicant_postal_code: {
                        required: true,
                    },
                    coapplicant_mortgage_rent_amount_monthly: {
                        required: true,
                        min: 0,
                        number: true,
                    },
                    coapplicant_mortgage_balance_amount: {
                        required: true,
                        min: 0,
                        number: true,
                    },
                    coapplicant_market_value: {
                        required: true,
                        min: 0,
                        number: true,
                    },
                    coapplicant_residence_period_years: {
                        required: true,
                        min: 0,
                        max: 65,
                        number: true,
                    },
                    coapplicant_residence_period_months: {
                        required: true,
                        min: 0,
                        max: 11,
                        number: true,
                    },
                    coapplicant_employer_work_phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    coapplicant_gross_income: {
                        required: false,
                        min: 0,
                        number: true
                    },
                    coapplicant_employer_type: "required",
                    coapplicant_employment_period_years: {
                        required: false,
                        min: 0,
                        max: 65,
                        number: true
                    },
                    coapplicant_employment_period_months: {
                        required: false,
                        min: 0,
                        max: 11,
                        number: true
                    },
                    coapplicant_employer_address: {
                        required: true,
                    },
                    coapplicant_employer_city: {
                        required: true,
                    },
                    coapplicant_employer_province: {
                        required: true,
                    },
                    coapplicant_employer_postal_code: {
                        required: true,
                    },
                    coapplicant: "required",
                    gender: "required",
                    trade_in_year: {
                        required: false,
                        min: 1970,
                        max: new Date().getFullYear() + 2,
                        number: true
                    },
                    trade_in_odometer: {
                        required: false,
                        min: 0,
                        number: true
                    },
                    lien_payoff_amount: {
                        required: false,
                        min: 0,
                        number: true
                    },
                    hear_about_us: "required",
                    inventory_id: "required"

                },
                messages: {
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
                    mortgage_holder: "Mortgage Holder Name is Required",
                    landlord_name: "Landlord Name is Required",
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
                    previous_employer_name: "Please Enter Previous Employer Name",
                    previous_employer_type: "Please Enter Your Previous Employment Type",
                    previous_job_title: "Please Enter Your Previous Job Title",
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
                    previous_employer_name_co_applicant: "Please Enter Previous Employer Name",
                    previous_job_title_co_applicant: "Please Enter Your Previous Job Title",
                    previous_work_phone_co_applicant: {
                        required: "Please Enter Your Previous Employer Work Phone Number",
                        number: "Previous Employer Work Phone should be a Number",
                        minlength: "Your Previous Employer Work Phone must be 10 characters long"

                    },
                    previous_employment_period_years_co_applicant: {
                        required: "Please Enter Previous Employment Period Years",

                    },
                    previous_employment_period_months_co_applicant: {
                        required: "Please Enter Previous Employment Period Months",

                    },
                    other_gross_income: {
                        required: "Please Enter Other Gross Income",
                        number: "Other Gross Income should be a Number"

                    },
                    coapplicant_first_name: {
                        required: "Please Enter Co-Applicant First Name"
                    },
                    coapplicant_last_name: {
                        required: "Please Enter Co-Applicant Last Name"
                    },
                    coapplicant_phone: {
                        required: "Please Enter Co-Applicant Phone Number",
                        number: "Co-Applicant Phone Number should be a Number",
                        minlength: "Co-Applicant Phone Number must be 10 characters long"

                    },
                    coapplicant_mobile_phone: {
                        required: "Please Enter Co-Applicant Mobile Number",
                        number: "Co-Applicant Mobile Number should be a Number",
                        minlength: "Co-Applicant Mobile Number must be 10 characters long"

                    },
                    coapplicant_work_phone: {
                        required: "Please Enter Co-Applicant Work Phone Number",
                        number: "Co-Applicant Work Phonae Number should be a Number",
                        minlength: "Co-Applicant Work Phone Number must be 10 characters long"

                    },
                    coapplicant_sin: {
                        minlength: "Co-Applicant SIN must be at least 9 characters long",
                        number: "Co-Applicant SIN should be a Number"
                    },
                    coapplicant_drivers_license_expiry_date: "Please Enter Co-Applicant Drivers License Expiry Date",
                    coapplicant_home_address: {
                        required: "Co-Applicant Home Address is Required",
                    },
                    coapplicant_city: {
                        required: "Co-Applicant City is Required",
                    },
                    coapplicant_province: {
                        required: "Co-Applicant Province is Required",
                    },
                    coapplicant_postal_code: {
                        required: "Co-Applicant Postal Code is Required",
                    },
                    coapplicant_mortgage_rent_amount_monthly: {
                        required: "Co-Applicant Mortgage / Rent Amount is Required",
                        number: "Co-Applicant Mortgage / Rent Amount should be a Number"
                    },
                    coapplicant_mortgage_balance_amount: {
                        required: "Co-Applicant Mortgage Balance Amount is Required",
                        number: "Co-Applicant Mortgage Balance Amount should be a Number"
                    },
                    coapplicant_market_value: {
                        required: "Co-Applicant Market Value is Required",
                        number: "Co-Applicant Market Value should be a Number"
                    },
                    coapplicant_mortgage_holder: "Co-Applicant Mortgage Holder Name is required.",
                    coapplicant_residence_period_years: {
                        required: "Please Enter the Co-Applicant Residence Period Years",

                    },
                    coapplicant_residence_period_months: {
                        required: "Please Enter the Co-Applicant Residence Period Months",

                    },
                    coapplicant_employer_work_phone: {
                        required: "Please Enter Co-Applicant Work Phone Number",
                        number: "Co-Applicant Work Phone Number should be a Number",
                        minlength: "Co-Applicant Work Phone Number must be 10 characters long"
                    },
                    coapplicant_gross_income: {
                        required: "Please Enter Your Co-Applicant Gross Income",
                        number: "Co-Applicant Gross Income should be a Number"

                    },
                    coapplicant_employer_type: "Please Select Co-Applicant Employment Type",
                    coapplicant_employment_period_years: {
                        required: "Please Enter Co-Applicant Employment Period Years",

                    },
                    coapplicant_employment_period_months: {
                        required: "Please Enter Co-Applicant Employment Period Months",

                    },
                    coapplicant_employer_address: {
                        required: "Co-Applicant Employer Address is Required",
                    },
                    coapplicant_employer_city: {
                        required: "Co-Applicant Employer City is Required",
                    },
                    coapplicant_employer_province: {
                        required: "Co-Applicant Employer Province is Required",
                    },
                    coapplicant_employer_postal_code: {
                        required: "Co-Applicant Employer Postal Code is Required",
                    },

                    coapplicant: "Please Select Your Co-Applicant Information",
                    gender: "This field is required",
                    hear_about_us: "Please let us know how did you hear about us",
                    inventory_id: "Please select the Vehicle of Interest"
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