<!DOCTYPE html>
<html>

<head>
    <title></title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
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
            height: 60px;
            background-color: #f5f5f5;
        }

        .header-content h3 {
            color: #000;
            text-align: center;
            margin: 0px;
            padding: 12px 0px;
            font-size: 30px;
        }

        .top-sp {
            padding-top: 50px;
        }

        .top-spp {
            padding: 15px 0px;
        }

        .section-content,
        .footer-sh {
            text-align: left;
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
    </style>

    <!--  HEAD END  -->
</head>
<!--  BODY START  -->

<body>
    <!--  HEADER START -->
    <header class="header-fixed">
        <div class="container">
            <div class="header-content">
                <?php echo $header; ?>
            </div>
        </div>
    </header>

    <!--  HEADER END -->
    <!-- BODY CODE START -->
    <div class="template-content">
        <!-- CONTAINER START -->
        <div class="container">
            <div class="section-content">
                <p class="top-sp">Hello,</p>
                <p>Thank you for taking the time to contact us, we truly appreciate your submission.<br/><br/></p>
                <p>Our team is currently reviewing your application, and we will have an update for you shortly. If you have any questions in the meantime, please feel free to reach out to us at finance@yvrautogroup.com, and we’ll be happy to assist within 2 business days.<br/><br/></p>
                <p>We look forward to connecting with you soon!<br/><br/></p>
                <p>Have a nice day!</p>
            </div>
        </div>
        <!-- CONTAINER END -->
    </div>

    <!-- BODY CODE END -->

    <!-- FOOTHER CODE START -->
    <footer class="footer-content">
        <div class="container">
            <div class="footer-sh">
                <?php echo $footer; ?>
            </div>
        </div>
    </footer>

    <!-- FOOTHER CODE END -->

    <!---- JAVA SCRIPT FILE ----->
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>

<!---- BODY END ---->

</html>