import axios from "axios";

const namespaced = true;

const state = {
  test_drives_customers_id: [],
  columns: {},
  test_drives_customers: [],
};

const getters = {
  getTestDrivesCustomerIds: (state) => state.test_drives_customers_id,
  getColumns: (state) => state.columns,
  allTestDrivesCustomers: (state) => state.test_drives_customers,
};

const actions = {
  async addNewTestDrivesCustomerId({ commit }, payload) {
    commit("addNewTestDrivesCustomerId", payload);
  },
  async removeOldTestDrivesCustomerId({ commit }, payload) {
    commit("removeOldTestDrivesCustomerId", payload);
  },
  async removeAllTestDrivesCustomerId({ commit }) {
    commit("removeAllTestDrivesCustomerId");
  },
  async commitColumns({ commit }, payload) {
    commit("updateColumns", payload);
  },
  async resetColumns({ commit }) {
    commit("resetColumns");
  },
  async fetchAllTestDrivesCustomers({ commit }) {
    commit("fetchAllTestDrivesCustomers");
  },
};

const mutations = {
  fetchAllTestDrivesCustomers: (state) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: window.location.origin + `/api/business/test-drives-customers?paginate=false`,
    }).then(({ data }) => {
      state.test_drives_customers = data.test_drives_customers_listing || [];
    }).catch((response) => {
      console.log('There were errors in fetching test drives customers');
    });
  },
  addNewTestDrivesCustomerId: (state, id) => {
    !state.test_drives_customers_id.includes(id) ? state.test_drives_customers_id.push(id) : "";
  },
  removeOldTestDrivesCustomerId: (state, id) => {
    let index = state.test_drives_customers_id.indexOf(id);
    if (index > -1) {
      state.test_drives_customers_id.splice(index, 1);
    }
  },
  removeAllTestDrivesCustomerId: (state) => {
    state.test_drives_customers_id = [];
  },
  updateColumns: (state, columns) => {
    state.columns = columns;
  },
  resetColumns: (state) => {
    state.columns = {};
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
