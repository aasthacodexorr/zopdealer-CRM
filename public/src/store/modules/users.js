import axios from "axios";

const namespaced = true;

const state = {
  users: [],
};

const getters = {
  allUsers: (state) => state.users,
};

const mutations = {
  fetchAllUsers: (state) => {
    if (state.users.length == 0) {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/user/all`,
      }).then(({ data }) => {
        state.users = data.users;
      });
    }
  },
};

const actions = {
  async getAllUsers({ commit }) {
    commit("fetchAllUsers");
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
