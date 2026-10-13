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
    <link rel="stylesheet" href="/dist/assets/css/sumoselect.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="/custom_forms/style.css"> -->
    <?php if (!empty($custom_css)) {
        $custom_css_urls = explode(',', $custom_css);
        foreach ($custom_css_urls as $each_url) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $each_url; ?>">
    <?php  }
    } ?>
    <!--  HEAD END  -->

    <style>
        select.form-control[size],
        select.form-control[multiple] {
            height: 60px;
        }

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

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>
<!--  BODY START  -->

<body>
    <!--Financial Services Starts-->
    <section class="details-main form-new-set">
        <div class="container">
            <form class="needs-validation" id="lead_form">
                <!--Financial Services Starts-->
                <div class="col-md-10 col-md-offset-1">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="form-layout">
                        <div class="step well" style="display:block;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Get a Car Loan Fast With Any Credit Rating</h3>
                                    <p>Good credit, bad credit & no credit approved!</p>
                                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                                    <input type="hidden" name="source" value="<?php echo $source; ?>">
                                    <ul class="form-list-desgine">
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="vehicle_type_under-month" name="vehicle_type" value="Car"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/carblue.png" class="img-resposive" /><strong>Car</strong></label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="vehicle_type_250-month" name="vehicle_type" value="SUV"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/suvwhite.png" class="img-resposive" /><strong>SUV</strong></label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle" style="opacity: 0.6;">
                                            <div class="head-felid">
                                                <input style="cursor: not-allowed !important;" type="radio" class="seclect-ratio-btn" id="vehicle_type_374-month" name="vehicle_type" value="Truck" disabled="disabled"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/no-truck.png" class="img-resposive" /><strong>No Pickup <br> Trucks</strong></label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="vehicle_type_Over-month" name="vehicle_type" value="Van"><label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/minivanblack.png" class="img-resposive" /><strong>Minivan</strong></label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="vehicle_type_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your monthly budget?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="budget_300-month" name="budget" value="Between 350 and 450 per month"><label>$350 - 450 / month</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="budget_400-month" name="budget" value="Between 450 and 550 per month"><label>$450 - 550 / month</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="budget_500-month" name="budget" value="Between 550 and 650 per month"><label>$550 - 650 / month</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="budget_Over-month" name="budget" value="Over 650 per month"><label>Over $650 / month</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="budget_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:block;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <p>These are the vehicles models available for financing - please pick your top 3 choices</p>
                                    <ul class="form-list-desgine model-selection" id="sedan_model_selection" style="max-height: 50vh;overflow-y:auto;">
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_honda_civic" name="selected_model[]" value="Honda Civic">
                                                <label class="img-boxs">
                                                    <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_honda_civic_hatchback_angularfront.jpg" class="img-resposive" />
                                                    <strong>Honda Civic</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_chevy_cruze" name="selected_model[]" value="Chevy Cruze">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_chevrolet_cruze_angularfront.jpg" class="img-resposive" />
                                                    <strong>Chevy Cruze</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_hyundai_elantra" name="selected_model[]" value="Hyundai Elantra">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_hyundai_elantra_angularfront.jpg" class="img-resposive" />
                                                    <strong>Hyundai Elantra</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_volkswagen_jetta" name="selected_model[]" value="Volkswagen Jetta">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_volkswagen_jetta_angularfront.jpg" class="img-resposive" />
                                                    <strong>Volkswagen Jetta</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_ford_fusion" name="selected_model[]" value="Ford Fusion">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_ford_fusion_angularfront.jpg" class="img-resposive" />
                                                    <strong>Ford Fusion</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_nissan_sentra" name="selected_model[]" value="Nissan Sentra"><label class="img-boxs">
                                                    <img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_nissan_sentra_angularfront.jpg" class="img-resposive" />
                                                    <strong>Nissan Sentra</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_kia_forte" name="selected_model[]" value="Kia Forte">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_kia_forte_angularfront.jpg" class="img-resposive" />
                                                    <strong>Kia Forte</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_nissan_sentra" name="selected_model[]" value="Mazda3">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_mazda_mazda_3_angularfront.jpg" class="img-resposive" />
                                                    <strong>Mazda3</strong>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="form-list-desgine model-selection d-none" id="suv_model_selection" style="max-height: 50vh;overflow-y:auto;">
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_honda_cr_v" name="selected_model[]" value="Honda CRV">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_honda_cr_v_angularfront.jpg" class="img-resposive" />
                                                    <strong>Honda CRV</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_chevrolet_equinox" name="selected_model[]" value="Chevy Equinox">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_chevrolet_equinox_angularfront.jpg" class="img-resposive" />
                                                    <strong>Chevy Equinox</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_chevrolet_trax" name="selected_model[]" value="Chevy Trax">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_chevrolet_trax_angularfront.jpg" class="img-resposive" />
                                                    <strong>Chevy Trax</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_hyundai_kona" name="selected_model[]" value="Hyundai Kona">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_hyundai_kona_angularfront.jpg" class="img-resposive" />
                                                    <strong>Hyundai Kona</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_hyundai_tucson" name="selected_model[]" value="Hyundai Tucson">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_hyundai_tucson_angularfront.jpg" class="img-resposive" />
                                                    <strong>Hyundai Tucson</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_ford_escape" name="selected_model[]" value="Ford Escape">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_ford_escape_angularfront.jpg" class="img-resposive" />
                                                    <strong>Ford Escape</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_mitsubishi_rvr" name="selected_model[]" value="Mitsubishi RVR">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_mitsubishi_rvr.jpg" class="img-resposive" />
                                                    <strong>Mitsubishi RVR</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_mitsubishi_outlander" name="selected_model[]" value="Mitsubishi Outlander">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_mitsubishi_outlander.jpg" class="img-resposive" />
                                                    <strong>Mitsubishi Outlander</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_nissan_qash" name="selected_model[]" value="Nissan Qashqai">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_nissan_qash.jpg" class="img-resposive" />
                                                    <strong>Nissan Qashqai</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_nissan_rogue_sport" name="selected_model[]" value="Nissan Rogue">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_nissan_rogue_sport_angularfront.jpg" class="img-resposive" />
                                                    <strong>Nissan Rogue</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_kia_sportage" name="selected_model[]" value="Kia Sportage">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_kia_sportage_angularfront.jpg" class="img-resposive" />
                                                    <strong>Kia Sportage</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_kia_sorento" name="selected_model[]" value="Kia Sorento">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_kia_sorento_angularfront.jpg" class="img-resposive" />
                                                    <strong>Kia Sorento</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_dodge_journey" name="selected_model[]" value="Dodge Journey">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_dodge_journey_angularfront.jpg" class="img-resposive" />
                                                    <strong>Dodge Journey</strong>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_mazda_cx_3" name="selected_model[]" value="Mazda CX3">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/models/2019_mazda_cx_3_angularfront.jpg" class="img-resposive" />
                                                    <strong>Mazda CX3</strong>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="form-list-desgine model-selection d-none" id="minivan_model_selection">
                                        <li class="home-vehicle">
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_model_minvan" name="selected_model[]" value="All Minivans">
                                                <label class="img-boxs"><img src="<?php echo getenv('image.cdn') ?>/dist/assets/images/minivanblack.png" class="img-resposive" />
                                                    <strong>All Minivans</strong>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="selected_model_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Which are your top 3 colour preferences?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_back" name="selected_color[]" value="Black">
                                                <label>Black</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_white" name="selected_color[]" value="White">
                                                <label>White</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_grey" name="selected_color[]" value="Grey">
                                                <label>Grey</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_dark_blue" name="selected_color[]" value="Dark Blue">
                                                <label>Dark Blue</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_dark_red" name="selected_color[]" value="Dark Red">
                                                <label>Dark Red</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_silver" name="selected_color[]" value="Silver">
                                                <label>Silver</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="checkbox" class="seclect-ratio-btn" id="selected_color_gold" name="selected_color[]" value="Gold">
                                                <label>Gold</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="selected_color_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Do You Have a Vehicle to Trade In?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="Trade-In-No" name="trade_in" value="No"><label>No</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="Trade-In-Yes" name="trade_in" value="Yes"><label>Yes, I want to trade</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="Trade-In-Unsure" name="trade_in" value="Unsure"><label>Yes, I'm keeping it</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="trade_in_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your date of birth?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <select type="number" class="form-control " name="birth_date" id="dobday" placeholder="Day">
                                                </select>
                                                <div id="birth_date_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <select class="form-control" name="birth_month" id="dobmonth" placeholder="Month"></select>
                                                <div id="birth_month_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <select class="form-control" name="birth_year" id="dobyear" placeholder="Year">
                                                </select>
                                                <div id="birth_year_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your current monthly income?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="monthly-b-over-4500" name="monthly_gross_income" value="Over 1600"><label>Over $1600</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="monthly-b-under-1600" name="monthly_gross_income" value="Under 1600"><label>Under $1600</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="monthly_gross_income_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>How long have you been earning this income?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="employment_period_less_than_3" name="employment_period_with_this_salary" value="Less Than 3 Months"><label>Less than 3 Months</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="employment_period_more_than_3" name="employment_period_with_this_salary" value="More Than 3 Months"><label>More than 3 Months</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="employment_period_with_this_salary_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>Where are you working?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="company_name" id="company_name" placeholder="Company Name">
                                                <div id="company_name_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Title">
                                                <div id="job_title_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <h4 class="mt-2">Employment Type</h4>
                                    <ul class="form-list-desgine">
                                        <li style="display:none;">
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="employment_type_default" name="employment_type" value=""><label></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="employment_type_self_employed" name="employment_type" value="Self-Employed"><label>Self-Employed</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="employment_type_other_type" name="employment_type" value="Home/Pension/Other"><label>Home/Pension/Other</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>How soon do you need this vehicle?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="days_14-days" name="vehicle_delivery_day" value="Within 14 Days"><label>Within 14 Days</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="days_30-days" name="vehicle_delivery_day" value="Within 30 Days"><label>Within 30 Days</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="days_90-days" name="vehicle_delivery_day" value="Within 90 Days"><label>Within 90 Days</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="days_180-days" name="vehicle_delivery_day" value="Within 180 Days"><label>Within 180 Days</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <h3 class="delay-reason d-none">Why are you applying today if you are not ready to get a vehicle within the next one full month?</h3>
                                    <ul class="form-list-desgine delay-reason d-none">
                                        <li>
                                            <div class="head-felid1">
                                                <textarea name="reason" rows="8" class="form-control" placeholder="" id="multi-step-reason"></textarea>
                                                <div id="reason_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="vehicle_delivery_day_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="step well" style="display:none ;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h4>We get approximately 350 applications per month from our customers and because of limited staffing the process can be very time consuming. How serious are you regarding applying for car financing today?</h4>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="customer_intention_serious" name="customer_intention" value="Very Serious"><label>Very Serious</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="customer_intention_semi_serious" name="customer_intention" value="Semi-serious"><label>Semi-serious</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="customer_intention_not_serious" name="customer_intention" value="Not Very Serious"><label>Not Very Serious</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid">
                                                <input type="radio" class="seclect-ratio-btn" id="customer_intention_just_browsing" name="customer_intention" value="Just Browsing"><label>Just Browsing</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="customer_intention_error" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div> -->
                        <div class="step well" style="display: none;">
                            <div class="head-text-f">
                                <div class="set-box">
                                    <h3>What is your address?</h3>
                                    <ul class="form-list-desgine">
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="street_address" id="street_address" placeholder="Street Address">
                                                <div id="street_address_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                                <div id="city_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control " name="province" id="province" placeholder="Province">
                                                <div id="province_error" class="invalid-feedback"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="head-felid1">
                                                <input type="text" class="form-control" name="postal_code" id="postal_code" placeholder="Postal Code">
                                                <div id="postal_code_error" class="invalid-feedback"></div>

                                            </div>
                                        </li>
                                    </ul>
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
                                            <input type="email" id="email" name="email" class="form-control" id="email" placeholder="Email">
                                            <div id="email_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="tel" id="phone" name="phone" class="form-control" id="tel" placeholder="Phone Number">
                                            <div id="phone_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="email" name="confirm_email" class="form-control" id="confirm_email" placeholder="Re-enter Email">
                                            <div id="confirm_email_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="head-felid1">
                                            <input type="tel" name="confirm_phone" class="form-control" id="confirm_phone" placeholder="Re-enter Phone Number">
                                            <div id="confirm_phone_error" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <input type="hidden" name="source_url" value="">

                                        <div class="link-set">
                                            <p>I have read and accept the <a href="https://www.richmondhillautoloans.ca/terms-conditions/">Terms of Use</a> . I also acknowledge having read the <a href="https://www.richmondhillautoloans.ca/privacy-policy/">Privacy Policy</a> and agree to the terms herein.</p>
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
                        <a class="action next btn btn-info" style="display:block;">Continue</a>
                        <button type="submit" class="action submit btn btn-success" style="display: none;">Get Approved</button>
                        <a type="submit" class="action back btn btn-info" style="display:block;">Back</a>

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
    <script src="/dist/assets/js/jquery.sumoselect.min.js"></script>
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


            $('select[multiple]').SumoSelect();

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
                budget: "required",
                credit_rating: "required",
                trade_in: "required",
                // customer_intention: "required",
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
                vehicle_delivery_day: "required",
                street_address: "required",
                city: "required",
                province: "required",
                postal_code: "required",
                first_name: "required",
                last_name: "required",
                reason: "required",
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
                confirm_email: {
                    required: true,
                    email: true,
                    equalTo: "#email"
                },
                confirm_phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    number: true,
                    equalTo: "#phone"
                },
                "selected_model[]": {
                    required: true,
                    minlength: 1,
                    maxlength: 3,
                },
                "selected_color[]": {
                    required: true,
                    minlength: 1,
                    maxlength: 3,
                }
            };
            var messages = {
                vehicle_type: "Vehicle Type is required",
                "selected_model[]": {
                    required: "Make and model is required",
                    maxlength: "Maximum 3 Make and models can be selected.",
                    minlength: "Minimum 1 Make and models can be selected."
                },
                "selected_color[]": {
                    required: "Color preference is required",
                    maxlength: "Maximum 3 color preferences can be selected.",
                    minlength: "Minimum 1 color preferences can be selected."
                },
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
                monthly_gross_income: "Monthly Gross Income is required",
                employment_period_with_this_salary: "Employment Period is required",
                company_name: "Please Enter Company Name",
                job_title: "Please Enter Job Title",
                street_address: "Please Enter Street Address",
                city: "Please Enter City",
                province: "Please Enter Province",
                postal_code: "Please Enter Postal Code",
                first_name: "Please Enter Your First Name",
                last_name: "Please Enter Your Last Name",
                reason: 'Please Enter A Reason For Delay',
                email: {
                    required: "Please Enter Your Email",
                    email: "Please Enter a valid Email"
                },
                phone: {
                    required: "Please Enter Your Phone Number",
                    number: "Phone Number should be a Number",
                    minlength: "Your Phone Number must be 10 characters long"
                },
                confirm_email: {
                    required: "Please Re-Enter Your Email",
                    email: "Please Enter a valid Email",
                    equalTo: "Please Re-Enter Same Email Again"
                },
                confirm_phone: {
                    required: "Please Re-Enter Your Phone Number",
                    number: "Phone Number should be a Number",
                    minlength: "Your Phone Number must be 10 characters long",
                    equalTo: "Please Re-Enter Same Phone Number Again"
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
                    error.appendTo("#" + element.attr("name").replace('[]', '') + "_error");
                    $("#" + element.attr("name").replace('[]', '') + "_error").addClass("d-block");
                    $("#" + element.attr("name").replace('[]', '') + "_error").find(".is-invalid").addClass("h5");

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

        $('#days_180-days').change(function() {
            if ($(this).is(":checked")) {
                $('.delay-reason').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#days_90-days').change(function() {
            if ($(this).is(":checked")) {
                $('.delay-reason').removeClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");
        });

        $('#days_30-days').change(function() {
            if ($(this).is(":checked")) {
                $('.delay-reason').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");

        });

        $('#days_14-days').change(function() {
            if ($(this).is(":checked")) {
                $('.delay-reason').addClass('d-none');
            }

            parent.postMessage({
                type: "css",
                value: $(document).height(),
                element_id: "finance_form"
            }, "*");

        });

        $('[name="vehicle_type"]').change(function() {
            console.log($(this).val());

            //Reset Model Selections
            $('.model-selection').addClass('d-none');
            $("[name='selected_model[]']").prop('checked', false);

            switch ($(this).val()) {
                case 'Car':
                    $('#sedan_model_selection').removeClass('d-none');
                    break;

                case 'SUV':
                    $('#suv_model_selection').removeClass('d-none');
                    break;

                case 'Van':
                    $('#minivan_model_selection').removeClass('d-none');
                    $('#selected_model_minvan').prop('checked', true);
                    break;


                default:
                    break;
            }
        });

        $('[name="vehicle_type"]:checked').trigger('change');

        $(`
        #days_180-days,
        #days_90-days,
        #days_30-days,
        #days_14-days        
        `).trigger('change');
    </script>


</body>