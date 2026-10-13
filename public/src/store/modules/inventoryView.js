import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Inventories",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/inventory`,
  current_entity: null,
  search_string: "",
  search: false,
  filter: false,
  compact: false,
  syndication_view_enabled: false,
  active_syndication: [],
  status: ["Instock", "Sold", "Archive", "Unpublished"],
  sub_status: [
    "Available For Sale",
    "Reconditioning",
    "At Auction",
    "Coming Soon",
    "Deal Pending",
    "Delivered",
  ],
  vehicle_type: ["New", "Used", "Demo", "As-is"],
  vehicle_tag: [
    "Certified",
    "Coming Soon",
    "Extended Warranty",
    "Just Arrived",
    "No Accidents",
    "Off Lease",
    "One Owner",
    "On Special",
    "Reduced",
  ],
  inventory_body_types: [
    "ATV",
    "Boat",
    "Car",
    "Commercial EV",
    "Convertible",
    "Commercial",
    "Coupe",
    "Coupe-2-Door",
    "Cargo Van",
    "Dump",
    "Golf Carts EV",
    "Hatchback",
    "Minivan-Van",
    "Motorcycles",
    "Other/Don’t Know",
    "Pickup-Truck",
    "Powersports",
    "Passenger Van",
    "RV",
    "Sedan",
    "SUV",
    "SUV-Crossover",
    "Snowmobiles",
    "Trailer",
    "Truck",
    "Wagon",
  ],
  inventory_makes: [
    "AGT",
    "AM General",
    "AMC",
    "Acura",
    "Alcom Cargo Pro",
    "Alfa Romeo",
    "Aluma",
    "Aston Martin",
    "Atlas Copco",
    "Audi",
    "Austin Healey",
    "BMW",
    "Bayliner",
    "Bentley",
    "Bricklin",
    "Bugatti",
    "Buick",
    "Budd",
    "CJay Trailers",
    "Cadillac",
    "Capacity",
    "Cargo Express",
    "Carrier",
    "Caterpillar",
    "Cedar Rapids",
    "Chevrolet",
    "Chrysler",
    "Club Car",
    "Daewoo",
    "Daihatsu",
    "Datsun",
    "Dodge",
    "Ducati",
    "E-Z-GO",
    "Eagle",
    "Excavating",
    "FIAT",
    "Ferrari",
    "Fisker",
    "Freightliner",
    "Ford",
    "GMC",
    "Genesis",
    "Genix",
    "Geo",
    "HUMMER",
    "Harley-Davidson",
    "Haulin",
    "Heartland",
    "Hino",
    "Honda",
    "Hyundai",
    "Infiniti",
    "International",
    "International Harvester",
    "Isuzu",
    "Jaguar",
    "JC",
    "Jeep",
    "Kawasaki",
    "Kenworth",
    "KLM",
    "Kia",
    "King",
    "Komatsu",
    "Lamborghini",
    "Land Rover",
    "Lexus",
    "Lincoln",
    "Lotus",
    "MACK",
    "MG",
    "MINI",
    "MV",
    "Mack",
    "Maserati",
    "Mastercraft",
    "Maybach",
    "Mazda",
    "McLaren",
    "Mercedes-Benz",
    "Mercury",
    "Mitsubishi",
    "Mobility Ventures",
    "Nissan",
    "Oasis",
    "Oldsmobile",
    "Opel",
    "Other",
    "PJ Trailers",
    "Pace American",
    "Panoz",
    "Peterbilt",
    "Peugeot",
    "Plymouth",
    "Polaris",
    "Pontiac",
    "Porsche",
    "Ram",
    "Rainbow",
    "Reefer",
    "Renault",
    "Roll-off",
    "Rolls-Royce",
    "Saab",
    "Saturn",
    "Scion",
    "Shelby",
    "Smart",
    "Southland",
    "Spyker",
    "Sterling",
    "Stronghaul",
    "Studebaker",
    "Subaru",
    "Suzuki",
    "Taylor",
    "Terex",
    "Tesla",
    "TGB",
    "Thermo King",
    "Thru-Way",
    "Toyota",
    "Trailmobile",
    "Triumph",
    "Utility",
    "Volkswagen",
    "Volvo",
    "Western Star",
    "Yamaha",
    "Yugo",
  ],
  view: [
    {
      header: "PRICE",
      column: "price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "SPECIAL PRICE",
      column: "special_price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "EXT COLOR",
      column: "exterior_color",
      component: "text",
      visibility: "1",
    },
    {
      header: "ODOMETER",
      column: "odometer",
      component: "distance",
      visibility: "1",
    },
    {
      header: "FEATURED",
      column: "feature_listing",
      component: "text",
      visibility: "1",
    },
    {
      header: "TRANSMISSION",
      column: "transmission",
      component: "text",
      visibility: "1",
    },
    {
      header: "BODY TYPE",
      column: "body_type",
      component: "text",
      visibility: "1",
    },
    {
      header: "STATUS",
      column: "status",
      component: "badge",
      visibility: "1",
    },
    {
      header: "TYPE",
      column: "vehicle_type",
      component: "text",
      visibility: "1",
    },
    {
      header: "CREATION DATE",
      column: "created_at",
      component: "date",
      visibility: "1",
    },
    {
      header: "MODIFIED DATE",
      column: "updated_at",
      component: "date",
      visibility: "1",
    },
  ],
  map_columns: [
    {
      header: "VIN Number",
      column: "vin",
      type: "Null Comparision",
    },
    {
      header: "Stock Number",
      column: "stock_no",
      type: "Null Comparision",
    },
    {
      header: "Stock Date",
      column: "stock_date",
      type: "Date Comparision",
    },
    { header: "Status", column: "status", type: "Multi Select" },
    {
      header: "Vehicle Type",
      column: "vehicle_type",
      type: "Multi Select",
    },
    {
      header: "Featured Vehicle",
      column: "feature_listing",
      type: "Single Select",
    },
    {
      header: "Vehicle Tags",
      column: "vehicle_tags",
      type: "Multi Select",
    },
    {
      header: "Days in Stock",
      column: "days_in_stock",
      type: "Numeric Comparision",
    },
    { header: "Odometer", column: "odometer", type: "Null Comparision" },
    { header: "Price", column: "price", type: "Numeric Comparision" },
    {
      header: "Price Visibility",
      column: "hide_price",
      type: "Single Select",
    },
    {
      header: "VIN Visibility",
      column: "vindisplay",
      type: "Single Select",
    },
    {
      header: "Special Price Visibility",
      column: "special_price_on",
      type: "Single Select",
    },
    {
      header: "Visibility",
      column: "visibility",
      type: "Single Select",
    },
    {
      header: "Special Price",
      column: "special_price",
      type: "Numeric Comparision",
    },
    {
      header: "Creation Date",
      column: "created_at",
      type: "Date Comparision",
    },
    {
      header: "Modified Date",
      column: "updated_at",
      type: "Date Comparision",
    },
    {
      header: "Location",
      column: "location",
      type: "Multi Select",
    },
  ],
  available_filters: {},
  filters: {
    year: "",
    make: "",
    model: "",
    status: "",
    visibility: "",
  },
};

const getters = {
  list: (state) => state.view,

  getViewId: (state) => state.id,
  getViewTitle: (state) => state.heading,
  getEntities: (state) => state.entities,
  getPager: (state) => state.pager,
  getSortColumn: (state) => state.sort_column,
  getLoadState: (state) => state.load,
  getEntitiesCount: (state) => state.number_of_entities,
  getCurrentPage: (state) => state.current_page,
  getRequestUrl: (state) => state.request_url,
  getCurrentEntity: (state) => state.current_entity,
  getSearchString: (state) => state.search_string,
  getSearch: (state) => state.search,
  getCompact: (state) => state.compact,
  getStatuses: (state) => state.status,
  getSubStatues: (state) => state.sub_status,
  getVehicleTypes: (state) => state.vehicle_type,
  getVehicleTags: (state) => state.vehicle_tag,
  getInventoryMakes: (state) => state.inventory_makes,
  getInventoryBodyTypes: (state) => state.inventory_body_types,
  getAvailableFilters: (state) => state.available_filters,
  getFilters: (state) => state.filters,
  getFilterState: (state) => state.filter,
  getCurrentSyndicationViewStatus: (state) => state.syndication_view_enabled,
  getActiveSyndicationList: (state) => state.active_syndication,
  getAvailableValueMapper: (state) => [
    {
      header: "Status",
      values: state.status,
    },
    {
      header: "Vehicle Type",
      values: state.vehicle_type,
    },
    {
      header: "Vehicle Tags",
      values: state.vehicle_tag,
    },
    {
      header: "Featured Vehicle",
      values: [
        { label: "Featured", value: "1" },
        { label: "Not Featured", value: "0" },
      ],
    },
    {
      header: "VIN Visibility",
      values: [
        { label: "Visible", value: "1" },
        { label: "Hidden", value: "0" },
      ],
    },
    {
      header: "Price Visibility",
      values: [
        { label: "Visible", value: "1" },
        { label: "Hidden", value: "0" },
      ],
    },
    {
      header: "Special Price Visibility",
      values: [
        { label: "ON", value: "1" },
        { label: "OFF", value: "0" },
      ],
    },
    {
      header: "Visibility",
      values: [
        { label: "ON", value: "1" },
        { label: "OFF", value: "0" },
      ],
    },
  ],
  getMapColumns: (state) => state.map_columns,
};

const actions = {
  async updateView({ commit }, payload) {
    commit("updateList", payload);
  },

  async commitViewId({ commit }, payload) {
    commit("updateViewId", payload);
  },
  async commitViewTitle({ commit }, payload) {
    commit("updateViewTitle", payload);
  },
  async commitViewEntities({ commit }, payload) {
    commit("updateViewEntities", payload);
  },
  async commitViewPager({ commit }, payload) {
    commit("updateViewPager", payload);
  },
  async commitSortColumn({ commit }, payload) {
    commit("updateSortColumn", payload);
  },
  async commitLoadState({ commit }, payload) {
    commit("updateLoadState", payload);
  },
  async commitEntitiesCount({ commit }, payload) {
    commit("updateEntitiesCount", payload);
  },
  async commitCurrentPage({ commit }, payload) {
    commit("updateCurrentPage", payload);
  },
  async commitRequestUrl({ commit }, payload) {
    commit("updateRequestUrl", payload);
  },
  async commitOnlyEntities({ commit }) {
    commit("fetchOnlyEntities");
  },
  async commitCurrentEntity({ commit }, payload) {
    commit("setCurrentEntity", payload);
  },
  async commitSearchState({ commit }, payload) {
    commit("updateSearchState", payload);
  },
  async commitSearchString({ commit }, payload) {
    commit("updateSearchString", payload);
  },
  async commitCompactFlag({ commit }, payload) {
    commit("updateCompactFlag", payload);
  },
  async commitAvailableFilters({ commit }) {
    commit("fetchAvailableFilters");
  },
  async commitFilters({ commit }, payload) {
    commit("updateFilters", payload);
  },
  async commitFilterState({ commit }, payload) {
    commit("updateFilterState", payload);
  },
  async commitInventoryVehicleTags({ commit }) {
    commit("fetchInventoryVehicleTags");
  },
  async commitInventoryMakes({ commit }) {
    commit("fetchInventoryMakes");
  },
  async commitCurrentSyndicationViewStatus({ commit }, payload) {
    commit("updateSyndicationViewStatus", payload);
  },
  async commitActiveSyndicationList({ commit }) {
    commit("fetchActiveSyndicationList");
  },
};

const mutations = {
  updateList: (state, list) => (state.view = list),
  updateSyndicationViewStatus: (state, syndication_view_enabled) =>
    (state.syndication_view_enabled = syndication_view_enabled),
  updateViewId: (state, id) => (state.id = id),
  updateViewTitle: (state, heading) => (state.heading = heading),
  updateViewEntities: (state, entities) => (state.entities = entities),
  updateViewPager: (state, pager) => (state.pager = pager),
  updateSortColumn: (state, sort_column) => (state.sort_column = sort_column),
  updateLoadState: (state, load) => (state.load = load),
  updateEntitiesCount: (state, number_of_entities) =>
    (state.number_of_entities = number_of_entities),
  updateCurrentPage: (state, current_page) =>
    (state.current_page = current_page),
  updateRequestUrl: (state, request_url) => (state.request_url = request_url),
  setCurrentEntity: (state, current_entity) =>
    (state.current_entity = current_entity),
  updateSearchState: (state, search) => (state.search = search),
  updateSearchString: (state, search_string) =>
    (state.search_string = search_string),
  updateCompactFlag: (state, compact) => (state.compact = compact),
  updateFilterState: (state, filter) => (state.filter = filter),
  updateFilters: (state, filter_obj) =>
    (state.filters[filter_obj.name] = filter_obj.value),
  fetchAvailableFilters: (state) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: window.location.origin + `/api/website/inventory/filters`,
    })
      .then(({ data }) => {
        state.available_filters = data;
      })
      .catch((e) => {
        console.log(e);
      });
  },
  fetchActiveSyndicationList: (state) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: `${window.location.origin}/api/business/configurations/active/syndication`,
    })
      .then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              state.active_syndication = JSON.parse(element["value"]);

              let hostname = window.location.origin;
              const parts = hostname.split(".");
              hostname = parts.slice(-2).join(".");
              hostname = "https://" + hostname;
              return axios({
                method: "get",
                headers: {
                  Authorization: "Bearer " + window.$cookies.get("_token"),
                },
                url: `${hostname}/api/syndication/companies/all?paginate=false`,
              });
            }
          }
        }
      })
      .then(({ data }) => {
        console.log(data.syndication_companies_list);
        for (const key in state.active_syndication) {
          data.syndication_companies_list.some((x) => {
            if (x["slug"] == state.active_syndication[key]["slug"]) {
              state.active_syndication[key]["image_url"] = x["image_url"];
            }
          });
        }
      })
      .catch((e) => {
        console.log(e);
      });
  },
  fetchOnlyEntities: (state) => {
    if (state.load) {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: state.request_url,
      })
        .then((response) => {
          state.entities = response.data.inventory;
          let pager = response.data.pager;
          pager.next =
            pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous =
            pager.previous == null
              ? null
              : "/api" + pager.previous.split("api")[1];

          state.pager = pager;

          state.current_page = parseInt(pager.currentPage);
          state.number_of_entities = parseInt(pager.perPage);
        })
        .catch(() => {});
    }
  },
  fetchInventoryVehicleTags: async (state) => {
    let request_url =
      window.location.origin + "/api/business/configurations/active/inventory";

    await axios({
      method: "get",
      url: request_url,
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
    }).then(({ data }) => {
      for (const key in data) {
        if (data.hasOwnProperty(key)) {
          const element = data[key];
          if (element.key == "vehicle_default_vehicle_tags") {
            state.vehicle_tag = JSON.parse(element.value);
          }
        }
      }
    });
  },
  fetchInventoryMakes: async (state) => {
    let request_url =
      window.location.origin + "/api/business/configurations/active/inventory";

    await axios({
      method: "get",
      url: request_url,
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
    }).then(({ data }) => {
      for (const key in data) {
        if (data.hasOwnProperty(key)) {
          const element = data[key];
          if (element.key == "inventory_makes") {
            state.inventory_makes = JSON.parse(element.value);
          } else if (element.key == "inventory_body_types") {
            state.inventory_body_types = JSON.parse(element.value);
          }
        }
      }
    });
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
