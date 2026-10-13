/**
 *  This will be used to fetch the configurations throughout the application in future.
 */

import axios from "axios";

const namespaced = true;

const state = {
    business_tax_settings: [],
}

const getters = {
    businessTaxSettings: (state) => state.business_tax_settings,
};

const mutations = {
    fetchBusinessTaxSettings: (state) => {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/configurations/global`,
        }).then(({ data }) => {
          for (const key in data) {
            const element = data[key];
            if (element.key == 'business_tax_settings') {
              state.business_tax_settings = JSON.parse(element.value);
            }
          }

        }).catch((response) => { console.log('There were errors in fetching business tax settings.'); });

    },
};

const actions = {
     getBusinessTaxSettings({ commit }) {
      commit("fetchBusinessTaxSettings");
    },
};

export default {
    namespaced,
    state,
    getters,
    actions,
    mutations,
  };