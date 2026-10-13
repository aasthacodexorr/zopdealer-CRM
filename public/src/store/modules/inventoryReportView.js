import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Inventory Reports",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/inventory-report`,
  current_entity: null,
  search_string: "",
  search: false,
  compact: false,

  view: [
    {
      header: "INVENTORY ID",
      column: "inventory_id",
      component: "text",
      visibility: "1",
    },
    {
      header: "VISIBILITY",
      column: "visibility",
      component: "text",
      visibility: "1",
    },
    {
      header: "YEAR",
      column: "year",
      component: "text",
      visibility: "1",
    },
    {
      header: "MAKE",
      column: "make",
      component: "text",
      visibility: "1",
    },
    {
      header: "MODEL",
      column: "model",
      component: "text",
      visibility: "1",
    },
    {
      header: "TRIM",
      column: "trim",
      component: "text",
      visibility: "1",
    },
    {
      header: "STOCK DATE",
      column: "stock_date",
      component: "date",
      visibility: "1",
    },
    {
      header: "DAYS IN STOCK",
      column: "days_in_stock",
      component: "text",
      visibility: "1",
    },
    {
      header: "STOCK NO",
      column: "stock_no",
      component: "text",
      visibility: "1",
    },
    {
      header: "VIN",
      column: "vin",
      component: "text",
      visibility: "1",
    },
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
      header: "SELLING PRICE",
      column: "selling_price",
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
      header: "LOCATION",
      column: "location",
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
    {
      header: "VENDOR NAME",
      column: "vendor_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR PHONE",
      column: "vendor_phone",
      component: "phone",
      visibility: "1",
    },
    {
      header: "VENDOR MOBILE",
      column: "vendor_mobile",
      component: "phone",
      visibility: "1",
    },
    {
      header: "VENDOR EMAIL",
      column: "vendor_email",
      component: "email",
      visibility: "1",
    },
    {
      header: "PLATE NUMBER",
      column: "vendor_plate_number",
      component: "text",
      visibility: "1",
    },
    {
      header: "KEY COUNT",
      column: "inventory_key_count",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR COMPANY NAME",
      column: "vendor_company_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR APT",
      column: "vendor_apt",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR STREET",
      column: "vendor_street_address",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR CITY",
      column: "vendor_city",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR PROVINCE",
      column: "vendor_province",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR POSTAL CODE",
      column: "vendor_postal_code",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR COUNTRY",
      column: "vendor_country",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE DATE",
      column: "vendor_purchase_date",
      component: "date",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE PRICE",
      column: "vendor_purchase_price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE DISCOUNT",
      column: "vendor_purchase_discount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE PRIMARY TAX",
      column: "vendor_purchase_primary_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE SECONDARY TAX",
      column: "vendor_purchase_secondary_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE TOTAL TAX",
      column: "vendor_purchase_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PAID STATUS",
      column: "inventory_paid_status",
      component: "yes_or_no",
      visibility: "0",
    },
    {
      header: "INVENTORY PAID DATE",
      column: "inventory_paid_date",
      component: "date",
      visibility: "0",
    },
    {
      header: "TITLE RECEIVED",
      column: "has_title_been_received",
      component: "yes_or_no",
      visibility: "0",
    },
    {
      header: "TITLE RECEIVED DATE",
      column: "title_received_date",
      component: "date",
      visibility: "0",
    },
    {
      header: "TITLE TRANSFER",
      column: "has_title_been_transferred",
      component: "yes_or_no",
      visibility: "0",
    },
    {
      header: "TITLE TRANSFER DATE",
      column: "title_transfer_date",
      component: "date",
      visibility: "0",
    },
    {
      header: "HAS LIEN",
      column: "has_lien",
      component: "yes_no_unselected",
      visibility: "0",
    },
    {
      header: "LIEN AMOUNT",
      column: "lien_amount",
      component: "currency",
      visibility: "0",
    },
    {
      header: "LIEN HOLDER NAME",
      column: "lien_holder_name",
      component: "text",
      visibility: "0",
    },
    {
      header: "HAS LIEN BEEN PAID",
      column: "lien_has_been_paid",
      component: "yes_no_unselected",
      visibility: "0",
    },
    {
      header: "LIEN PAID DATE",
      column: "lien_paid_date",
      component: "date",
      visibility: "0",
    },
    {
      header: "ADDITIONAL COST",
      column: "additional_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST DISCOUNT",
      column: "additional_cost_discount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST PRIMARY TAX",
      column: "additional_cost_primary_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST SECONDARY TAX",
      column: "additional_cost_secondary_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST TOTAL TAX",
      column: "additional_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST TOTAL",
      column: "additional_cost_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "NET PURCHASE TOTAL",
      column: "net_purchase_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "CUSTOMER NAME",
      column: "customer_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER PHONE",
      column: "customer_phone",
      component: "phone",
      visibility: "1",
    },
    {
      header: "CUSTOMER MOBILE",
      column: "customer_mobile",
      component: "phone",
      visibility: "1",
    },
    {
      header: "CUSTOMER EMAIL",
      column: "customer_email",
      component: "email",
      visibility: "1",
    },
    {
      header: "CUSTOMER COMPANY NAME",
      column: "customer_company_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER APT",
      column: "customer_apt",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER STREET",
      column: "customer_street_address",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER CITY",
      column: "customer_city",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER PROVINCE",
      column: "customer_province",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER POSTAL CODE",
      column: "customer_postal_code",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER COUNTRY",
      column: "customer_country",
      component: "text",
      visibility: "1",
    },
    {
      header: "DELIVERY DATE",
      column: "delivery_date_time",
      component: "date",
      visibility: "1",
    },
  ],
  filters: {
    visibility: "",
    status: "",
    feature_listing: "",
    vehicle_type: "",
    date_start: "",
    date_end: "",
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
  getFilters: (state) => state.filters,
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
  async commitFilters({ commit }, payload) {
    commit("updateFilters", payload);
  },
};

const mutations = {
  updateList: (state, list) => (state.view = list),

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
  updateFilters: (state, filter_obj) =>
    (state.filters[filter_obj.name] = filter_obj.value),
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
          state.entities = response.data.deal_listing;
        })
        .catch(() => {});
    }
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
