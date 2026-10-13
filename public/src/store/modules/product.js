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
  product_id: [],
  columns: {},
  categories: [],
  types: [
    "Stock",
    "Labour",
    "Accessories",
    "Consumable",
    "Sublet Repair",
    "Tyre",
  ],
};

const getters = {
  getProductIds: (state) => state.product_id,
  getColumns: (state) => state.columns,
  getCategories: (state) => state.categories,
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
  async commitCategories({ commit }) {
    commit("fetchCategories");
  },
};

const mutations = {
  addNewEntityId: (state, id) => {
    !state.product_id.includes(id) ? state.product_id.push(id) : "";
  },
  removeOldEntityId: (state, id) => {
    let index = state.product_id.indexOf(id);
    if (index > -1) {
      state.product_id.splice(index, 1);
    }
  },
  removeAllEntityId: (state) => {
    state.product_id = [];
  },
  addAllEntityId: (state, ids) => {
    ids.forEach((id) => {
      !state.product_id.includes(id) ? state.product_id.push(id) : "";
    });
  },
  fetchCategories: (state) => {
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
          if (element.key == "product_categories") {
            state.categories = JSON.parse(element.value);
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
