import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Work Orders",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/work-order`,
  current_entity: null,
  search_string: "",
  search: false,
  filter: false,
  compact: false,
  view: [
    {
      header: "ID",
      column: "work_order_id",
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
      header: "AUTO SYNC",
      column: "auto_sync_expense_flag",
      component: "text",
      visibility: "1",
    },
    {
      header: "TYPE",
      column: "type",
      component: "text",
      visibility: "1",
    },
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
      header: "CHECK IN",
      column: "check_in_date_time",
      component: "date",
      visibility: "1",
    },
    {
      header: "ODOMETER IN",
      column: "check_in_odometer",
      component: "distance",
      visibility: "1",
    },
    {
      header: "CHECK OUT",
      column: "check_out_date_time",
      component: "date",
      visibility: "1",
    },
    {
      header: "ODOMETER OUT",
      column: "check_out_odometer",
      component: "distance",
      visibility: "1",
    },
    {
      header: "MECHANIC",
      column: "assigned_mechanic",
      component: "text",
      visibility: "1",
    },
    {
      header: "ADVISOR",
      column: "assigned_advisor",
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
  filters: {
    type: "",
    status: "",
    assigned_advisor: "",
    assigned_mechanic: ""
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
  updateFilterState: (state, filter) => (state.filter = filter),
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
          state.entities = response.data.work_order_listing;
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
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
