<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
   <style>
   
/* latin */
@font-face {
  font-family: 'Titillium Web';
  font-style: normal;
  font-weight: 900;
  font-display: swap;
  src: url(https://font.gstatic.com/s/titilliumweb/v9/NaPDcZTIAOhVxoMyOr9n_E7ffEDBGItzYw.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
   
@import url('https://font.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,700&display=swap');

body{
    font-family: 'Titillium Web';
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
        width: 90px;
    }
	.main-logo{width: 300px;}
    .border{
      border:1px solid #000;
      border-radius: 10px;
    }
    .full-width{
        padding: 15px;
        overflow: hidden;
    }
    .report p strong{
        font-size: 17px;
    }
    .report p{
        color: #000;
        margin:0px;
        padding-top: 8px;
        font-size: 13px;
    }
    .report p a{
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
        margin: 0px;
        padding-left: 15px;
        padding-top: 0px;
        line-height: 25px;
    }
    .vehicle-information ul li {
        width: 50%;
        float: left;
        color: #000;
        font-size: 16px;
        display: block;
        padding: 10px 0px;
        position: relative;
        padding-left: 0px;
        border-bottom: 1px solid #000;
   }
    .vehicle-information ul li strong {
        font-size: 20px;
        float: right;
        padding-right: 10px;
       }
    img.icon-logo {
        width: 25px;
        position: absolute;
        left: -7px;
        top: 8px;
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
        font-size: 35px;
        font-weight: bold;
        color: #000;
        margin: 0px;
    }
	.information-detail p {
    margin: 5px 0px;
}
    .information-detail h1 sub{
        font-size: 22px;
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
}
.qr-code img{margin-top:-25px;}

    </style>
</head>

<body style="font-family: 'Titillium Web';">
    <table style="width:1100px;border-radius:5px;margin:auto;" cellpadding="0px" cellspacing="0px">
        <thead>
            <tr style="height:100px;padding:10px;text-align:center;">
                <td style="padding-bottom:20px;width:100%;text-align:center;" colspan="2">
               <img src="{{LOGO}}" class="main-logo"/>
				 </td>
            </tr>
            <tr style="height:100px;padding:10px;text-align:center;">
                <td style="padding-bottom:20px;width:100%;text-align:center;" colspan="2">
                    <img src="{{IMAGE}}" style="width:1100px; margin: auto;" />
				 </td>
            </tr>
        </thead>
        <tbody>
            <tr style="margin-top:10px;">
                <td style="text-align:center;" colspan="2">
                    <h1 style="color: #000000;text-align: center;margin: 0;font-size: 40px;font-weight:600;margin-bottom:0px;font-family: 'Titillium Web', sans-serif;">
                        {{YEAR}} {{MAKE}} {{MODEL}}  </h1>   
                </td>
            </tr>
            <tr style="margin-top:10px;">
                <td style="text-align:center;" colspan="2">
					<p style="font-size:25px;margin: 5px 0px; font-weight:400;">Nero Daytona Metallic with Nero Leather</p>
                </td>
				
            </tr>
             <tr>
                <td style="padding:0px 15px 0px; text-align:center;" colspan="2">
                <p style="margin:0px;text-align:center;">
                   <strong style="font-size:50px;padding-right: 15px;font-weight:400;padding-top:0px;text-align:center;line-height: 60px;">${{PRICE}}
                   <span style="text-align:center;font-size:25px;display:block;font-weight:400;line-height:45px;">+ Taxes and Licensing</span></strong>
				   </p> 
				</td>
            </tr>
            <tr>
                <td style="padding:0px 15px 15px; margin-top:0px;" colspan="2">
                    <table width="100%" style="text-align:left;">
                        <tbody>
                            <td valign="top" style="width:100%;">
                                <div class="vehicle-information">
                                <ul>
                                <li>Body Type <strong>{{BODY}}</strong></li>
                                <li>Engine Capacity <strong>{{ENGINE}}</strong></li>
                                <li>Body Style <strong>{{BODY}}</strong></li>
                                <li>Fuel <strong>{{FUEL_TYPE}}</strong></li>
                                <li>Transmission <strong>{{TRANSMISSION}}</strong></li>
                                <li>VIN  <strong>{{VIN}}</strong></li>
                            </ul>
                            </div>
                            </td>   		
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:0px 15px 15px; margin-top:0px;" colspan="2">
                    <table width="100%" style="text-align:left;">
                        <tbody>   
                            <td style="width:50%;padding-bottom:15px;padding:0px;">
                               <div class="report-header-left f-left" style="width:100%;padding: 0px 15px;">
                                    <p style="margin:0px;padding:0px;font-size:14px;" class="qr-code"> {{DESCRIPTION}}</p>
                                </div>
                            </td>		
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:0px 15px 15px; margin-top:0px;" colspan="2">
                    <table width="100%" style="text-align:left;">
                        <tbody>   
                            <td style="width:50%;padding-bottom:15px;padding:0px;">
                               <div class="report-header-left f-left" style="width:100%;padding: 0px 15px;">
                                    <p style="margin:0px;padding:0px;font-size:14px;" class="qr-code">{{BADDRESS}}, {{BCITY}}, {{BSTATE}} {{BCODE}}</p>
                                    <p style="margin:0px;padding:0px;font-size:14px;" class="qr-code"><strong>Tel:</strong>: {{BPHONE}} <strong style="padding-left:10px;">Fax:</strong> {{BFAX}}<strong style="padding-left:10px;">Email:</strong> {{BEMAIL}}  <span style="padding-left: 10px;">{{WEBSITE}}</span></p>
                                </div>
                            </td>		
                        </tbody>
                    </table>
                </td>
            </tr>           
		</tbody>
    </table>
</body>
</html>