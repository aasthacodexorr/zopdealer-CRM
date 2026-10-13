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
    <div style="margin:0px auto;max-width:600px;">
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
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;border-collapse: collapse;" width="100%">
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;text-align:left;color:#777;">Hello Admin, <br /> <span style="text-indent: 2em;display:block;">You have received new lead.</span></div>
                    </td>
                  </tr>
                  <!-- Vehicle Detail -->
                  <?php if(!empty($stock_no)) { ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Vehicle Details</th>
                        </tr>
                        <?php if(!empty($zm_year)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Year</td>
                          <td style="padding: 0 15px;"><?php echo $zm_year; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($zm_make)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Make</td>
                          <td style="padding: 0 15px;"><?php echo $zm_make; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($zm_model)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Model</td>
                          <td style="padding: 0 15px;"><?php echo $zm_model; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($zm_trim)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Trim</td>
                          <td style="padding: 0 15px;"><?php echo $zm_trim; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($zm_vin)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Vin</td>
                          <td style="padding: 0 15px;"><?php echo $zm_vin; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($stock_no)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Stock No</td>
                          <td style="padding: 0 15px;"><?php echo $stock_no; ?></td>
                        </tr>
                        <?php } ?>
                      </table>
                    </td>
                  </tr>
                  <?php } ?>
                  <!-- Vehicle Detail: END -->
                  <!-- Vehicle Image: START -->
                  <?php if(!empty($zm_image)){ ?>
                  <tr>
                    <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                        <tbody>
                          <tr>
                            <td style="width:240px;"> <img height="auto" src="<?php echo $zm_image; ?>" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="240" /> </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <?php } ?>
                  <!-- Vehcile Image: END -->
                  <!-- Simple Detail : START -->
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border-collapse: collapse;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Lead Details</th>
                        </tr>
                        <?php if(!empty($first_name)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">First Name</td>
                          <td style="padding: 0 15px;"><?php echo $first_name; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($last_name)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Last Name</td>
                          <td style="padding: 0 15px;"><?php echo $last_name; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($phone)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Phone</td>
                          <td style="padding: 0 15px;"><?php echo $phone; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($email)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Email</td>
                          <td style="padding: 0 15px;"><?php echo $email; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($stock_no)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Stock No</td>
                          <td style="padding: 0 15px;"><?php echo $stock_no; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($source)){ ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;">Source</td>
                          <td style="padding: 0 15px;"><?php echo $source; ?></td>
                        </tr>
                        <?php } ?>
                      </table>
                    </td>
                  </tr>
                  <!-- Simple Detail : END -->
                  <!-- Other Detail : START -->
                  <?php if(!empty($other_data)){ ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border-collapse: collapse;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Other Details</th>
                        </tr>
                        <?php 
                        $other_data = json_decode($other_data);
                        foreach($other_data as $key => $value) { ?>
                        <?php if(!empty($value)) { ?>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td style="padding: 0 15px 0 0;text-transform:uppercase;min-width: 30%;width: 30%;"><?php echo  str_replace('_',' ' , $key); ?></td>
                          <td style="padding: 0 15px;"><?php echo gettype($value) == "array" ? implode(",", $value) : $value;  ?></td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                      </table>
                    </td>
                  </tr>
                  <!-- Other Detail : END -->
                  <?php }  ?>

                  <!-- Other Detail : START -->
                  <?php if(!empty($drivers_licence_image)){ ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Drivers Licence Front</th>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><a href="<?php echo $drivers_licence_image; ?>" target="_blank" rel="noopener noreferrer">Open Link</a></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><?php echo $drivers_licence_image; ?></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Other Detail : END -->
                  <?php }  ?>

                  <?php if(!empty($drivers_licence_image2)){ ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Drivers Licence Back</th>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><a href="<?php echo $drivers_licence_image2; ?>" target="_blank" rel="noopener noreferrer">Open Link</a></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><?php echo $drivers_licence_image2; ?></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Other Detail : END -->
                  <?php }  ?>

                  <!-- Other Detail : START -->
                  <?php if(!empty($pay_stubs_image)){ ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Pay Stubs</th>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><a href="<?php echo $pay_stubs_image; ?>" target="_blank" rel="noopener noreferrer">Open Link</a></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><?php echo $pay_stubs_image; ?></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Other Detail : END -->
                  <?php }  ?>

                  <!-- Other Detail : START -->
                  <?php if(!empty($pay_stubs_image2)){ ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Pay Stubs 2</th>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><a href="<?php echo $pay_stubs_image2; ?>" target="_blank" rel="noopener noreferrer">Open Link</a></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><?php echo $pay_stubs_image2; ?></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Other Detail : END -->
                  <?php }  ?>

                  <!-- Other Detail : START -->
                  <?php if(!empty($pay_stubs_image3)){ ?>
                  <tr>
                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                      <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#777;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;table-layout:auto;width:100%;border:none;">
                        <tr style="border-bottom:1px solid #ecedee;text-align:left;padding:15px 							0;background:#eee;color:#777">
                          <th colspan="2" style="padding: 0 15px 0 0;">Pay Stubs 3</th>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><a href="<?php echo $pay_stubs_image3; ?>" target="_blank" rel="noopener noreferrer">Open Link</a></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                          <td><?php echo $pay_stubs_image3; ?></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Other Detail : END -->
                  <?php }  ?>
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