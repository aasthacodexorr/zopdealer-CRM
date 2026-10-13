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
      <h4 class="text-uppercase mb-0">Customer Information</h4>
      <hr />
      <div class="col-12 h6">
        Please click on <i class="bx bx-search-alt"></i> icon to view the
        snapshot of Customer Information.
      </div>
    </div>
    <div
      class="customizer-content p-2 ps-container ps-theme-dark ps-active-y"
      v-show="entity_id"
      style="overflow-y: auto"
    >
      <h4 class="text-uppercase mb-0">Customer Information</h4>
      <hr />
      <h6 class="mt-1 text-bold-500">Personal Information</h6>
      <div class="col-12 p-0 pb-25">
        <label for="">Full Name</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.first_name} ${form.last_name}`)"
          >{{ form.first_name }} {{ form.last_name }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Phone Number</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.phone}`)"
          >{{ formatPhoneNumber }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Mobile Number</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.mobile}`)"
          >{{ formatMobileNumber }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Email</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.email}`)"
          >{{ form.email }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Company Name</label>
        <span
          class="float-right copy-hover"
          title="Click to Copy"
          @click="doCopy(`${form.company_name}`)"
          style="font-weight: 600"
          >{{ form.company_name | truncate(20, "...") }}</span
        >
      </div>
      <hr />
      <h6 class="mt-1 text-bold-500">Address</h6>
      <div class="col-12 p-0 pb-25">
        <span class="d-block" style="font-weight: 600">{{
          completeAddress
        }}</span>
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Assignee</label>
        <span class="float-right" style="font-weight: 600">{{
          assigneeName
        }}</span>
      </div>
      <hr />
      <h6 class="mt-1 text-bold-500">Additional Notes</h6>
      <small class="col-12 p-0" style="font-size: 0.9em">
        {{ form.additional_information }}
      </small>
      <hr />
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
      <h6 class="mt-1 text-bold-500">Deals</h6>

      <span
        :key="`customer_snap_shot_deal` + deal.id"
        v-show="hasInventories(deal)"
        v-for="deal in deals"
      >
        <span v-if="hasInventoryDetails(deal.inventories)">
          <hr />
          <div class="col-12 p-0 pb-25">
            <label class="d-block" style="font-weight: 600; letter-spacing: 1px"
              >{{ deal.inventories[0]["details"]["year"] }}
              {{ deal.inventories[0]["details"]["make"] }}
              {{ deal.inventories[0]["details"]["model"] }}</label
            >
          </div>

          <div class="col-12 p-0 pb-25">
            <label for="">Stock No</label>
            <span
              class="float-right copy-hover"
              title="Click to Copy"
              @click="
                doCopy(`${deal['inventories'][0]['details']['stock_no']}`)
              "
              style="font-weight: 600"
              >{{ deal.inventories[0]["details"]["stock_no"] }}</span
            >
          </div>

          <div class="col-12 p-0 pb-25">
            <label for="">VIN</label>
            <span
              class="float-right copy-hover"
              title="Click to Copy"
              @click="doCopy(`${deal['inventories'][0]['details']['vin']}`)"
              style="font-weight: 600"
              >{{ deal.inventories[0]["details"]["vin"] }}</span
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
            <span class="float-right" style="font-weight: 600">{{
              parseDate(deal.date)
            }}</span>
          </div>
          <hr />
        </span>
      </span>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "CustomerSnapShot",
  data() {
    return {
      deals: [],
      all_notes: [],
      isOpen: false,
      entity_id: null,
      form: {
        first_name: "",
        last_name: "",
        customer_type: 0,
        company_name: "",
        rin: "",
        tax_number: "",
        street_address: "",
        apt: "",
        city: "",
        province: "",
        postal_code: "",
        country: "",
        phone: "",
        fax: "",
        mobile: "",
        email: "",
        driver_licence: "",
        rin_or_dl_flag: 1,
        driver_licence_expiry_date: "",
        date_of_birth: "",
        mvda: "",
        year_end: "",
        sales_person_registration: "",
        additional_information: "",
        assignee: "",
      },
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    formatPhoneNumber() {
      return this.$helpers.formatPhoneNumber(`${this.form.phone}`);
    },
    formatMobileNumber() {
      return this.$helpers.formatPhoneNumber(`${this.form.mobile}`);
    },
    completeAddress() {
      let address = ``;
      if (this.isNonEmptyString(this.form.street_address)) {
        address = `${this.form.apt} ${this.form.street_address}`;
      }

      if (this.isNonEmptyString(this.form.city)) {
        address = `${address}, ${this.form.city}`;
      }

      if (this.isNonEmptyString(this.form.province)) {
        address = `${address}, ${this.form.province}`;
      }

      if (this.isNonEmptyString(this.form.postal_code)) {
        address = `${address}, ${this.form.postal_code}`;
      }

      return address;
    },
    assigneeName() {
      return this.$helpers.assigneeName(this.allUsers, this.form.assignee);
    },
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
      for (const key in this.form) {
        this.form[key] = "";
      }

      this.form.rin_or_dl_flag = 1;
      this.form.customer_type = 0;
    },
    async getCustomer(id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/customers/show/${id}`,
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
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the customer details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async getAllCustomerDeals(customer_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/customers/" +
          customer_id +
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
    hasInventories(deal) {
      return deal.hasOwnProperty("inventories") && deal["inventories"];
    },
    hasInventoryDetails(inventory) {
      try {
        if (typeof inventory === "undefined") {
          return false;
        }

        if (inventory.hasOwnProperty("0")) {
          if (inventory[0].hasOwnProperty("details")) {
            return inventory[0]["details"].hasOwnProperty("year");
          }
        }

        return false;
      } catch (error) {
        return false;
      }
    },
    async allNotes(customer_id) {
      await axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/customers/${customer_id}/notes`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.all_notes = data.all_notes;
        })
        .catch((response) => {});
    },
  },
  async created() {
    const vm = this;
    await this.getAllUsers();
    Event.listen("change_selected_customer_id_for_snapshot", (data) => {
      vm.resetForm();
      vm.$data.entity_id = data;
      vm.getCustomer(vm.$data.entity_id);
      vm.allNotes(vm.$data.entity_id);
      vm.getAllCustomerDeals(vm.$data.entity_id);
      vm.$data.isOpen = true;
    });
  },
  beforeDestroy() {
    Event.vue.$off("change_selected_customer_id_for_snapshot", null);
  },
};
</script>
