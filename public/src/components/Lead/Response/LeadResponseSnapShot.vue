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
      <h4 class="text-uppercase mb-0">Lead Information</h4>
      <hr />
      <div class="col-12 h6">
        Please click on <i class="bx bx-search-alt"></i> icon to view the
        snapshot of Lead Information.
      </div>
    </div>
    <div
      class="customizer-content p-2 ps-container ps-theme-dark ps-active-y"
      v-show="entity_id"
      style="overflow-y: auto"
    >
      <h4 class="text-uppercase mb-0">Lead Information</h4>
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
        <label for="">Source</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.source}`)"
          >{{ form.source }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Status</label>
        <span
          class="float-right copy-hover"
          style="font-weight: 600"
          title="Click to Copy"
          @click="doCopy(`${form.status}`)"
          >{{ form.status }}</span
        >
      </div>
      <div class="col-12 p-0 pb-25">
        <label for="">Assignee</label>
        <span class="float-right" style="font-weight: 600">{{
          assigneeName
        }}</span>
      </div>
      <hr />
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
      <span v-show="website.stock_no !== ''">
        <h6 class="mt-1 text-bold-500">Inventory Details</h6>
        <div class="col-12 p-0 pb-25">
          <label for="">Year</label>
          <span class="float-right" style="font-weight: 600">{{
            website.year
          }}</span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Make</label>
          <span class="float-right" style="font-weight: 600">{{
            website.make
          }}</span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Model</label>
          <span class="float-right" style="font-weight: 600">{{
            website.model
          }}</span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Trim</label>
          <span class="float-right" style="font-weight: 600">{{
            website.trim
          }}</span>
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Stock No</label>
          <span
            class="float-right copy-hover"
            style="font-weight: 600"
            title="Click to Copy"
            @click="doCopy(`${website.stock_no}`)"
            >{{ website.stock_no }}</span
          >
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">VIN</label>
          <span
            class="float-right copy-hover"
            style="font-weight: 600"
            title="Click to Copy"
            @click="doCopy(`${website.vin}`)"
            >{{ website.vin }}</span
          >
        </div>
        <div class="col-12 p-0 pb-25">
          <label for="">Odometer</label>
          <span class="float-right" style="font-weight: 600"
            >{{ website.odometer }} {{ website.km_miles }}</span
          >
        </div>
      </span>
    </div>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "LeadResponseSnapShot",
  data() {
    return {
      all_notes: [],
      isOpen: false,
      entity_id: null,
      form: {
        first_name: "",
        last_name: "",
        phone: "",
        email: "",
        inventory_id: "",
        stock_no: "",
        source: "",
        status: "",
        assignee: "",
        created_at: "",
        updated_at: "",
      },
      website: {
        placeholder: "",
        vin: "",
        year: "",
        make: "",
        model: "",
        trim: "",
        stock_no: "",
        odometer: "",
        km_miles: "",
      },
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    formatPhoneNumber() {
      return this.$helpers.formatPhoneNumber(`${this.form.phone}`);
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
      this.all_notes = [];
      for (const key in this.form) {
        this.form[key] = "";
      }
      for (const key in this.website) {
        this.website[key] = "";
      }
    },
    findByStockNumber(stock_no) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory?zm_stock_no[]=" +
          stock_no,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("inventory")) {
            if (data["inventory"].hasOwnProperty("0")) {
              this.website.vin = data["inventory"]["0"].vin;
              this.website.year = data["inventory"]["0"].year;
              this.website.make = data["inventory"]["0"].make;
              this.website.model = data["inventory"]["0"].model;
              this.website.trim = data["inventory"]["0"].trim;
              this.website.odometer = data["inventory"]["0"].odometer;
              this.website.km_miles = data["inventory"]["0"].km_miles;
              this.website.stock_no = data["inventory"]["0"].stock_no;
              if (data["inventory"]["0"].image_urls != null) {
                this.website.placeholder =
                  data["inventory"]["0"].image_urls.split(";")[0];

                this.website.placeholder =
                  this.$helpers.parseAbsoluteOrRelativeUrl(
                    window.global.cdn,
                    this.website.placeholder
                  );
              }
            }
          }
        })
        .catch(() => {});
    },
    async getLeadResponse(id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/leads/responses/show/${id}`,
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

          if (data["stock_no"] != "" && data["stock_no"] != null) {
            vm.findByStockNumber(data["stock_no"]);
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the lead details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async allNotes(lead_response_id) {
      await axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/leads/responses/${lead_response_id}/notes`,
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
    Event.listen("change_selected_lead_response_id_for_snapshot", (data) => {
      vm.resetForm();
      vm.$data.entity_id = data;
      vm.getLeadResponse(vm.$data.entity_id);
      vm.allNotes(vm.$data.entity_id);
      vm.$data.isOpen = true;
    });
  },
  beforeDestroy() {
    Event.vue.$off("change_selected_lead_response_id_for_snapshot", null);
  },
};
</script>
