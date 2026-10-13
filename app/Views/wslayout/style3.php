<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Window Sticker</title>
<style>
  /* cyrillic-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYoYNNZQyQ.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYoadNZQyQ.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYoYdNZQyQ.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYobtNZQyQ.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYoYtNZQyQ.woff2) format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYoY9NZQyQ.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: italic;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVg2ZhZI2eCN5jzbjEETS9weq8-19eDtCYobdNZ.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCkYb8td.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCAYb8td.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCgYb8td.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCcYb8td.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCsYb8td.woff2) format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCoYb8td.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 300;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-33mZGCQYbw.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-19-7DRs5.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-19a7DRs5.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-1967DRs5.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-19G7DRs5.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-1927DRs5.woff2) format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-19y7DRs5.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVl2ZhZI2eCN5jzbjEETS9weq8-19K7DQ.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* cyrillic-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCkYb8td.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}
/* cyrillic */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCAYb8td.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCgYb8td.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCcYb8td.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCsYb8td.woff2) format('woff2');
  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCoYb8td.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Roboto Condensed';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(http://fonts.gstatic.com/s/robotocondensed/v19/ieVi2ZhZI2eCN5jzbjEETS9weq8-32meGCQYbw.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

</style>
</head>

<body>
<div style="width:100%; max-width:1000px; margin:auto;">
  <div style="margin-top:10px; width:50%;float:left;">
    <div style="text-align:left; ">
      <div>
        <div style="text-align:left; border-bottom:1px solid #44763f; padding-bottom:10px;">
          <h1 style="color: #44763f;margin: 0;font-size: 20px;font-weight:bold; font-family: 'Roboto Condensed', sans-serif;"> {{YEAR}} {{MAKE}} {{MODEL}} </h1>
          <h5 style="color: #44763f;margin: 0; padding-top:7px; font-size:18px;">{{TRIM}}</h5>
        </div>
      </div>
      <div style="display:block; overflow: hidden;">
        <div style="text-align:left; padding-top:10px;width:33%;float: left;">
          <h1 style="color: #44763f;margin: 0;font-size: 18px;font-weight:bold; font-family: 'Roboto Condensed', sans-serif;"> ${{PRICE}} </h1>
          <p style="color: #44763f;margin: 0;font-size: 10px; font-family: 'Roboto Condensed', sans-serif;"> PLUS TAXES AND LICENSING </p>
        </div>
        <div style="text-align:center; padding-top:10px; width:66%; float: right; text-align:right;">
          <h1 style="color: #000;margin: 0;font-size: 22px;font-weight:bold; font-family: 'Roboto Condensed', sans-serif;"> {{ODOMETER}} {{KM_MILES}}</h1>
        </div>
      </div>
      <div style="background-color:#cdcfd1; overflow:hidden;font-family: 'Roboto Condensed', sans-serif;">
        <div style="padding-top:0px;padding-bottom:5px; text-align: center;"> <img src="{{IMAGE}}" alt="" title="" style="width:100%;"/> </div>
        <div style="padding-bottom:7px; padding-left:8px;overflow: hidden;">
          <div style="padding-top:3px;">
            <div style="margin:0px;">
              <div style="width:50%;float:left;">Stock </div>
              <div style="font-size:16px; font-weight:700;width:50%;float:left;">{{STOCK}}</div>
            </div>
          </div>
          <div style="padding-top:3px;">
            <div style="margin:0px;">
              <div style="width:50%;float:left;">VIN </div>
              <div style="font-size:16px; font-weight:700;width:50%;float:left;">{{VIN}}</div>
            </div>
          </div>
        </div>
      </div>
      <div style="overflow: hidden; padding-top:5px; padding-bottom:0px;font-family: 'Roboto Condensed', sans-serif;">
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Exterior Colour </span> <br />
            <strong style="display:block;">{{EXTERIOR_COLOR}} </strong></p>
        </div>
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Interior Colour</span> <br />
            <strong style="display:block;"> {{INTERIOR_COLOR}} </strong></p>
        </div>
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Category </span> <br />
            <strong style="display:block;">{{BODY}}</strong></p>
        </div>
      </div>
      <div style="overflow: hidden; padding-top:0px; background-color:#e8efd7;">
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Condition </span> <br />
            <strong style="display:block;">{{VEHICLE_TYPE}}</strong></p>
        </div>
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Fuel Type </span> <br />
            <strong style="display:block;">{{FUEL_TYPE}}</strong></p>
        </div>
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Trans</span> <br />
            <strong style="display:block;"> {{TRANSMISSION}}</strong></p>
        </div>
      </div>
      <div style="overflow: hidden; padding-top:0px;">
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Door </span> <br />
            <strong style="display:block;">{{DOOR}}</strong></p>
        </div>
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Engine</span> <br />
            <strong style="display:block;">{{ENGINE}}</strong></p>
        </div>
        <div style="text-align:left; padding-top:5px;width:33%;float: left;">
          <p style="float:left;"><span>Drive Type</span> <br />
            <strong style="display:block;"> {{DRIVETRAIN}}</strong></p>
        </div>
      </div>
    </div>
  </div>
  <div style="text-align:left;  width:50%;float:left;">
    <div style="overflow: hidden; padding-top:0px;">
      <div style="text-align:left; padding-top:5px;width:40%;float:left;">
        <p><img src="{{LOGO}}" width='175px' style="margin-left:20px;padding:0px 0px;text-align:right;" /></p>
      </div>
      <div style="text-align:left; padding-top:5px;width:60%;float:left; background-color:#e8efd7;">
        <h1 style=" margin:0px; color: #000;text-align: right;margin: 0;font-size: 20px;font-weight:bold; font-family: 'Roboto Condensed', sans-serif;padding-right:10px;"> {{BNAME}} </h1>
        <p style="margin:0px; text-align:right;padding-right:10px;"> {{BADDRESS}}, {{BCITY}}, {{BSTATE}} {{BCODE}}</p>
        <p style="margin:0px; color:#44763f; font-size:18px; text-align:right;padding-right:10px;"> Call Us <strong style="color:#44763f;font-size:20px;font-weight:700;"> {{BPHONE}} </strong></p>
      </div>
    </div>
    <div style="overflow: hidden; padding-top:0px;">
      <div style="text-align:left; padding-top:5px;width:100%;">
        <div style="padding-left:20px;"> <strong style="font-size:16px;">DESCRIPTION</strong>
          <ul class="font-s-block list-line-box" style="padding-left:5px; font-family: 'Roboto Condensed', sans-serif;">
            <li> {{DESCRIPTION}} </li>
          </ul>
        </div>
      </div>
    </div>
	      <div style="text-align:left; padding-top:5px;width:40%;float:left;">
			<p>{{QR}}</p>
		</div>
  </div>
</div>
</body>
</html>
