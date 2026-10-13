<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <title> </title>
  <!--[if !mso]><!-- -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
    #outlook a {
      padding: 0;
    }

    body {
      color: #777;
      background: #f8f8f8;
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    table,
    td {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    p {
      display: block;
      margin: 13px 0;
    }
  </style>
  <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
  <!--[if lte mso 11]>
        <style type="text/css">
          .mj-outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
  <!--[if !mso]><!-->
  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
  <style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
  </style>
  <!--<![endif]-->
  <style type="text/css">
    @media only screen and (min-width:480px) {
      .mj-column-per-100 {
        width: 100% !important;
        max-width: 100%;
      }
    }
  </style>
  <style type="text/css">
    @media only screen and (max-width:480px) {
      table.mj-full-width-mobile {
        width: 100% !important;
      }
      td.mj-full-width-mobile {
        width: auto !important;
      }
    }
  </style>
</head>

<body>
  <div style="">
    <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    <div style="margin:0px auto;max-width:800px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;text-align:left;color:#777;">Hello <?php echo $user_name; ?>, <br /> <span style="text-indent: 2em;display:block;"><?php echo $activity_type; ?> Reminder!.</span></div>
                    </td>
                  </tr>
                  
                  <!-- Simple Detail : START -->
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;"><?php echo $activity_type; ?> Details</th>
                        </tr>
                        <?php if(!empty($title)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Title</td>
                          <td style="padding: 0 15px;"><?php echo $title; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($start_date)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Start Date</td>
                          <td style="padding: 0 15px;"><?php echo $start_date; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($end_date) && $activity_type == "Appointment" ){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">End Date</td>
                          <td style="padding: 0 15px;"><?php echo $end_date; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($module)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Related To</td>
                          <td style="padding: 0 15px;"><?php echo $module; ?> <?php echo $module_identifier; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($attendee_names)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Attendee(s)</td>
                          <td style="padding: 0 15px;"><?php echo $attendee_names; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($description)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Description</td>
                          <td style="padding: 0 15px;"><?php echo $description; ?></td>
                        </tr>
                        <?php } ?>

                      </table>
                    </td>
                  </tr>
                  <!-- Simple Detail : END -->

                </table>
              </div>
              <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
  </div>
</body>

</html>