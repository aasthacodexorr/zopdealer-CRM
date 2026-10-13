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
  review_feedback_id: [],
  columns: {},
  statuses: ["SENT", "SEEN", "COMPLETED"],
  responses: ["POSITIVE", "NEGATIVE"],
};

const getters = {
  getReviewFeedbackIds: (state) => state.review_feedback_id,
  getColumns: (state) => state.columns,
  getStatuses: (state) => state.statuses,
  getResponses: (state) => state.responses,
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
};

const mutations = {
  addNewEntityId: (state, id) => {
    !state.review_feedback_id.includes(id)
      ? state.review_feedback_id.push(id)
      : "";
  },
  removeOldEntityId: (state, id) => {
    let index = state.review_feedback_id.indexOf(id);
    if (index > -1) {
      state.review_feedback_id.splice(index, 1);
    }
  },
  removeAllEntityId: (state) => {
    state.review_feedback_id = [];
  },
  addAllEntityId: (state, ids) => {
    ids.forEach((id) => {
      !state.review_feedback_id.includes(id)
        ? state.review_feedback_id.push(id)
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
