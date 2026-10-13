import axios from "axios";

const namespaced = true;

const state = {
  default_comparator_mapping: [
    {
      type: "Null Comparision",
      comparator: ["is empty", "is not empty"],
    },
    {
      type: "Date Comparision",
      comparator: ["is", "after", "before"],
    },
    {
      type: "Multi Select",
      comparator: ["is", "is not"],
    },
    {
      type: "Single Select",
      comparator: ["is"],
    },
    {
      type: "Numeric Comparision",
      comparator: ["greater than", "less than", "between"],
    },
    {
      type: "Relative Time Period Comparision",
      comparator: ["greater than", "less than", "between"],
    },
  ],
};

const getters = {
  getDefaultComparatorMapping: (state) => state.default_comparator_mapping,
};

export default {
  namespaced,
  state,
  getters,
};
