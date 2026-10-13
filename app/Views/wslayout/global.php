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
        width: 150px;
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
        font-size: 35px;
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
<li> <strong class="list-name"> Stock#</strong> <strong class="list-des"> {{STOCK}} </strong> </li>
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
			<P> {{DESCRIPTION_1000}}</P>
			</div>


<div class="report-header-left t-center" style="padding: 25px 10px 40px;">
                    <p style="padding-top: 0px;font-size: 25px;padding-bottom:12px; font-weight:600;"> SCAN ME!</p>                    
                    <p style="border:1px solid #a5a5a5; padding:5px;display:inline-block;"> {{QR}}</p>
                </div>
				


<div class="bottom-text t-center">
                 <p style="font-size:10px; line-height:17px;">*Prices fo this vehicle may not include charges such as License; Registration Fees; Local Taxes; Finance Charges; or Delivery Fees. Every reasonable effort is made to ensure the accuracy of the information listed above. Vehicle pricing, incentives, options (including standard equipment), and technical specifications listed may not match the exact vehicle displayed. Please confirm with a sales representative the accuracy of this information.</p>
                </div>			
		
   </div>
   
</body>
</html>