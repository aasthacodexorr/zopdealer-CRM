import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Test Drives",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/test-drive`,
  current_entity: null,
  search_string: '',
  search: false,
  compact: false,
  filters: {
    status: '',
    sales_rep_id: '',
    date_start: '',
    date_end: '',
  },
  filter_state: false,

  view: [
    {
      column: "first_name",
      header: "FIRST NAME",
      component: "text",
      visibility: "1"
    },
    {
      column: "last_name",
      header: "LAST NAME",
      component: "text",
      visibility: "1"
    },
    {
      column: "phone",
      header: "PHONE",
      component: "phone",
      visibility: "1"
    },
    {
      column: "email",
      header: "EMAIL",
      component: "email",
      visibility: "1"
    },
    {
      column: "vehicle",
      header: "VEHICLE",
      component: "text",
      visibility: "1"
    },
    {
      column: "stock_no",
      header: "STOCK#",
      component: "text",
      visibility: "1"
    },
    {
      column: "vin",
      header: "VIN",
      component: "text",
      visibility: "1"
    },
    {
      column: "number_plate",
      header: "NUMBER PLATE",
      component: "text",
      visibility: "1"
    },
    {
      column: "odo_start",
      header: "ODO START",
      component: "text",
      visibility: "1"
    },
    {
      column: "odo_end",
      header: "ODO END",
      component: "text",
      visibility: "1"
    },
    {
      column: "sales_rep_id",
      header: "SALES REP",
      component: "user",
      visibility: "1"
    },
    {
      column: "issue",
      header: "ISSUES?",
      component: "text",
      visibility: "1"
    },
    {
      column: "status",
      header: "STATUS",
      component: "badge",
      visibility: "1"
    },
    {
      column: "created_at",
      header: "CREATION DATE",
      component: "date",
      visibility: "1"
    },
    {
      column: "updated_at",
      header: "MODIFIED DATE",
      component: "date",
      visibility: "1"
    },
  ],
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
  getFilterState: (state) => state.filter_state,
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
    commit("updateFilter", payload);
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
  updateEntitiesCount: (state, number_of_entities) => (state.number_of_entities = number_of_entities),
  updateCurrentPage: (state, current_page) => (state.current_page = current_page),
  updateRequestUrl: (state, request_url) => (state.request_url = request_url),
  setCurrentEntity: (state, current_entity) => (state.current_entity = current_entity),
  updateSearchState: (state, search) => (state.search = search),
  updateSearchString: (state, search_string) => (state.search_string = search_string),
  updateCompactFlag: (state, compact) => (state.compact = compact),
  updateFilter: (state, { name, value }) => { state.filters[name] = value; },
  updateFilterState: (state, filter_state) => (state.filter_state = filter_state),
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};

