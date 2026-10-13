<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="Description" content="Enter your description here" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.5.0/cosmo/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <title>ZopDealer</title>
  <style>
    .fixed-color-set {
      color: red;
    }

    label {
      font-weight: 700;
    }

    .form-check label {
      font-weight: 400;
    }
  </style>
</head>

<body>
  <form class="needs-validation container" id="lead_form">

    <div class="form-row <?php if ($VOI != '1') {
                            echo 'd-none';
                          } ?>">
      <div class="form-group offset-md-2 col-md-6">
        <div class="col-md-12">
          <label for="inventory_id">Select Inventory</label>
          <select id="inventory_id" name="inventory_id" class="form-control">
            <option disabled selected>Choose...</option>
          </select>
        </div>
        <div class="row d-none m-3" id="single_inventory">
          <div class="col-md-4">
            <label class="col-md-12">
              VIN
            </label>
            <div class="col-md-12" id="single_vin">
              XXXXXXXXXXXXX
            </div>
            <label class="col-md-12">
              Year
            </label>
            <div class="col-md-12" id="single_year">
              XXXXXXXXXXXXX
            </div>
            <label class="col-md-12">
              Make
            </label>
            <div class="col-md-12" id="single_make">
              XXXXXXXXXXXXX
            </div>
          </div>
          <div class="col-md-4">
            <label class="col-md-12">
              Model
            </label>
            <div class="col-md-12" id="single_model">
              XXXXXXXXXXXXX
            </div>
            <label class="col-md-12">
              Trim
            </label>
            <div class="col-md-12" id="single_trim">
              XXXXXXXXXXXXX
            </div>
            <label class="col-md-12">
              Stock Number
            </label>
            <div class="col-md-12" id="single_stock_no">
              XXXXXXXXXXXXX
            </div>
          </div>
          <div class="col-md-4">
            <img id="single_inventory_image" class="img-responsive img-thumbnail" src="" alt="" srcset="">
          </div>
        </div>
      </div>
    </div>

    <div class="form-row">
      <?php echo $dom_elements; ?>
    </div>
    <div class="form-group" style="display:none;">
      <input type="hidden" name="source" value="<?php echo $source; ?>">
      <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
      <input type="hidden" id="single_stock_no_input" name="stock_no" value="">
      <input type="hidden" id="single_stock_zm_year" name="zm_year" value="">
      <input type="hidden" id="single_stock_zm_make" name="zm_make" value="">
      <input type="hidden" id="single_stock_zm_model" name="zm_model" value="">
      <input type="hidden" id="single_stock_zm_trim" name="zm_trim" value="">
      <input type="hidden" id="single_stock_image" name="zm_image" value="">
      <input type="hidden" id="single_stock_zm_vin" name="zm_vin" value="">
      <input type="hidden" name="source_url" value="">
    </div>

    <div class="form-group">
      <div class="col-md-12">
        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
      </div>
      <div class="col-md-12">
        <?php echo $disclosure; ?>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
        <label class="form-check-label" for="invalidCheck">
          Agree to terms and conditions
        </label>
        <div class="invalid-feedback">
          You must agree before submitting.
        </div>
      </div>
    </div>
    <button class="btn btn-primary" type="submit">Submit form</button>
  </form>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script src="/dist/assets/js/jquery.inputmask.min.js"></script>

  <script>
    $(document).ready(function() {

      var source_url = (window.location != window.parent.location) ?
        document.referrer :
        document.location.href;

      document.querySelector(`input[name='source_url'`).value = source_url;

      function validURL(str) {
        regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
        if (regexp.test(str)) {
          return true;
        } else {
          return false;
        }
      }

      $('input[type="tel"]').each(function() {
        $(this).inputmask({
          "mask": "(999) 999-9999",
          "autoUnmask": true
        });
      });

      function getSearchParameters() {
        var prmstr = window.location.search.substr(1);
        return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
      }

      function transformToAssocArray(prmstr) {
        var params = {};
        var prmarr = prmstr.split("&");
        for (var i = 0; i < prmarr.length; i++) {
          var tmparr = prmarr[i].split("=");
          params[tmparr[0]] = tmparr[1];
        }
        return params;
      }

      window.params = getSearchParameters();

      var placeholder = '';

      //Fetch placeholder
      $.get(window.location.origin + '/api/website/image/placeholder', function(data) {
        window.placeholder = '<?php echo getenv('image.cdn') ?>' + data.image_url;
      });


      //Fetch instock inventory
      $.get(window.location.origin + '/api/website/inventory?zm_status[]=Instock&paginate=false&column=year&order=DESC', function(data) {
        for (var key in data.inventory) {
          if (data.inventory.hasOwnProperty(key)) {
            var element = data.inventory[key];
            var option = '<option value="' + element.inventory_id + '"> ' + element.year + ' ' + element.make + ' ' + element.model + ' #' + element.stock_no + '</option>';
            $('#inventory_id').append(option);

          }
        }
      }).done(function() {
        if (window.params.hasOwnProperty('inventory_id')) {
          $('#inventory_id').val(window.params.inventory_id);
          single_inventory(window.params.inventory_id);
        }
      });

      //Method for saving lead response
      $("form#lead_form").on("submit", function(event) {
        event.preventDefault();
        $('input[type="tel"]').each(function() {
          $(this).inputmask('remove');
        });
        $("input").removeClass("is-invalid");
        var form = $(this);
        var alert = Swal.mixin({
          showConfirmButton: false,
          timerProgressBar: true,
          confirmButtonText: "",
          title: "Sending your message...",
          onBeforeOpen: () => {
            alert.showLoading();
          },
        });
        alert.fire();
        $.ajax({
          url: window.location.origin + '/api/leads/responses/create',
          type: "POST",
          data: new FormData(this), // get all form variables
          contentType: false,
          processData: false,
          success: function(result) {
            alert.update({
              title: 'Thank You! \n Your message has been sent.',
              icon: "success",
            });

            setTimeout(() => {
              alert.close()
            }, 1000);

            $('form#lead_form').each(function() {
              this.reset();
            });
          },
          error: function(error) {
            var errors = error.responseJSON.errors;
            if (typeof(errors) == 'object') {
              for (var key in errors) {
                if (errors.hasOwnProperty(key)) {
                  //var va = errors[key];
                  $('input[name="' + key + '"]').addClass('is-invalid').next().text(errors[key]);
                }
              }
              alert.update({
                title: 'There were errors in submitting the form.',
                icon: "error",
              });

              setTimeout(() => {
                alert.close()
              }, 1000);

            } else {
              alert.update({
                title: errors,
                icon: "error",
              });

              setTimeout(() => {
                alert.close()
              }, 1000);

            }
          },
          complete: function() {
            $('input[type="tel"]').each(function() {
              $(this).inputmask({
                "mask": "(999) 999-9999",
                "autoUnmask": true
              });
            });

            if (window.params.hasOwnProperty('inventory_id')) {
              $('#inventory_id').val(window.params.inventory_id);
              single_inventory(window.params.inventory_id);
            }

            var inventory_value = $('#inventory_id').val();

            if (inventory_value == null || inventory_value == '') {
              $('#single_inventory').addClass('d-none');
            }

          }
        });
        grecaptcha.reset();
      });

      function single_inventory(id) {
        $.get(window.location.origin + '/api/website/inventory/show/' + id, function(data) {
          $('#single_inventory').removeClass('d-none');
          $('#single_vin').text(data.vin);
          $('#single_year').text(data.year);
          $('#single_make').text(data.make);
          $('#single_model').text(data.model);
          $('#single_trim').text(data.trim);
          $('#single_stock_no').text(data.stock_no);
          $('#single_stock_no_input').val(data.stock_no);

          $('#single_stock_zm_year').val(data.year);
          $('#single_stock_zm_make').val(data.make);
          $('#single_stock_zm_model').val(data.model);
          $('#single_stock_zm_trim').val(data.trim);
          $('#single_stock_zm_vin').val(data.vin);
        });

        $.get(window.location.origin + '/api/website/inventory/images/' + id, function(data) {
          //single_inventory_image
          if (data['gallery'].hasOwnProperty('0')) {
            var image_url = '';
            if (validURL(data['gallery'][0]['image_url'])) {
              image_url = data['gallery'][0]['image_url'];
            } else {
              image_url = '<?php echo getenv('image.cdn') ?>' + data['gallery'][0]['image_url'];
            }

            $('#single_inventory_image').attr("src", image_url);
            $('#single_stock_image').val(image_url);
          } else {
            $('#single_inventory_image').attr("src", window.placeholder);
            $('#single_stock_image').val(window.placeholder);
          }
        });

      }

      //Method for changing select 
      $('#inventory_id').change(function() {
        var data = $(this).val();
        single_inventory(data);
      });



    });
  </script>
</body>

</html>