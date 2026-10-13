/***
 *
 * We need to store the array of the ids of inventory based on the value the checkbox.
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
  work_order_id: [],
  columns: {},
  list_of_mechanics: [],
  statuses: ["Open", "In Progress", "Completed", "Lost"],
  types: ["Internal", "External"],
};

const getters = {
  getWorkOrderIds: (state) => state.work_order_id,
  getColumns: (state) => state.columns,
  getListOfMechanics: (state) => state.list_of_mechanics,
  getStatuses: (state) => state.statuses,
  getTypes: (state) => state.types,
};

const actions = {
  async addEntityId({ commit }, id) {
    commit("addNewEntityId", id);
  },
  async removeEntityId({ commit }, id) {
    commit("removeOldEntityId", id);
  },
  async removeEveryEntityId({ commit }) {
    commit("removeAllEntityId");
  },
  async addEveryEntityId({ commit }, ids) {
    commit("addAllEntityId", ids);
  },
  async commitListOfMechanics({ commit }) {
    commit("fetchListOfMechanics");
  },
};

const mutations = {
  addNewEntityId: (state, id) => {
    !state.work_order_id.includes(id) ? state.work_order_id.push(id) : "";
  },
  removeOldEntityId: (state, id) => {
    let index = state.work_order_id.indexOf(id);
    if (index > -1) {
      state.work_order_id.splice(index, 1);
    }
  },
  removeAllEntityId: (state) => {
    state.work_order_id = [];
  },
  addAllEntityId: (state, ids) => {
    ids.forEach((id) => {
      !state.work_order_id.includes(id) ? state.work_order_id.push(id) : "";
    });
  },
  fetchListOfMechanics: (state) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: window.location.origin + `/api/business/configurations/global`,
    })
      .then(({ data }) => {
        for (const key in data) {
          const element = data[key];
          if (element.key == "list_of_mechanics") {
            state.list_of_mechanics = JSON.parse(element.value);
          }
        }
      })
      .catch((response) => {
        //
      });
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
