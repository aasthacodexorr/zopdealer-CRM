<template>
    <li class="d-inline-block mr-1">
      <span
        class="form-control btn-outline-danger"
        style="cursor: pointer"
        @click="confirmDeleteAction"
      >
        Delete
      </span>
    </li>
  </template>
  <script>
  import { mapGetters, mapActions } from "vuex";
  import vSelect from "vue-select";
  import Toast from "../../library/Toast";
  export default {
    name: "WorkOrderBulkDelete",
    data() {
      return {
        entity_plural_label: "Work Orders",
        entity_base_delete_api: `/api/business/work-orders/deletes`,
        entity_listing_attribute: "work_order_listing",
        form: {
          type: "",
          category: "",
        },
      };
    },
    computed: {
      ...mapGetters("workOrder", ["getWorkOrderIds"]),
    },
    methods : {
      applyBulkDelete() {
        const vm = this;
        let formData = new FormData();
        formData.append("work_order_ids", this.getWorkOrderIds);
        axios({
          method: "post",
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + this.entity_base_delete_api,
        })
          .then((response) => {
            Event.fire("reset_selected_columns");
            
            Event.fire("reset_selected_rows");
  
            setTimeout(() => {
              Event.fire("reset_current_pagination");
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
            Event.fire("reset_selected_columns");
            
            Event.fire("reset_selected_rows");
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
          text: `Please confirm if you want to delete the ${this.entity_plural_label}.`,
        }).then((result) => {
          console.log(result);
          if (result.isDismissed) {
          } else if (result.isConfirmed) {
            vm.applyBulkDelete();
          }
        });
      }
    }
  };
  </script>
  