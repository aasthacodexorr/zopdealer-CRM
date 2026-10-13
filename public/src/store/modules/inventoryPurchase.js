/***
 *
 * We need to store the array of the ids of deal based on the value the checkbox.
 * Storing Columns And Values that are supposed to be updated.
 * On checkmark check if the id is already present in the array. If not then add.
 * On Bulk option add the true or false value based on the checkmark of the parent component
 * checkbox
 *
 *
 */

const namespaced = true;

const state = {
  inventory_purchases_id: [],
  columns: {},
};

const getters = {
  getIds: (state) => state.inventory_purchases_id,
  getColumns: (state) => state.columns,
};

const actions = {
  async addId({ commit }, id) {
    commit("addNewId", id);
  },
  async removeId({ commit }, id) {
    commit("removeOldId", id);
  },
  async removeEveryId({ commit }) {
    commit("removeAllId");
  },
  async addEveryId({ commit }, ids) {
    commit("addAllId", ids);
  },
};

const mutations = {
  addNewId: (state, id) => {
    !state.inventory_purchases_id.includes(id)
      ? state.inventory_purchases_id.push(id)
      : "";
  },
  removeOldId: (state, id) => {
    let index = state.inventory_purchases_id.indexOf(id);
    if (index > -1) {
      state.inventory_purchases_id.splice(index, 1);
    }
  },
  removeAllId: (state) => {
    state.inventory_purchases_id = [];
  },
  addAllId: (state, ids) => {
    ids.forEach((id) => {
      !state.inventory_purchases_id.includes(id)
        ? state.inventory_purchases_id.push(id)
        : "";
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
