<template>
  <li class="d-none d-sm-none d-md-inline-block">
    <span
      href="#"
      :class="getFilterState ? 'bg-secondary bx-tada text-white' : ''"
      class="list-space border bx bxs-filter-alt"
      title="Filters"
      data-toggle="modal"
      data-target="#filters"
    ></span>
    <!-- Filter Model : START -->
    <div
      class="modal fade text-left"
      id="filters"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Filter Products"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document"
      >
        <div class="modal-content" id="filterEntityModel">
          <div class="modal-header pl-1 pr-1">
            <h3 class="content-header-title">Filters</h3>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Filters to be here-->

              <!-- type : START -->
              <div class="col-md-5">
                <label>Type</label>
              </div>
              <div class="col-md-7 form-group">
                <v-select
                  id="filter-type"
                  placeholder="Type"
                  v-model="typeFilter"
                  clearable
                  :options="getTypes"
                >
                </v-select>
              </div>
              <!-- type : END -->

              <!-- category : START -->
              <div class="col-md-5">
                <label>Category</label>
              </div>
              <div class="col-md-7 form-group">
                <v-select
                  id="filter-type"
                  placeholder="Category"
                  v-model="categoryFilter"
                  clearable
                  :options="getCategories"
                >
                </v-select>
              </div>
              <!-- category : END -->

              <div class="col-xs-12 p-1"></div>

              <div class="col-sm-12 d-flex justify-content-left pb-1">
                <button
                  type="close"
                  @click="filterEntity"
                  class="btn btn-primary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  Apply Filters
                </button>
                <button
                  type="close"
                  @click="resetFilters"
                  class="btn btn-light-secondary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Reset"
                >
                  Reset
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Filter Model : END -->
  </li>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
export default {
  name: "ProductListFilter",
  data() {
    return {
      entity_plural_label: "Products",
      entity_base_filter_api: "/api/business/products?all=true",
      entity_listing_attribute: "product_listing",
    };
  },
  components: {
    "v-select": vSelect,
  },
  computed: {
    // Entity Specific Categories
    ...mapGetters("product", ["getTypes", "getCategories"]),
    // Replace Entity View
    ...mapGetters("productView", [
      "getSearchString",
      "getSearch",
      "getFilters",
      "getFilterState",
    ]),
    typeFilter: {
      get() {
        return this.getFilters.type;
      },
      set(value) {
        this.commitFilters({ name: "type", value: value });
      },
    },
    categoryFilter: {
      get() {
        return this.getFilters.category;
      },
      set(value) {
        this.commitFilters({ name: "category", value: value });
      },
    },
  },
  methods: {
    ...mapActions("product", ["commitCategories"]),
    ...mapActions("productView", [
      "commitViewEntities",
      "commitViewPager",
      "commitSortColumn",
      "commitEntitiesCount",
      "commitCurrentPage",
      "commitRequestUrl",
      "commitFilters",
      "commitFilterState",
    ]),
    resetFilters() {
      this.commitFilters({ name: "type", value: "" });
      this.commitFilters({ name: "category", value: "" });
      if (this.getSearch) {
        Event.fire("search_listing_page");
      } else {
        Event.fire("reset_listing_page");
      }
      this.commitFilterState(false);
    },
    filterEntity() {
      let vm = this;
      let url = window.location.origin;
      url += this.entity_base_filter_api;

      if (this.getSearch) {
        url += `&search=${this.getSearchString}`;
      }

      for (const key in this.getFilters) {
        if (this.getFilters.hasOwnProperty(key)) {
          if (this.getFilters[key]) {
            url += `&zm_${key}[]=${this.getFilters[key]}`;
          }
        }
      }
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data[this.entity_listing_attribute]);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        this.commitFilterState(true);

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `${this.entity_plural_label} have been filtered.`,
        });
      });
    },
  },
  async created() {
    this.commitCategories();
  },
};
</script>
<style lang="scss">
#filterEntityModel .vs__dropdown-menu {
  max-height: 200px;
}
</style>
