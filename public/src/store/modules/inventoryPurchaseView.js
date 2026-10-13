import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Inventory Purchase Reports",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/inventory-purchase-report`,
  current_entity: null,
  search_string: "",
  search: false,
  compact: false,

  view: [
    {
      header: "PURCHASE DATE",
      column: "purchase_date",
      component: "date",
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
      header: "VENDOR NAME",
      column: "vendor_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR PHONE",
      column: "phone",
      component: "phone",
      visibility: "1",
    },
    {
      header: "VENDOR MOBILE",
      column: "mobile",
      component: "phone",
      visibility: "1",
    },
    {
      header: "VENDOR EMAIL",
      column: "email",
      component: "email",
      visibility: "1",
    },
    {
      header: "PLATE NUMBER",
      column: "plate_number",
      component: "text",
      visibility: "1",
    },
    {
      header: "KEY COUNT",
      column: "key_count",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR COMPANY NAME",
      column: "company_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR APT",
      column: "apt",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR STREET",
      column: "street_address",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR CITY",
      column: "city",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR PROVINCE",
      column: "province",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR POSTAL CODE",
      column: "postal_code",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR COUNTRY",
      column: "country",
      component: "text",
      visibility: "1",
    },
    {
      header: "PURCHASE PRICE",
      column: "price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PURCHASE DISCOUNT",
      column: "discount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PURCHASE PRIMARY TAX",
      column: "tax_amount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PURCHASE SECONDARY TAX",
      column: "tax_amount_secondary",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PURCHASE TOTAL TAX",
      column: "tax_amount_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PAID STATUS",
      column: "has_been_paid_for",
      component: "yes_or_no",
      visibility: "1",
    },
    {
      header: "INVENTORY PAID DATE",
      column: "payment_date",
      component: "date",
      visibility: "1",
    },
    {
      header: "TITLE RECEIVED",
      column: "has_title_been_received",
      component: "yes_or_no",
      visibility: "1",
    },
    {
      header: "TITLE RECEIVED DATE",
      column: "title_received_date",
      component: "date",
      visibility: "1",
    },
    {
      header: "TITLE TRANSFER",
      column: "has_title_been_transferred",
      component: "yes_or_no",
      visibility: "1",
    },
    {
      header: "TITLE TRANSFER DATE",
      column: "title_transfer_date",
      component: "date",
      visibility: "1",
    },
    {
      header: "HAS LIEN",
      column: "has_lien",
      component: "yes_no_unselected",
      visibility: "1",
    },
    {
      header: "LIEN AMOUNT",
      column: "lien_amount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "LIEN HOLDER NAME",
      column: "lien_holder_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "HAS LIEN BEEN PAID",
      column: "lien_has_been_paid",
      component: "yes_no_unselected",
      visibility: "1",
    },
    {
      header: "LIEN PAID DATE",
      column: "lien_paid_date",
      component: "date",
      visibility: "1",
    },
  ],
  filters: {
    company_name: "",
    name: "",
    purchase_date_start: "",
    purchase_date_end: "",
    price_start: "",
    price_end: "",
    payment_date_start: "",
    payment_date_end: "",
    title_transfer_date_start: "",
    title_transfer_date_end: "",
    title_received_date_start: "",
    title_received_date_end: "",
    has_been_paid_for: "",
    has_title_been_received: "",
    has_title_been_transferred: "",
    has_lien: "",
    lien_has_been_paid: "",
    lien_paid_date_start: "",
    lien_paid_date_end: "",
    lien_amount_start: "",
    lien_amount_end: "",
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
          state.entities = response.data.inventory_cost_listing;
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
