//Currency Formattor Utility
var CurrencyFormatter = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
});

//Decimal Formatter Utility
var NumberFormatter = new Intl.NumberFormat("en-US", {
  style: "decimal",
});

//JSON Parser with error handling
function tryParseJSONObject(jsonString) {
  try {
    var o = JSON.parse(jsonString);
    if (o && typeof o === "object") {
      return o;
    }
  } catch (e) {}
  return [];
}

//Checks If URL is Absolute or Relative.
function parseAbsoluteOrRelativeUrl(base_url, url) {
  var isAbsoluteUrl = url.match(
    /^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})|^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,7})$/gim,
  );

  if (isAbsoluteUrl) {
    if (url.match(/^https?:\/\//i)) {
      return url;
    } else {
      return "https://" + url;
    }
  } else {
    return base_url + url;
  }
}

var PLACEHOLDER = "";

var global_properties = window.globalZDProperties();
// console.log(global_properties);

var IMAGE_CDN = "";

if (global_properties.hasOwnProperty("IMAGE_DOMAIN")) {
  IMAGE_CDN = global_properties.IMAGE_DOMAIN;
} else {
  throw "Property IMAGE_CDN is missing";
}

if (global_properties.hasOwnProperty("PLACEHOLDER")) {
  PLACEHOLDER = global_properties.PLACEHOLDER;
} else {
  throw "Property PLACEHOLDER is missing";
}

var COLLECTION = "";
if (global_properties.hasOwnProperty("COLLECTION")) {
  COLLECTION = global_properties.COLLECTION;
} else {
  throw "Property COLLECTION is missing";
}

var DEFAULT_SORTING = "/sort/status_rank:asc,created_at:desc";
if (global_properties.hasOwnProperty("DEFAULT_SORTING")) {
  if (global_properties.DEFAULT_SORTING.trim() !== "") {
    DEFAULT_SORTING = global_properties.DEFAULT_SORTING;
  }
}

var API_KEY =
  "MnM3c281VkdjZUN5N0s3VTc5VlR3TGRQemQxaTRmY3JxN01MQnFLSElCOD03UzFueyJmaWx0ZXJfYnkiOiJzdGF0dXM6W0luc3RvY2ssIFNvbGRdICYmIHZpc2liaWxpdHk6PjAgJiYgZGVsZXRlZF9hdDo9MCJ9";
if (global_properties.hasOwnProperty("API_KEY")) {
  if (global_properties.API_KEY.trim() !== "") {
    API_KEY = global_properties.API_KEY;
  }
}

var HITS_PER_PAGE = 10;
if (global_properties.hasOwnProperty("HITS_PER_PAGE")) {
  HITS_PER_PAGE = global_properties.HITS_PER_PAGE;
}

var ZD_HOST = "e7u8hmw4i3tkqvscp-1.a1.typesense.net";
if (global_properties.hasOwnProperty("TYPESENSE_HOST")) {
  ZD_HOST = global_properties.TYPESENSE_HOST;
}

//@TODO pass API Key for DOM
var typesenseInstantsearchAdapter = new TypesenseInstantSearchAdapter({
  server: {
    apiKey: API_KEY, // Be sure to use an API key that only allows search operations
    nodes: [
      {
        host: ZD_HOST,
        port: "443",
        protocol: "https",
      },
    ],
    cacheSearchResultsForSeconds: 0, // Cache search results from server. Defaults to 2 minutes. Set to 0 to disable caching.
  },

  additionalSearchParameters: {
    queryBy: "make,model,year_search,trim,vin,stock_no,exterior_color",
    infix: "fallback,fallback,off,fallback,always,always,off",
    numTypos: 0,
  },
});

const searchClient = typesenseInstantsearchAdapter.searchClient;

const search = instantsearch({
  searchClient,
  indexName: COLLECTION + DEFAULT_SORTING,
  routing: true,
  stalledSearchDelay: 1000,
});

search.addWidget(
  instantsearch.widgets.configure({
    hitsPerPage: HITS_PER_PAGE,
    clickAnalytics: true,
    enablePersonalization: true,
    getRankingInfo: true,
  }),
);

function renderStats(renderOptions, isFirstRender) {
  var nbHits = renderOptions["nbHits"];
  var hitsPerPage = renderOptions["hitsPerPage"];
  var page = renderOptions["page"];

  hitsPerPage = parseInt(hitsPerPage, 10) || 0;
  page = parseInt(page, 10) || 0;
  var start_index = hitsPerPage * page;

  setTimeout(function() {
    if (start_index > 0 && $("#__position_" + start_index).length > 0) {
      document
        .getElementById("__position_" + start_index)
        .scrollIntoView({ behavior: "smooth" });
    } else {
      document
        .getElementById("et-main-area")
        .scrollIntoView({ behavior: "smooth" });
    }
  }, 500);

  if ($(".count").length > 0) {
    document.querySelector(".count").innerHTML = nbHits;
  }
  if ($(".count_1").length > 0) {
    document.querySelector(".count_1").innerHTML = nbHits;
  }
}

var customStats = instantsearch.connectors.connectStats(renderStats);

search.addWidget(
  customStats({
    container: ".count",
  }),
);

var attributes_filter = [
  "vehicle_type",
  "year",
  "make",
  "model",
  "exterior_color",
  "body_type",
  "transmission",
  "fuel_type",
  "drivetrain",
  "engine",
  "litres",
  "passengers",
];

attributes_filter.forEach(function(attribute) {
  var filter_container = "#" + attribute + "-refinement-list";

  var sort_by =
    attribute === "year"
      ? ["isRefined", "name:desc", "count:desc"]
      : ["isRefined", "name:asc", "count:desc"];

  if ($(filter_container).length > 0) {
    search.addWidget(
      instantsearch.widgets.refinementList({
        container: filter_container,
        attribute: attribute,
        sortBy: sort_by,
        limit: 50,
      }),
    );
  }
});

var attributes__dropdown_filter = [
  "vehicle_type",
  "year",
  "make",
  "model",
  "exterior_color",
  "body_type",
  "transmission",
  "fuel_type",
  "drivetrain",
  "engine",
  "litres",
  "passengers",
];

attributes_filter.forEach(function(attribute) {
  var filter_container = "#" + attribute + "-dropdown-refinement-list";

  var sort_by =
    attribute === "year"
      ? ["isRefined", "name:desc", "count:desc"]
      : ["isRefined", "name:asc", "count:desc"];

  if ($(filter_container).length > 0) {
    search.addWidget(
      instantsearch.widgets.refinementList({
        searchable: true,
        collapsible: true,
        container: filter_container,
        attribute: attribute,
        sortBy: sort_by,
        limit: 50,
      }),
    );
  }
});

if ($("#selling-price-refinement-list").length > 0) {
  search.addWidget(
    instantsearch.widgets.rangeSlider({
      container: "#selling-price-refinement-list",
      attribute: "selling_price",
      templates: {
        header: "Price",
      },
      tooltips: {
        format: function(rawValue) {
          return "$" + Math.round(rawValue).toLocaleString();
        },
      },
    }),
  );
}

if ($("#selling-price-dropdown-refinement-list").length > 0) {
  search.addWidget(
    instantsearch.widgets.rangeSlider({
      container: "#selling-price-dropdown-refinement-list",
      attribute: "selling_price",
      templates: {
        header: "Price",
      },
      tooltips: {
        format: function(rawValue) {
          return "$" + Math.round(rawValue).toLocaleString();
        },
      },
    }),
  );
}

if ($("#odometer-dropdown-refinement-list").length > 0) {
  search.addWidget(
    instantsearch.widgets.rangeSlider({
      container: "#odometer-dropdown-refinement-list",
      attribute: "odometer",
      templates: {
        header: "Odometer",
      },
      tooltips: {
        format: function(rawValue) {
          return Math.round(rawValue);
        },
      },
    }),
  );
}

if ($("#odometer-refinement-list").length > 0) {
  search.addWidget(
    instantsearch.widgets.rangeSlider({
      container: "#odometer-refinement-list",
      attribute: "odometer",
      templates: {
        header: "Odometer",
      },
      tooltips: {
        format: function(rawValue) {
          return Math.round(rawValue);
        },
      },
    }),
  );
}

if ($("#custom-year-range-slider").length > 0) {
  search.addWidget(
    instantsearch.widgets.rangeSlider({
      container: "#custom-year-range-slider",
      attribute: "year",
      templates: {
        header: "Year",
      },
      tooltips: {
        format: function(rawValue) {
          return Math.round(rawValue);
        },
      },
    }),
  );
}

search.addWidget(
  instantsearch.widgets.searchBox({
    container: "#search",
    placeholder: "Search for Anything",
    autofocus: false,
    showReset: false,
    showLoadingIndicator: false,
    showSubmit: true,
  }),
);

//Replace value of Tags with a centralized URL
search.addWidget(
  instantsearch.widgets.currentRefinements({
    container: "#selectedOptionsBar",
    cssClasses: {
      link: "tag",
      label: "tag-value",
      clearAll: "btn",
    },
    templates: {
      item: function(attribute) {
        var label = attribute["name"];
        label =
          attribute["type"] === "numeric" ? attribute["computedLabel"] : label;
        console.log(attribute);
        return (
          '<span style="display:inline-block;padding-left:1em;padding-right:1em">' +
          label +
          '</span> <img src="https://zopsoftware-asset.b-cdn.net/media/tag-close.png">'
        );
      },
      clearAll: function(attribute) {
        return "Clear All";
      },
    },
    clearAll: "after",
    clearsQuery: true,
  }),
);

if (jQuery("#clear-all-filters").length > 0) {
  search.addWidget(
    instantsearch.widgets.clearRefinements({
      container: "#clear-all-filters",
      templates: {
        resetLabel: "Clear Filters",
      },
      autoHideContainer: true,
      clearsQuery: true,
    }),
  );
}

if (jQuery("#clearRefine").length > 0) {
  search.addWidget(
    instantsearch.widgets.clearRefinements({
      container: "#clearRefine",
      templates: {
        resetLabel: "Clear Filters",
      },
      autoHideContainer: true,
      clearsQuery: true,
    }),
  );
}

search.addWidget(
  instantsearch.widgets.sortBy({
    container: "#order_filter",
    items: [
      {
        value: COLLECTION + "/sort/status_rank:asc,created_at:desc",
        label: "Recently Added",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,selling_price:asc",
        label: "Price (Low to High)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,selling_price:desc",
        label: "Price (High to Low)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,odometer:asc",
        label: "Odometer (Low to High)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,odometer:desc",
        label: "Odometer (High to Low)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,make_rank:asc",
        label: "Make (A - Z)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,make_rank:desc",
        label: "Make (Z - A)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,model_rank:asc",
        label: "Model (A - Z)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,model_rank:desc",
        label: "Model (Z - A)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,year:asc",
        label: "Year (Low to High)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,year:desc",
        label: "Year (High to Low)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,image_count:asc",
        label: "Image Count (Low to High)",
      },
      {
        value: COLLECTION + "/sort/status_rank:asc,image_count:desc",
        label: "Image Count (High to Low)",
      },
    ],
  }),
);

search.addWidget(
  instantsearch.widgets.hits({
    container: "#inventory_table",
    cssClasses: {
      list: "row",
    },
    templates: {
      empty: "Currently, there are no vehicles that match your criteria.",
      item: function(hit) {
        hit["discount"] = parseFloat(hit.price) - parseFloat(hit.special_price);
        hit["discount"] = hit["discount"].toFixed(2);

        //hit['vehicle_tags'] = tryParseJSONObject(hit['vehicle_tags']);

        var files = [];
        if (hit.files.length !== 0) {
          hit.files.split(";").forEach(function(e) {
            var each_file = e.split("          ");
            files[each_file[0]] = each_file[1];
          }, files);
        }

        hit["files"] = files;
        return newElement(hit);
      },
    },
    escapeHTML: false,
    showPrevious: false,
  }),
);

var customPagination = instantsearch.connectors.connectPagination(
  renderPagination,
);

function renderPagination(renderOptions, isFirstRender) {
  var isFirstPage = renderOptions["isFirstPage"];
  var createURL = renderOptions["createURL"];
  var currentRefinement = renderOptions["currentRefinement"];
  var pages = renderOptions["pages"];
  var nbPages = renderOptions["nbPages"];
  var isLastPage = renderOptions["isLastPage"];
  var refine = renderOptions["refine"];

  jQuery("ul#pagination").html("");
  if (!isFirstPage) {
    jQuery("ul#pagination").html(
      '<li><a href="' +
        createURL(0) +
        '" data-value="0" class="first">&laquo; First Page</a></li><li><a href="' +
        createURL(currentRefinement - 1) +
        '" data-value="' +
        (currentRefinement - 1) +
        '" class="pages">Previous</a></li>',
    );
  }

  pages.forEach(function(page) {
    var className = currentRefinement === page ? "pages  current" : "pages";
    jQuery("ul#pagination").append(
      '<li><a href="' +
        createURL(page) +
        '" data-value="' +
        page +
        '" class="' +
        className +
        '" > ' +
        (page + 1) +
        " </a></li>",
    );
  });

  if (!isLastPage) {
    jQuery("ul#pagination").append(
      '<li><a href="' +
        createURL(currentRefinement + 1) +
        '" data-value="' +
        (currentRefinement + 1) +
        '" class="next">Next</a></li><li><a href="' +
        createURL(nbPages - 1) +
        '" data-value="' +
        (nbPages - 1) +
        '" class="last">Last Page &raquo;</a></li>',
    );
  }

  jQuery("ul#pagination")
    .find("a")
    .on("click", function(event) {
      event.preventDefault();
      refine(event.currentTarget.dataset.value);
    });
}

search.addWidgets([
  customPagination({
    container: document.querySelector("#pagination"),
  }),
]);

search.start();

function newElement(data) {
  var dom_element = jQuery("#single-inventory").html();
  dom_element = jQuery.parseHTML(dom_element);

  jQuery(dom_element).attr("id", "__position_" + data["__position"]);

  jQuery(dom_element)
    .find(".finance_page_url_value")
    .each(function() {
      jQuery(this).attr(
        "href",
        jQuery(this).attr("href") + "?inventory_id=" + data.inventory_id,
      );
    });

  jQuery(dom_element)
    .find(".book_a_test_drive_page_url_value")
    .each(function() {
      jQuery(this).attr(
        "href",
        jQuery(this).attr("href") + "?inventory_id=" + data.inventory_id,
      );
    });

  var title = jQuery("<p>")
    .html(data.title)
    .text();

  if (title.length > 60) {
    title = title.substring(0, 60);
    title = title + "...";
  }

  jQuery(dom_element)
    .find(".title_value")
    .append(title);
  jQuery(dom_element)
    .find(".image_count_value")
    .text(data.image_count);

  if (data.vindisplay == "1") {
    jQuery(dom_element)
      .find(".vin_value")
      .text(data.vin);
  } else {
    jQuery(dom_element)
      .find(".vin_block")
      .css("display", "none");
  }

  jQuery(dom_element)
    .find(".stock_no_value")
    .text(data.stock_no);
  jQuery(dom_element)
    .find(".engine_value")
    .text(data.engine);
  jQuery(dom_element)
    .find(".transmission_value")
    .text(data.transmission);
  jQuery(dom_element)
    .find(".drivetrain_value")
    .text(data.drivetrain);
  jQuery(dom_element)
    .find(".exterior_color_value")
    .text(data.exterior_color);
  jQuery(dom_element)
    .find(".interior_color_value")
    .text(data.interior_color);
  jQuery(dom_element)
    .find(".body_type_value")
    .text(data.body_type);
  jQuery(dom_element)
    .find(".litres_value")
    .text(data.litres);
  jQuery(dom_element)
    .find(".fuel_type_value")
    .text(data.fuel_type);

  jQuery(dom_element)
    .find(".odometer_value")
    .text(NumberFormatter.format(data.odometer) + " " + data.km_miles);

  if (data.fuel_hwy) {
    jQuery(dom_element)
      .find(".fuel_hwy_value")
      .text(data.fuel_hwy + " L/100km");
  }

  if (data.fuel_city) {
    jQuery(dom_element)
      .find(".fuel_city_value")
      .text(data.fuel_city + " L/100km");
  }

  if (parseInt(data.price) === 0 || parseInt(data.hide_price) === 1) {
    jQuery(dom_element)
      .find(".vehicle-price")
      .find(".add-price-block")
      .append(jQuery("#call_for_price_block").html());
  } else if (
    parseInt(data.special_price_on) === 1 &&
    parseInt(data.special_price) !== 0
  ) {
    jQuery(dom_element)
      .find(".vehicle-price")
      .find(".add-price-block")
      .append(jQuery("#special_price_block").html());

    jQuery(dom_element)
      .find(".vehicle-price")
      .find(".add-price-block")
      .append(jQuery("#selling_price_block").html());
  } else {
    jQuery(dom_element)
      .find(".vehicle-price")
      .find(".add-price-block")
      .append(jQuery("#only_price_block").html());

    jQuery(dom_element)
      .find(".vehicle-price")
      .find(".add-price-block")
      .append(jQuery("#selling_price_block").html());
  }

  if (
    parseInt(data.financing_on) !== 0 &&
    parseInt(data.emi_amount) > 0 &&
    parseInt(data.hide_price) !== 1
  ) {
    jQuery(dom_element)
      .find(".add-financing-block")
      .append(jQuery("#financing_block").html());
  }

  jQuery(dom_element)
    .find(".emi_amount_value")
    .text(
      CurrencyFormatter.format(data.emi_amount) + " " + data.payment_frequency,
    );
  jQuery(dom_element)
    .find(".interest_rate_value")
    .text(NumberFormatter.format(data.interest_rate));
  jQuery(dom_element)
    .find(".downpayment_value")
    .text(CurrencyFormatter.format(data.downpayment));
  jQuery(dom_element)
    .find(".term_value")
    .text(NumberFormatter.format(data.term));

  jQuery(dom_element)
    .find(".page_url_value")
    .attr("href", data.page_url);
  jQuery(dom_element)
    .find(".price_value")
    .text(CurrencyFormatter.format(data.price));
  jQuery(dom_element)
    .find(".discount_value")
    .text(CurrencyFormatter.format(data.discount));
  jQuery(dom_element)
    .find(".special_price_value")
    .text(CurrencyFormatter.format(data.special_price));
  jQuery(dom_element)
    .find(".selling_price_value")
    .text(CurrencyFormatter.format(data.selling_price));

  var image_urls =
    data.image_with_overlay_urls && data.image_with_overlay_urls.length !== 0
      ? data.image_with_overlay_urls
      : data.image_urls;

  var single_image_url =
    image_urls && image_urls.length !== 0
      ? image_urls.split(";")[0]
      : PLACEHOLDER;

  single_image_url = parseAbsoluteOrRelativeUrl(IMAGE_CDN, single_image_url);

  jQuery(dom_element)
    .find(".image_url_value")
    .attr("src", single_image_url);
  jQuery(dom_element)
    .find(".image_url_value")
    .attr("alt", data.year + " " + data.make + " " + data.model);

  if (data.vin !== "" && parseInt(data.selling_price) !== 0) {
    jQuery(dom_element)
      .find(".cargurus-block")
      .attr("data-cg-vin", data.vin);
    jQuery(dom_element)
      .find(".cargurus-block")
      .attr("data-cg-price", data.selling_price);
  }

  if (Object.keys(data.files).length > 0) {
    var links_section = jQuery("#files_block").html();
    links_section = jQuery.parseHTML(links_section);
    console.log(links_section);

    if (data.files.hasOwnProperty("Vehicle History Report")) {
      jQuery(links_section).append(
        jQuery("#vehicle_history_report_block").html(),
      );
      jQuery(links_section)
        .find(".vehicle_history_report_value")
        .attr(
          "href",
          parseAbsoluteOrRelativeUrl(
            IMAGE_CDN,
            data.files["Vehicle History Report"],
          ),
        );

      if (data.files.hasOwnProperty("Vehicle History Badge")) {
        jQuery(links_section)
          .find(".vehicle_history_badge_value")
          .attr(
            "src",
            parseAbsoluteOrRelativeUrl(
              IMAGE_CDN,
              data.files["Vehicle History Badge"],
            ),
          );
      }
    }

    if (
      data.files.hasOwnProperty("Vehicle Repair Report") &&
      jQuery("#vehicle_repair_report_block").length
    ) {
      jQuery(links_section).append(
        jQuery("#vehicle_repair_report_block").html(),
      );
      jQuery(links_section)
        .find(".vehicle_repair_report_value")
        .attr(
          "href",
          parseAbsoluteOrRelativeUrl(
            IMAGE_CDN,
            data.files["Vehicle Repair Report"],
          ),
        );
    }

    if (
      data.files.hasOwnProperty("Window Sticker") &&
      jQuery("#window_sticker_block").length
    ) {
      jQuery(links_section).append(jQuery("#window_sticker_block").html());
      jQuery(links_section)
        .find(".window_sticker_value")
        .attr(
          "href",
          parseAbsoluteOrRelativeUrl(IMAGE_CDN, data.files["Window Sticker"]),
        );
    }

    if (
      data.files.hasOwnProperty("Other Report") &&
      jQuery("#other_report_block").length
    ) {
      jQuery(links_section).append(jQuery("#other_report_block").html());
      jQuery(links_section)
        .find(".other_report_value")
        .attr(
          "href",
          parseAbsoluteOrRelativeUrl(IMAGE_CDN, data.files["Other Report"]),
        );
    }

    jQuery(dom_element)
      .find(".add-file-links-block")
      .append(links_section);
  }

  if (Array.isArray(data.vehicle_tags)) {
    if (data.vehicle_tags.length > 0) {
      var single_tag_class = data.vehicle_tags[0];
      single_tag_class = single_tag_class.replace(/\s+/g, "_").toLowerCase();
      jQuery(dom_element)
        .find(".vehicle_tag_single_value")
        .text(data.vehicle_tags[0]);
      jQuery(dom_element)
        .find(".vehicle_tag_single_value")
        .addClass(single_tag_class);
    } else {
      jQuery(dom_element)
        .find(".vehicle_tag_single_value")
        .css("display", "none");
    }
  } else {
    jQuery(dom_element)
      .find(".vehicle_tag_single_value")
      .css("display", "none");
  }

  if (data.status.toLowerCase() !== "Sold".toLowerCase()) {
    jQuery(dom_element)
      .find(".sold")
      .css("display", "none");
  }

  jQuery(dom_element)
    .find(".veh-tag")
    .removeClass("sold");

  if (
    data.status.toLowerCase() === "Sold".toLowerCase() &&
    $(".finance_page_url_value").length > 0
  ) {
    jQuery(dom_element)
      .find(".finance_page_url_value")
      .remove();
  }

  if (
    data.status.toLowerCase() === "Sold".toLowerCase() &&
    $(".book_a_test_drive_page_url_value").length > 0
  ) {
    jQuery(dom_element)
      .find(".book_a_test_drive_page_url_value")
      .remove();
  }

  if (
    data.status.toLowerCase() === "Sold".toLowerCase() &&
    $(".add-financing-block").length > 0
  ) {
    jQuery(dom_element)
      .find(".add-financing-block")
      .remove();
  }

  if (
    parseInt(data.financing_on) === 0 &&
    $(".finance_page_url_value").length > 0
  ) {
    jQuery(dom_element)
      .find(".finance_page_url_value")
      .remove();
  }

  return dom_element[1].outerHTML;
}

jQuery("#listViewBtn").on("click", function(e) {
  jQuery("#inventory_table").removeClass("list-view");
});

jQuery("#gridViewBtn").on("click", function(e) {
  jQuery("#inventory_table").addClass("list-view");
});
