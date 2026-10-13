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
      <h4 class="text-uppercase mb-0">Inventory Information</h4>
      <hr />
      <div class="col-12 h6">
        Please click on <i class="bx bx-search-alt"></i> icon to view the
        snapshot of Inventory Information.
      </div>
    </div>
    <div
      class="customizer-content p-2 ps-container ps-theme-dark ps-active-y"
      v-show="entity_id"
      style="overflow-y: auto"
    >
      <h4 class="text-uppercase mb-0">Inventory Information</h4>
      <hr />
      <div class="col-12 p-0 pb-25">
        <label for="">Year</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.year}`)"
          >{{ form.year }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Make</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.make}`)"
          >{{ form.make }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Model</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.model}`)"
          >{{ form.model }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Trim</label>
        <span class="float-right" style="font-weight: 600">{{
          form.trim | truncate(20, "...")
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
      <div class="col-12 p-0 pb-25">
        <label for="">Stock Date</label>
        <span class="float-right" style="font-weight: 600">{{
          form.stock_date
        }}</span>
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">VIN</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.vin}`)"
          >{{ form.vin }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Status</label>
        <span class="float-right" style="font-weight: 600">{{
          form.status
        }}</span>
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Sub Status</label>
        <span class="float-right" style="font-weight: 600">{{
          form.sub_status
        }}</span>
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Odometer</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.odometer}`)"
          >{{ form.odometer }} {{ form.km_miles }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Selling Price</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.selling_price_numeric}`)"
          >{{ $helpers.moneyFormatter(form.selling_price_numeric) }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Location</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.location}`)"
          >{{ form.location }}</span
        >
      </div>
      <medium
        class="col-12 p-0 text-right d-block"
        v-if="form.page_url != null && form.status == 'Instock'"
      >
        <a :href="form.page_url" target="_blank">
          <i
            class="bx-link-external bx pr-1"
            style="vertical-align: middle"
          ></i>
          Visit Page
        </a>
      </medium>
      <hr />
      <h6 class="mt-1 text-bold-500" v-show="files.length > 0">Attachments</h6>
      <medium
        class="col-12 p-0 d-block"
        :key="each_file.id + `_snapshot_file`"
        v-for="each_file in files"
      >
        <a
          :href="parseAbsoluteOrRelativeUrl(each_file.file_url)"
          target="_blank"
        >
          <i
            class="bx-link-external bx pr-1"
            style="vertical-align: middle"
          ></i>
          {{ each_file.header }}
        </a>
      </medium>

      <hr v-show="files.length > 0" />
      <h6 class="mt-1 text-bold-500" v-show="all_notes.length > 0">
        Additional Notes
      </h6>
      <span
        :key="each_note.id + `_snapshot_note`"
        v-for="each_note in all_notes"
      >
        <div class="col-12 p-0 pb-25">
          <label
            class="d-block"
            style="font-weight: 600; letter-spacing: 1px"
            >{{ each_note.title | truncate(50, "...") }}</label
          >
        </div>

        <small
          class="col-12 p-0 d-block"
          v-if="each_note.description != null && each_note.description != ''"
          style="font-size: 0.9em"
        >
          {{ each_note.description }}
        </small>
        <medium class="col-12 p-0" v-if="each_note.attachment != null">
          <a
            :href="parseAbsoluteOrRelativeUrl(each_note.attachment)"
            target="_blank"
          >
            <i class="bx-link-alt bx"></i>
            {{ attachment_link(each_note.attachment) }}
          </a>
        </medium>
        <small class="col-12 p-0 d-block text-muted">
          <span class="font-weight-bold font-italic">
            {{ note_author(each_note.user_id) }}
          </span>
          <span style="float: right">
            <i
              style="vertical-align: middle; padding-right: 1em"
              class="bxs-time bx"
            ></i>
            {{ modified_data(each_note.updated_at) }}
          </span>
        </small>
        <hr />
      </span>
      <!-- Disclosure : START -->
      <h6 class="mt-1 text-bold-500">Disclosures</h6>
      <div class="col-12 p-0 pb-25">
        <label for="">Brand</label>
        <span class="float-right" style="font-weight: 600">{{
          disclosures.brand
        }}</span>
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Certified</label>
        <span class="float-right" style="font-weight: 600">{{
          disclosures.certified
        }}</span>
      </div>
      <label
        class="text-bold-500"
        v-if="
          disclosures.complete_disclosure != null &&
          disclosures.complete_disclosure != ''
        "
      >
        Complete Disclosure
      </label>
      <small
        class="col-12 p-0 d-block"
        v-if="
          disclosures.complete_disclosure != null &&
          disclosures.complete_disclosure != ''
        "
        style="font-size: 0.9em"
      >
        {{ disclosures.complete_disclosure }}
      </small>
      <hr />
      <!-- Disclosure : END -->
      <!-- Deals : START -->
      <h6 class="mt-1 text-bold-500">Deals</h6>
      <hr>
      <span :key="deal.id" v-show="hasCustomers(deal)" v-for="deal in deals">
        <div
          class="col-12 p-0 pb-25"
          :key="`customer${index}`"
          v-for="(customer, index) in deal.customers"
        >
          <label class="d-block" style="font-weight: 600; letter-spacing: 1px"
            >{{ customer["details"]["first_name"] }}
            {{ customer["details"]["last_name"] }} -
            {{
              customer["customer_type"] == "primary_customer"
                ? "Primary Customer"
                : "Co-Buyer"
            }}</label
          >
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Type</label>
          <span class="float-right" style="font-weight: 600">{{
            deal.type
          }}</span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Status</label>
          <span
            class="float-right"
            style="font-weight: 600"
            v-html="switchStatus(deal.status)"
          ></span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Date</label>
          <span class="float-right" style="font-weight: 600;">{{
            parseDate(deal.date)
          }}</span>
        </div>
        <hr />
      </span>
      <!-- Deals : END -->
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "InventorySnapShot",
  data() {
    return {
      deals: [],
      all_notes: [],
      files: [],
      isOpen: false,
      entity_id: null,
      disclosures: {
        complete_disclosure: "",
        brand: "",
        certified: "",
      },
      form: {
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "",
        sub_status: "",
        feature_listing: "0",
        vehicle_type: "",
        visibility: "1",
        title: "",
        year: "",
        make: "",
        model: "",
        trim: "",
        body_type: "",
        door: "",
        engine: "",
        litres: "",
        transmission: "",
        drivetrain: "",
        passengers: "",
        odometer: "",
        km_miles: "",
        fuel_type: "",
        fuel_city: "",
        fuel_hwy: "",
        exterior_color: "",
        interior_color: "",
        price: "",
        hide_price: "",
        special_price_on: "",
        special_price: "",
        show_stripe: "0",
        financing_on: "0",
        financing_special: "0",
        downpayment: "0",
        interest_rate: "0",
        term: "0",
        payment_frequency: "",
        location: "",
        page_url: "",
        selling_price_numeric: "",
      },
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
    note_author(author_id) {
      return this.$helpers.assigneeName(this.allUsers, author_id);
    },
    modified_data(updated_at) {
      return moment(updated_at).format("MM/DD/YYYY hh:mm:ss");
    },
    parseAbsoluteOrRelativeUrl(file_url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(
        window.global.cdn,
        file_url
      );
    },
    attachment_link(attachment) {
      if (attachment != null) {
        let attachment_array = attachment.split("/");
        return attachment_array[attachment_array.length - 1];
      } else {
        return "";
      }
    },
    parseDate(date) {
      if (moment(date).isValid()) {
        return moment(date).format("MM/DD/YYYY");
      } else {
        return "";
      }
    },
    isNonEmptyString(value) {
      try {
        if (typeof value === "string") {
          if (value.trim() !== "" && value !== "null") {
            return true;
          }
        }
        return false;
      } catch (error) {
        return false;
      }
    },
    booleanValueSwitchLabel(value) {
      return value == "1" ? "ON" : "OFF";
    },
    hasCustomers(deal) {
      return deal.hasOwnProperty("customers") && deal["customers"];
    },
    switchStatus(status) {
      switch (status) {
        case "Delivered":
          return `<span class='badge badge-light-success'>${status}</span>`;
          break;
        case "Open":
          return `<span class='badge badge-light-primary'>${status}</span>`;
          break;
        case "Lost":
          return `<span class='badge badge-light-danger'>${status}</span>`;
          break;

        default:
          return `<span class='badge badge-light-secondary'>${status}</span>`;
          break;
      }
    },
    resetForm() {
      this.deals = [];
      this.all_notes = [];
      this.files = [];
      for (const key in this.form) {
        this.form[key] = "";
      }
      this.form.vindisplay = this.form.visibility = "1";

      for (const key in this.disclosures) {
        this.disclosures[key] = "";
      }
    },
    async getInventory(id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/inventory/show/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          let stock_date = moment(data.stock_date);

          this.form.stock_date = stock_date.isValid()
            ? stock_date.format("MM/DD/YYYY")
            : moment(new Date()).format("MM/DD/YYYY");
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the inventory details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async getAllInventoryDeals(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/deals",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.deals = data.deals;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async getInventoryDisclosure(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/inventory/${inventory_id}/common/disclosures`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (Array.isArray(data)) {
            if (data.length > 0) {
              for (const key in data) {
                let element = data[key];
                if (
                  element.hasOwnProperty("question") &&
                  element.hasOwnProperty("answer")
                ) {
                  let form_key = element["question"];
                  form_key = form_key.replaceAll(" ", "_");
                  form_key = form_key.toLowerCase();

                  if (this.disclosures.hasOwnProperty(form_key)) {
                    this.disclosures[form_key] = element["answer"];
                  }
                }
              }
            }
          }
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async allNotes(inventory_id) {
      await axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/inventory/${inventory_id}/notes`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.all_notes = data.all_notes;
        })
        .catch((response) => {});
    },
    async allAttachments(inventory_id) {
      await axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/inventory/file/${inventory_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.files = data.files;
        })
        .catch((response) => {});
    },
  },
  async created() {
    const vm = this;
    await this.getAllUsers();
    Event.listen("change_selected_inventory_id_for_snapshot", (data) => {
      vm.resetForm();
      vm.$data.entity_id = data;
      vm.getInventory(vm.$data.entity_id);
      vm.allAttachments(vm.$data.entity_id);
      vm.allNotes(vm.$data.entity_id);
      vm.getInventoryDisclosure(vm.$data.entity_id);
      vm.getAllInventoryDeals(vm.$data.entity_id);
      vm.$data.isOpen = true;
    });
  },
  beforeDestroy() {
    Event.vue.$off("change_selected_inventory_id_for_snapshot", null);
  },
};
</script>
