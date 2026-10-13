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
            .section-content p, .footer-sh p, .footer-sh p a, .section-content ol li {
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
                   <p> Thanks for reaching out to <strong> Velocity Auto</strong> - Scarborough's top-rated destination for quality pre-owned vehicles.

Whether you're exploring your options or ready to finance, we can get you approved quickly and at the lowest interest rates possible - regardless of your credit situation. And if you have a vehicle to trade, we'll pay top dollar to make upgrading even easier.
<ol style="padding:20px 20px;" type="number">
<strong>Here's how to move forward right now:</strong>
<li>  <strong> Call us at <a href="tel:647-366-2580" style="color:#f00;">647-366-2580</a></strong> - speak directly with a finance specialist. </li>
<li>  <strong> Text us at  <a href="sms:647-786-5614" style="color:#f00;">647-786-5614</a></strong> -  for quick answers. </li>
<li>  Finalize your pre-approval so your favourite vehicle is reserved for you. </li>
<li>  Book your test drive and ride it home the same day. </li>
</ol>
 <p> We'll be in touch shortly, but if you want to skip the wait and lock in your approval today, just give us a quick call or email <a href="mailto:sales@velocityauto.ca" style="color:#f00;">sales@velocityauto.ca</a> <br/><br/> </p>
 <p> We're looking forward to helping you get your next vehicle - the easy, stress-free way.</p>

</p>
                </div>    
             </div>
             <!-- CONTAINER END -->
         </div>
         
         <!-- BODY CODE END -->
         
         <!-- FOOTHER CODE START -->
         <footer class="footer-content" style="margin-top:35px">
        <div class="container">
            <div class="footer-sh">
                <p> Best Regards,<br />
                    <strong style="font-size: 22px;"> Velocity Auto </strong><br />
                    <img src="data:image/png;base64,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
" style="max-width:18px;width:100%; margin:0px auto;display:inline"/> <a href="https://maps.app.goo.gl/zxGZ5ayQhMDKv7sT6" target="_blank" style="color:black;">  6525 Kingston Road, Scarborough, ON M1C 1L5 </a><br />
                     <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAABmCAMAAACgNwLRAAAAAXNSR0IB2cksfwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAVZQTFRFAAAAra2tYmJib29vW1tbV1dXWFhYVlZWVVVVVFRUWlpacHBwY2NjrKyslZWVWVlZFxcXCAgIAwMDAQEBAAAAAgICBAQEWlpalJSUWVlZMDAwBgYGMTExW1tbm5ubRUVFCQkJDw8PDQ0NDAwMCgoKaWlpwsLC0NDQy8vLzs7Oz8/PFhYWampqbGxsBQUFDg4OiYmJ/f39+Pj4/Pz8/v7+SEhIKCgod3d3eXl5eHh4QEBAPT09Q0NDLy8viYmJhYWFhoaGh4eHiIiIQUFBuLi4QkJCvLy8Q0NDuLi4Q0NDtLS0QUFBurq6QkJCubm5Pz8/urq6ODg4v7+/u7u7R0dHra2tNzc3NDQ0ODg4PDw8ISEhPj4+REREOzs7v7+/8/Pz+vr6Pz8/Xl5e9/f3////BwcH9vb2bW1tGRkZqamp6Ojo7u7uaWlpRkZGIiIiJiYmYWFhXFxc5LKrAgAAAHJ0Uk5TADlv4P/////////hcDle4//////////jWzn///85OeP//////47///////+O//////////////////////vCqqqqqu1D4znrQfJI5z3qQOY83jQ8+E7///////////////////////z/////i+P//2//LmEFoAAAArdJREFUeJzt189LlEEYB/B5Zt53XX9khw6xbooGthYEkSyhJl76hZhKi7cORVf7a/LSIQw6dBBDS6Kii5hGLEZQ2K6HlGxViA6Zudu+O+/TvPNu77vBTC8RXWQGdt/nndnPzryze5gvkH9oYLDBBu9TDF6TBVKAileihegCep22GPymxc0Blt+DrrhSQES/SxR1sK3BCfgRL/o9aNmkVO2OE0eugTDiWHWwocRtYlHxPSanYZWDYgly2WLCrxaXn+UN5UbEjyrc+Zkxl/iP17TZXn0CsVZcb9mVywHi0sPwToVPwmZQJ715f2HxKgQjLfhWhdNrYU3biw0AcsMQ9+rX3XCoI6vCvfnwhvLkVuyQLL+UEwUWYkwtqXBfLrwBl/HghnGKIe5aVM9MgzrmEORMlpwBscvBiKue+eza0Zo/iaJ5G48fOl4o8ftT8Fwr/XYO3xxX45VueBaBL+DyCQ1Ow5MIfAmzGpxLw+MIPIjZLg0+A3MReAhf6XAPPIzAw/jSYIMNNthggw02+D/jPpiJwKO4qMP98CACX8EFDV7pt6YjcKayoDnE5QdgKgKP4XxKjVcTKXG6/8OpV5z681vH1HingMxFpsUcKIdksxL3roa1Syw7yFVOhYTHeHL+vgr/FhOEv1hNN0+pl66CtQ/eU85cE1AIJuNdVZwrFWoHhidVuDYaiRw6BK+7RbV8GuewdhfV0ag2lFEycvd61ts7np68NkvCaNTSc1uFO2PbSL3M53UjjgWJbgr8jOjFQeBXJ1S4beTR95i96wdRjBVbBySe36gvgx9Em5xy4+VZZRAlrdwZnfZ7RJLkVkUuW1zQ320kmRmbqSOwF76P9E7LHhorUVf+uuLdjZflM2Nm6RPuEA0mB27c8kPZ+ASjjozSwG2Xj8vnRLx5Rx/7/7YZbLDB+wf/BNLqwHYt75UiAAAAAElFTkSuQmCC
" style="max-width:18px;width:100%; margin:0px auto;display:inline"/><a href="tel:647-366-2580" style="color:red;"> <strong> 647-366-2580 </strong> </a><br />
 <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAABmCAMAAACgNwLRAAAAAXNSR0IB2cksfwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAVZQTFRFAAAAra2tYmJib29vW1tbV1dXWFhYVlZWVVVVVFRUWlpacHBwY2NjrKyslZWVWVlZFxcXCAgIAwMDAQEBAAAAAgICBAQEWlpalJSUWVlZMDAwBgYGMTExW1tbm5ubRUVFCQkJDw8PDQ0NDAwMCgoKaWlpwsLC0NDQy8vLzs7Oz8/PFhYWampqbGxsBQUFDg4OiYmJ/f39+Pj4/Pz8/v7+SEhIKCgod3d3eXl5eHh4QEBAPT09Q0NDLy8viYmJhYWFhoaGh4eHiIiIQUFBuLi4QkJCvLy8Q0NDuLi4Q0NDtLS0QUFBurq6QkJCubm5Pz8/urq6ODg4v7+/u7u7R0dHra2tNzc3NDQ0ODg4PDw8ISEhPj4+REREOzs7v7+/8/Pz+vr6Pz8/Xl5e9/f3////BwcH9vb2bW1tGRkZqamp6Ojo7u7uaWlpRkZGIiIiJiYmYWFhXFxc5LKrAgAAAHJ0Uk5TADlv4P/////////hcDle4//////////jWzn///85OeP//////47///////+O//////////////////////vCqqqqqu1D4znrQfJI5z3qQOY83jQ8+E7///////////////////////z/////i+P//2//LmEFoAAAArdJREFUeJzt189LlEEYB/B5Zt53XX9khw6xbooGthYEkSyhJl76hZhKi7cORVf7a/LSIQw6dBBDS6Kii5hGLEZQ2K6HlGxViA6Zudu+O+/TvPNu77vBTC8RXWQGdt/nndnPzryze5gvkH9oYLDBBu9TDF6TBVKAileihegCep22GPymxc0Blt+DrrhSQES/SxR1sK3BCfgRL/o9aNmkVO2OE0eugTDiWHWwocRtYlHxPSanYZWDYgly2WLCrxaXn+UN5UbEjyrc+Zkxl/iP17TZXn0CsVZcb9mVywHi0sPwToVPwmZQJ715f2HxKgQjLfhWhdNrYU3biw0AcsMQ9+rX3XCoI6vCvfnwhvLkVuyQLL+UEwUWYkwtqXBfLrwBl/HghnGKIe5aVM9MgzrmEORMlpwBscvBiKue+eza0Zo/iaJ5G48fOl4o8ftT8Fwr/XYO3xxX45VueBaBL+DyCQ1Ow5MIfAmzGpxLw+MIPIjZLg0+A3MReAhf6XAPPIzAw/jSYIMNNthggw02+D/jPpiJwKO4qMP98CACX8EFDV7pt6YjcKayoDnE5QdgKgKP4XxKjVcTKXG6/8OpV5z681vH1HingMxFpsUcKIdksxL3roa1Syw7yFVOhYTHeHL+vgr/FhOEv1hNN0+pl66CtQ/eU85cE1AIJuNdVZwrFWoHhidVuDYaiRw6BK+7RbV8GuewdhfV0ag2lFEycvd61ts7np68NkvCaNTSc1uFO2PbSL3M53UjjgWJbgr8jOjFQeBXJ1S4beTR95i96wdRjBVbBySe36gvgx9Em5xy4+VZZRAlrdwZnfZ7RJLkVkUuW1zQ320kmRmbqSOwF76P9E7LHhorUVf+uuLdjZflM2Nm6RPuEA0mB27c8kPZ+ASjjozSwG2Xj8vnRLx5Rx/7/7YZbLDB+wf/BNLqwHYt75UiAAAAAElFTkSuQmCC
" style="max-width:18px;width:100%; margin:0px auto;display:inline"/><a href="sms:647-786-5614" style="color:red;"> <strong> 647-786-5614 </strong> </a><br />
<img src="data:image/png;base64,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
" style="max-width:18px;width:100%; margin:0px auto;display:inline"/> <a href="mailto:sales@velocityauto.ca" style="color:black;"> sales@velocityauto.ca</a><br/>
                     <img src="data:image/png;base64,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
" style="max-width:18px;width:100%; margin:0px auto;display:inline"/> <a href="https://www.velocityauto.ca" target="_blank" style="color:black;"> www.velocityauto.ca</a><br />
                     
            </div>
        </div>
    </footer>
         
         <!-- FOOTHER CODE END -->
        
        <!---- JAVA SCRIPT FILE ----->
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
         
    </body>
    
    <!---- BODY END ---->
</html>