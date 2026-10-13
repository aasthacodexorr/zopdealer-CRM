<template>
  <div
    class="tab-pane active"
    id="lead-view"
    aria-labelledby="lead-view"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-view-main">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-8">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">
                  Lead Details
                  <span style="float: right; font-size: 0.8em"
                    ><i
                      class="bxs-calendar bx text-secondary lead-user-icon"
                      style="color: #6497b1 !important"
                    ></i
                    >{{ createdAt }}</span
                  >
                </h4>
                <hr />
                <div class="row">
                  <span class="col-12 col-md-4 mb-1"
                    ><i
                      class="bxs-user-rectangle bx text-info lead-user-icon"
                    ></i>
                    {{ leadName }}
                  </span>
                  <span class="col-12 col-md-4 mb-1" v-show="phoneFormat != ''"
                    ><i
                      class="bx-phone-call bx text-success lead-user-icon"
                    ></i>
                    {{ phoneFormat }}</span
                  >
                  <span class="col-12 col-md-4 mb-1" v-show="form.email != null"
                    ><i class="bxs-inbox bx text-secondary lead-user-icon"></i>
                    {{ form.email }}</span
                  >
                </div>
                <hr />
                <div class="row">
                  <div class="col-12 col-md-4 mb-1">
                    <span
                      ><i
                        class="bxs-detail bx text-secondary lead-user-icon text-capitalize"
                      ></i>
                      {{ this.form.source }}
                    </span>
                  </div>
                  <div class="col-12 col-md-4 mb-1">
                    <span
                      ><i
                        class="bx-bookmarks bx text-primary lead-user-icon text-uppercase"
                      ></i>
                      {{ this.form.status }}
                    </span>
                  </div>
                  <div class="col-12 col-md-4 mb-1">
                    <div
                      class="chip"
                      style="float: right"
                      :class="[
                        form.assignee == null ? 'chip-warning' : 'chip-primary',
                      ]"
                    >
                      <div class="chip-body">
                        <i
                          style="font-size: 2em"
                          class="bx-user-circle bx lead-user-icon"
                        ></i>
                        <span class="chip-text">{{ assigneeName }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="divider divider-dashed">
                  <div class="divider-text">More Details</div>
                </div> -->
                <div class="card-header border-line-bottom-title">
                  <h5 class="card-title">More Details</h5>
                </div>
                <div class="row">
                  <template v-for="(each_data, index) in form.other_data">
                    <div
                      class="col-12 col-md-4"
                      v-if="
                        each_data != null &&
                        each_data != '' &&
                        index !== `price_advisory`
                      "
                      :key="'other_data_' + index"
                    >
                      <div
                        class="font-small-3 col-12 text-capitalize text-bold-600 font-small-3 m-1 border-bottom p-0"
                      >
                        {{ formatHeader(index) }}
                      </div>
                      <div class="col-12">{{ each_data }}</div>
                    </div>
                    <div
                      class="col-12"
                      :key="'price_advisory_' + index"
                      v-else-if="index === `price_advisory`"
                    >
                      <div class="w-100"></div>
                      <div class="card-header border-line-bottom-title mt-1">
                        <h5 class="card-title">
                          {{ formatHeader(index) }}
                        </h5>
                      </div>
                      <div
                        class="col-xs-12 col-md-5 offset-md-3"
                        v-html="each_data"
                      ></div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-xs-12 col-md-4 p-1">
          <div class="card-group">
            <div class="card" v-show="website.vin != null && website.vin != ''">
              <div class="card-content">
                <router-link
                  tag="img"
                  style="cursor: pointer"
                  :src="website.placeholder"
                  :to="{
                    name: 'inventory-update',
                    params: { id: form.inventory_id },
                  }"
                  class="card-img-top img-fluid"
                  exact
                >
                </router-link>
                <div class="card-body">
                  <h4 class="card-title">
                    {{ website.year }} {{ website.make }} {{ website.model }}
                    {{ website.trim }}
                  </h4>
                  <div class="row">
                    <div class="col-8 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >VIN</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.vin"
                      ></h6>
                    </div>
                    <div class="col-4 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Stock No</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.stock_no"
                      ></h6>
                    </div>
                    <div class="col-3 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Year</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.year"
                      ></h6>
                    </div>
                    <div class="col-4 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Make</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.make"
                      ></h6>
                    </div>
                    <div class="col-4 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Model</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.model"
                      ></h6>
                    </div>
                    <div class="col-6 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Trim</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.trim"
                      ></h6>
                    </div>
                    <div class="col-6 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Odometer</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                      >
                        {{ website.odometer }} {{ website.km_miles }}
                      </h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Toast from "../../../../library/Toast";

export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    time_zone: String,
  },
  name: "LeadView",
  data() {
    return {
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
      form: {
        first_name: "",
        last_name: "",
        phone: "",
        email: "",
        inventory_id: "",
        stock_no: "",
        source: "",
        status: "",
        other_data: "",
        assignee: "",
        created_at: "",
        updated_at: "",
      },
    };
  },
  computed: {
    leadName() {
      return this.form.last_name
        ? this.form.first_name + " " + this.form.last_name
        : this.form.first_name;
    },
    phoneFormat() {
      var x = this.form.phone
        .replace(/\D/g, "")
        .match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      return !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
    },
    assigneeName() {
     return this.$helpers.assigneeName(this.allUsers, this.form.assignee);
    },
    createdAt() {
      return this.$helpers.changeUTCtoLocalDate(
        this.form.created_at,
        "MM/DD/YYYY hh:mm:ss A",
        this.time_zone
      );
    },
  },
  methods: {
    formatHeader(text) {
      return text.replace(/_/g, " ");
    },
    getPlaceholder() {
      axios({
        method: "get",
        url: window.location.origin + "/api/website/image/placeholder/",
      }).then(({ data }) => {
        this.website.placeholder = data.image_url;
        this.website.placeholder = this.$helpers.parseAbsoluteOrRelativeUrl(
          window.global.cdn,
          this.website.placeholder
        );
      });
    },
    getInventory(inventory_id) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory/show/" +
          inventory_id,
      }).then(({ data }) => {
        this.website.vin = data.vin;
        this.website.year = data.year;
        this.website.make = data.make;
        this.website.model = data.model;
        this.website.trim = data.trim;
        this.website.odometer = data.odometer;
        this.website.km_miles = data.km_miles;
        this.website.stock_no = data.stock_no;
      });
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
    getImage(inventory_id) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory/images/" +
          inventory_id,
      }).then(({ data }) => {
        if (data.hasOwnProperty("gallery")) {
          if (count(data["gallery"] > 0)) {
            this.website.placeholder = data["gallery"][0]["image_url"];

            this.website.placeholder = this.$helpers.parseAbsoluteOrRelativeUrl(
              window.global.cdn,
              this.website.placeholder
            );
          }
        }
      });
    },
    getLeadResponse(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/leads/responses/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.form = data;
          this.form.other_data = JSON.parse(data["other_data"]);
          if (data["stock_no"] != "" && data["stock_no"] != null) {
            this.findByStockNumber(data["stock_no"]);
            //this.getImage(data["inventory_id"]);
          }

          Event.fire("get_lead_email", vm.form.email);
          Event.fire("get_lead_phone", vm.form.phone);
          Event.fire("get_lead_converted_flag", data["is_converted"]);
          Event.fire("get_lead_name", vm.leadName);
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the lead";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
  mounted() {
    this.getPlaceholder();
    this.getLeadResponse(this.id);
  },
  beforeDestroy() {
    Event.vue.$off("save_complete", null);
    Event.vue.$off("save_lead", null);
    Event.vue.$off("get_lead_email", null);
    Event.vue.$off("get_lead_phone", null);
    Event.vue.$off("get_lead_name", null);
    Event.vue.$off("get_lead_converted_flag", null);
  },
};
</script>
<style scoped>
.lead-user-icon {
  font-size: 1.4em;
  vertical-align: bottom;
  padding-right: 1em;
}
</style>
