<template>
  <div
    class="tab-pane"
    id="deal-delivery-checklist-panel"
    aria-labelledby="Delivery Checklist"
    role="tabpanel"
  >
    <section
      id="delivery-checklist-panel"
      v-show="vehicle_delivery_checklist !== null"
    >
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row" style="position: relative">
                            <span
                              style="
                                position: absolute;
                                right: -6em;
                                z-index: 999;
                              "
                            >
                              <i
                                class="bx bx-repost pr-1 bx-md"
                                title="Reset Delivery Checklist"
                                style="cursor: pointer"
                                @click="confirmResetAction"
                              ></i>
                              <i
                                class="bx bx-save pr-1 bx-md"
                                @click="
                                  edit.main_index = null;
                                  edit.index = null;
                                  saveDeliveryChecklistData();
                                "
                                title="Save Details"
                                style="cursor: pointer"
                              ></i>
                            </span>
                            <h5
                              class="col-12"
                              v-if="vehicle_delivery_checklist !== null"
                            >
                              <span>
                                {{ vehicle_delivery_checklist.title }}
                              </span>
                            </h5>
                            <div
                              class="col-12 p-1"
                              v-if="vehicle_delivery_checklist !== null"
                            >
                              <span>
                                {{ vehicle_delivery_checklist.description }}
                              </span>
                            </div>

                            <div
                              class="col-12 text-right"
                              v-if="created_at !== null"
                            >
                              <span class="pl-1" style="font-size: 0.8em"
                                >Created At : {{ created_at }} Last Updated :
                                {{ updated_at }}</span
                              >
                            </div>
                            <table
                              class="table col-12"
                              v-if="vehicle_delivery_checklist !== null"
                            >
                              <tbody>
                                <template
                                  v-for="(section,
                                  main_index) in vehicle_delivery_checklist.sections"
                                >
                                  <tr :key="section.name" class="thead-dark">
                                    <th scope="col" colspan="4">
                                      {{ section.name }}
                                    </th>
                                  </tr>

                                  <tr
                                    v-for="(item, index) in section.list"
                                    :key="`section_` + main_index + `_` + index"
                                  >
                                    <td class="p-50 text-center">
                                      <input
                                        type="checkbox"
                                        class="checkbox-input"
                                        v-model="
                                          vehicle_delivery_checklist[
                                            'sections'
                                          ][main_index]['list'][index]['value']
                                        "
                                        :true-value="'Yes'"
                                        :false-value="'No'"
                                        @change="saveDeliveryChecklistData()"
                                      />
                                    </td>
                                    <td class="p-50">{{ index + 1 }}</td>
                                    <td class="p-50">
                                      <h6 class="col-12">
                                        <span>{{ item.title }}</span>
                                      </h6>
                                      <div
                                        class="col-12"
                                        style="
                                          font-size: 0.8em;
                                          font-style: italic;
                                        "
                                      >
                                        <textarea
                                          class="form-control"
                                          placeholder="Additional Details..."
                                          v-if="
                                            edit.main_index == main_index &&
                                              edit.index == index
                                          "
                                          v-model="
                                            vehicle_delivery_checklist[
                                              'sections'
                                            ][main_index]['list'][index][
                                              'description'
                                            ]
                                          "
                                        ></textarea>
                                        <span v-else>{{
                                          item.description
                                        }}</span>
                                      </div>
                                    </td>
                                    <td
                                      class="p-50 text-center"
                                      style="width: 100px"
                                    >
                                      <i
                                        class="bx bx-save pr-1 bx-sm"
                                        v-if="
                                          edit.main_index == main_index &&
                                            edit.index == index
                                        "
                                        @click="
                                          edit.main_index = null;
                                          edit.index = null;
                                          saveDeliveryChecklistData();
                                        "
                                        title="Save Details"
                                        style="cursor: pointer"
                                      ></i>
                                      <i
                                        class="bx bxs-pencil pr-1 bx-sm"
                                        v-else
                                        @click="
                                          edit.main_index = main_index;
                                          edit.index = index;
                                        "
                                        title="Add Details"
                                        style="cursor: pointer"
                                      ></i>
                                    </td>
                                  </tr>
                                </template>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";

export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    time_zone: String,
    primary_customer_name: { required: false },
    inventory_details: { required: false },
  },
  name: "DealDeliveryChecklist",
  data() {
    return {
      vehicle_delivery_checklist_id: null,
      default_vehicle_delivery_checklist: null,
      vehicle_delivery_checklist: null,
      created_at: null,
      updated_at: null,
      edit: {
        main_index: null,
        index: null,
      },
    };
  },
  watch: {
    inventory_details: function(newVal, oldVal) {
      if (this.vehicle_delivery_checklist) {
        this.changeVehicleSectionNames();
        this.saveDeliveryChecklistData(false);
      }
    },
    primary_customer_name: function(newVal, oldVal) {
      if (this.vehicle_delivery_checklist) {
        this.changeVehicleSectionNames();
        this.saveDeliveryChecklistData(false);
      }
    },
    vehicle_delivery_checklist: function(newVal, oldVal) {
      this.changeVehicleSectionNames();
      let remaining_count = this.vehicle_delivery_checklist.sections.reduce(
        (count, section) => {
          let current_count = section.list.filter(
            (item) => item.value !== "Yes",
          ).length;
          return count + current_count;
        },
        0,
      );

      Event.fire("delivery_checklist_count", remaining_count);
    },
  },
  methods: {
    changeVehicleSectionNames() {
      if (typeof this.vehicle_delivery_checklist === "object") {
        for (const key in this.vehicle_delivery_checklist.sections) {
          if (
            this.vehicle_delivery_checklist.sections[key].name.includes(
              `CUSTOMER`,
            ) &&
            typeof this.primary_customer_name == `string`
          ) {
            this.vehicle_delivery_checklist.sections[
              key
            ].name = `CUSTOMER: ${this.primary_customer_name}`;
          } else if (
            this.vehicle_delivery_checklist.sections[key].name.includes(
              `VEHICLE`,
            ) &&
            typeof this.inventory_details == `string`
          ) {
            this.vehicle_delivery_checklist.sections[
              key
            ].name = `VEHICLE: ${this.inventory_details}`;
          }
        }
      }
    },
    confirmResetAction() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to reset the delivery checklist.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.vehicle_delivery_checklist = JSON.parse(
            JSON.stringify(vm.default_vehicle_delivery_checklist),
          );
          vm.saveDeliveryChecklistData();
        }
      });
    },
    async getDeliveryChecklist() {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/deals/${this.$route.params.id}/common/delivery-checklist`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.length > 0) {
            this.vehicle_delivery_checklist = JSON.parse(data[0].detail);
            this.vehicle_delivery_checklist_id = data[0].id;
            this.created_at = this.$helpers.changeUTCtoLocalDate(
              data[0].created_at.date,
              "MM/DD/YYYY hh:mm A",
              this.time_zone,
            );
            this.updated_at = this.$helpers.changeUTCtoLocalDate(
              data[0].updated_at.date,
              "MM/DD/YYYY hh:mm A",
              this.time_zone,
            );
          }
        })
        .catch(({ response }) => {
          this.vehicle_delivery_checklist = JSON.parse(
            JSON.stringify(this.default_vehicle_delivery_checklist),
          );
        });
    },
    async saveDeliveryChecklistData(show_toast = true) {
      const vm = this;
      let formData = new FormData();
      formData.append(
        "detail",
        JSON.stringify(this.vehicle_delivery_checklist),
      );

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.vehicle_delivery_checklist_id) &&
        this.vehicle_delivery_checklist_id != null;

      let success_message = update_request
        ? `Deal Delivery Checklist updated.`
        : `Deal Delivery Checklist added.`;

      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/delivery-checklist/update/${this.vehicle_delivery_checklist_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/delivery-checklist/create`;

      //message during request
      let request_message = update_request
        ? `Updating Deal Delivery Checklist ...`
        : `Adding new Deal Delivery Checklist ...`;

      const toast = new Toast(request_message);

      if (show_toast) {
        toast.fire();
      }

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          if (show_toast) {
            toast.success(data.success);
            toast.close();
          }

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            vm.getDeliveryChecklist();
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.vehicle_delivery_checklist_id) &&
            vm.vehicle_delivery_checklist_id != null;
          let error_message = update_request
            ? "There were errors while updating the delivery checklist."
            : "There were some errors while adding delivery checklist.";

          if (show_toast) {
            toast.fail(error_message);
            toast.close();
          }
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "deal_delivery_checklist",
        "",
      );
    },
    async getDeliveryChecklistConfigurations() {
      let request_url =
        window.location.origin + "/api/business/configurations/active/deal";
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "default_vehicle_delivery_checklist") {
              this.default_vehicle_delivery_checklist = JSON.parse(
                element.value,
              );
            }
          }
        }
      });
    },
  },
  async created() {
    await this.getDeliveryChecklistConfigurations();
    await this.getDeliveryChecklist();
  },
  beforeDestroy() {
    Event.vue.$off("delivery_checklist_count", null);
  },
};
</script>
<style lang="scss">
#deal-delivery-checklist-panel {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent > input {
    padding-left: 2.2em !important;
    line-height: 1.4;
    color: #777;
    background-color: #ffffff !important;
    background-clip: padding-box;
    border: 1px solid #dfe3e7;
    border-radius: 0.267rem;
    font-size: 1.4em;
  }

  .vdpComponent input {
    background: white;
    border: 0px;
  }
  .vdpComponent input:read-only {
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
  }

  .vdpComponent .vdpTimeUnit {
    min-width: 50px;
  }

  .notification-icon-small {
    font-size: 0.5em;
    height: 50px;
    width: 50px;
  }

  .vdpComponent .vdpClearInput {
    display: none !important;
  }
}
</style>
