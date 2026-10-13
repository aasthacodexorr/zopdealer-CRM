import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Inventory Cost Reports",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/inventory-cost-report`,
  current_entity: null,
  search_string: "",
  search: false,
  compact: false,

  view: [
    {
      header: "EXPENSE DATE",
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
      header: "NAME",
      column: "name",
      component: "text",
      visibility: "1",
    },
    {
      header: "INVOICE",
      column: "invoice",
      component: "text",
      visibility: "1",
    },
    {
      header: "PAYMENT REF",
      column: "payment_reference",
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
      header: "PRICE",
      column: "price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "QUANTITY",
      column: "quantity",
      component: "text",
      visibility: "1",
    },
    {
      header: "DISCOUNT",
      column: "discount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PRIMARY TAX",
      column: "tax_amount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "SECONDARY TAX",
      column: "tax_amount_secondary",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TOTAL TAX",
      column: "tax_amount_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "LINE TOTAL",
      column: "sub_total",
      component: "currency",
      visibility: "1",
    },
  ],
  filters: {
    invoice: "",
    vendor_name: "",
    name: "",
    purchase_date_start: "",
    purchase_date_end: "",
    sub_total_start: "",
    sub_total_end: "",
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
