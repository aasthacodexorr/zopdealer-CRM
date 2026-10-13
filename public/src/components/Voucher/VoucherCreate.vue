<template>
  <span>
    <a
      class="btn btn-primary text-white w-100 text-center"
      type="button"
      data-toggle="modal"
      data-target="#voucherModal"
    >
      <i class="bx bxs-add-to-queue mr-1"></i> Create Voucher
    </a>
    <!-- Create A Voucher : START  -->
    <div
      class="modal fade"
      id="voucherModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Add Another Voucher"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="voucherHeader">Create New Voucher</h5>
            <button
              type="button"
              id="voucherClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <!-- Date : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="expiry_date">Expiry Date : </label>
                <fieldset
                  class="has-icon-left"
                  style="position: relative"
                  id="expiry_date"
                >
                  <date-picker
                    v-model="form.expiry_date"
                    :format="'MM/DD/YYYY'"
                    :value-type="'MM/DD/YYYY'"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('expiry_date'),
                    }"
                    @change="delete errors.expiry_date"
                    :title="errors.expiry_date"
                  ></date-picker>
                </fieldset>
              </div>
            </div>
            <!-- Date : END -->

            <!-- Template : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="voucher_template_id">Voucher Template</label>
                <select
                  class="custom-select form-control mr-1"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('voucher_template_id'),
                  }"
                  @keyup="delete errors.voucher_template_id"
                  :title="errors.voucher_template_id"
                  v-model="form.voucher_template_id"
                  @change="delete errors.voucher_template_id"
                  placeholder="Voucher Template"
                  id="voucher_template_id"
                >
                  <option value="" selected>-- Select Template --</option>
                  <option
                    :value="voucher_template.id"
                    :key="`voucher_template_${voucher_template.id}`"
                    v-for="voucher_template in voucher_template_listing"
                  >
                    {{ voucher_template.title }}
                  </option>
                </select>
              </div>
            </div>
            <!-- Template : END -->

            <!-- Deal : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="costs">Search Deal : </label>
                <v-select
                  placeholder="Select Deal"
                  label="label"
                  :options="deals"
                  :reduce="(search_index) => search_index.id"
                  v-model="form.module_id"
                  :title="errors.module_id"
                  @change="delete errors.module_id"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('module_id'),
                  }"
                  clearable
                ></v-select>
              </div>
            </div>
            <!-- Deal : END -->
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="save()"
            >
              Save
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Create A Voucher : END  -->
  </span>
</template>
<script>
import vSelect from "vue-select";
import { mapGetters, mapActions } from "vuex";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import Toast from "../../library/Toast";

export default {
  name: "VoucherCreate",
  data() {
    return {
      deals: [],
      voucher_template_listing: [],
      form: {
        expiry_date: null,
        voucher_template_id: "",
        module_id: null,
        module: "DEAL",
      },
      errors: [],
    };
  },
  components: {
    "v-select": vSelect,
    DatePicker,
  },
  watch: {
    "form.voucher_template_id": function (newVal, oldVal) {
      if (newVal !== oldVal) {
        if (newVal) {
          let voucher_template = this.voucher_template_listing.filter(
            (voucher_template) => voucher_template.id == newVal
          );
          if (voucher_template.length > 0) {
            let calculated_expiry_date = moment(
              voucher_template[0].calculated_expiry_date
            );
            this.form.expiry_date = calculated_expiry_date.isValid()
              ? calculated_expiry_date.format("MM/DD/YYYY")
              : null;
          }
        }
      }
    },
  },
  methods: {
    ...mapActions("voucherView", ["commitOnlyEntities"]),
    resetForm() {
      this.form.expiry_date = this.form.module_id = null;
      this.form.voucher_template_id = "";
      this.form.module = "DEAL";
      this.errors = [];
    },
    async save() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      let request_url = window.location.origin + "/api/business/vouchers/clone";
      let message = "Saving Voucher...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.errors = [];
          this.resetForm();
          toast.success(data.success);
          toast.close();
          // document.getElementById("paymentClose").click();
          this.commitOnlyEntities();
          // Event.fire("reload_payments", null);
        })
        .catch((error) => {
          let error_message = "There were errors in saving voucher.";
          toast.fail(error_message);
          toast.close();
          vm.$data.errors = error.response.data;
        });

      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "voucher",
        "voucher_create",
        ""
      );
    },
    async voucherTemplateListing() {
      let vm = this;
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/voucher-templates?paginate=false",
      }).then((response) => {
        vm.$data.voucher_template_listing =
          response.data.voucher_template_listing;
      });
    },
    async createIndexForDeals() {
      if (this.deals.length > 0) {
        for (var index in this.deals) {
          // this.deals[index].label = "Hello World";

          let label =
            this.deals[index].id != "" && this.deals[index].id != null
              ? `< D-${this.deals[index].id} > `
              : "";

          label +=
            this.deals[index].first_name != "" &&
            this.deals[index].first_name != null
              ? `${this.deals[index].first_name} `
              : "";

          label +=
            this.deals[index].last_name != "" &&
            this.deals[index].last_name != null
              ? `${this.deals[index].last_name} `
              : "";

          label +=
            this.deals[index].phone != "" && this.deals[index].phone != null
              ? `< ${this.deals[index].phone} > `
              : "";

          label +=
            this.deals[index].mobile != "" && this.deals[index].mobile != null
              ? `< ${this.deals[index].mobile} > `
              : "";

          label +=
            this.deals[index].email != "" && this.deals[index].email != null
              ? `< ${this.deals[index].email} >`
              : "";

          this.deals[index].label = label;
        }
      }
      return this.deals;
    },
    async getDeals() {
      const vm = this;
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/deals?paginate=false",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.deals = data.deal_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
  },
  async created() {
    await this.getDeals();
    await this.createIndexForDeals();
    await this.voucherTemplateListing();
  },
  async mounted() {
    const vm = this;
    jQuery("#voucherModal").on("hidden.bs.modal", function () {
      vm.resetForm();
    });
  },
};
</script>
<style lang="scss">
#voucherModal .vdpComponent {
  width: 100%;
}
#voucherModal .vdpComponent input {
  display: block;
  width: 100%;
  height: calc(1.4em + 0.94rem + 3.7px);
  padding: 0.47rem 0.8rem;
  padding-right: 0.8rem;
  padding-left: 2.2em !important;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.small {
  width: 300px;
}

#voucherModal .vdpComponent.is-invalid input {
  border-color: #ff5b5c;
}
</style>
