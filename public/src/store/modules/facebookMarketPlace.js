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

const namespaced = true;

const state = {
  post_id: [],
  columns: {},
};

const getters = {
  getPostIds: (state) => state.post_id,
  getColumns: (state) => state.columns,
};

const actions = {
  async addPostId({ commit }, id) {
    commit("addNewPostId", id);
  },
  async removePostId({ commit }, id) {
    commit("removeOldPostId", id);
  },
  async removeEveryPostId({ commit }) {
    commit("removeAllPostId");
  },
  async addEveryPostId({ commit }, ids) {
    commit("addAllPostId", ids);
  },
};

const mutations = {
  addNewPostId: (state, id) => {
    !state.post_id.includes(id) ? state.post_id.push(id) : "";
  },
  removeOldPostId: (state, id) => {
    let index = state.post_id.indexOf(id);
    if (index > -1) {
      state.post_id.splice(index, 1);
    }
  },
  removeAllPostId: (state) => {
    state.post_id = [];
  },
  addAllPostId: (state, ids) => {
    ids.forEach((id) => {
      !state.post_id.includes(id)
        ? state.post_id.push(id)
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
