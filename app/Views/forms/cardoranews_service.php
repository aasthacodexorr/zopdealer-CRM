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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Flatpickr Calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

        .form-control.is-invalid,
        .was-validated .form-control:invalid {
            border-color: var(--bs-form-invalid-border-color);
            padding-right: calc(1.5em + .75rem);
            background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e);
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body class="service-form">
    <section class="get-sp-form">
        <div class="container py-5">
            <form class="needs-validation" id="lead_form" novalidate="novalidate">
                <!--Financial Services Starts-->
                <div class="col-md-12">
                    <div class="form-layout">
                        <div class="offset-md-1 col-md-10">
                            <div class="list">
                                <!-- STEP 1: START -->
                                <div class="step-section active step well" style="display: block;">
                                    <div class="about-car-content financical-d-content">
                                        <h4>What vehicle needs service or repair?</h4>
                                        <div class="mt-3 form-info-com">
                                            <label class="form-label"><strong>Provide the following details about your vehicle:</strong></label>
                                            <div class="row g-3">
                                                <div class="col-md-10">
                                                    <select id="year" name="year" placeholder="Select" class="form-control">
                                                        <option disabled selected> Select Year</option>
                                                    </select>
                                                    <div id="year_error" class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-10">
                                                    <select id="make" name="make" placeholder="Select" class="form-control">
                                                        <option disabled selected> Select Make</option>
                                                    </select>
                                                    <div id="make_error" class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-10">
                                                    <select id="model" name="model" placeholder="Select" class="form-control">
                                                        <option disabled selected> Select Model</option>
                                                    </select>
                                                    <div id="model_error" class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-10">
                                                    <select id="trim" name="trim" placeholder="Select" class="form-control">
                                                        <option disabled selected> Select Trim</option>
                                                    </select>
                                                    <div id="trim_error" class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="odometer" id="odometer" placeholder="Odometer">
                                                    <div id="odometer_error" class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- STEP 1: END -->

                                <!-- STEP 2 : START -->
                                <div class="step-section check-btn-list step well" style="display:none;">
                                    <div class="about-car-content financical-d-content">
                                        <h4 class="mb-3">What's the problem with your vehicle?</h4>
                                        <div class="vehicle-service-l">
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="general-diagnosis" name="service_check[]" value="General Diagnosis" class="service-check">
                                                <label for="general-diagnosis" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#1f1f1f">
                                                            <path d="M792-120.67 532.67-380q-30 25.33-69.64 39.67Q423.39-326 378.67-326q-108.44 0-183.56-75.17Q120-476.33 120-583.33t75.17-182.17q75.16-75.17 182.5-75.17 107.33 0 182.16 75.17 74.84 75.17 74.84 182.27 0 43.23-14 82.9-14 39.66-40.67 73l260 258.66-48 48Zm-414-272q79.17 0 134.58-55.83Q568-504.33 568-583.33q0-79-55.42-134.84Q457.17-774 378-774q-79.72 0-135.53 55.83-55.8 55.84-55.8 134.84t55.8 134.83q55.81 55.83 135.53 55.83Zm-34-76.66v-81.34h-81.33v-66.66H344V-698h66.67v80.67h80.66v66.66h-80.66v81.34H344Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">General Diagnosis</div>
                                                        <p class="service-subtitle">Not sure? Have a technician diagnose</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="ac-heating" name="service_check[]" value="AC Heating" class="service-check">
                                                <label for="ac-heating" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.6667 5.33366C18.6667 3.8609 19.8606 2.66699 21.3333 2.66699H24C25.4728 2.66699 26.6667 3.8609 26.6667 5.33366V6.66699H29.3333V9.33366H26.6667V12.0003H29.3333V14.667H26.6667V17.3332C28.2859 18.5494 29.3333 20.4859 29.3333 22.667C29.3333 26.3489 26.3486 29.3337 22.6667 29.3337C18.9848 29.3337 16 26.3489 16 22.667C16 20.4859 17.0474 18.5494 18.6667 17.3332V5.33366ZM21.3333 5.33366L24 5.33366V18.8946C25.5536 19.4437 26.6667 20.9254 26.6667 22.667C26.6667 24.0029 26.0118 23.7231 25.0055 23.2932C24.3477 23.0122 23.5399 22.667 22.6667 22.667C21.7934 22.667 20.9856 23.0122 20.3278 23.2932C19.3216 23.7231 18.6667 24.0029 18.6667 22.667C18.6667 20.9254 19.7797 19.4437 21.3333 18.8946V5.33366ZM13.3333 3.99996H16V16.1349C14.3532 17.816 13.3333 20.1225 13.3333 22.6666C13.3333 24.6496 13.9518 26.4882 15.0063 28H13.3333V23.3141L10.4379 26.2096L8.55224 24.324L13.3333 19.5429V17.3333H11.3141L6.43786 22.2095L4.55224 20.3239L7.54283 17.3333H2.66666V14.6666H7.44657L4.55224 11.7723L6.43786 9.88668L11.2178 14.6666H13.3333V12.5521L8.55224 7.77099L10.4379 5.88538L13.3333 8.78085V3.99996Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">AC/Heating</div>
                                                        <p class="service-subtitle">A/C systems, blower motor, heater core</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="battery" name="service_check[]" value="Battery" class="service-check">
                                                <label for="battery" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M26.6662 4H21.3329V6.66667H26.6662V4ZM5.33318 4.00006H10.6665V6.66673H5.33318V4.00006ZM27.9998 11.9997H3.99985V25.3331H27.9998V11.9997ZM1.33318 9.33307V27.9997H30.6665V9.33307H1.33318ZM6.66672 17.3331H13.3334V19.9998H6.66672V17.3331ZM22.6662 14.6661V17.3327H25.3329V19.9994H22.6662V22.6661H19.9996V19.9994H17.3329V17.3327H19.9996V14.6661H22.6662Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Battery</div>
                                                        <p class="service-subtitle">Charge, replacement, testing, starter</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="brakes" name="service_check[]" value="Brakes" class="service-check">
                                                <label for="brakes" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M23.3333 16C23.3333 11.9499 20.0501 8.66667 16 8.66667V6.66667C10.8453 6.66667 6.66667 10.8453 6.66667 16C6.66667 21.1547 10.8453 25.3333 16 25.3333C21.1547 25.3333 25.3333 21.1547 25.3333 16H23.3333ZM30 16H28C28 22.6274 22.6274 28 16 28C9.37258 28 4 22.6274 4 16C4 9.37258 9.37258 4 16 4V2C23.732 2 30 8.26801 30 16ZM17.3333 16C17.3333 16.7364 16.7364 17.3333 16 17.3333C15.2636 17.3333 14.6667 16.7364 14.6667 16C14.6667 15.2636 15.2636 14.6667 16 14.6667C16.7364 14.6667 17.3333 15.2636 17.3333 16ZM20 16C20 18.2091 18.2091 20 16 20C13.7909 20 12 18.2091 12 16C12 13.7909 13.7909 12 16 12C18.2091 12 20 13.7909 20 16Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Brakes</div>
                                                        <p class="service-subtitle">Issues, pads, rotors, calipers</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="engine-light" name="service_check[]" value="Check Engine Light" class="service-check">
                                                <label for="engine-light" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path d="M16 8.65301L26.04 25.9997H5.96L16 8.65301ZM16 3.33301L1.33334 28.6663H30.6667L16 3.33301ZM17.3333 21.9997H14.6667V24.6663H17.3333V21.9997ZM17.3333 13.9997H14.6667V19.333H17.3333V13.9997Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Check Engine Light</div>
                                                        <p class="service-subtitle">Diagnostic scan, code interpretation, reset</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="infotainment-systems" name="service_check[]" value="Infotainment Systems" class="service-check">
                                                <label for="infotainment-systems" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path d="M22.5 25C24.433 25 26 23.433 26 21.5C26 19.567 24.433 18 22.5 18C20.567 18 19 19.567 19 21.5C19 23.433 20.567 25 22.5 25Z" stroke="#2A343D" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M6.5 29C8.433 29 10 27.433 10 25.5C10 23.567 8.433 22 6.5 22C4.567 22 3 23.567 3 25.5C3 27.433 4.567 29 6.5 29Z" stroke="#2A343D" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M26 10L10 14" stroke="#2A343D" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path fill="none" d="M10 25.5V8L26 4V21.5" stroke="#2A343D" stroke-width="2.5" stroke-linecap="round"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Infotainment Systems</div>
                                                        <p class="service-subtitle">Display screen, speakers, head unit, GPS</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="movable-glass" name="service_check[]" value="Movable Glass" class="service-check">
                                                <label for="movable-glass" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7126 6.0402C13.2052 5.5855 13.8509 5.33301 14.5213 5.33301H25.3333C26.8061 5.33301 28 6.52691 28 7.99967V25.333C28 26.8058 26.8061 27.9997 25.3333 27.9997H6.66667C5.19391 27.9997 4 26.8058 4 25.333V15.2501C4 14.5059 4.31103 13.7955 4.85792 13.2906L12.7126 6.0402ZM25.3333 7.99967L14.5213 7.99967L8.74371 13.3328H25.3333V7.99967ZM6.66667 25.333V15.9995H25.3333V25.333H6.66667ZM16 21.3328H22.6667V18.6662H16V21.3328Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Movable Glass</div>
                                                        <p class="service-subtitle">Window regulator, motors, switches, glass</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="oil-change" name="service_check[]" value="Oil Change And Lube" class="service-check">
                                                <label for="oil-change" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#1f1f1f">
                                                            <path d="M483-205.33q14.67-.34 23.17-6.84 8.5-6.5 8.5-17.83 0-12-8.67-18.83-8.67-6.84-24.67-6.5-41.66 1-86-25.17Q351-306.67 339-373.67q-2-9.66-9.5-15.66t-16.5-6q-12 0-19 9.16-7 9.17-4.67 19.84Q305-280 363.33-242q58.34 38 119.67 36.67ZM480-80q-137 0-228.5-94T160-408q0-100 79.5-217.5T480-880q161 137 240.5 254.5T800-408q0 140-91.5 234T480-80Zm0-66.67q109.33 0 181.33-74.5 72-74.5 72-186.83 0-77-64.5-174.67-64.5-97.66-188.83-208.66-124.33 111-188.83 208.66Q226.67-485 226.67-408q0 112.33 72 186.83 72 74.5 181.33 74.5ZM480-480Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Oil Change & Lube</div>
                                                        <p class="service-subtitle">Engine oil change, filter, reset oil light</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="powertrain-engine" name="service_check[]" value="Powertrain And Engine" class="service-check">
                                                <label for="powertrain-engine" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M24.0002 6.66667H20V9.33301H17.3333V6.66667H13.3335V4H24.0002V6.66667ZM29.3335 22.333L29.3335 14.333H32.0002V22.333H29.3335ZM0 23.6667L3.49697e-07 17H2.66667L2.66667 23.6667H0ZM10.995 18.1333H7.8433V23.2H9.18491L13.7563 26.6667H20.6668L24.157 22.8546V14.6667H14.0426L10.995 18.1333ZM12.9525 12H26.6668V24.1333L21.9049 29.3333H12.9525L8.38112 25.8667H5.3335V15.4667H9.90493L12.9525 12ZM18.0002 16V19.3333H20.6668L17.3335 25.3333V22H14.6668L18.0002 16Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Powertrain & Engine</div>
                                                        <p class="service-subtitle">Head gasket, cylinder head, timing chain, ECM</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="scheduled-maintenance" name="service_check[]" value="Scheduled Maintenance" class="service-check">
                                                <label for="scheduled-maintenance" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path d="M25.3333 5.33366H24V2.66699H21.3333V5.33366H10.6667V2.66699H8V5.33366H6.66667C5.18667 5.33366 4.01333 6.53366 4.01333 8.00033L4 26.667C4 28.1337 5.18667 29.3337 6.66667 29.3337H25.3333C26.8 29.3337 28 28.1337 28 26.667V8.00033C28 6.53366 26.8 5.33366 25.3333 5.33366ZM25.3333 26.667H6.66667V13.3337H25.3333V26.667ZM25.3333 10.667H6.66667V8.00033H25.3333V10.667ZM12 18.667H9.33333V16.0003H12V18.667ZM17.3333 18.667H14.6667V16.0003H17.3333V18.667ZM22.6667 18.667H20V16.0003H22.6667V18.667ZM12 24.0003H9.33333V21.3337H12V24.0003ZM17.3333 24.0003H14.6667V21.3337H17.3333V24.0003ZM22.6667 24.0003H20V21.3337H22.6667V24.0003Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Scheduled Maintenance</div>
                                                        <p class="service-subtitle">Manufacturer service intervals</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="steering-suspension" name="service_check[]" value="Steering And Suspension" class="service-check">
                                                <label for="steering-suspension" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16 2.66699C17.3098 2.66699 18.5754 2.85585 19.7713 3.20782C18.8497 3.78278 18.0537 4.54006 17.4337 5.42918C16.9647 5.36619 16.4862 5.33366 16 5.33366C10.5605 5.33366 6.07203 9.4052 5.41587 14.6669H9.33349H10.6668H17.5017C18.5644 16.144 20.1182 17.2441 21.9241 17.728C21.0115 19.4905 19.3353 20.7939 17.3335 21.2002L17.3335 26.5844C22.0742 25.9932 25.8488 22.291 26.5497 17.5851C27.5894 17.2358 28.5337 16.6787 29.3333 15.9631L29.3333 16.0003C29.3333 23.3641 23.3638 29.3337 16 29.3337C8.6362 29.3337 2.66666 23.3641 2.66666 16.0003C2.66666 8.63653 8.6362 2.66699 16 2.66699ZM9.88822 17.3336L5.41585 17.3336C6.01748 22.1586 9.84177 25.9829 14.6668 26.5845L14.6668 21.2002C12.5184 20.7641 10.7451 19.2948 9.88822 17.3336ZM16 26.667L15.9577 26.6669H16.0423L16 26.667ZM29.3333 10.0002C29.3333 12.9457 26.9455 15.3335 24 15.3335C21.0545 15.3335 18.6667 12.9457 18.6667 10.0002C18.6667 7.05468 21.0545 4.66687 24 4.66687C26.9455 4.66687 29.3333 7.05468 29.3333 10.0002ZM23.3334 11.3332H24.6667V5.99988H23.3334V11.3332ZM23.3334 13.9999H24.6667V12.6665H23.3334V13.9999Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Steering & Suspension</div>
                                                        <p class="service-subtitle">Shocks, control arms, suspension alignment</p>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="check-box-btn">
                                                <input type="checkbox" id="tire-services" name="service_check[]" value="Tire Services" class="service-check">
                                                <label for="tire-services" class="service-label">
                                                    <div class="service-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                            <path d="M24 11.333C23.2667 11.333 22.6667 10.733 22.6667 9.99967C22.6667 9.62634 22.8133 9.29301 23.0533 9.05301C23.5867 8.51967 26.3333 7.66634 26.3333 7.66634C26.3333 7.66634 25.48 10.413 24.9467 10.9463C24.7067 11.1863 24.3733 11.333 24 11.333ZM25.3333 17.9997V24.6663C25.3333 26.8663 23.5333 28.6663 21.3333 28.6663C19.1333 28.6663 17.3333 26.8663 17.3333 24.6663V21.9997C17.3333 21.2663 16.7333 20.6663 16 20.6663C15.2667 20.6663 14.6667 21.2663 14.6667 21.9997V25.9997C14.6667 27.4663 13.4667 28.6663 12 28.6663H4C2.53334 28.6663 1.33334 27.4663 1.33334 25.9997V7.33301C1.33334 5.86634 2.53334 4.66634 4 4.66634H12C13.4667 4.66634 14.6667 5.86634 14.6667 7.33301V18.2263C15.08 18.0797 15.5333 17.9997 16 17.9997C18.2 17.9997 20 19.7997 20 21.9997V24.6663C20 25.3997 20.6 25.9997 21.3333 25.9997C22.0667 25.9997 22.6667 25.3997 22.6667 24.6663V17.9997H21.3333V16.1063C18.9733 15.0797 17.3333 12.733 17.3333 9.99967C17.3333 6.31967 20.32 3.33301 24 3.33301C27.68 3.33301 30.6667 6.31967 30.6667 9.99967C30.6667 12.733 29.0267 15.0797 26.6667 16.1063V17.9997H25.3333ZM28 9.99967C28 7.78634 26.2133 5.99967 24 5.99967C21.7867 5.99967 20 7.78634 20 9.99967C20 12.213 21.7867 13.9997 24 13.9997C26.2133 13.9997 28 12.213 28 9.99967ZM12 9.99967L9.33334 12.6663V8.89301L10.8933 7.33301H5.10667L6.66667 8.89301V12.6663L4 9.99967V12.893L6.66667 15.5597V19.333L4 16.6663V19.5597L6.66667 22.2263V25.9997L4 23.333V25.9997H12V23.333L9.33334 25.9997V22.2263L12 19.5597V16.6663L9.33334 19.333V15.5597L12 12.893V9.99967Z"></path>
                                                        </svg></div>
                                                    <div>
                                                        <div class="service-title">Tire Services</div>
                                                        <p class="service-subtitle">Replacement, flat, rotation, alignment</p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="checkbox" style="visibility: hidden;width:0px;display:inline" name="service_check[]">
                                        <div id="service_check_error" class="invalid-feedback text-center"></div>
                                    </div>
                                </div>
                                <!-- STEP 2 : END -->

                                <!-- STEP 3: START -->
                                <div class="step-section step well" id="step3" style="display:none;">
                                    <h4 class="center-text mb-3">Select an available date and time.</h4>

                                    <!-- Calendar -->
                                    <div class="box-card cal">
                                        <div id="calendar"></div>
                                    </div>
                                    <input type="text" style="visibility: hidden;display:inline;width:0px;position:absolute;" value="" id="appointment_date_input" name="appointment_date">
                                    <div id="appointment_date_error" class="invalid-feedback text-center"></div>

                                    <!-- Time Slots -->
                                    <div class="box-card">
                                        <div class="time-header">
                                            <h4 id="selected_date_text">Select a date</h4>
                                            <span id="slot_count"></span>
                                        </div>
                                        <div id="time_slots" class="slot-grid" style="display:none;">
                                            <div class="slot-btn">8:00 AM</div>
                                            <div class="slot-btn">9:00 AM</div>
                                            <div class="slot-btn">10:00 AM</div>
                                            <div class="slot-btn">11:00 AM</div>
                                            <div class="slot-btn">12:00 PM</div>
                                            <div class="slot-btn">1:00 PM</div>
                                            <div class="slot-btn">2:00 PM</div>
                                            <div class="slot-btn">3:00 PM</div>
                                        </div>
                                    </div>
                                    <input type="text" style="visibility: hidden;display:none;width:0px;position:absolute;" value="" id="appointment_time_input" name="appointment_time">
                                    <div id="appointment_time_error" class="invalid-feedback text-center"></div>
                                </div>
                                <!-- STEP 3: END -->

                                <!-- STEP 4: START -->
                                <div class="step-section step well" style="display: none;" id="step4">
                                    <div class="about-car-content financical-d-content">
                                        <h4>Your Contact Information</h4>
                                        <div class="mt-3 form-info-com">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control mb-3" placeholder="First Name" name="first_name" id="first_name" value="">
                                                    <div id="first_name_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control mb-3" name="last_name" id="last_name" placeholder="Last Name" value="">
                                                    <div id="last_name_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="">
                                                    <div id="email_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="tel" class="form-control" placeholder="Phone Number" name="phone" id="tel" value="">
                                                    <div id="phone_error" class="invalid-feedback text-danger"></div>
                                                </div>
                                            </div>
                                            <div class="checkbox-block link-set service-check-text">
                                                <input type="hidden" name="source" value="<?php echo $source; ?>">
                                                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                                <input type="hidden" name="source_url" value="">
                                                <p><input type="checkbox" name="sms_consent" checked="">By providing my phone number, I consent to be contacted by Cardora and its affiliated service partners. Cardora may contact me via phone call, email, or text message (including automated systems) for appointment confirmations, service updates, and marketing communications. Standard message and data rates may apply. Consent is not a condition of purchase. You may opt out of text messages at any time by replying STOP. </p>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12 col-xs-12 text-center">
                                                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                                    <div class="invalid-feedback" id="recaptcha-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- STEP 4: END -->
                            </div>
                            <div class="step-footer">
                                <a type="submit" class="action back btn btn-info text-uppercase" style="display: none;">← BACK</a>
                                <a class="action next btn btn-info text-uppercase" style="display: block;">SELECT</a>
                                <button type="submit" class="action submit btn btn-success text-uppercase" style="display: none;">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="/custom_forms/js/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/dist/assets/js/jquery.inputmask.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

            var current = 1;

            widget = $(".step");
            //        btnskip     = $(".skip");
            btnnext = $(".next");
            btnnext.text("GET STARTED");
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
                vin: {
                    required: true,
                    minlength: 17,
                    maxlength: 17,
                },
                odometer: {
                    required: true,
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
                },
                "service_check[]": "required",
                appointment_date: "required",
                appointment_time: "required"
            };
            var messages = {
                year: {
                    required: "Please Enter the Year",
                    number: "Year should be a Number"
                },
                make: "Please Enter the Make of the Vehicle",
                model: "Please Enter the Model",
                trim: "Please Enter the Trim",
                vin: {
                    required: "Please Enter VIN",
                    minlength: "VIN must be 17 characters long",
                },
                odometer: {
                    required: "Please Enter Odometer",
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
                },
                "service_check[]": {
                    required: "Select one or more options to proceed"
                },
                appointment_date: "Select the desired appointment date",
                appointment_time: "Select the desired appointment time"

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
                    $("#" + cleansed_id + "_error").text("");
                    error.appendTo("#" + cleansed_id + "_error");
                    $("#" + cleansed_id + "_error").addClass("d-block");
                    $("#" + cleansed_id + "_error").find(".is-invalid").addClass("h5");

                    parent.postMessage({
                        type: "css",
                        value: $("body").outerHeight(true),
                        element_id: "service_appointment"
                    }, "*");

                },
                submitHandler: function(form, event) {
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
                                parent.postMessage("redirectToAlternateThankYouPage", "*");

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

                if (current !== 0) {
                    btnnext.text("SELECT");
                } else {
                    btnnext.text("GET STARTED");
                }


                if (current < widget.length) {
                    widget.show();
                    widget.not(':eq(' + (current++) + ')').hide();
                    setProgress(current);
                }
                hideButtons(current);

                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "service_appointment"
                }, "*");
            })

            // Back button click action
            btnback.click(function() {

                if (current !== 0) {
                    btnnext.text("SELECT");
                } else {
                    btnnext.text("GET STARTED");
                }

                if (current > 1) {
                    current = current - 2;
                    btnnext.trigger('click', false);
                }
                hideButtons(current);

                parent.postMessage({
                    type: "css",
                    value: $("body").outerHeight(true),
                    element_id: "service_appointment"
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
    </script>
    <script>
        /* Calendar Setup */
        flatpickr("#calendar", {
            inline: true,
            minDate: "today",
            maxDate: new Date().fp_incr(60),

            disable: [
                function(date) {
                    return date.getDay() === 0; // Sunday Disabled
                }
            ],

            onChange: function(selectedDates, dateStr) {
                console.log(dateStr);

                document.getElementById("selected_date_text").innerText =
                    new Date(dateStr).toDateString();

                document.getElementById("appointment_date_input").value = new Intl.DateTimeFormat('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                }).format(new Date(dateStr));

                document.getElementById("slot_count").innerText =
                    "8 available times";

                document.getElementById("time_slots").style.display = "grid";
                document.getElementById("appointment_time_input").style.display = "inline";
            }
        });

        /* Time Slot Selection */
        document.querySelectorAll(".slot-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                document.querySelectorAll(".slot-btn").forEach(b =>
                    b.classList.remove("active")
                );
                this.classList.add("active");
                document.getElementById("appointment_time_input").value = this.innerText;
            });
        });
    </script>
    <script>
        $(window).on("load", function() {
            parent.postMessage({
                type: "css",
                value: $("body").outerHeight(true),
                element_id: "service_appointment"
            }, "*");
        });
    </script>
    <script src="/dist/assets/js/autoTradeInDropdown.js"></script>

</body>