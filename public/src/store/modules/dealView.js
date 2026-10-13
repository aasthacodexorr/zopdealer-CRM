import axios from "axios";
import deal from "./deal";

const namespaced = true;

const state = {
  id: 1,
  heading: "Deals",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/deal`,
  current_entity: null,
  search_string: "",
  search: false,
  compact: false,
  view: [
    {
      header: "FIRST NAME",
      column: "first_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "LAST NAME",
      column: "last_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "COMPANY NAME",
      column: "company_name",
      component: "text",
      visibility: "0",
    },
    {
      header: "PHONE",
      column: "phone",
      component: "phone",
      visibility: "1",
    },
    {
      header: "MOBILE",
      column: "mobile",
      component: "phone",
      visibility: "1",
    },
    {
      header: "EMAIL",
      column: "email",
      component: "email",
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
      header: "VIN",
      column: "vin",
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
      header: "ODOMETER",
      column: "odometer",
      component: "distance",
      visibility: "1",
    },
    {
      header: "EXTERIOR COLOR",
      column: "exterior_color",
      component: "text",
      visibility: "0",
    },
    {
      header: "INTERIOR COLOR",
      column: "interior_color",
      component: "text",
      visibility: "0",
    },
    {
      header: "TYPE",
      column: "type",
      component: "text",
      visibility: "1",
    },
    {
      header: "DATE",
      column: "date",
      component: "date",
      visibility: "1",
    },
    {
      header: "RL or WHL",
      column: "retail_or_wholesale_flag",
      component: "rtl_wh_flag",
      visibility: "1",
    },
    {
      header: "STATUS",
      column: "status",
      component: "badge",
      visibility: "1",
    },
    {
      header: "Approved By",
      column: "approved_by",
      component: "user",
      visibility: "1",
    },
    {
      header: "Salesperson",
      column: "salesperson",
      component: "user",
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
    { header: "Status", column: "status", type: "Multi Select" },

    { header: "Type", column: "type", type: "Single Select" },

    {
      header: "Retail Or Wholesale",
      column: "retail_or_wholesale_flag",
      type: "Single Select",
    },

    { header: "Approved By", column: "approved_by", type: "Single Select" },
    { header: "Salesperson", column: "salesperson", type: "Single Select" },

    {
      header: "Deal Time Period",
      column: "date",
      type: "Relative Time Period Comparision",
    },
    {
      header: "Delivery Time Period",
      column: "delivery_date_time",
      type: "Relative Time Period Comparision",
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
  ],
  filters: {
    type: "",
    retail_or_wholesale_flag: "",
    status: "",
    approved_by: "",
    salesperson: "",
    date_start: "",
    date_end: "",
    delivery_date_start: "",
    delivery_date_end: "",
  },
  filter: false,
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
  getAvailableValueMapper: (state) => [
    {
      header: "Status",
      values: deal.getters.getDealStatuses(deal.state),
    },
    {
      header: "Retail Or Wholesale",
      values: [
        { label: "Retail", value: "0" },
        { label: "Wholesale", value: "1" },
      ],
    },
    {
      header: "Type",
      values: [
        { label: "Financed", value: "Financed" },
        { label: "Cash", value: "Cash" },
      ],
    },
  ],
  getMapColumns: (state) => state.map_columns,
  getFilters: (state) => state.filters,
  getFilterState: (state) => state.filter,
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
  async commitFilterState({ commit }, payload) {
    commit("updateFilterState", payload);
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
  updateFilterState: (state, filter) => (state.filter = filter),
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
