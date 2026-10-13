/***
 *
 * We need to store the array of the ids of payment based on the value the checkbox.
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
  payments_id: [],
  columns: {},
  statuses: ["Charge", "Refund"],
  modules: ["Lead", "Deal"],
  modes: ["Online", "Offline"],
  types: [
    "ACH",
    "Bank Transfer",
    "Cash",
    "Cheque",
    "Credit",
    "Draft",
    "Email Transfer",
    "Store Credit",
  ],
  reasons: [],
};

const getters = {
  getPaymentStatuses: (state) => state.statuses,
  getPaymentModules: (state) => state.modules,
  getIds: (state) => state.payments_id,
  getColumns: (state) => state.columns,
  getModes: (state) => state.modes,
  getTypes: (state) => state.types,
  getReasons: (state) => state.reasons,
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
  async getPaymentConfigurations({ commit }) {
    commit("fetchPaymentConfigurations");
  },
};

const mutations = {
  addNewId: (state, id) => {
    !state.payments_id.includes(id) ? state.payments_id.push(id) : "";
  },
  removeOldId: (state, id) => {
    let index = state.payments_id.indexOf(id);
    if (index > -1) {
      state.payments_id.splice(index, 1);
    }
  },
  removeAllId: (state) => {
    state.payments_id = [];
  },
  addAllId: (state, ids) => {
    ids.forEach((id) => {
      !state.payments_id.includes(id) ? state.payments_id.push(id) : "";
    });
  },
  fetchPaymentConfigurations: async (state) => {
    await axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: window.location.origin + `/api/business/configurations/payment`,
    })
      .then(({ data }) => {
        for (const key in data) {
          const element = data[key];
          if (element.key == "payment_reasons") {
            state.reasons = JSON.parse(element.value);
          }
        }
      })
      .catch((response) => {});
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
