<!doctype html>
<html>
<head>
<meta charset="utf-8">

<style>
    @import url('http://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');
    
    body{
     font-family: 'Roboto', sans-serif;
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
        width: 275px;
		margin-top:1em;
    }
    .border{
      border:1px solid #000;
      /*border-radius: 10px;*/
    }
    .full-width{
        overflow: hidden;
    }
    .report p strong{
        font-size: 17px;
    }
    .report p{
        color: #000;
        margin:0px;
        padding-top: 8px;
        font-size: 17px;
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
        border-bottom: 10px solid #376092;
        overflow: hidden;
        padding-top: 5px;
        margin-bottom: 5px;
    }
    .border-top{
         border-top: 10px solid #376092;
    }
    .side-pp{
        padding: 0px 15px;
    }
    .vehicle-information .information-d{
        padding-top: 5px;
    }
    .vehicle-information ul{
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
}
</style>
</head>
<body style="width:1350px;margin:50px auto;">
    <div class="report" style="border: 10px solid #376092; padding: 0px; margin:50px 0px;"> 
        <div class="head-block" style="margin-bottom:0px; padding:0px 10px;">
            <div class="report-header-left f-left" style="width:33%;">
                <img src="{{LOGO}}" class="logo-m"/>
			</div>
            <div class="report-header-left f-left" style="width:33%;">
                <h2 style="text-align: center;font-size:35px;padding-top: 6px;">Pre-Owned Vehicle</h2>
			</div>
            <div class="report-header-right f-right t-left" style="width:33%;">
<!--                <p style="color:#000;"><strong>{{BNAME}}</strong></p>-->
                <p style="color:#000;font-weight: 700;font-size:21px;">{{BADDRESS}}, <br>  {{BCITY}}, {{BSTATE}} <br> {{BCODE}}</p>
                <p style="color:#f00;"><strong style="font-size:30px;">{{BPHONE}}</strong></p>
            </div>
        </div>
        <div class="border-top full-width">
                <div class="" style="width:39%;padding-top:5px;border-right:10px solid #376092;float:left;">
                    <div class="bar-code">
                        <div class="side-pp">
                            <div class="report-header-left f-left" style="width:63%;">
                                <h4 style="font-size:18px; padding-top:20px;">Visit us online at:</h4>
                                <h3 style="font-size:22px;padding-top:5px; margin:0px;">{{WEBSITE}}</h3>
                                <h4 style="font-size:18px;padding-top: 10px;">or email us at:</h4>
                                <h3 style="font-size:22px;margin:0px;padding-top:5px;">{{BEMAIL}}</h3>
                            </div>
                            <div class="report-header-left f-left t-center" style="width:35%;border:5px solid #376092;padding:0px;margin:0px;">    
                                {{QR}}
                            </div>
                        </div>
                      <div class="border-bottom" style="width:100%; margin-bottom:0px;"></div>
                  </div>
                  <div class="bar-code">
                        <div class="side-pp" style="padding-right:0px;">
                            <div class="report-header-left f-left" style="width:49%;">
                                <h4 style="font-size:18px; padding-top:10px;">Year: <span>{{YEAR}}</span></h4>
                                <h4 style="font-size:18px; padding-top:5px;">Make: <span>{{MAKE}}</span></h4>
                                <h4 style="font-size:18px; padding-top:5px;">Make: <span>{{MODEL}}</span></h4>
                                <h4 style="font-size:18px; padding-top:5px;">Model: <span>{{MODEL}}</span></h4>
                                <h4 style="font-size:18px; padding-top:5px;">Trim: <span>{{YEAR}}</span></h4> 
                                <h4 style="font-size:18px; padding-top:5px;">Color: <span>{{EXTERIOR_COLOR}}</span></h4>
                            </div>
                            <div class="report-header-left f-left t-center" style="width:49%;border-left:5px solid #376092;padding:0px;margin:0px;">    
                              <h2 style="font-size:37px; margin: 10px 0px;">{{ODOMETER}} <span style="font-size: 16px;">{{KM_MILES}}</span></h2>
                               <div class="border-bottom" style="width:100%;border-bottom:5px solid #376092;"></div>
                                <h3 style="font-size:34px; margin: 20px 0px  30px;color: red;">${{PRICE}} <span style="font-size: 16px;display:block;">+HST +Lic. Fee</span></h3>
                               
                              
                            </div>
                        </div>
                      <div class="border-bottom" style="width:100%;padding-top:0px; margin-bottom:0px;"></div>
                  </div>
                <div class="bar-code">
                        <div class="side-pp">
                            <div class="report-header-left f-left" style="width:45%;">
                                <h2 style="font-size:30px; margin: 10px 0px;">Transmission </h2>
                            </div>
                            <div class="report-header-left f-left t-center" style="width:50%;border-left:5px solid #376092;padding:0px;margin:0px;">    
                              <h2 style="font-size:30px; margin: 10px 0px;">{{TRANSMISSION}}</h2>
                              
                            </div>
                        </div>
                      <div class="border-bottom" style="width:100%;padding-top:0px;"></div>
                  </div>
                  <div class="bar-code">
                        <div class="side-pp">
                            <div class="report-header-left f-left" style="width:100%;">
                                <h3 style="text-align: center;margin: 0px;padding:6px 0px;">A Note from the Dealer</h3>
                                <ul style="padding-top: 0px;">
                                    <li>FREE - 3 Mo. / 3000 Kms Optima Coverage</li>
                                    <li>Full SAFETY CERTIFICATION Included</li>
                                    <li>Rear Back-Up Camera</li>
                                    <li>Push Button Start</li>
                                    <li>Premium Alloy Wheels</li>
                                    <li>ONE OWNER</li>
                                    <li>New Oil Change Complete</li>
                                    <li>CarFax Available</li>
                                </ul>
                            </div>
                        </div>
                      <div class="border-bottom" style="width:100%;padding-top:0px;"></div>
                  </div>
                  <div class="bar-code">
                        <div class="side-pp">
                            <div class="report-header-left f-left" style="width:100%;">
                                <p style="font-size: 11px;padding: 0px;line-height: 18px;">All bi-weekly payments include 13% HST, based on 7.99% OAPC for 48 months with 10% down payment.Terms may vary and financing is subject to approval. Interest rate is subject to change. Cash prices shown exclude HST & License Fees. This label has been installed to assist the customer with vehicle selection. This dealership recommends that you verify the information listed above.</p>
                            </div>
                        </div>
                  </div>
              </div>
                <div class="" style="width:60%; float: left;">
                    <div class="bar-code">
                    <div class="side-pp">
                        <div class="report-header-right" style="width:100%;margin:20px 0px; overflow: hidden;">
                            <h3 style="text-align: center;margin: 0px;padding:6px 0px;">Standard Options</h3>
                            <p style="margin:0px;padding:0px;font-size:13px; min-height:450px;" class="qr-code">   {{INSTALLED_OPTIONS_STYLE1}}</p>
                        </div>
                    </div>
                         <div class="border-bottom" style="width:100%;padding-top:0px;border-bottom:5px solid #376092;margin-bottom:0px;"></div>
                    </div>
                    <div class="bar-code">
                        <div class="side-pp">
                            <div class="report-header-left f-left" style="width:49%; padding:0px;margin:0px;">
                                <h2 style="font-size:30px; margin: 40px 0px;">SAFETY CERTIFIED</h2>
                            </div>
                            <div class="report-header-left f-left t-center" style="width:49%;border-left:5px solid #376092;padding:0px;margin:0px;">    
                              <h2 style="font-size:30px; margin: 40px 0px;"> FINANCING AVAILABLE </h2>
                                                          </div>
                        </div>
                      <div class="border-bottom" style="width:100%;padding-top:0px;margin-bottom:0px;border-bottom:5px solid #376092;"></div>
                  </div>
                    <div class="bar-code">
                        <div class="side-pp">
                            <div class="report-header-left f-left" style="width:49%;">
                                <h2 style="font-size:30px; margin: 45px 0px;">DETAILING COMPLETE</h2>
                            </div>
                            <div class="report-header-left f-left t-center" style="width:49%;border-left:5px solid #376092;padding:0px;margin:0px;">    
                              <h2 style="font-size:30px; margin: 45px 0px;">WARRANTY AVAILABLE</h2>
                              
                            </div>
                        </div>
                  </div>
            </div> 
        </div>
    </div>
</body>
</html>
