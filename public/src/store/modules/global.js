/**
 *
 * TODO : Future Implementation and replacing the window event global object with this.
 */
import axios from "axios";

const state = {
  global: {
    guest: true,
    user: "",
    token: "",
    base_url: window.location.origin,
  },
};

const getters = {
  inventory: (state) => state.global,
};

const actions = {
  async changeGlobal({ commit }, payload) {
    commit("globalObjectUpdate", payload);
  },
};

const mutations = {
  globalObjectUpdate: (state, new_global) => (state.global = new_global),
};

export default {
  state,
  getters,
  actions,
  mutations,
};
