/***
 *
 * We need to store the array of the ids of test drives based on the value the checkbox.
 * Storing Columns And Values that are supposed to be updated.
 * On checkmark check if the id is already present in the array. If not then add.
 * On Bulk option add the true or false value based on the checkmark of the parent component
 * checkbox
 *
 *
 */

import axios from "axios";

const namespaced = true;

const state = {
  test_drive_id: [],
  columns: {},
  testDrives: [],
  status: ['pending', 'in_progress', 'completed', 'cancelled'],
  sales_rep_name: [],
};

const getters = {
  getTestDriveIds: (state) => state.test_drive_id,
  getColumns: (state) => state.columns,
  allTestDrives: (state) => state.testDrives,
  getTestDriveStatuses: (state) => state.status,
  getTestDriveSalesReps: (state) => state.sales_rep_name,
};

const actions = {
  async getAllTestDrives({ commit }) {
    commit("fetchAllTestDrives");
  },
  async addTestDriveId({ commit }, id) {
    commit("addNewTestDriveId", id);
  },
  async removeTestDriveId({ commit }, id) {
    commit("removeOldTestDriveId", id);
  },
  async removeEveryTestDriveId({ commit }) {
    commit("removeAllTestDriveId");
  },
  async addEveryTestDriveId({ commit }, ids) {
    commit("addAllTestDriveId", ids);
  },
  async fetchTestDriveFilters({ commit }) {
    try {
      const { data } = await axios({
        method: 'get',
        headers: { Authorization: 'Bearer ' + window.$cookies.get('_token') },
        url: window.location.origin + '/api/business/user/all',
      });
      if (data.users && Array.isArray(data.users)) {
        commit('setTestDriveSalesReps', data.users);
      }
    } catch (e) {
      console.error('Failed to load sales rep list', e);
    }
  },
};

const mutations = {
  fetchAllTestDrives: (state) => {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/test-drives?paginate=false`,
      }).then(({ data }) => {
        state.testDrives = data.test_drive_listing;
      }).catch((response) => { console.log('There were errors in fetching test drives'); });
  },
  addNewTestDriveId: (state, id) => {
    !state.test_drive_id.includes(id) ? state.test_drive_id.push(id) : "";
  },
  removeOldTestDriveId: (state, id) => {
    let index = state.test_drive_id.indexOf(id);
    if (index > -1) {
      state.test_drive_id.splice(index, 1);
    }
  },
  removeAllTestDriveId: (state) => {
    state.test_drive_id = [];
  },
  addAllTestDriveId: (state, ids) => {
    ids.forEach((id) => {
      !state.test_drive_id.includes(id)
        ? state.test_drive_id.push(id)
        : "";
    });
  },
  setTestDriveSalesReps: (state, reps) => {
    state.sales_rep_name = reps;
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};

