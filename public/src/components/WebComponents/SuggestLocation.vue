<template>
  <v-select
    :id="`autopopulate_location_${elementId}`"
    placeholder="Search for a location"
    label="text"
    v-model="selected_place"
    clearable
    :options="places"
    @search="triggerSearch"
  >
    <template v-slot:no-options="{ search, searching }">
      <template v-if="searching && places.length === 0">
        No results found for <em>{{ search }}</em
        >.
      </template>
      <em v-else style="opacity: 0.5">Start typing to search for a location</em>
    </template>
    <template slot="selected-option" slot-scope="option">
      <span>{{ option.text }}</span>
    </template>
    <template slot="option" slot-scope="option">
      <span>{{ option.text }}</span>
    </template>
  </v-select>
</template>
<script>
import vSelect from "vue-select";
import { debounce } from "vue-debounce";
import Toast from "../../library/Toast";

export default {
  props: {
    elementId: {
      type: String,
      required: true,
      default: "",
    },
  },
  name: "SuggestLocation",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      places: [],
      selected_place: null,
    };
  },
  watch: {
    selected_place: function (newVal, oldVal) {
      if (newVal !== oldVal) {
        if (newVal && newVal !== "") {
          if (newVal.hasOwnProperty("placeId")) {
            this.getALocation(newVal["placeId"]);
          }
        }
      }
    },
  },
  methods: {
    triggerSearch: debounce(function (search = "", cb = null) {
      this.getPlacesSuggestion(search, cb);
    }, "300ms"),
    getPlacesSuggestion(search = "", cb = null) {
      if (typeof cb === "function") {
        cb(true);
      }

      if (search) {
        const vm = this;
        let request_url = `${window.location.origin}/api/services/locations/suggestion`;
        let formData = new FormData();
        formData.append("search", search.trim());
        axios({
          method: "post",
          url: request_url,
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            vm.places = data.places;

            if (typeof cb === "function") {
              cb(false);
            }
          })
          .catch(({ response }) => {
            if (response.status === 429) {
              const toast = new Toast("");
              toast.fire();
              toast.fail(`Monthly Limit has been exhausted.`);
              toast.close();
            }

            if (typeof cb === "function") {
              cb(false);
            }
          })
          .finally(() => {
            vm.$helpers.gTagClickEvent(
              vm,
              "zd_action",
              "global",
              "location_autocomplete",
              "get_location_autocomplete"
            );
          });
      } else {
        if (typeof cb === "function") {
          cb(false);
        }
      }
    },
    getALocation(id) {
      let request_url = `${window.location.origin}/api/services/locations/${id}`;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("success")) {
            Event.fire(`autopopulate_location_${this.elementId}`, data);
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the the location";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
};
</script>