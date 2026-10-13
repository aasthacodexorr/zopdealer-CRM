export default {
  data() {
    return {
      form: {},
      view_default: "",
      view_flag: 1,
      views: [],
      bulk_select: "0",
      api_entity_listing_attribute: "",
      api_entity_view_id_attribute: "",
      api_entity_views_attribute: "",
      api_entity_bulk_ids_attribute: "",
      entity_view_route_slug: "",
      entity_index_route_slug: "",
      entity_bulk_deletion_route_slug: "",
      entity_bulk_update_route_slug: "",
      user_entity_route_slug: "",
      entity_label: "",
    };
  },
  computed: {
    getDefaultViews() {
      return this.views.filter((view) => view.default == "1");
    },
    getCustomViews() {
      return this.views.filter((view) => view.default == "0");
    },
    selectedView() {
      return this.getViewTitle.length > 12
        ? this.getViewTitle.substring(0, 12 - 3) + "..."
        : this.getViewTitle;
    },
    getNumberOfEntities: {
      get() {
        return this.getEntitiesCount;
      },
      set(value) {
        this.commitEntitiesCount(value);
        this.pagination();
      },
    },
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
    clearBulkSelection() {
      this.bulk_select = "0";
    },
    resetBulkColumns() {
      Object.keys(this.form).forEach((key) => {
        this.form[key] = "";
      });
    },
    filterEntities() {
      let vm = this;
      let url = window.location.origin;
      url += `${this.entity_index_route_slug}?all=true`;

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
        this.commitViewEntities(
          response.data[this.api_entity_listing_attribute],
        );
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
          title: `${this.entity_label}(s) have been filtered.`,
        });
      });
    },
    searchAction(value, e) {
      if (value.length >= 3) {
        this.commitSearchState(true);
        this.searchAnEntity(this.getSearchString);
      } else {
        if (this.getSearch) {
          this.currentUserSelectedViewEntityListing(true);
          this.commitSearchState(false);
        }
      }
    },
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    entityViews(refresh = false) {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}${this.entity_view_route_slug}`,
      }).then((response) => {
        this.views = response.data[this.api_entity_views_attribute];
      });
    },
    unsavedView() {
      const vm = this;
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.view_flag == 1) {
        this.view_flag = 0;
        this.$swal({
          showConfirmButton: true,
          showCancelButton: true,
          cancelButtonText: "Revert",
          confirmButtonText: "Save",
          toast: true,
          position: "bottom",
          text: "Do you like the view ? Save It.",
        }).then((result) => {
          if (result.hasOwnProperty("dismiss")) {
            this.updateView(JSON.parse(JSON.stringify(this.view_default)));
          } else if (result.hasOwnProperty("value") && result.value) {
            let formData = new FormData();
            formData.append("view", JSON.stringify(vm.list));
            axios({
              method: "post",
              data: formData,
              headers: {
                Authorization: "Bearer " + window.$cookies.get("_token"),
              },
              url: `${window.location.origin}${this.entity_view_route_slug}/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: `${this.entity_label} view has been updated.`,
              });
            });
          }
          this.view_flag = 1;
        });
      }
    },
    updateSortColumn(column, order) {
      if (this.getSearch || this.getFilterState) {
        this.$swal({
          showConfirmButton: false,
          icon: "error",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Sort order for view cannot be updated while searching.",
        });
        return;
      }

      const vm = this;
      let formData = new FormData();
      vm.sort_column = {};
      vm.sort_column[column] = order;
      formData.append("sort", JSON.stringify(vm.sort_column));
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}${this.entity_view_route_slug}/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `${this.entity_label} view has been updated.`,
        });

        this.commitLoadState(false);
        vm.currentUserSelectedViewEntityListing();
      });
    },
    searchAnEntity(search) {
      let url = window.location.origin;
      url += `${this.entity_index_route_slug}?all=true`;

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

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(
          response.data[this.api_entity_listing_attribute],
        );
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
    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;

      if (this.getSearch || this.getFilterState) {
        url += `${this.entity_index_route_slug}?all=true`;

        if (this.getSearch) {
          url += `&search=${this.getSearchString}`;
        }

        if (this.getFilterState) {
          for (const key in this.getFilters) {
            if (this.getFilters.hasOwnProperty(key)) {
              if (this.getFilters[key]) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              }
            }
          }
        }

        url += `&page=`;
      } else {
        url += `${this.user_entity_route_slug}?page=`;
      }

      url += n;
      url += "&items=";
      url += this.getEntitiesCount;
      this.clearBulkSelection();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(
          response.data[this.api_entity_listing_attribute],
        );
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
      });
    },
    paginationWithUrl(url) {
      const vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(
          response.data[this.api_entity_listing_attribute],
        );
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
      });
    },
    async currentUserSelectedViewEntityListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: `${window.location.origin}${this.user_entity_route_slug}`,
        }).then((response) => {
          this.commitViewId(response.data[this.api_entity_view_id_attribute]);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(
            response.data[this.api_entity_listing_attribute],
          );
          this.commitRequestUrl(
            `${window.location.origin}${this.user_entity_route_slug}`,
          );

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

          this.updateView(Object.values(response.data.view));
          this.view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view)),
          );
        });

        this.commitLoadState(true);
      }
    },
    resetFilters() {
      this.resetFilterProperties();

      if (this.getSearch) {
        this.searchAnEntity(this.getSearchString);
      } else {
        this.currentUserSelectedViewEntityListing(true);
      }
    },
    changeCurrentEntityView(id) {
      this.commitSearchString("");
      this.commitSearchState(false);

      this.resetFilterProperties();

      this.view_id = id;
      let vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}${this.entity_view_route_slug}/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data[this.api_entity_view_id_attribute]);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(
          response.data[this.api_entity_listing_attribute],
        );
        this.commitRequestUrl(
          `${window.location.origin}${this.entity_view_route_slug}/result/${id}`,
        );

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

        this.updateView(Object.values(response.data.view));
        this.view_default = JSON.parse(
          JSON.stringify(Object.values(response.data.view)),
        );

        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));

        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: `${window.location.origin}${this.user_entity_route_slug}/update?id=${this.getViewId}`,
        }).then((response) => {
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: `${this.entity_label} view changed.`,
          });
        });
      });
    },
    applyBulkOptions() {
      const vm = this;
      let formData = new FormData();
      formData.append(this.api_entity_bulk_ids_attribute, this.getIds);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}${this.entity_bulk_update_route_slug}`,
      })
        .then((response) => {
          this.resetBulkColumns();
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Columns are updated successfully.",
            });
          }, 1000);
        })
        .catch(function(error) {
          vm.resetBulkColumns();
          vm.$data.bulk_select = 0;
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "No columns Or rows were selected.",
            });
          }, 1000);
        });
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append(this.api_entity_bulk_ids_attribute, this.getIds);
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}${this.entity_bulk_deletion_route_slug}`,
      })
        .then((response) => {
          this.resetBulkColumns();
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Selected data has been deleted.",
            });
          }, 1000);
        })
        .catch(function(error) {
          vm.resetBulkColumns();
          vm.$data.bulk_select = 0;
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "There were some errors in deleting data.",
            });
          }, 1000);
        });
    },
    confirmDeleteAction() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: `Please confirm if you want to delete the ${this.entity_label}.`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
  },
};
