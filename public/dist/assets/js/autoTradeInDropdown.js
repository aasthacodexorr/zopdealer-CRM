$(document).ready(function() {
  /**
   * triggerDropdownOnChange Accepts three arguments column : current selector, filters: initial filters to use and reset: columns to reset
   *
   * @return void
   */
  function triggerDropdownOnChange(column, filters, reset) {
    const trade_in_api_domain = "https://zoptradein.zopsoftware.com";

    if (reset.length !== 0) {
      for (const reset_column of reset) {
        document
          .querySelectorAll(`select[name='${reset_column}'] option`)
          .forEach((option) => option.remove());
        let reset_dom = document.createElement("option");
        reset_dom.innerText = `Select ${reset_column.charAt(0).toUpperCase() +
          String(reset_column).slice(1)}`;
        reset_dom.value = "";
        reset_dom.setAttribute("selected", true);
        reset_dom.setAttribute("disabled", true);
        document
          .querySelector(`select[name='${reset_column}']`)
          .appendChild(reset_dom);
        reset_dom = null;
      }
    }

    if (column == "year") {
      $.get(`${trade_in_api_domain}/api/website/inventory/filters`, function(
        data,
      ) {
        console.log(data);

        if (!data.hasOwnProperty("errors")) {
          if (data.hasOwnProperty("year")) {
            for (const { name } of data.year.sort(
              (a, b) => parseInt(b.name, 10) - parseInt(a.name),
            )) {
              let optional_dom = document.createElement("option");
              optional_dom = document.createElement("option");
              optional_dom.innerText = name;
              optional_dom.value = name;
              document
                .querySelector(`select[name='${column}']`)
                .appendChild(optional_dom);
            }
          }
        }
      });
    }

    if (column == "make") {
      let selected_year = document.querySelector(`select[name='year']`).value;
      $.get(
        `${trade_in_api_domain}/api/website/inventory/filters?zm_year[]=${selected_year}`,
        function(data) {
          if (!data.hasOwnProperty("errors")) {
            if (data.hasOwnProperty("make")) {
              for (const { name } of data.make) {
                let optional_dom = document.createElement("option");
                optional_dom = document.createElement("option");
                optional_dom.innerText = name;
                optional_dom.value = name;
                document
                  .querySelector(`select[name='${column}']`)
                  .appendChild(optional_dom);
              }
            }
          }
        },
      );
    }

    if (column == "model") {
      let selected_year = document.querySelector(`select[name='year']`).value;
      let selected_make = document.querySelector(`select[name='make']`).value;
      $.get(
        `${trade_in_api_domain}/api/website/inventory/filters?zm_year[]=${selected_year}&zm_make[]=${selected_make}`,
        function(data) {
          if (!data.hasOwnProperty("errors")) {
            if (data.hasOwnProperty("model")) {
              for (const { name } of data.model) {
                let optional_dom = document.createElement("option");
                optional_dom = document.createElement("option");
                optional_dom.innerText = name;
                optional_dom.value = name;
                document
                  .querySelector(`select[name='${column}']`)
                  .appendChild(optional_dom);
              }
            }
          }
        },
      );
    }

    if (column == "trim") {
      let selected_year = document.querySelector(`select[name='year']`).value;
      let selected_make = document.querySelector(`select[name='make']`).value;
      let selected_model = document.querySelector(`select[name='model']`).value;
      $.get(
        `${trade_in_api_domain}/api/website/inventory/filters?zm_year[]=${selected_year}&zm_make[]=${selected_make}&zm_model[]=${selected_model}`,
        function(data) {
          if (!data.hasOwnProperty("errors")) {
            if (data.hasOwnProperty("trim")) {
              for (const { name } of data.trim) {
                let optional_dom = document.createElement("option");
                optional_dom = document.createElement("option");
                optional_dom.innerText = name;
                optional_dom.value = name;
                document
                  .querySelector(`select[name='${column}']`)
                  .appendChild(optional_dom);
              }
            }
          }
        },
      );
    }
  }

  triggerDropdownOnChange("year", [], ["year", "make", "model", "trim"]);
  document
    .querySelector(`select[name='year']`)
    .addEventListener("change", () => {
      triggerDropdownOnChange("make", ["year"], ["make", "model", "trim"]);
    });

  document
    .querySelector(`select[name='make']`)
    .addEventListener("change", () => {
      triggerDropdownOnChange("model", ["year", "make"], ["model", "trim"]);
    });

  document
    .querySelector(`select[name='model']`)
    .addEventListener("change", () => {
      triggerDropdownOnChange("trim", ["year", "make", "model"], ["trim"]);
    });

  document
    .querySelector(`select[name='trim']`)
    .addEventListener("change", () => {});
});
