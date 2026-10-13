<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @import url('http://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }

        .t-right {
            text-align: right;
        }

        .t-center {
            text-align: center;
        }

        .t-left {
            text-align: left;
        }

        .logo-h {
            width: 40px;
        }

        .border {
            border: 1px solid #000;
            border-radius: 10px;
        }

        .full-width {
            padding: 15px;
            overflow: hidden;
        }

        .report p strong {
            font-size: 13px;
        }

        .report p {
            color: #000;
            margin: 0px;
            padding-top: 8px;
            font-size: 13px;
        }

        .report p a {
            color: #000;
        }

        .head-block {
            overflow: hidden;
            margin-bottom: 15px;
        }

        .full-width h4 {
            margin: 0px;
            color: #000;
            font-size: 20px;
        }

        .border-bottom {
            border-bottom: 1px solid #000000;
            overflow: hidden;
            padding-top: 15px;
            margin-bottom: 15px;
			display:block;
        }

        .vehicle-information .information-d {
            padding-top: 5px;
        }

        .vehicle-information ul {
            margin-top: 0px;
            padding-left: 15px;
        }

        .vehicle-information ul li {
            width: 45%;
            float: left;
            color: #000;
            font-size: 16px;
            display: block;
            padding: 12px 0px;
            position: relative;
            padding-left: 25px;
        }

        img.icon-logo {
            width: 25px;
            position: absolute;
            left: -2px;
            top: 10px;
        }

        .information-detail h3 {
            margin: 0px;
            color: #000;
            font-weight: 300;
            font-size: 22px;
        }

        .information-detail h5 {
            margin: 0px;
            color: #000;
            font-weight: 300;
            font-size: 35px;
        }

        .information-detail h1 {
            font-size: 40px;
            font-weight: bold;
            color: #000;
            margin: 0px;
        }

        .information-detail h1 sub {
            font-size: 20px;
            font-weight: 300;
        }

        .w-full {
            width: 100%;
        }

        .bottom-text {
            width: 100%;
            overflow: hidden;
            line-height: 15px;
        }

        .vehicle-information {
            overflow: hidden;
        }
        .report-header-left.f-left h4 {
            margin-bottom: 0px;
        }
        .fule-list-left h5 {
           font-size: 13px;
           margin: 0;
        }
        ul.car-detail-list {
            padding: 0px;
            margin-top: 0px;
        }
        ul.car-detail-list li {
            display: inline-block;
            width: 48%;
          font-size: 15px;
          line-height: 29px;
        }
        .report-header-left h4 {
            margin-bottom: 10px;
        }
        ul.car-detail-list.w-33 li{
            width: 32%;
        }
    </style>
</head>

<body style="width:800px;margin:150px auto;">
    <div class="report" style="border: 1px solid #000;margin:50px auto;">
        <div class="head-block border-bottom" style="padding-bottom:10px;margin-bottom: 0px;">
            <div class="report-header-right" style="width:100%; text-align: center;">
                <p><strong style="font-size:25px;">{{BNAME}}</strong></p>
                <p>{{BPHONE}}</p>
                <p> {{WEBSITE}} </p>
            </div>
        </div>
        <div class="width-30" style="width:30%; border-right: 1px solid #919191; padding: 0px 5px; float:left;">
            <div class="report-header-left f-left" style="width:100%;">
                <h4>WARRANTY</h4>
                <p>Please note that your extended warranty purchase is an additional cost above the purchase price of your new vehicle. You may have the option to include the cost of your warranty purchase into your vehicle financing or you may pay for it separately. Please, ask us about our extended warranty products.</p>
            </div>
            <div class="report-header-left f-left" style="width:100%;">
                <h4>COMPANY INFO</h4>
                <p>Company: {{BNAME}}
                    {{BADDRESS}}, <br/> {{BCITY}}, {{BSTATE}} <br/> {{BCODE}}</p>
                    <p>Phone: {{BPHONE}}</p>
                    <p>Fax: {{BFAX}}</p>
                    <p>Email: {{BEMAIL}}</p>
            </div>
            <div class="report-header-left f-left" style="width:100%;">
               <h4>COMPANY INFO</h4>
               <p>Monday 09:00 am - 06:00 pm</p>
               <p>Tuesday 09:00 am - 06:00 pm</p>
               <p>Wednesday 09:00 am - 06:00 pm</p>
               <p>Thursday 09:00 am - 06:00 pm</p>
               <p>Friday 09:00 am - 06:00 pm</p>
               <p>Saturday 09:00 am - 05:00 pm</p>
               <p>Sunday Closed</p>
            </div>
            <div class="report-header-left f-left">
                <h4>FUEL ECONOMY</h4>
                <div class="report-header-left f-left t-center fule-list-left" style="width:33%;padding: 15px 0px 0px;">
                    <h5>CITY</h5>
                    <h5>{{FUEL_CITY}}</h5>
                    <p><strong>L/100 {{KM_MILES}} </strong></p>
                </div>
                <div class="report-header-left f-left t-center fule-list-img" style="width:33%;padding: 10px 0px 0px;">
                    <img src="data:image/png;base64,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"
                        class="logo-h" />
                </div>
                <div class="report-header-left f-left t-center fule-list-left" style="width:33%;padding: 15px 0px 0px;">
                    <h5>HIGHWAY</h5>
                    <h5>{{FUEL_HWY}}</h5>
                    <p><strong>L/100 {{KM_MILES}}</strong></p>
                </div>
            </div>
            <div class="report-header-left f-left t-left" style="width:100%;padding: 0px 0px 20px;">
                <p>Actual Mileage will vary with options, driving conditions, driving habits, and vehicle condition. From results reported to us indicated that the majority of similar vehicles will achieve between the mpg stipulated above.</p>
                {{QR}}
            </div>
        </div>
        <div class="width-70" style="width:65%;float: left;padding: 0px 9px;">
             <div class="report-header-left">
              <h4 class="t-center"> {{YEAR}} {{MAKE}} {{MODEL}} {{TRIM}}</h4>
              <ul class="car-detail-list">
                <li><strong>Body Type:</strong> {{BODY}}</li>
                <li><strong>VIN: </strong> {{VIN}}</li>
                <li><strong>Odometer:</strong> {{ODOMETER}} {{KM_MILES}}</li>
                <li><strong>Doors:</strong> {{DOOR}}</li>
                <li><strong>Engine:</strong> {{ENGINE}}</li>
                <li><strong>Cylinder: </strong> {{CYLINDER}}</li>
                <li><strong>Transmission:</strong> {{TRANSMISSION}}</li>
                <li><strong>Passengers:</strong> {{PASSENGERS}}</li>
                <li><strong>Exterior Colour:</strong> {{EXTERIOR_COLOR}}</li>
                <li><strong>Drivetrain:</strong> {{DRIVETRAIN}}</li>
            </ul>
             </div>
             <div class="report-header-left">
                <h4 class="t-left">Vehicle Description</h4>
                <p> {{DESCRIPTION_1000}} </p>
            </div>
            <div class="report-header-left" style="border:5px solid #000; margin:30px 0px;">
                <h4 class="t-center" style="font-size: 35px;margin: 15px 0px;">${{PRICE}}</h4>
            </div>
        </div>
        <div class="f-width-100" style="padding: 10px 0px;width:100%; display:inline-block;border-top: 1px solid #919191;">
            <p class="t-center" style="padding-top:0px;">{{WEBSITE}}, {{BNAME}}</p>
        </div>
    </div>
</body>

</html>