function debounce(func, timeout = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(this, args);
    }, timeout);
  };
}

function selectAPlace(
  place_id,
  element_list_id,
  street_address_element_id = "credit-application-address",
  city_element_id = "credit-application-city",
  province_element_id = "credit-application-province",
  postal_element_id = "credit-application-postal-code",
) {
  $.ajax({
    url: `${window.location.origin}/api/website/locations/${place_id}`,
    type: "GET",
    success: (result) => {
      if (result.hasOwnProperty("success") && result.hasOwnProperty("place")) {
        let {
          street,
          street_number,
          city,
          province,
          country,
          postal_code,
        } = result.place;

        const streetAddressDOM = document.getElementById(
          street_address_element_id,
        );
        if (streetAddressDOM) {
          streetAddressDOM.value = `${street_number} ${street}`;
        }

        const cityDOM = document.getElementById(city_element_id);
        if (cityDOM) {
          cityDOM.value = city;
        }

        const provinceDOM = document.getElementById(province_element_id);
        if (provinceDOM) {
          provinceDOM.value = province;
        }

        const postalCodeDOM = document.getElementById(postal_element_id);
        if (postalCodeDOM) {
          postalCodeDOM.value = postal_code;
        }
      }
    },
    error: function(error) {},
    complete: function() {},
  });
}

function clearAllOptionsForPlacesList(element_id = "locations_list") {
  const locationsListDOM = document.getElementById(element_id);
  while (locationsListDOM.firstChild)
    locationsListDOM.removeChild(locationsListDOM.firstChild);
}

function updateSearchedPlaces(places, element_id = "locations_list") {
  const locationsListDOM = document.getElementById(element_id);
  this.clearAllOptionsForPlacesList(element_id);

  for (const place of places) {
    const optionDOM = document.createElement("option");
    optionDOM.innerText = place.text;
    optionDOM.setAttribute("data-value", place.placeId);
    locationsListDOM.appendChild(optionDOM);
  }
}

function isPlaceSelected(
  street_address_element_id,
  city_element_id,
  province_element_id,
  postal_element_id,
  event,
) {
  const locations_element_id = event.target.getAttribute("list");
  if (locations_element_id) {
    const currentValue = event.target.value;
    const locationsListDOM = document.getElementById(locations_element_id);
    const places = locationsListDOM.options;
    for (let i = 0; i < places.length; i++) {
      if (currentValue === places[i].value) {
        this.selectAPlace(
          places[i].getAttribute("data-value"),
          locations_element_id,
          street_address_element_id,
          city_element_id,
          province_element_id,
          postal_element_id,
        );
        this.clearAllOptionsForPlacesList(locations_element_id);
        event.target.value = "";
        continue;
      }
    }
  }
}

function searchLocationRequest(
  search_param,
  search_list_element_id = "locations_list",
) {
  $.ajax({
    url: `${window.location.origin}/api/website/locations/suggestion`,
    type: "POST",
    data: {
      search: search_param,
    },
    success: (result) => {
      if (result.hasOwnProperty("success") && result.hasOwnProperty("places")) {
        if (Array.isArray(result.places)) {
          this.updateSearchedPlaces(result.places, search_list_element_id);
        }
      }
    },
    error: function(error) {},
    complete: function() {},
  });
}

const searchLocation = debounce((event) => {
  const search_location_text = event.target.value;
  const locations_element_id = event.target.getAttribute("list");

  if (search_location_text.length < 4) return;
  this.searchLocationRequest(search_location_text, locations_element_id);
}, 600);
