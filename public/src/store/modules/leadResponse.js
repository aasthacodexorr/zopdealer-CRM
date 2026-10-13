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
  lead_response_id: [],
  columns: {},
  statuses: [
    "New",
    "Appointment Booked",
    "Contacted",
    "Contact in Future",
    "Follow Up Soon",
    "Interested",
    "Junk Lead",
    "Lost Lead",
    "Not Contacted",
    "Not Qualified",
    "No Response",
    "Won",
  ],
  sources: [
    "Web Lead - Contact",
    "Web Lead - Trade-In",
    "Web Lead - KBB Trade-In",
    "Web Lead - Offer",
    "Web Lead - Test Drive",
    "Web Lead - Vehicle History",
    "Web Lead - Credit Application",
    "Web Lead - Car Finder",
    "Web Lead - Service Appointment",
    "Web Lead - Request Info",
    "Web Lead - Reserve Online",
    "Walk-in",
    "Phone",
    "Kijiji",
    "Cargurus",
    "Car Pages",
    "Autotrader",
    "Edealer",
    "Facebook",
    "Zop",
    "Credit Application Consent",
  ],
};

const getters = {
  getLeadStatuses: (state) => state.statuses,
  getLeadSources: (state) => state.sources,
  getLeadResponseIds: (state) => state.lead_response_id,
  getColumns: (state) => state.columns,
};

const actions = {
  async addLeadResponseId({ commit }, id) {
    commit("addNewLeadResponseId", id);
  },
  async removeLeadResponseId({ commit }, id) {
    commit("removeOldLeadResponseId", id);
  },
  async removeEveryLeadResponseId({ commit }) {
    commit("removeAllLeadResponseId");
  },
  async addEveryLeadResponseId({ commit }, ids) {
    commit("addAllLeadResponseId", ids);
  },
  async getLeadConfigurations({ commit }) {
    commit("fetchLeadConfigurations");
  },
};

const mutations = {
  addNewLeadResponseId: (state, id) => {
    !state.lead_response_id.includes(id) ? state.lead_response_id.push(id) : "";
  },
  removeOldLeadResponseId: (state, id) => {
    let index = state.lead_response_id.indexOf(id);
    if (index > -1) {
      state.lead_response_id.splice(index, 1);
    }
  },
  removeAllLeadResponseId: (state) => {
    state.lead_response_id = [];
  },
  addAllLeadResponseId: (state, ids) => {
    ids.forEach((id) => {
      !state.lead_response_id.includes(id)
        ? state.lead_response_id.push(id)
        : "";
    });
  },
  fetchLeadConfigurations: (state) => {
    axios({
      method: "get",
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
      },
      url: window.location.origin + `/api/business/configurations/lead`,
    })
      .then(({ data }) => {
        for (const key in data) {
          const element = data[key];
          if (element.key == "lead_statuses") {
            state.statuses = JSON.parse(element.value);
          } else if (element.key == "lead_sources") {
            state.sources = JSON.parse(element.value);
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
