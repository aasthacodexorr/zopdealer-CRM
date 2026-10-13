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
  inventory_id: [],
  columns: {},
};

const getters = {
  getInventoryIds: (state) => state.inventory_id,
  getColumns: (state) => state.columns,
};

const actions = {
  async addInventoryId({ commit }, id) {
    commit("addNewInventoryId", id);
  },
  async removeInventoryId({ commit }, id) {
    commit("removeOldInventoryId", id);
  },
  async removeEveryInventoryId({ commit }) {
    commit("removeAllInventoryId");
  },
  async addEveryInventoryId({ commit }, ids) {
    commit("addAllInventoryId", ids);
  },
};

const mutations = {
  addNewInventoryId: (state, id) => {
    !state.inventory_id.includes(id) ? state.inventory_id.push(id) : "";
  },
  removeOldInventoryId: (state, id) => {
    let index = state.inventory_id.indexOf(id);
    if (index > -1) {
      state.inventory_id.splice(index, 1);
    }
  },
  removeAllInventoryId: (state) => {
    state.inventory_id = [];
  },
  addAllInventoryId: (state, ids) => {
    ids.forEach((id) => {
      !state.inventory_id.includes(id) ? state.inventory_id.push(id) : "";
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
