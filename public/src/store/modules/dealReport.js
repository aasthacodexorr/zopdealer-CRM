/***
 *
 * We need to store the array of the ids of deal based on the value the checkbox.
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
   deals_id: [],
   columns: {},
   statuses: [
     "Open",
     "Delivered",
     "Lost"
   ]
 };
 
 const getters = {
   getDealStatuses: (state) => state.statuses,
   getIds: (state) => state.deals_id,
   getColumns: (state) => state.columns,
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
 };
 
 const mutations = {
   addNewId: (state, id) => {
     !state.deals_id.includes(id) ? state.deals_id.push(id) : "";
   },
   removeOldId: (state, id) => {
     let index = state.deals_id.indexOf(id);
     if (index > -1) {
       state.deals_id.splice(index, 1);
     }
   },
   removeAllId: (state) => {
     state.deals_id = [];
   },
   addAllId: (state, ids) => {
     ids.forEach((id) => {
       !state.deals_id.includes(id)
         ? state.deals_id.push(id)
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
 