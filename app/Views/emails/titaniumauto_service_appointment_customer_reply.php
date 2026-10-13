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
            body, .container{
                width: 600px;
                margin: auto;
            }
            .header-fixed{
                height: 60px;
                background-color: #f5f5f5;
            }
            .header-content h3{
                color: #000;
                text-align: center;
                margin: 0px;
                padding: 12px 0px;
                font-size: 30px;
            }
	   .top-sp{
		padding-top:50px;
	    }
	   .top-spp{
		padding:15px 0px;
	    }
            .section-content, .footer-sh {
                text-align: left;
            }
            .section-content h3, .footer-sh h3 {
                margin: 0px;
                padding-top: 50px;
                font-size: 25px;
                padding-bottom: 20px;
            }
            .section-content p, .footer-sh p, .footer-sh p a {
                font-size: 16px;
                margin: auto;
            }
            .section-content h5 {
                margin: 0px;
                padding-top: 20px;
                font-size: 18px;
            }
            p.t-sp {padding-bottom: 10px;}
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
                    <p class="top-spp">Thank you for contacting Titanium Auto Service! </p>
                    <p class="t-sp">
                        We value your time and are dedicated to responding promptly. You can expect to hear from us within the next three business hours. 
                    </p>
                    <p class="t-sp"> For your convenience, we offer the option of a loaner vehicle (4 available) upon request for an additional fee. To secure one, please book in advance. 
                    </p>
                    <p class="t-sp"> If you have further questions or need assistance, please don't hesitate to reach out to us: 
                    </p>
                    <p class="t-sp"> Address: Titanium Auto Service 211 Wharncliffe Rd S. London, ON N6J 2K8   </p>
                    <p class="t-sp"> Email: <a href="mailto:service@titaniumauto.ca">service@titaniumauto.ca</a>   </p>
                    <p class="t-sp" > Phone: <a href="tel: +18005781237">1-(800) 578-1237</a> | Direct: <a href="tel: +15196722886">(519) 672-2886</a>   </p>
                    <p class="t-sp"> Hours: Monday - Friday, 8am - 5pm    </p>
                    <p class="t-sp"> Thank you once again for choosing us. We look forward to assisting you soon. Have a wonderful day!    </p>
                    <p class="t-sp"> Best regards,  <br>
                        Titanium Auto LTD 
                    </p>
                    <!-- <p>Have a nice day!</p> -->
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