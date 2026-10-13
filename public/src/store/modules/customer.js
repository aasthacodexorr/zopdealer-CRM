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
  customer_id: [],
  columns: {},
  customers: [],
};

const getters = {
  getIds: (state) => state.customer_id,
  getColumns: (state) => state.columns,
  allCustomers: (state) => state.customers,
};

const actions = {
  async getAllVendors({ commit }) {
    commit("fetchAllCustomers");
  },
  async addCustomerId({ commit }, id) {
    commit("addNewCustomerId", id);
  },
  async removeCustomerId({ commit }, id) {
    commit("removeOldCustomerId", id);
  },
  async removeEveryCustomerId({ commit }) {
    commit("removeAllCustomerId");
  },
  async addEveryCustomerId({ commit }, ids) {
    commit("addAllCustomerId", ids);
  },
};

const mutations = {
  fetchAllCustomers: (state) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: window.location.origin + `/api/business/customers?paginate=false`,
    })
      .then(({ data }) => {
        state.customers = data.vendor_listing;
      })
      .catch((response) => {
        console.log("There were errors in fetching customers");
      });
  },
  addNewCustomerId: (state, id) => {
    !state.customer_id.includes(id) ? state.customer_id.push(id) : "";
  },
  removeOldCustomerId: (state, id) => {
    let index = state.customer_id.indexOf(id);
    if (index > -1) {
      state.customer_id.splice(index, 1);
    }
  },
  removeAllCustomerId: (state) => {
    state.customer_id = [];
  },
  addAllCustomerId: (state, ids) => {
    ids.forEach((id) => {
      !state.customer_id.includes(id) ? state.customer_id.push(id) : "";
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
