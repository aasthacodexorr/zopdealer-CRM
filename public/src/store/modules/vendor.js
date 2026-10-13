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
  vendor_id: [],
  columns: {},
  vendors: [],
};

const getters = {
  getVendorIds: (state) => state.vendor_id,
  getColumns: (state) => state.columns,
  allVendors: (state) => state.vendors,
};

const actions = {
  async getAllVendors({ commit }) {
    commit("fetchAllVendors");
  },
  async addVendorId({ commit }, id) {
    commit("addNewVendorId", id);
  },
  async removeVendorId({ commit }, id) {
    commit("removeOldVendorId", id);
  },
  async removeEveryVendorId({ commit }) {
    commit("removeAllVendorId");
  },
  async addEveryVendorId({ commit }, ids) {
    commit("addAllVendorId", ids);
  },
};

const mutations = {
  fetchAllVendors: (state) => {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/vendors?column=company_name&order=ASC&paginate=false`,
      }).then(({ data }) => {
        state.vendors = data.vendor_listing;
      }).catch((response) => { console.log('There were errors in fetching vendors'); });
  },
  addNewVendorId: (state, id) => {
    !state.vendor_id.includes(id) ? state.vendor_id.push(id) : "";
  },
  removeOldVendorId: (state, id) => {
    let index = state.vendor_id.indexOf(id);
    if (index > -1) {
      state.vendor_id.splice(index, 1);
    }
  },
  removeAllVendorId: (state) => {
    state.vendor_id = [];
  },
  addAllVendorId: (state, ids) => {
    ids.forEach((id) => {
      !state.vendor_id.includes(id)
        ? state.vendor_id.push(id)
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
