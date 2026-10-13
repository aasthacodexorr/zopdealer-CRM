<!doctype html>
<html>
<head>
<meta charset="utf-8">

<style>
   
	@import url('http://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');
    
    body{
     font-family: 'Montserrat', sans-serif;
    }
    .f-left{
      float: left;
    }
    .f-right{
      float: right;
    }
    .t-right{
        text-align: right;
    }
    .t-center{
        text-align: center;
    }
    .t-left{
        text-align: left;
    }
    .logo-h{
        width: 100px;
    }
	
	.logo-m{
        width: 350px;
    }
	
	.fuel-logo{
	width: 90px;
    position: relative;
    top: 50px;}
	
    .border{
      border:1px solid #000;
      border-radius: 10px;
    }
    .full-width{
        padding: 15px;
        overflow: hidden;
    }
	.report{padding:20px 20px;}
    .report p strong{
        font-size: 27px;
    }
    .report p{
        color: #000;
        margin:0px;
        padding-top: 8px;
        font-size: 18px;
    line-height: 23px;}
	.report p.header-phone {font-weight: 800;font-size: 23px;}
    .report p a{
        color: #000;
    }
	.report-header-left p {
    color: #000;
}
.report-header-right.information-detail p {
    color: #000;
}
    .head-block {
    overflow: hidden;
	border-bottom: 1px solid #000000;
    }
    .full-width h4 {
        margin: 0px;
        color: #000;
        font-size: 20px;
    }
    .border-bottom{
        border-bottom: 1px solid  #0000006e;
        overflow: hidden;
        padding-top: 15px;
        margin-bottom: 15px;
    }
    .vehicle-information .information-d{
        padding-top: 5px;
    }
    .vehicle-information ul{
        margin-top: 0px;
        padding-left: 15px;
    }
    .vehicle-information ul li {
        width: 100%;
        float: left;
        color: #000;
        font-size: 16px;
        display: block;
         padding: 5px 0px 10px;
        position: relative;
        padding-left: 25px;
		
    }
    img.icon-logo {
        width: 25px;
        position: absolute;
        left: -5px;
        top: 0px;
    }
    .information-detail h3{
        margin: 0px;
        color: #000;
        font-weight: 300;
        font-size: 22px;
    }
    .information-detail h5{
        margin: 0px;
        color: #000;
        font-weight: 300;
        font-size: 25px;
    }
    .information-detail h1{
        font-size: 40px;
        font-weight: bold;
        color: #000;
        margin: 0px;
    }
    .information-detail h1 sub{
        font-size: 20px;
        font-weight: 300;
    }
    .w-full{
        width: 100%;
    }
    .bottom-text{
        width: 100%;
        overflow: hidden;
        line-height: 15px;
    }
    .vehicle-information {
    overflow: hidden;
	width: 50%;
	float:left;
}
.vehicle-image{width: 50%;float:left;}
.vehicle-img-list {float: left;width: 100%;border: 1px solid #000;margin: 15px 0px;}
.vehicle-information p {color: #000;padding-bottom: 10px;}
.vehicle-information p strong {
    font-size: 25px;
}
.vehicle-image img {margin:10px;}
.vehicle-list {width: 50%;float: left;}
.vehicle-list ul li {list-style-type: none;line-height: 40px;border-bottom: 1px solid #a5a5a5;width: 95%;margin: auto;display: block;float: left;}
.vehicle-list ul li strong.list-name {float: left;width: 50%;font-weight: 500;}
.vehicle-list ul li strong.list-des {float: right; font-size:20px;}
.vehicle-detail {clear: both;margin: 15px 15px;}
.vehicle-detail h3 {margin: 0px;font-weight: 700;font-size: 25px;padding: 0px;line-height: 29px;padding-bottom: 10px;width: 92%;}
.vehicle-Description.vehicle-detail p {margin: 2px;font-size: 16px;height: 250px; line-height:25px;}
.bottom-informations .report-header-left {width: 30%;float: left;}
.bottom-informations .information-detail {width: 70%;float: right;}



</style>
</head>
<body style="width:1000px;margin:50px auto 0px;">
   <div class="report"> 
   <div class="head-block" style="padding:10px;margin-bottom: 15px;padding-bottom: 15px;">
            <div class="report-header-left f-left" style="width:70%;">
                <img src="{{LOGO}}" class="logo-m"/>
            </div>
            <div class="report-header-right f-right t-right" style="width:30%;">
                <p class="dealership-name"><strong>{{BNAME}}</strong></p>
                <p class="header-address">{{BADDRESS}},<br/> {{BCITY}}, {{BSTATE}} {{BCODE}}</p>
                <p class="header-phone">{{BPHONE}}</p>
            </div>
        </div>   
    
   <div class="vehicle-title">
            <div class="report-header-left f-left" style="width:50%;">
<h1 style="color: #000;text-align:left;margin: 0;font-size: 40px;font-weight:bold;margin-bottom:0px;"> {{YEAR}} {{MAKE}} {{MODEL}}﻿ </h1>
            </div>
            <div class="report-header-right f-right t-right" style="width:50%;">
               <h1
                        style="color: #000;margin: 0;font-size: 60px;font-weight:bold;margin-bottom:0px;">
                        ${{PRICE}} </h1><span style="color:#000;">+taxes and licensing</span>
            </div>
        </div> 

<div class="vehicle-img-list">
<div class="vehicle-image">
<img src="{{IMAGE}}" height="350px" style="width:100%;">
</div>

<div class="vehicle-list">
<ul class="list-item">
<li> <strong class="list-name"> Stock#</strong> <strong class="list-des">{{STOCK}} </strong> </li>
<li> <strong class="list-name">Transmission</strong> <strong class="list-des"> {{TRANSMISSION}} </strong> </li>
<li> <strong class="list-name"> Door</strong> <strong class="list-des"> {{DOOR}} </strong> </li>
<li> <strong class="list-name"> Exterior</strong> <strong class="list-des"> {{EXTERIOR_COLOR}} </strong> </li>
<li> <strong class="list-name"> Interior</strong> <strong class="list-des"> {{INTERIOR_COLOR}} </strong> </li>
<li> <strong class="list-name"> Engine</strong> <strong class="list-des"> {{ENGINE}} </strong> </li>
<li> <strong class="list-name"> Passengers</strong> <strong class="list-des"> {{PASSENGERS}} </strong> </li>
<li> <strong class="list-name"> Odometer</strong> <strong class="list-des"> {{ODOMETER}} {{KM_MILES}} </strong> </li>
</ul>	
</div>


</div>

<div class="vehicle-Description vehicle-detail">
			<h3>Vehicle Description</h3>
			<P> {{DESCRIPTION_LIMITED}}</P>
			</div>


<div class="bottom-informations">
<div class="report-header-left" style="padding:0px;">
                    <p style="padding-top: 0px;font-size: 25px;padding-bottom:12px; font-weight:600;"> SCAN ME!</p>                    
                    <p style="border:1px solid #a5a5a5; padding:5px;display:inline-block;"> {{QR}}</p>
                </div>


<div class="information-detail">
<p style="padding-top: 0px;font-size: 25px;padding-bottom:12px;text-align: center;font-weight:600;">Fuel Economy</p>
                <div class="report-header-left f-left t-center" style="width:30%;padding: 45px 10px;">
                    <h5>CITY</h5>
                    <h1 style="font-size:30px;">{{FUEL_CITY}}</h1>
                    <p><strong style="font-size:20px;">L/100 {{KM_MILES}} </strong></p>
                </div>
                
                <div class="report-header-left f-left t-center" style="width:30%;padding: 10px 10px;">
                   <img src="data:image/png;base64,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" class="logo-h"/>
                </div>
				<div class="report-header-left f-left t-center" style="width:30%;padding: 45px 10px;">
                    <h5>HIGHWAY</h5>
                    <h1 style="font-size:30px;">{{FUEL_HWY}}</h1>
                    <p><strong style="font-size:20px;">L/100 {{KM_MILES}}</strong></p>
                </div>
            </div>

</div>
				


<div class="bottom-text t-center">
                 <p style="font-size:10px; line-height:17px;">*Prices fo this vehicle may not include charges such as License; Registration Fees; Local Taxes; Finance Charges; or Delivery Fees. Every reasonable effort is made to ensure the accuracy of the information listed above. Vehicle pricing, incentives, options (including standard equipment), and technical specifications listed may not match the exact vehicle displayed. Please confirm with a sales representative the accuracy of this information.</p>
                </div>			
		
   </div>
   
</body>
</html>