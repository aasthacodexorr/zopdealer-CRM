<!DOCTYPE html>
<html>

<head>
    <title></title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEET -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
        body,
        .container {
            width: 600px;
            margin: auto;
        }

        .header-fixed {
            background-color: #f5f5f5;
            text-align: center;
            padding: 25px 0px;
            font-size: 24px;
        }

        .header-content h3 {
            color: #000;
            text-align: center;
            margin: 0px;
            padding: 12px 0px;
            font-size: 30px;
        }

        .list-v-content {
            padding: 0px 0px 0px 15px;
        }

        .list-v-content li {
            color: #000;
            font-size: 14px;
            line-height: 22px;
        }

        .top-sp {
            padding-top: 30px;
        }

        .top-spp strong,
        .top-sp strong {
            padding: 15px 0px 0px;
            display: block;
        }

        .section-content {
            text-align: left;
            padding-bottom: 30px;
        }

        .section-content h3,
        .footer-sh h3 {
            margin: 0px;
            padding-top: 50px;
            font-size: 25px;
            padding-bottom: 20px;
        }

        .section-content p,
        .footer-sh p,
        .footer-sh p a {
            font-size: 16px;
            margin: auto;
        }

        .section-content h5 {
            margin: 0px;
            padding-top: 20px;
            font-size: 18px;
        }

        .footer-content {
            background-color: #f5f5f5;
            text-align: center;
            padding: 25px 0px;
            font-size: 16px;
        }
    </style>

    <!--  HEAD END  -->
</head>
<!--  BODY START  -->

<body>
<!--  HEADER START -->
<header class="header-fixed">
    <div class="container">
        <div class="header-content">
            ZOP Dealer
        </div>
    </div>
</header>

<!--  HEADER END -->
<!-- BODY CODE START -->
<div class="template-content">
    <!-- CONTAINER START -->
    <div class="container">
        <div class="section-content">
            <p class="top-sp"><strong>Lead Info</strong></p>
            <ul class="list-v-content">
                <li>Source: ZOP Dealer</li>
                <li>Submitted on: <?php echo $leadinfo['creation_date'] ?> </li>
                <li>Dealer: <?php echo $leadinfo['business_name'] ?> </li>
            </ul>
            <!-- SECTION_END : Additional Fields -->

            <!-- SECTION_START : Customer -->
            <div class="c-block-l">
                <p class="top-spp"><strong>Customer</strong></p>
                <ul class="list-v-content">
                    <li>Name: <?php echo $customer['customer_name'] ?> </li>
                    <li>Phone: <?php echo $customer['customer_phone'] ?> </li>
                    <li>Email: <?php echo $customer['customer_email'] ?> </li>
                </ul>
            </div>
            <!-- SECTION_END : Customer -->

            <!-- SECTION_START : Vechile Interest -->
            <?php if (isset($vehicle)) : ?>
                <div class="c-block-l">
                    <p class="top-spp"><strong>Vehicle of Interest</strong></p>
                    <ul class="list-v-content">
                        <li>Interest type: Purchase</li>
                        <li>Year: <?php echo $vehicle['year']; ?> </li>
                        <li>Make: <?php echo $vehicle['make']; ?></li>
                        <li>Model: <?php echo $vehicle['model']; ?></li>
                        <li>Stock Number: <?php echo $vehicle['stock_no']; ?> </li>
                        <li>VIN: <?php echo $vehicle['vin']; ?></li>
                        <li>
                            Price: <?php echo ($vehicle['special_price_on'] == "1") ? $vehicle['special_price'] : $vehicle['price']; ?></li>

                    </ul>
                </div>
            <?php endif; ?>
            <!-- SECTION_END : Vechile Interest -->

            <!-- SECTION_START : Additional Fields -->
            <div class="c-block-l">
                <p class="top-spp"><strong>Additional Fields</strong></p>
                <p>
                    <?php if (count($additional) > 0) {
                        echo '<ul class="list-v-content">';
                        foreach ($additional as $key => $val) {
                            echo "<li>{$key}: {$val}</li>";
                        }
                        echo '</ul>';
                    } ?>
                </p>
            </div>
            <!-- SECTION_END : Additional Fields -->
             ̰
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
<!-- BODY CODE END -->

<!-- FOOTHER CODE START -->
<footer class="footer-content">
    <div class="container">
        <div class="footer-sh">
            © ZOP Dealer. All Rights Reserved.
        </div>
    </div>
</footer>
<!-- FOOTHER CODE END -->

<!---- JAVA SCRIPT FILE ----->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>

<!---- BODY END ---->

</html>