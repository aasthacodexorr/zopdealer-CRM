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
        ul.form-list-desgine li {
            vertical-align: top;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>
    <!--Financial Services Starts-->
    <section class="details-main form-new-set total-auto-sale">
        <div class="container">
            <form class="needs-validation" id="lead_form">
                <!--Financial Services Starts-->
                <div class="col-md-10 col-md-offset-1">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display:block;">
                            <div class="side-content-box">
                                <div class="left-side-conetent">
                                    <ul class="left-side-link">
                                        <li><a href="#contact-information">Contact Information</a></li>
                                        <li><a href="#personal-information">Personal Information</a></li>
                                        <li><a href="#current-home">Current Home Address</a></li>
                                        <li><a href="#current-address">How long have you lived at your current address?</a></li>
                                    </ul>
                                </div>
                                <div class="head-text-f">
                                    <div class="set-box" id="contact-information">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Contact Information</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Salutation</strong></p>
                                                    <select id="salutation" name="salutation" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="Dr.">Dr.</option>
                                                        <option value="Mr.">Mr.</option>
                                                        <option value="Ms.">Ms.</option>
                                                        <option value="Miss">Miss</option>
                                                        <option value="Mrs.">Mrs.</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>First Name</strong></p>
                                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                                    <div id="first_name_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Middle Name</strong></p>
                                                    <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name">
                                                    <div id="middle_name_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Last Name</strong></p>
                                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                                    <div id="last_name_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Suffix</strong></p>
                                                    <select id="suffix" name="suffix" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="I">I</option>
                                                        <option value="II">II</option>
                                                        <option value="III">III</option>
                                                        <option value="IV">IV</option>
                                                        <option value="JR">JR</option>
                                                        <option value="SR">SR</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Email</strong></p>
                                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                                    <div id="email_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Phone</strong></p>
                                                    <input type="tel" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                                    <div id="phone_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="personal-information">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Personal Information</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Date of Birth</strong></p>
                                                    <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="">
                                                    <div id="date_of_birth_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>SIN</strong></p>
                                                    <input type="text" class="form-control" name="sin" id="sin" placeholder="SIN">
                                                    <div id="sin_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Marital Status</strong></p>
                                                    <select id="marital-status" name="marital_status" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Common Law">Common Law</option>
                                                        <option value="Divorced">Divorced</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Gender</strong></p>
                                                    <select id="gender" name="gender" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="current-home">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Current Home Address</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Address Type</strong></p>
                                                    <input type="text" class="form-control " name="address_type" id="address_type" placeholder="Address Type">
                                                    <div id="address_type_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Unit Number</strong></p>
                                                    <input type="text" class="form-control " name="unit_number" id="unit_number" placeholder="Unit Number">
                                                    <div id="unit_number_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Street Number</strong></p>
                                                    <input type="text" class="form-control " name="street_number" id="street_number" placeholder="Street Number">
                                                    <div id="street_number_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Street Address</strong></p>
                                                    <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                                    <div id="street_address_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>City</strong></p>
                                                    <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                                    <div id="city_error" class="invalid-feedback"></div>

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Province</strong></p>
                                                    <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                                    <div id="province_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Postal Code</strong></p>
                                                    <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                                    <div id="postal_code_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="set-box" id="current-address">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">How long have you lived at your current address?</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Years</strong></p>
                                                    <input type="text" min="0" max="100" class="form-control" name="residence_period_years" placeholder="Years" id="residence_period_years" />
                                                    <div id="residence_period_years_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Months</strong></p>
                                                    <input type="text" min="0" max="11" name="residence_period_months" class="form-control" placeholder="Months" id="credit-application-month" />
                                                    <div id="residence_period_months_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="side-content-box">
                                <div class="left-side-conetent">
                                    <ul class="left-side-link">
                                        <li><a href="#housing">Housing</a></li>
                                        <li><a href="#current-employment">Current Employment</a></li>
                                        <li><a href="#employment-address">Employment Address</a></li>
                                        <li><a href="#current-employer">How long have you worked at your current employer?</a></li>
                                        <li><a href="#current-income">Current Income</a></li>
                                        <li><a href="#info-title">Other Income</a></li>
                                    </ul>
                                </div>
                                <div class="head-text-f">
                                    <div class="set-box" id="housing">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Housing</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Home Status</strong></p>
                                                    <select id="home_status" name="home_status" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="Own with Mortgage">Own with Mortgage</option>
                                                        <option value="Own Free & Clear">Own Free & Clear</option>
                                                        <option value="Own Mobile Home">Own Mobile Home</option>
                                                        <option value="Rent">Rent</option>
                                                        <option value="With Parents">With Parents</option>
                                                        <option value="Reserve Housing">Reserve Housing</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Monthly Housing Payment</strong></p>
                                                    <input type="text" class="form-control" name="monthly_housing_payment" id="monthly_housing_payment" placeholder="Monthly Housing Payment">
                                                    <div id="monthly_housing_payment_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Mortgage Amount</strong></p>
                                                        <input type="text" class="form-control" name="mortgage_amount" id="mortgage_amount" placeholder="Mortgage Amount">
                                                        <div id="mortgage_amount_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Additional Info.</strong></p>
                                                        <input type="text" class="form-control" name="additional_info" id="additional_info" placeholder="Additional Info">
                                                        <div id="additional_info_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Market Value</strong></p>
                                                        <input type="text" class="form-control" name="market_value" id="market_value" placeholder="Market Value">
                                                        <div id="market_value_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="current-employment">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Current Employment</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Employment Status</strong></p>
                                                    <select id="employment_status" name="employment_status" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="Full Time">Full Time</option>
                                                        <option value="Full Time (Probation)">Full Time (Probation)</option>
                                                        <option value="Part Time (Casual)">Part Time (Casual)</option>
                                                        <option value="Part Time (Regular)">Part Time (Regular)</option>
                                                        <option value="Retired">Retired</option>
                                                        <option value="Seasonal Summer">Seasonal Summer</option>
                                                        <option value="Seasonal Winter">Seasonal Winter</option>
                                                        <option value="Self-Employed">Self-Employed</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Employment Type</strong></p>
                                                    <select id="employment_type" name="employment_type" placeholder="Select" class="form-control">
                                                        <option disabled="disabled" selected value="">Select</option>
                                                        <option value="At Home">At Home</option>
                                                        <option value="Executive">Executive</option>
                                                        <option value="Labourer">Labourer</option>
                                                        <option value="Management">Management</option>
                                                        <option value="Office Staff">Office Staff</option>
                                                        <option value="Other">Other</option>
                                                        <option value="Production">Production</option>
                                                        <option value="Professional">Professional</option>
                                                        <option value="Retired">Retired</option>
                                                        <option value="Sales">Sales</option>
                                                        <option value="Self-Employed">Self-Employed</option>
                                                        <option value="Service">Service</option>
                                                        <option value="Student">Student</option>
                                                        <option value="Trades">Trades</option>
                                                        <option value="Unemployed">Unemployed</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Employer</strong></p>
                                                        <input type="text" class="form-control" name="employer" id="employer" placeholder="Employer">
                                                        <div id="employer_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Occupation</strong></p>
                                                        <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Occupation">
                                                        <div id="occupation_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Work Phone</strong></p>
                                                        <input type="tel" name="work_phone" class="form-control" id="tel" placeholder="Work Phone Number">
                                                        <div id="work_phone_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Work Contact Extension</strong></p>
                                                        <input type="text" class="form-control" name="work_contact_extension" id="work_contact_extension" placeholder="Work Contact Extension">
                                                        <div id="work_contact_extension_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="employment-address">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Employment Address</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Address Type</strong></p>
                                                    <input type="text" class="form-control " name="employment_address_type" id="employment_address_type" placeholder="Address Type">
                                                    <div id="employment_address_type_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Unit Number</strong></p>
                                                    <input type="text" class="form-control " name="employment_unit_number" id="employment_unit_number" placeholder="Unit Number">
                                                    <div id="employment_unit_number_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Street Number</strong></p>
                                                    <input type="text" class="form-control " name="employment_street_number" id="employment_street_number" placeholder="Street Number">
                                                    <div id="employment_street_number_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Street Address</strong></p>
                                                    <input type="text" class="form-control " name="employment_street_address" id="employment_street_address" placeholder="Street Address">
                                                    <div id="employment_street_address_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>City</strong></p>
                                                    <input type="text" class="form-control" name="employment_city" id="employment_city" placeholder="City">
                                                    <div id="employment_city_error" class="invalid-feedback"></div>

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Province</strong></p>
                                                    <input type="text" class="form-control " name="employment_province" id="employment_province" placeholder="Province">
                                                    <div id="employment_province_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Postal Code</strong></p>
                                                    <input type="text" class="form-control" name="employment_postal_code" id="employment_postal_code" placeholder="Postal Code">
                                                    <div id="employment_postal_code_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="current-employer">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">How long have you worked at your current employer?</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Years</strong></p>
                                                    <input type="text" min="0" max="100" class="form-control" name="employment_period_years" placeholder="Years" id="employment_period_years" />
                                                    <div id="employment_period_years_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Months</strong></p>
                                                    <input type="text" min="0" max="11" name="employment_period_months" class="form-control" placeholder="Months" id="employment_period_month" />
                                                    <div id="employment_period_months_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="current-income">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Current Income</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Income Amount</strong></p>
                                                        <input type="text" class="form-control" name="income_amount" id="income_amount" placeholder="Income Amount">
                                                        <div id="income_amount_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Income Frequency</strong></p>
                                                        <input type="text" class="form-control" name="income_frequency" id="income_frequency" placeholder="Income Frequency">
                                                        <div id="income_frequency_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="other-income">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Other Income</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Other Income Amount</strong></p>
                                                        <input type="text" class="form-control" name="other_income_amount" id="other_income_amount" placeholder="Income Amount">
                                                        <div id="other_income_amount_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Other Income Frequency</strong></p>
                                                        <input type="text" class="form-control" name="other_income_frequency" id="other_income_frequency" placeholder="Income Frequency">
                                                        <div id="other_income_frequency_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Other Income Type</strong></p>
                                                        <input type="text" class="form-control" name="other_income_type" id="other_income_type" placeholder="Income Type">
                                                        <div id="other_income_type_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Other Income Description</strong></p>
                                                        <input type="text" class="form-control" name="other_income_description" id="other_income_description" placeholder="Income Description">
                                                        <div id="other_income_description_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="side-content-box">
                                <div class="left-side-conetent">
                                    <ul class="left-side-link">
                                        <li><a href="#financing">Financing</a></li>
                                        <li><a href="#desired-purchase">Desired Purchase</a></li>
                                        <li><a href="#additional-information">Additional Information</a></li>
                                    </ul>
                                </div>
                                <div class="head-text-f">
                                    <div class="set-box" id="financing">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Financing</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Financed Amount</strong></p>
                                                        <input type="text" class="form-control" name="financed_amount" id="financed_amount" placeholder="Financed Amount">
                                                        <div id="financed_amount_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Cash Down</strong></p>
                                                        <input type="text" class="form-control" name="cash_down" id="cash_down" placeholder="Cash Down">
                                                        <div id="cash_down_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Term</strong></p>
                                                        <input type="text" class="form-control" name="term" id="term" placeholder="Term">
                                                        <div id="term_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Monthly Payment</strong></p>
                                                        <input type="text" class="form-control" name="monthly_payment" id="monthly_payment" placeholder="Monthly Payment">
                                                        <div id="monthly_payment_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="desired-purchase">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100 info-title">Desired Purchase</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Asset Type</strong></p>
                                                        <input type="text" class="form-control" name="asset_type" id="asset_type" placeholder="Asset Type">
                                                        <div id="asset_type_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Stock Number</strong></p>
                                                        <input type="text" class="form-control" name="stock_number" id="stock_number" placeholder="Stock Number">
                                                        <div id="stock_number_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>VIN/HIN/Serial Number</strong></p>
                                                        <input type="text" class="form-control" name="vin" id="vin" placeholder="VIN">
                                                        <div id="vin_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="set-box" id="additional-information">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <h3 class="w-100">Additional Information</h3>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Sales Rep</strong></p>
                                                        <input type="text" class="form-control" name="sales_rep" id="sales_rep" placeholder="Sales Rep">
                                                        <div id="sales_rep_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="head-felid1">
                                                    <div class="head-felid1">
                                                        <p><strong>Referral Source</strong></p>
                                                        <input type="text" class="form-control" name="referral_source" id="referral_source" placeholder="Referral Source">
                                                        <div id="referral_source_error" class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12">
                                                <div class="head-felid1">
                                                    <p><strong>Comments & Feedback</strong></p>
                                                    <textarea name="message" rows="8" class="form-control" placeholder="" id="message"></textarea>
                                                    <div id="message_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-xs-12">
                                                <input type="hidden" name="source" value="<?php echo $source; ?>">
                                                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

                                                <input type="hidden" name="source_url" value="">

                                                <div class="link-set">
                                                    <p><input type="checkbox" name="terms_condition[]" value="I have read and accept the Terms of Use. I also acknowledge having read the Privacy Policy and agree to the terms herein." /><span class="left-sp">I have read and accept the Terms of Use. I also acknowledge having read the Privacy Policy and agree to the terms herein.</span></p>
                                                </div>
                                                <div class="checkbox-block link-set">
                                                    <p class="sec-check-box"><input type="checkbox" name="sms_consent" checked="">
                                                        <span class="left-sp"> By submitting this form, you agree to be contacted through phone calls, SMS, WhatsApp, or email regarding your inquiry, offers, and updates. You can opt out anytime by notifying us. </span>
                                                    </p>
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
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="btn-list-b">
                                    <a type="submit" class="action back btn btn-info" style="display:block;">Back</a>
                                    <a class="action next btn btn-info" style="display:block;">Continue</a>
                                    <button type="submit" class="action submit btn btn-success" style="display: none;">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="safe-and-secure text-center mt-3">
                        <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/icon-bb.png" style="width:25px;padding-right:5px; padding-top: 0px;" />Confidential, Safe and Secure</p>
                        <p><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/trust-badges-small.webp" class="bottom-img" /> </p>
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
                value: $("body").outerHeight(true),
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
                budget: "required",
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
                monthly_gross_income: "required",
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
                salutation: "required",
                date_of_birth: "required",
                address_type: "required",
                residence_period_years: "required",
                residence_period_months: "required",
                home_status: "required",
                employment_status: "required",
                employment_type: "required",
                employer: "required",
                occupation: "required",
                work_phone: "required",
                employment_address_type: "required",
                employment_unit_number: "required",
                employment_street_number: "required",
                employment_street_address: "required",
                employment_city: "required",
                employment_province: "required",
                employment_postal_code: "required",
                employment_period_years: "required",
                employment_period_months: "required",
            };
            var messages = {
                vehicle_type: "Vehicle Type is required",
                budget: "Budget is required",
                credit_rating: "Credit Rating is required",
                trade_in: "Trade In is required",
                date_of_birth: "Please Enter Your Date Of Birth",
                address_type: "Please Enter Address Type",
                employment_status: "Employment Status is required",
                monthly_gross_income: "Monthly Gross Income is required",
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
                debug: true,
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
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "finance_form"
            }, "*");
        });
    </script>

</body>