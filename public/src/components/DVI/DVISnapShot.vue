<template>
  <div
    class="customizer border-left-blue-grey border-left-lighten-4 d-none d-lg-block"
    :class="isOpen ? 'open' : ''"
  >
    <a
      class="customizer-close"
      role="button"
      @click="isOpen = false"
      style="padding: 0px; cursor: pointer"
      ><i class="bx bx-x bx-md" style="font-weight: 600"></i></a
    ><a
      class="customizer-toggle bg-secondary box-shadow-3"
      @click="isOpen = !isOpen"
      ><i
        class="bx text-white bx-md"
        :class="isOpen ? 'bx-chevrons-right' : 'bx-chevrons-left'"
      ></i
    ></a>
    <div
      class="customizer-content p-2 ps-container ps-theme-dark ps-active-y"
      v-show="!entity_id"
    >
      <h4 class="text-uppercase mb-0">DVI Information</h4>
      <hr />
      <div class="col-12 h6">
        Please click on any DVI to view it's snapshot.
      </div>
    </div>
    <div
      class="customizer-content p-2 ps-container ps-theme-dark ps-active-y"
      v-show="entity_id"
      style="overflow-y: auto"
    >
      <h4 class="text-uppercase mb-0">DVI Information</h4>
      <hr />
      <!-- Inspection Title (Primary) -->
      <div class="mb-1">
        <h4 class="text-uppercase mb-0 text-primary" style="font-weight: 700">
          {{ form.title | truncate(50, "...") }}
        </h4>
        <small class="text-muted">{{
          form.description | truncate(70, "...")
        }}</small>
      </div>
      <div class="mt-25 mb-25">
        <small class="text-muted mr-25 text-uppercase">Assignee</small>
        <span
          class="badge badge-light-primary"
          style="font-weight: 600; float: right"
        >
          {{ getAssigneeName(form.assignee) }}
        </span>
      </div>
      <div class="mt-25 mb-25">
        <small class="text-muted mr-25 text-uppercase">Status</small>
        <span
          v-if="form.status == 'INCOMPLETE'"
          class="badge badge-light-danger"
          style="font-weight: 600; float: right"
          >{{ form.status }}</span
        >
        <span
          v-else-if="form.status == 'ARCHIVE'"
          class="badge badge-light-warning"
          style="font-weight: 600; float: right"
          >{{ form.status }}</span
        >
        <span
          v-else-if="form.status == 'COMPLETE'"
          class="badge badge-light-success"
          style="font-weight: 600; float: right"
          >{{ form.status }}</span
        >
        <span
          v-else
          class="badge badge-light-secondary"
          style="font-weight: 600; float: right"
          >{{ form.status }}</span
        >
      </div>
      <div class="mt-25 mb-25">
        <small class="text-muted mr-25 text-uppercase">Odometer</small>
        <span style="font-weight: 600; float: right">
          {{ getFormattedOdometer(form.odometer) }}
        </span>
      </div>
      <hr />

      <!-- Inventory : Start  -->
      <template v-if="form.stock_no && form.stock_no != ''">
        <h6 class="mt-1 text-bold-600">Inventory Information</h6>
        <hr />
        <div class="col-12 p-0 pb-25">
          <label for="">Year</label>
          <span
            class="float-right copy-hover"
            style="font-weight: 600"
            title="Click to Copy"
            @click="doCopy(`${form.inventory.year}`)"
            >{{ form.inventory.year }}</span
          >
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Make</label>
          <span
            class="float-right copy-hover"
            style="font-weight: 600"
            title="Click to Copy"
            @click="doCopy(`${form.inventory.make}`)"
            >{{ form.inventory.make }}</span
          >
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Model</label>
          <span
            class="float-right copy-hover"
            style="font-weight: 600"
            title="Click to Copy"
            @click="doCopy(`${form.inventory.model}`)"
            >{{ form.inventory.model }}</span
          >
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Trim</label>
          <span class="float-right" style="font-weight: 600">{{
            form.inventory.trim | truncate(20, "...")
          }}</span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Stock No</label>
          <span
            class="float-right copy-hover"
            style="font-weight: 600"
            title="Click to Copy"
            @click="doCopy(`${form.stock_no}`)"
            >{{ form.stock_no }}</span
          >
        </div>
      </template>
      <!-- inventory : END -->

      <!-- Issues -->
      <template v-if="parseInt(form.issues_count, 10) > 0">
        <hr />
        <h6 class="mt-1 text-bold-600">
          Issues
          <span class="badge badge-light-danger float-right">{{
            form.issues_count
          }}</span>
        </h6>

        <hr />
      </template>
      <!-- Issues -->
      <template v-for="item in getFailedItems">
        <div class="col-12 p-0 pb-50" :key="`failed_item_${item.id}`">
          <label class="d-block mb-25" style="font-weight: 600">
            {{ item.title }}
          </label>
          <small class="d-block text-muted"> {{ item.description }}</small>
          <small class="d-block text-danger mt-25" style="font-size: 1em">
            <strong>Reason:</strong> {{ item.reason }}
          </small>
          <h6 class="mt-1 text-bold-500" v-if="form.comment">Comment</h6>
          <small
            v-if="form.comment"
            class="col-12 p-0"
            style="font-size: 0.9em"
          >
            {{ form.comment }}
          </small>
        </div>

        <hr :key="`hr_failed_item_${item.id}`" />
      </template>
      <div
        v-if="entity_id && form.inventory.vin !== ''"
        style="position: absolute; left: 0; right: 0; bottom: 0px"
      >
        <button
          class="btn btn-lg btn-primary w-100 rounded-0"
          @click.prevent="downloadPDF"
        >
          <i class="bx bxs-printer mr-1"></i> Print
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "DVISnapShot",
  data() {
    return {
      isOpen: false,
      entity_id: null,
      form: {
        title: "",
        description: "",
        odometer: "",
        require_odometer: "",
        stock_no: "",
        status: "INCOMPLETE",
        assignee: null,
        issues_count: 0,
        inventory: {
          year: "",
          make: "",
          model: "",
          trim: "",
          vin: "",
        },
        items: [],
      },
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    getFailedItems() {
      return this.form.items.filter((item) => {
        if (item) {
          if (item.hasOwnProperty("status") && item.status) {
            return item.status.toUpperCase() === "FAIL";
          }
        }
      });
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
    getAssigneeName(assignee_id) {
      return this.$helpers.assigneeName(this.allUsers, assignee_id);
    },
    getFormattedOdometer(odometer) {
      return new Intl.NumberFormat("en-US").format(odometer);
    },
    getDVI(id) {
      const vm = this;
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/render/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (
              this.form.hasOwnProperty(key) &&
              data.hasOwnProperty(key) &&
              !["items", "inventory"].includes(key)
            ) {
              this.form[key] = data[key];
            }
          }

          if (data.hasOwnProperty("inventory")) {
            for (const key in this.form["inventory"]) {
              if (
                this.form["inventory"].hasOwnProperty(key) &&
                data["inventory"].hasOwnProperty(key)
              ) {
                this.form["inventory"][key] = data["inventory"][key];
              }
            }
          }

          this.form["items"] = data["items"];
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving DVI details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    downloadPDF() {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/pdf/dvi-complete/generate/${this.entity_id}`,
        responseType: "blob",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const disposition = response.headers["content-disposition"];

          let fileName = "DVI.pdf";

          if (disposition) {
            const match = disposition.match(/filename="?([^"]+)"?/);
            if (match && match[1]) {
              fileName = match[1];
            }
          }

          const blob = new Blob([response.data], {
            type: response.headers["content-type"] || "application/pdf",
          });

          const url = window.URL.createObjectURL(blob);
          window.open(url);
        })
        .catch(() => {
          console.log("There were some errors in retrieving the DVI");
        });
    },
    resetForm() {
      for (const key in this.form) {
        if (!["items", "inventory"].includes(key)) {
          this.form[key] = "";
        }
      }

      if (this.form.hasOwnProperty("inventory")) {
        for (const key in this.form["inventory"]) {
          this.form["inventory"][key] = "";
        }
      }

      this.form["items"] = [];
    },
  },
  async created() {
    const vm = this;
    await this.getAllUsers();
    Event.listen("change_selected_dvi_id_for_snapshot", (data) => {
      console.log(data);
      vm.resetForm();
      vm.$data.entity_id = data;
      vm.getDVI(vm.$data.entity_id);
      vm.$data.isOpen = true;
    });
  },
  beforeDestroy() {
    Event.vue.$off("change_selected_dvi_id_for_snapshot", null);
  },
};
</script>

