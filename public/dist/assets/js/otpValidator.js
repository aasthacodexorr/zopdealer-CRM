$(document).ready(function() {
  function formatPhoneNumber(phoneNumberString) {
    var cleaned = ("" + phoneNumberString).replace(/\D/g, "");
    var match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
      return "(" + match[1] + ") " + match[2] + "-" + match[3];
    }
    return null;
  }

  function OTPFunctionality() {
    const phone_no = $("#zop-phone-number")
      .val()
      .replace(/\D+/g, "");
    const request_button = $("#request_otp");
    const otp_input = $("input[name='otp']");
    if (phone_no.length === 10) {
      request_button.prop("disabled", false);
      otp_input.prop("disabled", false);
      $("#hash").val("");
      $("#submit-button").addClass("d-none");
      //Enable OTP Fields
    } else {
      //Disable OTP Fields
      request_button.prop("disabled", true);
      otp_input.prop("disabled", true);
      $("#hash").val("");
      $("#submit-button").addClass("d-none");
    }
  }

  $("#zop-phone-number").on("keyup", () => {
    OTPFunctionality();
  });

  OTPFunctionality();

  $("#submit-button").on("click", () => {
    $("#lead_form").trigger("submit");
  });

  $("#request_otp").on("click", () => {
    const phone_dom = $("#zop-phone-number");
    const phone = phone_dom.val().replace(/\D+/g, "");
    const masked_phone_no = formatPhoneNumber(phone);
    phone_dom.prop("disabled", true);
    $("#request_otp_message")
      .addClass("alert")
      .addClass("alert-primary");
    $("#request_otp_message").html(
      `Sending OTP to <b> ${masked_phone_no} </b> ...`,
    );

    $.ajax({
      url: window.location.origin + "/api/services/one-time-password/request",
      type: "POST",
      data: {
        phone: phone,
      },
      success: (result) => {
        $("#hash").val(result.hash);
        $("#request_otp_message")
          .addClass("mt-1")
          .addClass("alert")
          .removeClass("alert-info")
          .addClass("alert-success");
        $("#request_otp_message").html(
          `OTP (One Time Passcode) has been sent to <b> +1 ${masked_phone_no} </b>. Please enter the OTP in the field above.`,
        );
        $("#submit-button").removeClass("d-none");
      },
      error: (error) => {
        $("#request_otp_message")
          .addClass("mt-1")
          .addClass("alert")
          .removeClass("alert-info")
          .addClass("alert-success");
        $("#request_otp_message").html(
          `OTP (One Time Passcode) already sent to <b> +1 ${masked_phone_no} </b>. Please wait for 3 minutes to send another request.`,
        );
        $("#submit-button").removeClass("d-none");
      },
    });
  });
});
