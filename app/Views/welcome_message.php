<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="DCZero">
  <title>Zop Dealer</title>
  <link rel="apple-touch-icon" href="dist/app-assets/images/logo/zop-logo.png">
  <link rel="shortcut icon" type="image/x-icon" href="dist/app-assets/images/logo/zop-logo.png">
  <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

  <!-- BEGIN: Vendor CSS-->
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/vendors.min.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/tables/datatable/datatables.min.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/charts/apexcharts.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/file-uploaders/dropzone.min.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/extensions/nouislider.min.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/editors/quill/quill.snow.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/editors/quill/quill.bubble.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/vendors/css/forms/select/select2.min.css">
  <!-- END: Vendor CSS-->

  <!-- BEGIN: Theme CSS-->
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/bootstrap-extended.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/colors.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/components.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/themes/dark-layout.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/themes/semi-dark-layout.css">
  <link href="https://cdn.quilljs.com/1.2.6/quill.snow.css" rel="stylesheet">
  <!-- END: Theme CSS-->

  <!-- BEGIN: Page CSS-->
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/plugins/file-uploaders/dropzone.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/plugins/extensions/noui-slider.css">
  <link rel="stylesheet" type="text/css" href="dist/app-assets/css/core/colors/palette-noui.css">
  <!-- END: Page CSS-->

  <!-- BEGIN: Custom CSS-->
  <link rel="stylesheet" type="text/css" href="dist/assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="dist/app.css?version=<?php echo ZD_VERSION; ?>">
  <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body id="body" class="vertical-layout vertical-menu-modern 2-columns navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
  <div id="app">
    <div class="page-loader" v-if="!application">
      <div class="lds-grid">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div>
        </div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <div v-if="application">
      <!-- BEGIN: Header-->
      <div class="header-navbar-shadow"></div>
      <main-header :application=application :app_metadata=app_metadata :user_metadata=user_metadata></main-header>
      <!-- END: Header-->
      <side-bar :application=application :app_metadata=app_metadata :user_metadata=user_metadata></side-bar>

      <router-view :application=application :app_metadata=app_metadata :user_metadata=user_metadata :key="$route.fullPath"></router-view>

      <div class="sidenav-overlay"></div>
      <div class="drag-target"></div>

      <main-footer></main-footer>
    </div>
  </div>

  <!-- BEGIN: Vendor JS-->
  <script src="dist/app-assets/vendors/js/vendors.min.js"></script>
  <script src="dist/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
  <script src="dist/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
  <script src="dist/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
  <!-- BEGIN Vendor JS-->

  <!-- BEGIN: Page Vendor JS-->
  <script src="dist/app-assets/vendors/js/extensions/wNumb.js"></script>
  <script src="dist/app-assets/vendors/js/extensions/nouislider.min.js"></script>
  <script src="dist/app-assets/vendors/js/extensions/dropzone.min.js"></script>
  <script src="dist/app-assets/vendors/js/ui/prism.min.js"></script>
  <script src="dist/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
  <script src="dist/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
  <script src="dist/app-assets/vendors/js/charts/apexcharts.min.js"></script>
  <script src="dist/app-assets/vendors/js/pickers/daterange/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data-10-year-range.js"></script>
  <script src="dist/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
  <script src="dist/app-assets/vendors/js/editors/quill/katex.min.js"></script>
  <script src="dist/app-assets/vendors/js/editors/quill/highlight.min.js"></script>
  <script src="dist/app-assets/vendors/js/editors/quill/quill.min.js"></script>
  <script src="dist/app-assets/vendors/js/extensions/jquery.steps.min.js"></script>
  <script src="dist/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
  <script src="dist/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
  <script src="dist/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
  <!-- END: Page Vendor JS-->

  <!-- BEGIN: Theme JS-->
  <script src="dist/app-assets/js/scripts/configs/vertical-menu-light.js"></script>
  <script src="dist/app-assets/js/core/app-menu.js"></script>
  <script src="dist/app-assets/js/core/app.js"></script>
  <script src="dist/app-assets/js/scripts/components.js"></script>
  <script src="dist/app-assets/js/scripts/footer.js"></script>
  <!-- END: Theme JS-->



  <!-- BEGIN: Page JS-->
  <script src="/dist/js/pdf.js"></script>
  <script src="dist/psl.min.js"></script>
  <script src="dist/manifest.js"></script>
  <script src="dist/vendor.js?version=<?php echo ZD_VERSION; ?>"></script>
  <script src="dist/app.js?version=<?php echo ZD_VERSION; ?>"></script>

  <!-- END: Page JS-->

  <!-- BEGIN: Page JS-->
  <script src="dist/assets/js/scripts.js"></script>
  <script>
      pdfjsLib.GlobalWorkerOptions.workerSrc =
          "/dist/js/pdf.worker.min.js";
  </script>
  <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>