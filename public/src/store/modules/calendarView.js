import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  load: false,
  request_url: `${window.location.origin}/api/business/user/activity?paginate=false`,
  activity_types: [],
  statuses: [],
  priorities: [],
  modules: [],
  entities: [],
  appointment_methods: [],
  log_methods: [],
  notification_methods: [],
  filters: {
    assignee: "",
    status: "",
    priority: "",
    module: "",
    activity_type: "",
  },
  filter_flag: false,
  today_count: 0,
};

const getters = {
  getViewId: (state) => state.id,
  getEntities: (state) => state.entities,
  getLoadState: (state) => state.load,
  getRequestUrl: (state) => state.request_url,
  getFilters: (state) => state.filters,
  getActivityTypes: (state) => state.activity_types,
  getStatuses: (state) => state.statuses,
  getPriorities: (state) => state.priorities,
  getModules: (state) => state.modules,
  getFilterFlag: (state) => state.filter_flag,
  getTodayCount: (state) => state.today_count,
  getAppointmentMethods: (state) => state.appointment_methods,
  getLogMethods: (state) => state.log_methods,
  getNotificationMethods: (state) => state.notification_methods,
};

const actions = {
  async commitViewId({ commit }, payload) {
    commit("updateViewId", payload);
  },
  async commitViewEntities({ commit }, payload) {
    commit("updateViewEntities", payload);
  },
  async commitLoadState({ commit }, payload) {
    commit("updateLoadState", payload);
  },
  async commitRequestUrl({ commit }, payload) {
    commit("updateRequestUrl", payload);
  },
  async commitOnlyEntities({ commit }) {
    commit("fetchOnlyEntities");
  },
  async commitFilters({ commit }, payload) {
    commit("updateFilters", payload);
  },
  async commitFilterFlag({ commit }, payload) {
    commit("updateFilterFlag", payload);
  },
  async commitTodayCount({ commit }, payload) {
    commit("updateTodayCount", payload);
  },
  async getSetUpAttributes({ commit }) {
    commit("fetchAttribute", {
      slug: "activity_statuses",
      state_attribute: "statuses",
    });
    commit("fetchAttribute", {
      slug: "activity_types",
      state_attribute: "activity_types",
    });
    commit("fetchAttribute", {
      slug: "activity_priorities",
      state_attribute: "priorities",
    });
    commit("fetchAttribute", {
      slug: "activity_notification_methods",
      state_attribute: "notification_methods",
    });
    commit("fetchAttribute", {
      slug: "activity_appointment_methods",
      state_attribute: "appointment_methods",
    });
    commit("fetchAttribute", {
      slug: "activity_log_methods",
      state_attribute: "log_methods",
    });

     commit("fetchAttribute", {
      slug: "activity_modules",
      state_attribute: "modules",
    });
  },
};

const mutations = {
  updateViewId: (state, id) => (state.id = id),
  updateViewEntities: (state, entities) => (state.entities = entities),
  updateLoadState: (state, load) => (state.load = load),
  updateRequestUrl: (state, request_url) => (state.request_url = request_url),
  updateFilterFlag: (state, filter_flag) => (state.filter_flag = filter_flag),
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
          state.entities = response.data.activity_listing;
        })
        .catch(() => {});
    }
  },
  updateTodayCount: (state, user_id) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url:
        window.location.origin +
        `/api/business/activities?only_today=true&paginate=false&column=start_date&order=ASC&zm_assignee=${user_id}`,
    })
      .then(({ data }) => {
        state.today_count = data.activity_listing.length;
      })
      .catch(() => {});
  },
  updateFilters: (state, filter_obj) =>
    (state.filters[filter_obj.name] = filter_obj.value),
  fetchAttribute: (state, { slug, state_attribute }) => {
    axios
      .get(`/json/${slug}.json`)
      .then(({ data }) => {
        state[state_attribute] = data;
      })
      .catch(() => {});
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
