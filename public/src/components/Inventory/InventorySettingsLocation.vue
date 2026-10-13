<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="locations-setting"
    aria-labelledby="stacked-pill-1"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                    >Locations</span
                  >
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <ul class="p-0 float-right d-flex">
            <li class="d-inline-block">
              <!-- <button type="reset" class="btn btn-light-secondary mr-1">Cancel</button> -->
              <!--                                                                <button type="submit" class="btn btn-primary">Save</button>-->
            </li>
            <li class="d-inline-block">
              <!-- Button trigger modal -->
              <button
                type="button"
                class="btn btn-outline-primary"
                data-toggle="modal"
                data-target="#locationModel"
              >
                + Add location
              </button>

              <!-- Modal -->
              <div
                class="modal fade"
                id="locationModel"
                tabindex="-1"
                role="dialog"
                aria-labelledby="locationModelLabel"
                aria-hidden="true"
              >
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">
                        {{ title }}
                      </h5>
                      <button
                        type="button"
                        id="locationModalClose"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="location_name"
                              >Nick Name (Used For Reference):
                            </label>
                            <input
                              type="text"
                              :disabled="action === 'Update'"
                              class="form-control"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('name'),
                              }"
                              @keyup="delete errors.name"
                              :title="errors.name"
                              v-model="form.name"
                              id="location_name"
                              placeholder="Name"
                            />
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="location_business_name"
                              >Business Name:
                            </label>
                            <input
                              type="text"
                              class="form-control"
                              :class="{
                                'is-invalid':
                                  errors.hasOwnProperty('business_name'),
                              }"
                              @keyup="delete errors.business_name"
                              :title="errors.business_name"
                              v-model="form.business_name"
                              id="location_business_name"
                              placeholder="Business Name"
                            />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="street">Street</label>
                          <input
                            type="text"
                            class="form-control"
                            id="street"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('street'),
                            }"
                            @keyup="delete errors.street"
                            :title="errors.street"
                            v-model="form.street"
                            placeholder="1234 Main St"
                          />
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label for="city">City</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('city'),
                              }"
                              @keyup="delete errors.city"
                              :title="errors.city"
                              v-model="form.city"
                              placeholder="City"
                              class="form-control"
                              id="city"
                            />
                          </div>
                          <div class="form-group col-md-3">
                            <label for="state">Province / State</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('state'),
                              }"
                              @keyup="delete errors.state"
                              :title="errors.state"
                              v-model="form.state"
                              placeholder="Province / State"
                              class="form-control"
                              id="state"
                            />
                          </div>
                          <div class="form-group col-md-3">
                            <label for="zipcode">Postal Code / ZIP</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('zipcode'),
                              }"
                              @keyup="delete errors.zipcode"
                              :title="errors.zipcode"
                              class="form-control"
                              v-model="form.zipcode"
                              placeholder="Postal Code / ZIP"
                              id="zipcode"
                            />
                          </div>
                          <div class="form-group col-md-3">
                            <label for="country">Country (CA or USA)</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('country'),
                              }"
                              @keyup="delete errors.country"
                              :title="errors.country"
                              v-model="form.country"
                              placeholder="Country"
                              class="form-control"
                              id="country"
                            />
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="phone">Phone</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('phone'),
                              }"
                              class="form-control"
                              placeholder="9XXXXXXXXXX"
                              v-model="form.phone"
                              id="phone"
                              @keyup="delete errors.phone"
                              :title="errors.phone"
                            />
                          </div>
                          <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input
                              type="text"
                              v-model="form.email"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('email'),
                              }"
                              placeholder="support@example.com"
                              class="form-control"
                              id="email"
                              @keyup="delete errors.email"
                              :title="errors.email"
                            />
                          </div>
                          <div class="form-group col-md-4">
                            <label for="fax">Fax</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('fax'),
                              }"
                              class="form-control"
                              placeholder="9XXXXXXXXXX"
                              v-model="form.fax"
                              id="fax"
                              @keyup="delete errors.fax"
                              :title="errors.fax"
                            />
                          </div>
                        </div>
                        <!-- tax information section : START -->
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="dealer_registration_number"
                              >Dealer Registration #</label
                            >
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'dealer_registration_number'
                                ),
                              }"
                              class="form-control"
                              placeholder="Dealer Registration"
                              v-model="form.dealer_registration_number"
                              id="dealer_registration_number"
                              @keyup="delete errors.dealer_registration_number"
                              :title="errors.dealer_registration_number"
                            />
                          </div>
                          <div class="form-group col-md-4">
                            <label for="tax_number">Tax #</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid':
                                  errors.hasOwnProperty('tax_number'),
                              }"
                              class="form-control"
                              placeholder="Tax Number"
                              v-model="form.tax_number"
                              id="tax_number"
                              @keyup="delete errors.tax_number"
                              :title="errors.tax_number"
                            />
                          </div>
                          <div class="form-group col-md-4">
                            <label for="rin_number">RIN #</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid':
                                  errors.hasOwnProperty('rin_number'),
                              }"
                              class="form-control"
                              placeholder="RIN Number"
                              v-model="form.rin_number"
                              id="rin_number"
                              @keyup="delete errors.rin_number"
                              :title="errors.rin_number"
                            />
                          </div>
                        </div>
                        <!-- tax infomration section : END -->
                        <!-- default setting : START -->
                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label for="default">Default</label>
                            <div
                              class="custom-control custom-switch form-control"
                              style="border: 0"
                            >
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="default_switch"
                                name="Default"
                                v-model="form.default"
                                :true-value="'1'"
                                :false-value="'0'"
                                checked
                              />
                              <label
                                class="custom-control-label"
                                for="default_switch"
                              >
                                <span class="switch-icon-left"> ON</span>
                                <span class="switch-icon-right">OFF</span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <!-- default setting : END -->
                        <hr />
                        <h6 class="w-100">Phaeton Settings</h6>
                        <!-- phaeton settings : START -->
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="show_on_website">Show On Website</label>
                            <div
                              class="custom-control custom-switch form-control"
                              style="border: 0"
                            >
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="show_on_website_switch"
                                name="show_on_website"
                                v-model="form.show_on_website"
                                :true-value="'1'"
                                :false-value="'0'"
                                checked
                              />
                              <label
                                class="custom-control-label"
                                for="show_on_website_switch"
                              >
                                <span class="switch-icon-left"> ON</span>
                                <span class="switch-icon-right">OFF</span>
                              </label>
                            </div>
                          </div>

                          <div class="form-group col-md-12">
                            <label for="address_bar">Address Bar Map URL</label>
                            <textarea
                              type="text"
                              :class="{
                                'is-invalid':
                                  errors.hasOwnProperty('address_bar'),
                              }"
                              class="form-control"
                              placeholder="Address Bar Map URL"
                              v-model="form.address_bar"
                              id="address_bar"
                              @keyup="delete errors.address_bar"
                              :title="errors.address_bar"
                            ></textarea>
                          </div>

                          <div class="form-group col-md-12">
                            <label for="address_map_url"
                              >Address Map Iframe URL
                            </label>
                            <textarea
                              type="text"
                              :class="{
                                'is-invalid':
                                  errors.hasOwnProperty('address_map_url'),
                              }"
                              class="form-control"
                              placeholder="Address Map Iframe URL"
                              v-model="form.address_map_url"
                              id="address_map_url"
                              @keyup="delete errors.address_map_url"
                              :title="errors.address_map_url"
                            ></textarea>
                          </div>
                        </div>
                        <!-- phaeton settings : END -->
                      </form>
                    </div>
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
                        @click.prevent="saveLocation"
                      >
                        {{ action }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="list-right-task-locations list-group ps p-0">
      <div class="content-wrapper mt-0 p-0">
        <div class="">
          <div class="">
            <table
              id="table-extended-locations"
              class="list-page-content-p mt-0 mb-0 w-100"
            >
              <thead>
                <tr class="bg-grey">
                  <th style="width: 20px"></th>
                  <th class="text-center">Name</th>
                  <th class="text-center">Street</th>
                  <th class="text-center">City</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="location in locations"
                  :key="`location` + location.id"
                  :class="location.default == '1' ? 'table-active' : ''"
                >
                  <td>
                    <p
                      :class="[location.status == '1' ? 'bg-green' : 'bg-red']"
                      class="rounded-circle round-box"
                    ></p>
                  </td>
                  <td class="text-center">
                    <span class="blue-color text-bold-600 title-size-font">
                      {{ location.name }}</span
                    >
                    <span
                      v-if="location.default == '1'"
                      class="ml-1 badge badge-light-success"
                      >Default</span
                    >
                  </td>
                  <td class="text-center">{{ location.street }}</td>
                  <td class="text-center">{{ location.city }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <span
                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        role="menu"
                      ></span>
                      <div
                        class="dropdown-menu dropdown-menu-right"
                        x-placement="bottom-end"
                        style="
                          position: absolute;
                          will-change: transform;
                          top: 0px;
                          left: 0px;
                          transform: translate3d(19px, 19px, 0px);
                        "
                      >
                        <a
                          v-show="location.status == '1'"
                          class="dropdown-item"
                          style="cursor: pointer"
                          @click.prevent="changeDefault(location.id)"
                          >Make Default</a
                        >
                        <a
                          class="dropdown-item"
                          style="cursor: pointer"
                          @click="editLocation(location.id)"
                        >
                          Update
                        </a>
                        <a
                          class="dropdown-item"
                          style="cursor: pointer"
                          @click.prevent="changeActivation(location.id, '1')"
                          >Enabled</a
                        >
                        <a
                          v-show="location.default != '1'"
                          class="dropdown-item"
                          style="cursor: pointer"
                          @click.prevent="changeActivation(location.id, '0')"
                          >Disabled</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col"></div>
          <div class="col"></div>
          <div class="col p-1">
            <div
              class="dataTables_paginate paging_simple_numbers text-right"
              id="table-extended-transactions_paginate"
            >
              <ul class="pagination">
                <li
                  style="cursor: pointer; padding-right: 0.2em"
                  class="paginate_button page-item previous"
                  @click="paginationWithUrl(pager.previous)"
                  :class="{ disabled: pager.previous == null }"
                  id="table-extended-transactions_previous"
                >
                  <a
                    aria-controls="table-extended-transactions"
                    data-dt-idx="0"
                    tabindex="0"
                    class="page-link"
                    >&lt;</a
                  >
                </li>
                <li
                  style="cursor: pointer; padding-right: 0.2em"
                  class="paginate_button page-item"
                  :class="{ active: n === page }"
                  v-for="n in pager.pageCount"
                  v-show="n >= page - 2 && n <= page + 2"
                  :key="'nav_id' + n"
                >
                  <a
                    aria-controls="table-extended-transactions"
                    @click="pagination(n)"
                    :data-dt-idx="n"
                    tabindex="0"
                    class="page-link"
                    >{{ n }}</a
                  >
                </li>
                <li
                  style="cursor: pointer; padding-right: 0.2em"
                  class="paginate_button page-item next"
                  :class="{ disabled: pager.next == null }"
                  id="table-extended-transactions_next"
                >
                  <a
                    aria-controls="table-extended-transactions"
                    @click="paginationWithUrl(pager.next)"
                    data-dt-idx="3"
                    tabindex="0"
                    class="page-link"
                    >&gt;</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";

export default {
  name: "InventorySettingsLocation",
  data() {
    return {
      form: {
        name: "",
        business_name: "",
        street: "",
        city: "",
        state: "",
        zipcode: "",
        country: "",
        phone: "",
        email: "",
        fax: "",
        dealer_registration_number: "",
        tax_number: "",
        rin_number: "",
        default: "1",
        show_on_website: "1",
        address_bar: "",
        address_map_url: "",
      },
      location_id: null,
      title: "Add New Location",
      action: "Create",
      errors: [],
      locations: [],
      pager: {},

      page: 1,
    };
  },
  methods: {
    saveLocation() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request = !isNaN(this.location_id) && this.location_id != null;

      request_url = update_request
        ? `${request_url}/api/business/location/update/${this.location_id}`
        : `${request_url}/api/business/location/create`;

      let request_message = update_request
        ? `Updating location details ...`
        : `Adding new location details ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.resetForm();
          document.getElementById("locationModalClose").click();

          toast.success(data.success);
          toast.close();

          vm.pagination(1);
        })
        .catch((error) => {
          vm.$data.errors = error.response.data;

          let error_message = `There were some errors while submitting form.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    editLocation(id) {
      const vm = this;
      let url = `${window.location.origin}/api/business/location/show/${id}`;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.errors = [];
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          this.location_id = data.id;
          this.action = "Update";
          this.title = "Update Location";

          jQuery("#locationModel").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching location.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    resetForm() {
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          this.form[key] = "";
        }
      }

      this.form.default = "1";
      this.form.show_on_website = "1";

      this.action = "Create";
      this.title = "Add New Location";
      this.errors = [];
    },
    locationsListing() {
      let request_url = window.location.origin + "/api/business/location";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        this.locations = data.location_listing;
        this.pager = data.pager;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
      });
    },
    pagination(n) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/location?page=`;
      url += n;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.locations = response.data.location_listing;
        this.pager = response.data.pager;
        this.page = this.pager.currentPage;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    paginationWithUrl(url) {
      const vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.locations = response.data.location_listing;
        this.pager = response.data.pager;
        this.page = this.pager.currentPage;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    changeActivation(id, activation) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/location/active/`;
      url += id;
      let formData = new FormData();
      formData.append("status", activation);

      let message = "Changing status for location...";
      const toast = new Toast(message);
      toast.fire();
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Status changed for location.`;
          toast.success(success_message);
          toast.close();

          this.pagination(this.page);
        })
        .catch(({ error }) => {
          toast.success(`There was error in changing the status of location.`);
          toast.close();
        });
    },
    changeDefault(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/location/default/`;
      url += id;
      let formData = new FormData();
      formData.append("default", 1);

      let message = "Changing default location...";
      const toast = new Toast(message);
      toast.fire();
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Default location changed.`;
          toast.success(success_message);
          toast.close();

          this.pagination(this.page);
        })
        .catch(({ error }) => {
          toast.success(`There was error in changing the default location.`);
          toast.close();
        });
    },
    deleteLocation(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/location/delete/`;
      url += id;

      let message = "Deleting location...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Location Has Been Deleted.`;
          toast.success(success_message);
          toast.close();

          if (this.page == 1) {
            this.pagination(this.page);
          } else if (this.page > 1) {
            if (this.locations.length == 1) {
              this.pagination(this.page - 1);
            } else {
              this.pagination(this.page);
            }
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting location`;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  mounted() {
    this.locationsListing();

    const vm = this;
    jQuery("#locationModel").on("hidden.bs.modal", function () {
      vm.resetForm();
    });
  },
  beforeDestroy() {
    console.log("Destroy called");
  },
};
</script>
