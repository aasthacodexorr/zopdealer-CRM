import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Payments",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/payment`,
  current_entity: null,
  search_string: "",
  search: false,
  compact: false,

  view: [
    {
      header: "DATE",
      column: "date",
      component: "date",
      visibility: "1",
    },
    {
      header: "NAME",
      column: "name",
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
      header: "EMAIL",
      column: "email",
      component: "email",
      visibility: "1",
    },
    {
      header: "TRANS ID",
      column: "transaction_id",
      component: "text",
      visibility: "1",
    },
    {
      header: "EVENT ID",
      column: "event_id",
      component: "text",
      visibility: "0",
    },
    {
      header: "RECEIPT ID",
      column: "receipt_number",
      component: "text",
      visibility: "1",
    },
    {
      header: "AMOUNT",
      column: "amount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TYPE",
      column: "type",
      component: "badge",
      visibility: "0",
    },
    {
      header: "MODE",
      column: "mode",
      component: "badge",
      visibility: "0",
    },
    {
      header: "STATUS",
      column: "status",
      component: "badge",
      visibility: "1",
    },
    {
      header: "SUB STATUS",
      column: "sub_status",
      component: "badge",
      visibility: "1",
    },
    {
      header: "MODULE",
      column: "module",
      component: "module",
      visibility: "1",
    },
    {
      header: "REASON",
      column: "reason",
      component: "text",
      visibility: "1",
    },
    {
      header: "RECEIPT EMAIL",
      column: "receipt_email",
      component: "email",
      visibility: "0",
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
          state.entities = response.data.payment_listing;
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
