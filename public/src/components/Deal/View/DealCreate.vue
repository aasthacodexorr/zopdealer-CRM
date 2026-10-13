<template>
  <div
    class="tab-pane active"
    id="deal-create-panel"
    aria-labelledby="Deal Create or Update"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="deal-create-section">
      <div class="row match-height">
        <div class="col-12 col-xs-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">
                  {{ tabTitle }}
                </h4>
                <!-- Details Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Details</h5>
                  </div>
                  <div class="row">
                    <div v-show="!updateView" class="col-xs-12 col-md-12 mb-50">
                      <router-link
                        tag="a"
                        title="Quick Deal Import"
                        :to="{ name: 'deal-import' }"
                        exact
                      >
                        <i
                          class="bx bx-link-external mr-50"
                          style="vertical-align: middle"
                        ></i>
                        Import multiple deals manually or from DealerTrack PDF
                      </router-link>
                    </div>

                    <!-- date : START -->
                    <div class="col-md-4 col-xs-12">
                      <div class="form-group">
                        <label for="date">Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            placeholder="Date"
                            v-model="form.date"
                            :clearable="false"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            id="date"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('date'),
                            }"
                            @change="delete errors.date"
                            :title="errors.date"
                          >
                          </date-picker>
                        </fieldset>
                        <small
                          id="date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.date }}</small
                        >
                      </div>
                    </div>
                    <!-- date : END -->

                    <!-- type : START -->
                    <div
                      class="col-xs-12 col-md-4"
                      v-show="parseInt(this.form.retail_or_wholesale_flag) == 0"
                    >
                      <div class="form-group">
                        <label for="type">Deal Type</label>
                        <select
                          id="type"
                          class="custom-select"
                          v-model="form.type"
                          name="type"
                        >
                          <option value="Cash">Cash</option>
                          <option value="Financed">Financed</option>
                        </select>
                        <small
                          id="type-error"
                          class="form-text invalid-feedback"
                          >{{ errors.type }}</small
                        >
                      </div>
                    </div>
                    <!-- type : END -->

                    <!-- status : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="status">Deal Status</label>
                        <select
                          id="status"
                          class="custom-select"
                          v-model="form.status"
                          name="status"
                        >
                          <option
                            :value="current_status"
                            v-for="current_status in getDealStatuses"
                            :key="`status_${current_status}`"
                          >
                            {{ current_status }}
                          </option>
                        </select>
                        <small
                          id="status-error"
                          class="form-text invalid-feedback"
                          >{{ errors.status }}</small
                        >
                      </div>
                    </div>
                    <!-- status : END -->
                    <div class="w-100"></div>

                    <!-- retail_or_whole_sale_deal : START -->
                    <div class="col-md-4 col-xs-12">
                      <label>Retail or Wholesale</label>
                    </div>
                    <div class="col-md-4 col-xs-12 form-group">
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          id="swithForWholeSaleOrRetail"
                          v-model="form.retail_or_wholesale_flag"
                          name="retail_or_wholesale_flag"
                          true-value="1"
                          false-value="0"
                          checked="checked"
                          class="custom-control-input"
                        />
                        <label
                          for="swithForWholeSaleOrRetail"
                          class="custom-control-label"
                        >
                          <span class="switch-icon-left">WH</span>
                          <span class="switch-icon-right">RT</span>
                        </label>
                      </div>
                    </div>
                    <!-- retail_or_whole_sale_deal : END -->
                  </div>
                </div>
                <!-- Details Section : END -->

                <!-- Customer Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Customers</h5>
                  </div>

                  <!-- row : start -->
                  <div class="row">
                    <!-- primary customer : START -->
                    <div class="col-md-6 col-xs-12">
                      <!-- customer 1 : START -->
                      <div class="form-group">
                        <label for="primary_customer">Primary Customer: </label>
                        <v-select
                          id="primary_customer"
                          placeholder="Type to search primary customer"
                          label="search_keyword"
                          v-model="selected_primary_customer"
                          clearable
                          @search="
                            (query, loading) => getCustomers(query, loading)
                          "
                          :options="customers"
                        >
                          <template slot="selected-option" slot-scope="option">
                            {{ option.first_name }} {{ option.last_name }}
                            <span
                              v-show="
                                option.company_name !== null &&
                                option.company_name !== ''
                              "
                            >
                              - {{ option.company_name }}
                            </span>
                          </template>
                          <template slot="option" slot-scope="option">
                            <span v-show="option.first_name !== null">
                              {{ option.first_name }}
                            </span>
                            <span v-show="option.last_name !== null">
                              {{ option.last_name }}
                            </span>
                            <span
                              v-show="
                                option.company_name !== null &&
                                option.company_name !== ''
                              "
                            >
                              - {{ option.company_name }}
                            </span>
                          </template>
                        </v-select>
                      </div>

                      <!-- customer 1 : END -->
                    </div>
                    <!-- primary customer : END -->
                    <!-- secondary customer : START -->
                    <div
                      class="col-md-6 col-xs-12"
                      v-show="parseInt(form.retail_or_wholesale_flag) == 0"
                    >
                      <!-- customer 2 : START -->
                      <div class="form-group">
                        <label for="secondary_customer"
                          >Secondary Customer:
                        </label>
                        <v-select
                          id="secondary_customer"
                          placeholder="Type to search secondary customer"
                          label="search_keyword"
                          v-model="selected_secondary_customer"
                          clearable
                          @search="
                            (query, loading) => getCustomers(query, loading)
                          "
                          :options="customers"
                        >
                          <template slot="selected-option" slot-scope="option">
                            {{ option.first_name }} {{ option.last_name }}
                            <span
                              v-show="
                                option.company_name !== null &&
                                option.company_name !== ''
                              "
                            >
                              - {{ option.company_name }}
                            </span>
                          </template>
                          <template slot="option" slot-scope="option">
                            <span v-show="option.first_name !== null">
                              {{ option.first_name }}
                            </span>
                            <span v-show="option.last_name !== null">
                              {{ option.last_name }}
                            </span>
                            <span
                              v-show="
                                option.company_name !== null &&
                                option.company_name !== ''
                              "
                            >
                              - {{ option.company_name }}
                            </span>
                          </template>
                        </v-select>
                      </div>

                      <!-- customer 2 : END -->
                    </div>
                    <!-- secondary customer : END -->

                    <div class="w-100"></div>
                    <!-- route to add new customer or show existing customer selected : START -->
                    <div
                      class="col-md-6 col-xs-12 text-center"
                      v-if="
                        primary_customer == null ||
                        Object.keys(primary_customer).length === 0
                      "
                    >
                      <router-link
                        tag="span"
                        :to="{ name: 'customer-create' }"
                        class="d-inline-block mr-1"
                        exact
                      >
                        <a class="btn btn-link">Add New Customer</a>
                      </router-link>
                    </div>
                    <div class="col-md-6 col-xs-12 text-left" v-else>
                      <div class="card-body">
                        <h4
                          class="card-title text-left"
                          v-if="primary_customer.customer_type == '0'"
                        >
                          <div class="avatar bg-warning mr-1">
                            <div class="avatar-content">IND</div>
                          </div>
                          {{ primary_customer.first_name }}
                          {{ primary_customer.last_name }}
                        </h4>
                        <h4 class="card-title text-left" v-else>
                          <div class="avatar bg-danger mr-1">
                            <div class="avatar-content">COM</div>
                          </div>
                          {{ primary_customer.company_name }}
                        </h4>
                        <li class="list-group-item d-none">
                          <span v-if="primary_customer.rin_or_dl_flag == '0'"
                            >RIN : {{ primary_customer.rin }}</span
                          >
                          <span v-else
                            >DL : {{ primary_customer.driver_licence }}</span
                          >
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            primary_customer.phone != '' &&
                            primary_customer.phone != null
                          "
                        >
                          <i class="bx bx-phone-call phone-icon mr-1"></i>
                          {{ phoneFormat(primary_customer.phone) }}
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            primary_customer.mobile != '' &&
                            primary_customer.mobile != null
                          "
                        >
                          <i class="bx bxs-mobile text-danger mr-1"></i>
                          {{ phoneFormat(primary_customer.mobile) }}
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            primary_customer.email != '' &&
                            primary_customer.email != null
                          "
                        >
                          <i class="bx bxs-inbox text-success mr-1"></i>
                          {{ primary_customer.email }}
                        </li>
                        <router-link
                          tag="li"
                          style="cursor: pointer"
                          :to="{
                            name: 'customer-update',
                            params: { id: primary_customer.id },
                          }"
                          class="list-group-item active"
                          exact
                        >
                          Update Customer
                        </router-link>
                      </div>
                    </div>
                    <!-- route to add new customer or show existing customer selected : END -->

                    <!-- route to add new customer or show secondary existing customer selected : START -->
                    <div
                      class="col-md-6 col-xs-12 text-center"
                      v-show="parseInt(form.retail_or_wholesale_flag) == 0"
                      v-if="
                        secondary_customer == null ||
                        Object.keys(secondary_customer).length === 0
                      "
                    >
                      <router-link
                        tag="span"
                        :to="{ name: 'customer-create' }"
                        class="d-inline-block mr-1"
                        exact
                      >
                        <a class="btn btn-link">Add New Customer</a>
                      </router-link>
                    </div>
                    <div
                      class="col-md-6 col-xs-12 text-left"
                      v-show="parseInt(form.retail_or_wholesale_flag) == 0"
                      v-else
                    >
                      <div class="card-body">
                        <h4
                          class="card-title text-left"
                          v-if="secondary_customer.customer_type == '0'"
                        >
                          <div class="avatar bg-warning mr-1">
                            <div class="avatar-content">IND</div>
                          </div>
                          {{ secondary_customer.first_name }}
                          {{ secondary_customer.last_name }}
                        </h4>
                        <h4 class="card-title text-left" v-else>
                          <div class="avatar bg-danger mr-1">
                            <div class="avatar-content">COM</div>
                          </div>
                          {{ secondary_customer.company_name }}
                        </h4>
                        <li class="list-group-item d-none">
                          <span v-if="secondary_customer.rin_or_dl_flag == '0'"
                            >RIN : {{ secondary_customer.rin }}</span
                          >
                          <span v-else
                            >DL : {{ secondary_customer.driver_licence }}</span
                          >
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            secondary_customer.phone != '' &&
                            secondary_customer.phone != null
                          "
                        >
                          <i class="bx bx-phone-call phone-icon mr-1"></i>
                          {{ phoneFormat(secondary_customer.phone) }}
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            secondary_customer.mobile != '' &&
                            secondary_customer.mobile != null
                          "
                        >
                          <i class="bx bxs-mobile text-danger mr-1"></i>
                          {{ phoneFormat(secondary_customer.mobile) }}
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            secondary_customer.email != '' &&
                            secondary_customer.email != null
                          "
                        >
                          <i class="bx bxs-inbox text-success mr-1"></i>
                          {{ secondary_customer.email }}
                        </li>
                        <li class="list-group-item">
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="secondary_customer_type"
                              type="radio"
                              id="co-signer"
                              value="co-signer"
                            />
                            <label class="form-check-label" for="co-signer"
                              >Co-Signer</label
                            >
                          </div>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="secondary_customer_type"
                              type="radio"
                              id="co-buyer"
                              value="co-buyer"
                            />
                            <label class="form-check-label" for="co-buyer"
                              >Co-Buyer</label
                            >
                          </div>
                        </li>
                        <router-link
                          tag="li"
                          style="cursor: pointer"
                          :to="{
                            name: 'customer-update',
                            params: { id: secondary_customer.id },
                          }"
                          class="list-group-item active"
                          exact
                        >
                          Update Customer
                        </router-link>
                      </div>
                    </div>
                    <!-- route to add new customer or show secondary existing customer selected : END -->
                  </div>
                </div>
                <!-- Customer Section : END -->
                <!-- row : end -->
                <!-- Inventory Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Inventory</h5>
                  </div>
                  <!-- row : start -->
                  <div class="row">
                    <div class="col-md-11 col-xs-12">
                      <!-- inventory : START -->
                      <div class="form-group">
                        <label for="inventories"
                          >Select Instock Inventory:
                        </label>
                        <v-select
                          id="inventories"
                          placeholder="Select Inventory"
                          label="label"
                          v-model="selected_inventory"
                          clearable
                          :options="inventories"
                        >
                          <template slot="selected-option" slot-scope="option">
                            <label>
                              <div>
                                {{ option.year }} {{ option.make }}
                                {{ option.model }} &lt;
                                {{ option.stock_no }} &gt; &lt;
                                {{ option.vin }} &gt;
                              </div>
                            </label>
                          </template>
                          <template slot="option" slot-scope="option">
                            <div>
                              {{ option.year }} {{ option.make }}
                              {{ option.model }} &lt; {{ option.stock_no }} &gt;
                              &lt; {{ option.vin }} &gt;
                            </div>
                            <div style="vertical-align: middle">
                              <i
                                class="bx bxs-detail bx-sm d-inline-block mr-50"
                              ></i>
                              {{ option.trim }}
                              <i
                                class="bx bx-palette bx-sm d-inline-block mr-50 ml-1"
                              ></i>
                              {{ option.exterior_color }}
                              <i
                                class="bx bxs-dashboard bx-sm d-inline-block mr-50"
                              ></i>
                              {{ option.odometer }}
                            </div>
                          </template>
                        </v-select>
                      </div>

                      <!-- inventory : END -->
                    </div>
                    <router-link
                      tag="button"
                      v-show="
                        inventory != null && Object.keys(inventory).length != 0
                      "
                      style="cursor: pointer"
                      :to="{
                        name: 'inventory-update',
                        params: { id: inventory.inventory_id },
                      }"
                      class="p-0 col-xs-2 btn btn-link"
                      exact
                    >
                      <i
                        class="bx bx-link-external"
                        style="font-size: 1.4em"
                      ></i>
                    </router-link>
                    <div class="w-100"></div>
                    <!-- inventory section : START -->
                    <div
                      class="col-xs-12 col-12"
                      v-show="
                        inventory != null && Object.keys(inventory).length != 0
                      "
                    >
                      <div class="row">
                        <!-- year: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="YEAR"
                        >
                          <i
                            class="bx bxs-calendar-alt bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.year }}</span
                          >
                        </div>
                        <!-- year: END -->

                        <!-- make: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="MAKE"
                        >
                          <i class="bx bx-car bx-lg" style="display: block"></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.make }}</span
                          >
                        </div>
                        <!-- make: END -->

                        <!-- model: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="MODEL"
                        >
                          <i
                            class="bx bxs-cog bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.model }}</span
                          >
                        </div>
                        <!-- model: END -->

                        <!-- trim: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="TRIM"
                        >
                          <i
                            class="bx bxs-detail bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.trim }}</span
                          >
                        </div>
                        <!-- trim: END -->

                        <!-- status: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="STATUS"
                        >
                          <i
                            class="bx bxs-layer bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.status }}</span
                          >
                        </div>
                        <!-- status: END -->

                        <!-- vin: START -->
                        <div class="col-md-4 col-xs-12 text-center" title="VIN">
                          <i
                            class="bx bx-barcode bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.vin }}</span
                          >
                        </div>
                        <!-- vin: END -->

                        <!-- stock_no: START -->
                        <div
                          class="col-md-2 col-xs-12 text-center"
                          title="STOCK NUMBER"
                        >
                          <i
                            class="bx bx-hash bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.stock_no }}</span
                          >
                        </div>
                        <!-- stock_no: END -->

                        <!-- exterior_color: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="EXTERIOR COLOR"
                        >
                          <i
                            class="bx bx-palette bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.exterior_color }}</span
                          >
                        </div>
                        <!-- exterior_color: END -->

                        <!-- odometer: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="ODOMETER"
                        >
                          <i
                            class="bx bxs-dashboard bx-lg"
                            style="display: block"
                          ></i>
                          <span
                            style="
                              display: block;
                              line-height: 2em;
                              font-weight: 700;
                              font-size: 1.4em;
                            "
                            >{{ inventory.odometer }}
                            {{ inventory.km_miles }}</span
                          >
                        </div>
                        <!-- odometer: END -->

                        <!-- odometer: START -->
                        <div
                          class="col-md-3 col-xs-12 text-center"
                          title="ODOMETER"
                        >
                          <i
                            class="bx bxs-dashboard bx-lg"
                            style="display: block"
                          ></i>

                          <input
                            type="text"
                            name="odometer"
                            v-model="odometer"
                            class="form-control"
                            id=""
                          />
                        </div>
                        <!-- odometer: END -->
                        <!-- sub status : START -->
                        <div class="row col-md-6 col-xs-12">
                          <label
                            class="col-md-12 text-bold mt-50"
                            style="line-height: 2em"
                          >
                            Select Sub Status
                          </label>
                          <div class="col-md-12">
                            <select
                              name="inventory_sub_status"
                              class="form-control"
                              v-model="form.inventory_sub_status"
                              id=""
                            >
                              <option value="" selected disabled>
                                -- Select Sub Status --
                              </option>
                              <option
                                :value="sub_status"
                                v-for="sub_status in getSubStatues"
                                :key="sub_status"
                              >
                                {{ sub_status }}
                              </option>
                            </select>
                          </div>
                        </div>
                        <!-- sub status : END -->
                      </div>
                    </div>

                    <!-- inventory section : END -->
                  </div>
                </div>
                <!-- Inventory Section : END -->
                <!-- row : end -->
                <div
                  v-show="
                    inventory != null && Object.keys(inventory).length != 0
                  "
                >
                  <div class="card-header border-line-bottom-title">
                    <h6 class="card-title">Disclosures</h6>
                  </div>
                </div>
                <!-- row : start -->
                <div
                  class="row"
                  v-show="
                    inventory != null && Object.keys(inventory).length != 0
                  "
                >
                  <!-- disclosure : START -->
                  <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="default-disclosure"
                        >Description :
                        <span class="text-danger" style="font-size: 0.8em">
                          This is displayed on paperwork.
                        </span></label
                      >
                      <fieldset class="" style="position: relative">
                        <textarea
                          id="default-disclosure"
                          v-model="form.disclosure"
                          class="form-control"
                          rows="3"
                        ></textarea>
                      </fieldset>
                    </div>
                  </div>
                  <!-- disclosure : END -->

                  <div class="col-md-12 col-xs-12 text-right p-1">
                    <inventory-disclosure-template-c-t-a
                      :elementId="'deal_disclosure'"
                    />
                  </div>

                  <!-- brand : START -->
                  <div class="row col-md-12 pb-1">
                    <div class="col-md-4">Brand Type</div>
                    <div class="col-md-4">
                      <v-select
                        v-model="form.brand"
                        append-to-body
                        placeholder="Brand Type"
                        taggable
                        pushTags
                        clearable
                        :options="brands"
                      ></v-select>
                    </div>
                  </div>
                  <!-- brand : END -->

                  <!-- certified : START -->
                  <div class="row col-md-12 pb-1">
                    <div class="col-md-4">Certified</div>
                    <div class="col-md-4">
                      <select
                        name="certified"
                        class="form-control"
                        v-model="form.certified"
                        id=""
                      >
                        <option value="">-- Select --</option>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                      </select>
                    </div>
                  </div>
                  <!-- certified : END -->

                  <!-- condition : START -->
                  <div class="col-md-12 col-xs-12" style="display: none">
                    <div class="form-group">
                      <label for="default-condition">Conditions : </label>
                      <fieldset class="" style="position: relative">
                        <textarea
                          id="default-condition"
                          v-model="form.condition"
                          class="form-control"
                        ></textarea>
                      </fieldset>
                    </div>
                  </div>
                  <!-- condition : END -->
                </div>
                <!-- row : end -->
                <!-- row : start -->
                <!-- Delivery Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Delivery</h5>
                  </div>
                  <div class="row">
                    <!-- delivery_date : START -->
                    <div class="col-md-4 col-xs-12">
                      <div class="form-group">
                        <label for="delivery_date">Delivery Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            placeholder="Delivery Date"
                            v-model="form.delivery_date"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            id="delivery_date"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('delivery_date'),
                            }"
                            @change="delete errors.delivery_date"
                            :title="errors.delivery_date"
                          >
                          </date-picker>
                        </fieldset>
                        <small
                          id="delivery_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.delivery_date }}</small
                        >
                      </div>
                    </div>
                    <!-- delivery_date : END -->

                    <!-- delivery time : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="delivery_time">Delivery Time</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="delivery_time"
                            type="time"
                            placeholder="Delivery Time"
                            v-model="form.delivery_time"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('delivery_time'),
                            }"
                            @keyup="delete errors.delivery_time"
                            :title="errors.delivery_time"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-time font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="delivery_time-error"
                          class="form-text invalid-feedback"
                          >{{ errors.delivery_time }}</small
                        >
                      </div>
                    </div>
                    <!-- delivery time : END -->

                    <!-- cancellation_date : START -->
                    <div
                      class="col-md-4 col-xs-12"
                      v-show="form.status === 'Cancelled'"
                    >
                      <div class="form-group">
                        <label for="cancellation_date">Cancellation Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            placeholder="Cancellation Date"
                            v-model="form.cancellation_date"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            id="cancellation_date"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('cancellation_date'),
                            }"
                            @change="delete errors.cancellation_date"
                            :title="errors.cancellation_date"
                          >
                          </date-picker>
                        </fieldset>
                        <small
                          id="cancellation_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.cancellation_date }}</small
                        >
                      </div>
                    </div>
                    <!-- cancellation_date : END -->

                    <div class="w-100"></div>
                    <!-- approved : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="approved_by">Approved By </label>
                      <select
                        id="approved_by"
                        class="custom-select"
                        name="approved_by"
                        v-model="form.approved_by"
                      >
                        <option disabled value="">-- Select Approver --</option>
                        <option
                          :value="user.user_id"
                          v-for="user in managersAndOwners"
                          :key="user.user_id + '_approved_by'"
                          v-text="user.name"
                        ></option>
                      </select>

                      <small
                        id="approved_by-error"
                        class="form-text invalid-feedback"
                        >{{ errors.approved_by }}</small
                      >
                    </div>
                    <!-- approved : end -->

                    <!-- salesperson : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="salesperson">Salesperson </label>
                      <select
                        id="salesperson"
                        class="custom-select"
                        name="salesperson"
                        v-model="form.salesperson"
                      >
                        <option disabled value="">
                          -- Select Salesperson --
                        </option>
                        <option
                          :value="user.user_id"
                          v-for="user in salesPersons"
                          :key="user.user_id + '_salesperson'"
                          v-text="user.name"
                        ></option>
                      </select>

                      <small
                        id="salesperson-error"
                        class="form-text invalid-feedback"
                        >{{ errors.salesperson }}</small
                      >
                    </div>
                    <!-- salesperson : end -->

                    <div class="w-100"></div>

                    <!-- note : START -->
                    <div class="col-md-12 col-xs-12 d-none">
                      <div class="form-group">
                        <label for="default-note"
                          >Notes :
                          <span class="text-danger" style="font-size: 0.8em">
                            Internal notes, not visible on paperwork.
                          </span></label
                        >
                        <fieldset class="" style="position: relative">
                          <textarea
                            id="default-note"
                            v-model="form.note"
                            class="form-control"
                          ></textarea>
                        </fieldset>
                      </div>
                    </div>
                    <!-- note : END -->
                  </div>
                  <!-- row : end -->
                </div>
                <!-- Delivery Section : END -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <inventory-disclosure-template :elementId="'deal_disclosure'" />
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import vSelect from "vue-select";

import { mapGetters, mapActions } from "vuex";
import InventoryDisclosureTemplate from "../../Inventory/InventoryDisclosureTemplate.vue";
import InventoryDisclosureTemplateCTA from "../../Inventory/InventoryDisclosureTemplateCTA.vue";

export default {
  name: "DealCreate",
  props: {
    id: { required: false },
    has_trade_in: { required: false },
    allUsers: { required: true },
    prev_name: { required: false },
    app_metadata: { required: true },
    preselected_stock_no: { required: false, type: String, default: "" },
    preselected_customer_id: { required: false, default: "" },
  },
  watch: {
    "form.retail_or_wholesale_flag": function (newVal, oldVal) {
      Event.fire("change_retail_or_wholesale", newVal);
      if (parseInt(newVal) == 1) {
        this.secondary_customer = {};
      }

      if (
        parseInt(newVal) != parseInt(oldVal) &&
        !this.retail_or_wholesale_dismissed
      ) {
        if (
          this.$route.params.id &&
          this.$route.name == "deal-update" &&
          this.load
        ) {
          this.show_alert = true;

          this.$swal({
            showConfirmButton: true,
            showCancelButton: true,
            cancelButtonText: "No",
            confirmButtonText: "Yes",
            text: "Changes to type of deal will affect the Deal Worksheet and Trade In Details. Do you want to continue?",
          }).then((result) => {
            if (result.isConfirmed) {
              this.form.retail_or_wholesale_flag = newVal;
              if (parseInt(this.form.retail_or_wholesale_flag) == 1) {
                this.form.type = "Cash";
              }
              this.save_worksheet = true;
            } else if (result.isDismissed) {
              this.form.retail_or_wholesale_flag = oldVal;
              this.retail_or_wholesale_dismissed = true;
            }
          });
        }
      } else {
        this.retail_or_wholesale_dismissed = false;
      }
    },
    "form.type": function (newVal, oldVal) {
      Event.fire("change_deal_type", newVal);
      if (newVal != oldVal && !this.type_dismissed) {
        if (
          this.$route.params.id &&
          this.$route.name == "deal-update" &&
          this.load &&
          parseInt(this.form.retail_or_wholesale_flag) != 1
        ) {
          this.show_alert = true;

          this.$swal({
            showConfirmButton: true,
            showCancelButton: true,
            cancelButtonText: "No",
            confirmButtonText: "Yes",
            text: "Changes to type of deal will affect the Deal Worksheet and Trade In Details. Do you want to continue?",
          }).then((result) => {
            if (result.isConfirmed) {
              this.form.type = newVal;
              this.save_worksheet = true;
            } else if (result.isDismissed) {
              this.form.type = oldVal;
              this.type_dismissed = true;
            }
          });
        }
      } else {
        this.type_dismissed = !this.type_dismissed;
      }
    },
    inventory: function (newVal, oldVal) {
      let purchase_price =
        this.inventory.special_price_on == "1"
          ? this.inventory.special_price
          : this.inventory.price;

      if (this.load) {
        this.getDisclosures(this.inventory["inventory_id"], true);

        this.form.disclosure = "";
        this.form.brand = "";
        this.form.certified = "";
        Event.fire("change_purchase_price", purchase_price);
      } else if (!this.load && this.prev_name == "deal-create") {
        Event.fire("change_purchase_price", purchase_price);
      }
    },
    selected_inventory: function (newVal, oldVal) {
      if (newVal) {
        this.inventory = newVal;
        if (this.inventory.hasOwnProperty("sub_status")) {
          this.form.inventory_sub_status = this.inventory.sub_status;
        }
      }
    },
    selected_primary_customer: function (newVal, oldVal) {
      if (newVal) {
        this.primary_customer = newVal;
      }
    },
    selected_secondary_customer: function (newVal, oldVal) {
      if (newVal) {
        this.secondary_customer = newVal;
      }
    },
    "form.status": async function (newVal, oldVal) {
      if (newVal != oldVal && this.load) {
        this.form.import_inventory = false;
        this.form.mark_inventory_as_sold = false;

        const isDelivered = newVal === "Delivered" ? true : false;

        if (isDelivered && this.has_trade_in) {
          const { value: option } = await this.$swal.fire({
            target: "#deal-create-panel",
            title: "Please make a selection below:",
            input: "radio",
            inputOptions: {
              1: "Update the Status of Delivered Vehicle to Sold",
              2: "Add Trade-In Inventory into Stock",
              3: "BOTH of the above",
              4: "None of the above",
            },
            inputValidator: (value) => {
              if (!value) {
                return "Please select an option.";
              }
            },
          });
          if (option) {
            switch (option) {
              case "1":
                this.form.mark_inventory_as_sold = true;
                this.form.inventory_sub_status = "Delivered";
                break;
              case "2":
                this.form.import_inventory = true;
                break;
              case "3":
                this.form.mark_inventory_as_sold = true;
                this.form.import_inventory = true;
                this.form.inventory_sub_status = "Delivered";
                break;

              default:
                break;
            }
          }
        } else if (isDelivered) {
          await this.$swal({
            showConfirmButton: true,
            showCancelButton: true,
            cancelButtonText: "No",
            confirmButtonText: "Yes",
            text: "Do you want to mark the inventory as SOLD?",
          }).then((result) => {
            if (result.isConfirmed) {
              this.form.mark_inventory_as_sold = true;
              this.form.inventory_sub_status = "Delivered";
            }
          });
        }
      }
    },
  },
  data() {
    return {
      errors: [],
      brands: ["N/A", "None", "Rebuilt", "Salvage", "Irreparable"],
      retail_or_wholesale_dismissed: false,
      type_dismissed: false,
      show_alert: false,
      save_worksheet: false,
      load: false,
      primary_customer: {},
      selected_primary_customer: null,
      primary_customer_type: "primary",
      secondary_customer: {},
      selected_secondary_customer: null,
      secondary_customer_type: "co-buyer",
      odometer: "",
      inventory: {},
      selected_inventory: null,
      customers: [],
      inventories: [],
      form: {
        type: "Cash",
        import_inventory: false,
        inventory_sub_status: "Available For Sale",
        mark_inventory_as_sold: false,
        date: "",
        retail_or_wholesale_flag: 0,
        status: "Open",
        delivery_date: null,
        delivery_time: null,
        note: "",
        disclosure: "",
        condition: "",
        approved_by: "",
        salesperson: "",
        cancellation_date: "",
        close_date: "",
        brand: "",
        certified: "",
      },
    };
  },
  components: {
    "v-select": vSelect,
    DatePicker,
    InventoryDisclosureTemplate,
    InventoryDisclosureTemplateCTA,
  },
  computed: {
    ...mapGetters("deal", ["getDealStatuses"]),
    ...mapGetters("inventoryView", ["getLoadState", "getSubStatues"]),
    managersAndOwners() {
      return this.allUsers.filter(
        (user) => user.role == "Owner" || user.role == "Manager"
      );
    },
    salesPersons() {
      return this.allUsers.filter(
        (user) =>
          user.role == "Staff" || user.role == "Owner" || user.role == "Manager"
      );
    },
    tabTitle() {
      return this.$route.params.id && this.$route.name == "deal-update"
        ? "Update Deal"
        : "Create Deal";
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "deal-update";
    },
  },
  methods: {
    ...mapActions("inventoryView", ["commitOnlyEntities"]),
    async getDisclosures(inventory_id, set_disclosure = true) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/common/disclosures",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (Array.isArray(data)) {
            if (data.length > 0) {
              for (const key in data) {
                if (
                  data[key].question == "Complete Disclosure" &&
                  set_disclosure
                ) {
                  this.form.disclosure = data[key].answer;
                } else if (data[key].question == "Brand" && set_disclosure) {
                  this.form.brand = data[key].answer;
                } else if (
                  data[key].question == "Certified" &&
                  set_disclosure
                ) {
                  this.form.certified = data[key].answer;
                }
              }
            }
          }
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async findByStockNumber(stock_no, set_disclosure = true) {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/inventory?all=true&zm_stock_no[]=" +
          stock_no,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("inventory")) {
            if (data["inventory"].hasOwnProperty("0")) {
              this.inventory = data["inventory"]["0"];
              this.getDisclosures(
                this.inventory["inventory_id"],
                set_disclosure
              );

              let label =
                this.inventory.year != "" && this.inventory.year != null
                  ? `${this.inventory.year} `
                  : "";

              label +=
                this.inventory.make != "" && this.inventory.make != null
                  ? `${this.inventory.make} `
                  : "";

              label +=
                this.inventory.model != "" && this.inventory.model != null
                  ? `${this.inventory.model} `
                  : "";

              label +=
                this.inventory.stock_no != "" && this.inventory.stock_no != null
                  ? `< ${this.inventory.stock_no} >`
                  : "";

              label +=
                this.inventory.vin != "" && this.inventory.vin != null
                  ? `< ${this.inventory.vin} >`
                  : "";

              this.inventory.label = label;
              Event.fire(
                "get_inventory_details",
                `${this.inventory.year} ${this.inventory.make} ${this.inventory.model} - ${this.inventory.vin} #${this.inventory.stock_no}`
              );

              this.form.inventory_sub_status = this.inventory.sub_status;
            }
          }
        })
        .catch(() => {
          let error =
            "There were some errors in retrieving the inventory details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async findCustomerById(customer_type, id) {
      let update_request =
        this.$route.params.id && this.$route.name == "deal-update";

      let url = update_request
        ? `${window.location.origin}/api/business/deals/${this.$route.params.id}/customers/show/${id}`
        : `${window.location.origin}/api/business/customers/show/${id}`;

      await axios({
        method: "get",
        url: url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          let label =
            data.first_name != "" && data.first_name != null
              ? `${data.first_name} `
              : "";

          label +=
            data.last_name != "" && data.last_name != null
              ? `${data.last_name}`
              : "";

          label +=
            data.company_name != "" && data.company_name != null
              ? ` < ${data.company_name} >`
              : "";

          data.label = label;

          if (customer_type == "primary_customer") {
            this.primary_customer = data;
            this.primary_customer_type = customer_type;
            Event.fire("get_primary_customer_email", data.email);
            Event.fire(
              "get_primary_customer_name",
              `${data.first_name} ${data.last_name}`
            );
            Event.fire("get_primary_customer_phone", data.mobile);
            Event.fire("get_primary_customer_id", data.id);
          } else {
            this.secondary_customer = data;
            this.secondary_customer_type = customer_type;
          }
        })
        .catch(() => {
          let error =
            "There were some errors in retrieving the customer details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async getDeal(id) {
      const vm = this;
      const allPromises = [];
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/deals/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          console.log(data);

          Event.fire("get_private_share_id", data["private_share_id"]);
          Event.fire("change_deal_lock", data["lock"]);

          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          let date = moment(data.date);
          this.form.date = date.isValid() ? date.format("MM/DD/YYYY") : null;

          let delivery_date = moment(data.delivery_date_time);
          this.form.delivery_date = delivery_date.isValid()
            ? delivery_date.format("MM/DD/YYYY")
            : null;

          this.form.delivery_time = delivery_date.isValid()
            ? delivery_date.format("HH:mm")
            : null;

          let close_date = moment(data.close_date);
          this.form.close_date = close_date.isValid()
            ? close_date.format("MM/DD/YYYY")
            : null;

          let cancellation_date = moment(data.cancellation_date);
          this.form.cancellation_date = cancellation_date.isValid()
            ? cancellation_date.format("MM/DD/YYYY")
            : null;

          if (data.inventory && data.inventory.length > 0) {
            data.inventory.forEach((element) => {
              allPromises.push(
                this.findByStockNumber(element["stock_no"], false)
              );
              if (!isNaN(element["odometer"] && element["odometer"] != "")) {
                this.odometer = element["odometer"];
              }
            });
          }

          if (data.customers && data.customers.length > 0) {
            data.customers.forEach((customer) => {
              let customer_type =
                customer["customer_type"] == "primary_customer"
                  ? "primary_customer"
                  : customer["customer_type"];
              allPromises.push(
                this.findCustomerById(customer_type, customer["customer_id"])
              );
            });
          }

          Event.fire("get_deal_status", data.status);
        })
        .catch(({ response }) => {
          console.log(response.data.errors);
          let error = response.data.errors;
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });

      await Promise.all(allPromises);
      vm.load = true;
    },
    phoneFormat(phoneText) {
      if (phoneText == null) {
        return null;
      }
      var x = phoneText.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      return !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
    },
    getCustomers(search = "", cb = null) {
      console.log(cb);
      if (typeof cb === "function") {
        cb(true);
      }
      const vm = this;
      let request_url = `${window.location.origin}/api/business/customers?items=30`;
      if (search !== "") {
        request_url += `&search=${search}`;
      }

      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.customers = data.customer_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        })
        .finally(() => {
          if (typeof cb === "function") {
            cb(false);
          }
        });
    },
    getInventories() {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory?paginate=false&all=true&zm_status[]=Instock",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.inventory) {
            if (data.inventory.length > 0) {
              for (var index in data.inventory) {
                let label =
                  data.inventory[index].year != "" &&
                  data.inventory[index].year != null
                    ? `${data.inventory[index].year} `
                    : "";

                label +=
                  data.inventory[index].make != "" &&
                  data.inventory[index].make != null
                    ? `${data.inventory[index].make} `
                    : "";

                label +=
                  data.inventory[index].model != "" &&
                  data.inventory[index].model != null
                    ? `${data.inventory[index].model} `
                    : "";

                label +=
                  data.inventory[index].stock_no != "" &&
                  data.inventory[index].stock_no != null
                    ? `< ${data.inventory[index].stock_no} >`
                    : "";

                label +=
                  data.inventory[index].vin != "" &&
                  data.inventory[index].vin != null
                    ? `< ${data.inventory[index].vin} >`
                    : "";

                data.inventory[index].label = label;
              }
            }
          }

          vm.inventories = data.inventory;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    save() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let date_time = moment(
        this.form.delivery_date + " " + this.form.delivery_time,
        "MM/DD/YYYY HH:mm"
      );

      if (this.primary_customer != null && this.primary_customer != {}) {
        if (this.primary_customer.hasOwnProperty("id")) {
          formData.append("primary_customer", this.primary_customer["id"]);
          formData.append("primary_customer_type", "primary_customer");
        }
      }

      if (this.secondary_customer != null && this.secondary_customer != {}) {
        if (this.secondary_customer.hasOwnProperty("id")) {
          formData.append("secondary_customer", this.secondary_customer["id"]);
          formData.append(
            "secondary_customer_type",
            this.secondary_customer_type
          );
        }
      }

      if (this.inventory != null && this.inventory != {}) {
        if (this.inventory.hasOwnProperty("stock_no")) {
          formData.append("inventory", this.inventory["stock_no"]);
        }
      }

      if (this.odometer) {
        formData.append("odometer", this.odometer);
      }

      formData.append(
        "delivery_date_time",
        date_time.isValid() ? date_time : null
      );

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "deal-update";

      let success_message = update_request
        ? `Deal updated successfully.`
        : `New deal added.`;

      request_url = update_request
        ? `${request_url}/api/business/deals/update/${this.$route.params.id}`
        : `${request_url}/api/business/deals/create`;

      let request_message = update_request
        ? `Updating deal ...`
        : `Adding new deal ...`;

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
          vm.show_alert = false;
          toast.success(data.success);
          toast.close();

          let update_request =
            vm.$route.params.id && vm.$route.name == "deal-update";
          if (vm.save_worksheet && update_request) {
            Event.fire("update_worksheet_data", null);
            vm.save_worksheet = false;
          }

          if (this.inventory != null && this.inventory != {}) {
            Event.fire(
              "get_inventory_details",
              `${this.inventory.year} ${this.inventory.make} ${this.inventory.model} - ${this.inventory.vin} #${this.inventory.stock_no}`
            );
          }

          if (this.primary_customer != null && this.primary_customer != {}) {
            if (this.primary_customer.hasOwnProperty("id")) {
              Event.fire(
                "get_primary_customer_email",
                this.primary_customer.email
              );
              Event.fire(
                "get_primary_customer_name",
                `${this.primary_customer.first_name} ${this.primary_customer.last_name}`
              );
              Event.fire(
                "get_primary_customer_phone",
                this.primary_customer.mobile
              );
              Event.fire("get_primary_customer_id", this.primary_customer.id);
            }
          }

          setTimeout(function () {
            Event.fire("save_deal_complete", data);
          }, 500);

          Event.fire("get_deal_status", this.form.status);

          if (
            (this.form.import_inventory || this.form.mark_inventory_as_sold) &&
            this.getLoadState
          ) {
            this.commitOnlyEntities();
          }
        })
        .catch(function (error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "deal-update";
          let error_message = update_request
            ? "There were errors while updating the deal."
            : "There were some errors while adding new deal.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
          setTimeout(function () {
            Event.fire("save_deal_complete", null);
          }, 500);
        });
    },
    addPrefilledTemplate(template, additional_information = "") {
      this.form.disclosure = `${this.form.disclosure} ${template}`;
      if (additional_information.trim() !== "") {
        this.form.disclosure = `${this.form.disclosure} ${additional_information}`;
      }
    },
  },
  created() {
    this.getCustomers("");
    this.getInventories();
    this.form.date = moment(new Date()).format("MM/DD/YYYY");

    if (this.$route.params.id && this.$route.name == "deal-update") {
      this.getDeal(this.$route.params.id);
    } else {
      this.form.salesperson = this.app_metadata.id;

      if (
        this.app_metadata.role == "Owner" ||
        this.app_metadata.role == "Manager"
      ) {
        this.form.approved_by = this.app_metadata.id;
      }
      this.load = true;
    }
  },
  mounted() {
    const vm = this;

    if (this.preselected_stock_no !== "" && this.$route.name == "deal-create") {
      this.findByStockNumber(this.preselected_stock_no);
    }

    if (
      this.preselected_customer_id !== "" &&
      this.$route.name == "deal-create"
    ) {
      this.findCustomerById("primary_customer", this.preselected_customer_id);
    }

    Event.listen("save_deal", (data) => {
      vm.save();
    });

    Event.listen("add_prefilled_template_deal_disclosure", (template) => {
      vm.addPrefilledTemplate(template);
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_deal", null);
    Event.vue.$off("update_worksheet_data", null);
  },
};
</script>
<style lang="scss">
#deal-create-section {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent input {
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
}
#deal-create-panel {
  .swal2-radio {
    flex-direction: column;
    align-items: start;

    label {
      text-align: left;
      position: relative;

      input[type="radio"] {
        position: absolute;
        left: -1.5em;
        top: 4px;
      }
    }
  }
}
</style>
