import moment from "moment";
import { copyText as writeClipboardText } from "./ClipboardPlugin.js";

export default {
  parseAbsoluteOrRelativeUrl(base_url, url) {
    let isAbsoluteUrl = url.match(
      /^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})|^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,7})$/gim
    );

    if (isAbsoluteUrl) {
      if (url.match(/^https?:\/\//i)) {
        return url;
      } else {
        return "http://" + url;
      }
    } else {
      return base_url + url;
    }
  },
  isValidJsonString(jsonString) {
    if (!(jsonString && typeof jsonString === "string")) {
      return false;
    }

    try {
      JSON.parse(jsonString);
      return true;
    } catch (error) {
      return false;
    }
  },
  moneyFormatter(price) {
    var formatter = new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD",

      // These options are needed to round to whole numbers if that's what you want.
      //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
      //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });

    try {
      return formatter.format(price);
    } catch (error) {
      return formatter.format(0);
    }
  },
  changeLocalDateToUTC(date, format, timezone = "Canada/Eastern") {
    try {
      date = moment.tz(date, format, timezone).format("YYYY-MM-DDTHH:mm:ss");
      let prefered_time_zone_date = moment.tz(date, timezone).format();
      return moment.utc(prefered_time_zone_date).format(format);
    } catch (error) {
      console.log("Some error in conversion of Date To UTC " + error);
    }
  },

  changeUTCtoLocalDate(date, format, timezone = "Canada/Eastern") {
    try {
      date = moment.tz(date, "UTC").format("YYYY-MM-DDTHH:mm:ss");
      return moment
        .tz(date, "UTC")
        .tz(timezone)
        .format(format);
    } catch (error) {
      console.log("Some error in conversion of Date To UTC " + error);
    }
  },

  //http://www.jacklmoore.com/notes/rounding-in-javascript/
  round(value, decimals) {
    return Number(Math.round(value + "e" + decimals) + "e-" + decimals);
  },

  async copyText(data) {
    try {
      await writeClipboardText(data);
      alert("Text Copied.");
    } catch (e) {
      console.error("Clipboard copy failed", e);
    }
  },

  formatPhoneNumber(phone_no) {
    var x = phone_no.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
    return !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
  },
  isNumeric(str) {
    if (typeof str != "string") return false; // we only process strings!
    return (
      !isNaN(str) && !isNaN(parseFloat(str)) // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
    ); // ...and ensure strings of whitespace fail
  },
  validateEmail(email) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
      return true;
    }
    return false;
  },
  playNotificationSound() {
    const audio = new Audio(
      "https://zopsoftware-asset.b-cdn.net/OGG_Chord.ogg"
    );
    audio.play();
  },
  async gTagClickEvent(self, action, category, label, value) {
    try {
      await self.$gtag.event(action, {
        event_category: category,
        event_label: label,
        value: value,
      });
    } catch (error) {}
  },
  setTaxRates(all_taxes, current_tax) {
    let new_rate = "";
    let selected_tax = all_taxes.filter((tax) => tax.name == current_tax);
    if (selected_tax.length > 0) {
      if (isNaN(selected_tax[0].value)) {
        new_rate = "";
      } else {
        new_rate = selected_tax[0].value;
      }
    } else {
      new_rate = "";
    }
    return new_rate;
  },
  coerceToInteger(current_value) {
    let new_value =
      !isNaN(current_value) && current_value != "" ? current_value : 0;
    return parseInt(new_value, 10);
  },
  coerceToNumberString(current_value) {
    let new_value =
      !isNaN(current_value) && current_value != "" ? current_value : 0;
    return new_value;
  },
  isAuthorized(role, authorized_roles = ["Super Owner", "Owner", "Manager"]) {
    return authorized_roles.indexOf(role) >= 0 ? true : false;
  },
  assigneeName(users, current_user_id, placeholder = "Not Assigned") {
    try {
      const current_assigned_user =
        current_user_id == null || current_user_id == "" || users.length == 0
          ? placeholder
          : users.filter((x) => current_user_id == x.user_id);
      if (typeof current_assigned_user !== "undefined") {
        if (current_assigned_user.length > 0) {
          return current_assigned_user[0]["name"];
        }
      }
      return placeholder;
    } catch (error) {
      return placeholder;
    }
  },
  downloadFileName(file_name) {
    const date = new Date();

    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();

    file_name = `${file_name} ${year} ${month} ${day}`;
    file_name = file_name.toLowerCase().replace(/[^A-Z0-9]/gi, "_");

    return file_name;
  },
  sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  },
  doCopy(current_object, string) {
    const copyFn =
      current_object && typeof current_object.$copyText === "function"
        ? (str) => current_object.$copyText(str)
        : (str) => writeClipboardText(str);

    Promise.resolve()
      .then(() => copyFn(string))
      .then(
        function(e) {
          if (current_object && typeof current_object.$swal === "function") {
            current_object.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Text has been copied.",
            });
          }
          console.log(e);
        },
        function(e) {
          if (current_object && typeof current_object.$swal === "function") {
            current_object.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Can not copy.",
            });
          }
          console.log(e);
        }
      );
  },
  truncate(text, length, clamp = "...") {
    if (!text && text !== 0) return "";
    clamp = clamp || "...";
    var node = typeof document !== "undefined" ? document.createElement("div") : null;
    let content = "";
    if (node) {
      node.innerHTML = text;
      content = node.textContent;
    } else {
      content = String(text).replace(/<[^>]*>/g, "");
    }
    return content.length > length ? content.slice(0, length) + clamp : content;
  },
};
