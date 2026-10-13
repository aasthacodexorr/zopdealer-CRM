<template>
    <div class="col-12">
      <fieldset
        class="
          form-label-group form-group
          position-relative
          has-icon-right
          mt-1
          "
      >
        <input
          type="text"
          id="searchText"
          v-debounce:1000ms="searchAction"
          v-model="searchString"
          class="form-control round"
          style="height: 2.6em"
          placeholder="Search Anything ..."
        />
  
        <div class="form-control-position">
          <i class="bx bx-search"></i>
        </div>
      </fieldset>
    </div>
  </template>
  <script>
  import { mapGetters, mapActions } from "vuex";
  export default {
    name: "WorkOrderSearch",
    data() {
      return {
        entity_plural_label: "Work Orders",
        entity_base_filter_api: "/api/business/work-orders?all=true",
        entity_listing_attribute: "work_order_listing"
      };
    },
    computed: {
      ...mapGetters("workOrderView", [
        "getSearchString",
        "getSearch",
        "getFilters",
        "getFilterState",
      ]),
      searchString: {
        get() {
          return this.getSearchString;
        },
        set(value) {
          this.commitSearchString(value);
        },
      },
    },
    methods: {
      ...mapActions("workOrderView", [
        "commitViewEntities",
        "commitViewPager",
        "commitSortColumn",
        "commitEntitiesCount",
        "commitCurrentPage",
        "commitRequestUrl",
        "commitSearchState",
        "commitSearchString",
        "commitFilters",
        "commitFilterState",
      ]),
      searchAction(value, e) {
        if (value.length >= 3) {
          this.commitSearchState(true);
          this.searchEntity(this.getSearchString);
        } else {
          if (this.getSearch) {
            Event.fire("reset_listing_page");
            this.commitSearchState(false);
          }
        }
      },
      async searchEntity(search) {
        let vm = this;
  
        let url = window.location.origin;
        url += this.entity_base_filter_api;
  
        url += `&search=${this.getSearchString}`;
  
        if (this.getFilterState) {
          for (const key in this.getFilters) {
            if (this.getFilters.hasOwnProperty(key)) {
              if (this.getFilters[key]) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              }
            }
          }
        }
  
        await axios({
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
  
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: `Search for ${search} has been completed.`,
          });
        });
      },
    },
    mounted() {
      const vm = this;
      Event.listen("search_listing_page", (data) => {
        vm.searchEntity(vm.getSearchString);
      });
  
      var input = document.getElementById("searchText");
      input.addEventListener("keydown", function(event) {
        if (event.keyCode === 13) {
          event.preventDefault();
          this.searchAction();
        }
      });
    },
    beforeDestroy() {
      Event.vue.$off("search_listing_page", null);
    },
  };
  </script>
  