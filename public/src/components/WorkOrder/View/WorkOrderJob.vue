<template>
  <div
    class="tab-pane"
    id="work-order-job-panel"
    aria-labelledby="Work Order Jobs Panel"
    role="tabpanel"
  >
    <section id="work-order-job-section">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <div class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="">
                          <div class="row">
                            <!-- Add Work Job Button : START -->
                            <div class="col-12 mb-1" style="text-align: right">
                              <button
                                class="btn btn-primary text-bold-700"
                                data-toggle="modal"
                                data-target="#workJobModal"
                              >
                                <i class="bx bxs-plus-square"></i> Add Work Job
                              </button>
                            </div>
                            <!-- Add Work Job Button : END -->

                            <!-- Work Job Create or Update : START -->
                            <div
                              class="modal fade"
                              id="workJobModal"
                              tabindex="-1"
                              role="dialog"
                              aria-labelledby="Work Job Modal"
                              aria-hidden="true"
                            >
                              <div
                                class="modal-dialog modal-lg"
                                role="document"
                              >
                                <div class="modal-content">
                                  <!-- Modal Header : START -->
                                  <div class="modal-header">
                                    <h5 class="modal-title">
                                      {{ work_order_label }}
                                    </h5>
                                    <button
                                      type="button"
                                      id="workJobModalClose"
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
                                    <form>
                                      <!-- title : START -->
                                      <div class="form-row">
                                        <div class="form-group col-md-12">
                                          <label for="work_job_title"
                                            >Title :
                                          </label>
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': errors.hasOwnProperty(
                                                'title'
                                              ),
                                            }"
                                            @keyup="delete errors.title"
                                            :title="errors.title"
                                            v-model="form.title"
                                            id="work_job_title"
                                            placeholder="Title"
                                          />
                                        </div>
                                      </div>
                                      <!-- title : END -->

                                      <!-- description : START -->
                                      <div class="form-row">
                                        <div class="form-group col-md-12">
                                          <label for="work_job_description"
                                            >Description :
                                          </label>
                                          <textarea
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': errors.hasOwnProperty(
                                                'description'
                                              ),
                                            }"
                                            @keyup="delete errors.description"
                                            :title="errors.description"
                                            v-model="form.description"
                                            id="work_job_description"
                                            placeholder="Description"
                                          ></textarea>
                                        </div>
                                      </div>
                                      <!-- description : END -->

                                      <!-- issue : START -->
                                      <div class="form-row">
                                        <div class="form-group col-md-12">
                                          <label for="work_job_issue"
                                            >Issues :
                                          </label>
                                          <textarea
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': errors.hasOwnProperty(
                                                'issue'
                                              ),
                                            }"
                                            @keyup="delete errors.issue"
                                            :title="errors.issue"
                                            v-model="form.issue"
                                            id="work_job_issue"
                                            placeholder="Issues found during inspection"
                                          ></textarea>
                                        </div>
                                      </div>
                                      <!-- issue : END -->

                                      <!-- reason : START -->
                                      <div class="form-row">
                                        <div class="form-group col-md-12">
                                          <label for="work_job_reason"
                                            >Reason :
                                          </label>
                                          <textarea
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': errors.hasOwnProperty(
                                                'reason'
                                              ),
                                            }"
                                            @keyup="delete errors.reason"
                                            :title="errors.reason"
                                            v-model="form.reason"
                                            id="work_job_reason"
                                            placeholder="Reason for the issues"
                                          ></textarea>
                                        </div>
                                      </div>
                                      <!-- reason : END -->

                                      <!-- solution : START -->
                                      <div class="form-row">
                                        <div class="form-group col-md-12">
                                          <label for="work_job_solution"
                                            >Solution :
                                          </label>
                                          <textarea
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': errors.hasOwnProperty(
                                                'solution'
                                              ),
                                            }"
                                            @keyup="delete errors.solution"
                                            :title="errors.solution"
                                            v-model="form.solution"
                                            id="work_job_solution"
                                            placeholder="How were the issues resolved"
                                          ></textarea>
                                        </div>
                                      </div>
                                      <!-- solution : END -->

                                      <!-- job_card_information : START -->
                                      <div class="form-row">
                                        <div class="form-group col-md-12">
                                          <label for="job_card_information"
                                            >Job Comment :
                                          </label>
                                          <textarea
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': errors.hasOwnProperty(
                                                'job_card_information'
                                              ),
                                            }"
                                            @keyup="
                                              delete errors.job_card_information
                                            "
                                            :title="errors.job_card_information"
                                            v-model="form.job_card_information"
                                            id="job_card_information"
                                            placeholder="Job Comment"
                                          ></textarea>
                                        </div>
                                      </div>
                                      <!-- job_card_information : END -->
                                    </form>
                                  </div>
                                  <!-- Modal Body : END -->

                                  <!-- modal footer : START -->
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
                                      @click.prevent="saveWorkJob()"
                                    >
                                      Save
                                    </button>
                                  </div>
                                  <!-- modal footer : END -->
                                </div>
                              </div>
                            </div>
                            <!-- Work Job Create or Update : END -->
                          </div>
                          <!-- List of Work Job : START -->
                          <div class="row">
                            <!-- Template for each work job : START -->
                            <div
                              class="mb-0 col-md-12 col-sm-12"
                              v-for="work_order in work_job_listing"
                              :key="`work_order_` + work_order.id"
                            >
                              <div class="card" style="background: #f5f5f5;">
                                <div class="card-content mb-0">
                                  <div class="card-body">
                                    <h6 class="card-title mb-0">
                                      <span class="pr-3">{{
                                        work_order.title
                                      }}</span>
                                      <span
                                        class="lead-note-buttons"
                                        style="position: absolute; right: 1em; top: 1em;"
                                      >
                                        <i
                                          style=" font-size: 1.4em; background: whitesmoke;"
                                          class="bx text-info bx bx-edit"
                                          @click="
                                            openEditWorkJob(work_order.id)
                                          "
                                        ></i>
                                        <i
                                          style="font-size:1.4em;background: whitesmoke;"
                                          class="bx text-danger bx bx-trash"
                                          @click="deleteWorkJob(work_order.id)"
                                        ></i>
                                      </span>
                                    </h6>
                                    <p class="card-text">
                                      {{
                                        work_order.description
                                          | truncate(250, "...")
                                      }}
                                    </p>
                                    <hr class="w-100" />
                                    <!-- Add Product Button : START -->
                                    <div
                                      class="col-12 mb-1"
                                      style="text-align: right"
                                    >
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        data-toggle="modal"
                                        data-target="#workJobProductModal"
                                        @click="
                                          current_work_order_job_id =
                                            work_order.id
                                        "
                                      >
                                        <i class="bx bxs-plus-square"></i> Add
                                        Product
                                      </button>
                                    </div>
                                    <!-- Add Product Button : END -->

                                    <!-- Product List : START -->
                                    <div class="col-12">
                                      <div class="table-responsive">
                                        <table class="table inventory_costs">
                                          <thead>
                                            <tr>
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Details
                                              </th>
                                              <!-- <th class="text-center" scope="col">Invoice Ref</th> -->
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Vendor
                                              </th>
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Price
                                              </th>
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Qty / Hrs
                                              </th>
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Discount
                                              </th>
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Total Tax
                                              </th>
                                              <th
                                                class="text-center"
                                                scope="col"
                                              >
                                                Sub Total
                                              </th>
                                              <th
                                                class="text-center"
                                                scope="col"
                                                style="width:100px;"
                                              ></th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="product in work_order.products"
                                              :key="product.id"
                                              style="background: #eee"
                                            >
                                              <td class="p-1">
                                                <h6 class="col-12">
                                                  {{ product.name }}
                                                  <template
                                                    v-if="
                                                      product.item_code != ''
                                                    "
                                                    >/
                                                    {{
                                                      product.item_code
                                                    }}</template
                                                  >
                                                </h6>
                                                <p
                                                  class="col-12"
                                                  style="font-size:0.8em;"
                                                >
                                                  {{
                                                    product.description
                                                      | truncate(50, "...")
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center p-0">
                                                <h6 class="col-12">
                                                  {{ product.vendor_name }}
                                                </h6>
                                                <h6 class="col-12">
                                                  {{ product.vendor_code }}
                                                </h6>
                                              </td>
                                              <td class="text-center p-0">
                                                <div class="pt-1">
                                                  {{
                                                    `$ ${Number(
                                                      product.price
                                                    ).toLocaleString()}`
                                                  }}
                                                </div>
                                                <p
                                                  :title="
                                                    product.tax_percentage +
                                                      ` %`
                                                  "
                                                  class="text-primary mb-0"
                                                  style="font-weight:600;"
                                                >
                                                  {{ product.tax_type }}
                                                </p>
                                                <p
                                                  v-show="
                                                    product.tax_type_secondary !==
                                                      ''
                                                  "
                                                  :title="
                                                    product.tax_percentage_secondary +
                                                      ` %`
                                                  "
                                                  class="text-primary"
                                                  style="font-weight:600;"
                                                >
                                                  {{
                                                    product.tax_type_secondary
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center p-0">
                                                {{ product.quantity }}
                                              </td>
                                              <td class="text-center p-0">
                                                {{
                                                  `$ ${Number(
                                                    product.discount
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center p-0">
                                                {{
                                                  `$ ${Number(
                                                    product.tax_amount_total
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center p-0">
                                                {{
                                                  `$ ${Number(
                                                    product.sub_total
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="p-0">
                                                <button
                                                  class="btn btn-info"
                                                  @click.prevent="
                                                    openEditWorkJobProduct(
                                                      product.work_job_id,
                                                      product.id
                                                    )
                                                  "
                                                  style="padding-right:0.8em;padding-left:0.8em;"
                                                >
                                                  <i class="bx bx-edit"></i>
                                                </button>
                                                <button
                                                  class="btn btn-danger"
                                                  @click.prevent="
                                                    deleteProductForAWorkJob(
                                                      product.work_job_id,
                                                      product.id
                                                    )
                                                  "
                                                  style="padding-right:0.8em;padding-left:0.8em;"
                                                >
                                                  <i
                                                    class="bx bx-trash-alt"
                                                  ></i>
                                                </button>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <!-- Product List : END -->
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- Template for each work job : END -->
                          </div>
                          <!-- List of Work Job : END -->
                          <!-- Product Update Create : START -->
                          <div
                            class="modal fade"
                            id="workJobProductModal"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="Product Modal"
                            aria-hidden="true"
                          >
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <!-- Modal Header : START -->
                                <div class="modal-header">
                                  <h5 class="modal-title">
                                    Product
                                  </h5>
                                  <button
                                    type="button"
                                    id="workJobProductModalClose"
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
                                  <form>
                                    
                                     <!-- Product Search : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="products"
                                          >Search Product :
                                        </label>
                                        <v-select
                                          taggable
                                          placeholder="Search Product Name"
                                          label="label"
                                          :reduce="(product) => product.id"
                                          v-model="selected_product_name"
                                          clearable
                                          :options="createIndexForProducts"
                                        ></v-select>
                                      </div>
                                    </div>
                                     <!-- Product Search : END -->

                                    <!-- Name : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="product_name"
                                          >Name :
                                        </label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'name'
                                            ),
                                          }"
                                          @keyup="delete product_errors.name"
                                          :title="product_errors.name"
                                          v-model="product_form.name"
                                          id="product_name"
                                          placeholder="Name"
                                        />
                                      </div>
                                    </div>
                                    <!-- Name : END -->

                                    <!-- Item Code : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="item_code"
                                          >Item Code :
                                        </label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'item_code'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.item_code
                                          "
                                          :title="product_errors.item_code"
                                          v-model="product_form.item_code"
                                          id="item_code"
                                          placeholder="Item Code"
                                        />
                                      </div>
                                    </div>
                                    <!-- Item Code : END -->

                                    <!-- Description : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="product_description"
                                          >Description :
                                        </label>
                                        <textarea
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'description'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.description
                                          "
                                          :title="product_errors.description"
                                          v-model="product_form.description"
                                          id="product_description"
                                          placeholder="Description"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <!-- Description : END -->

                                    <!-- Category : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="product_form_category"
                                          >Category</label
                                        >
                                        <select
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'category'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.category
                                          "
                                          :title="product_errors.category"
                                          v-model="product_form.category"
                                          placeholder="Category"
                                          id="product_form_category"
                                        >
                                          <option value="" selected disabled>
                                            - Select Category -
                                          </option>
                                          <option
                                            :value="category"
                                            :key="category"
                                            v-for="category in getCategories"
                                            >{{ category }}</option
                                          >
                                        </select>
                                      </div>
                                    </div>
                                    <!-- Category : END -->

                                    <!-- Type : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="product_form_type"
                                          >Type</label
                                        >
                                        <select
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'type'
                                            ),
                                          }"
                                          @keyup="delete product_errors.type"
                                          :title="product_errors.type"
                                          v-model="product_form.type"
                                          placeholder="Type"
                                          id="product_form_type"
                                        >
                                          <option value="" selected disabled>
                                            - Select Type -
                                          </option>
                                          <option
                                            :value="ptype"
                                            :key="ptype"
                                            v-for="ptype in getTypes"
                                            >{{ ptype }}</option
                                          >
                                        </select>
                                      </div>
                                    </div>
                                    <!-- Type : END -->

                                    <!-- Vendor Name : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="vendors"
                                          >Select Vendor :
                                        </label>
                                        <v-select
                                          taggable
                                          placeholder="Vendor Name"
                                          label="company_name"
                                          :reduce="
                                            (vendor) => vendor.company_name
                                          "
                                          v-model="product_form.vendor_name"
                                          clearable
                                          :options="allVendors"
                                        ></v-select>
                                      </div>
                                    </div>
                                    <!-- Vendor Name : END -->

                                    <!-- Vendor Code : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="vendor_code"
                                          >Vendor Code :
                                        </label>
                                        <input
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'vendor_code'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.vendor_code
                                          "
                                          :title="product_errors.vendor_code"
                                          v-model="product_form.vendor_code"
                                          id="vendor_code"
                                          placeholder="Vendor Code"
                                        />
                                      </div>
                                    </div>
                                    <!-- Vendor Code : END -->

                                    <!-- TODO unit Text -->

                                    <!-- additional_information : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="additional_information"
                                          >Additional Information :
                                        </label>
                                        <textarea
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'additional_information'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.additional_information
                                          "
                                          :title="
                                            product_errors.additional_information
                                          "
                                          v-model="
                                            product_form.additional_information
                                          "
                                          id="additional_information"
                                          placeholder="Additional Information"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <!-- additional_information : END -->

                                    <!-- job_card_information : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="job_card_information"
                                          >Job Card Information :
                                        </label>
                                        <textarea
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'job_card_information'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.job_card_information
                                          "
                                          :title="
                                            product_errors.job_card_information
                                          "
                                          v-model="
                                            product_form.job_card_information
                                          "
                                          id="job_card_information"
                                          placeholder="Job Card Information"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <!-- job_card_information : END -->

                                    <!-- warranty : START -->
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
                                        <label for="warranty"
                                          >Warranty :
                                        </label>
                                        <textarea
                                          type="text"
                                          class="form-control"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'warranty'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.warranty
                                          "
                                          :title="product_errors.warranty"
                                          v-model="product_form.warranty"
                                          id="warranty"
                                          placeholder="Warranty"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <!-- warranty : END -->

                                    <!-- price section : START -->
                                    <div class="form-row">
                                      <!-- price : START -->
                                      <div class="form-group col-md-4">
                                        <label for="product_form_price"
                                          >{{productPriceLabel}}</label
                                        >
                                        <input
                                          type="text"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'price'
                                            ),
                                          }"
                                          @keyup="delete product_errors.price"
                                          :title="product_errors.price"
                                          v-model="product_form.price"
                                          placeholder="Price"
                                          class="form-control"
                                          id="product_form_price"
                                        />
                                      </div>
                                      <!-- price : END -->

                                      <!-- quantity : START -->
                                      <div class="form-group col-md-4">
                                        <label for="product_form_quantity"
                                          >{{productHourOrQuantityLabel}}</label
                                        >
                                        <input
                                          type="text"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'quantity'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.quantity
                                          "
                                          :title="product_errors.quantity"
                                          v-model="product_form.quantity"
                                          placeholder="Quantity"
                                          class="form-control"
                                          id="product_form_quantity"
                                        />
                                      </div>
                                      <!-- quantity : END -->

                                      <!-- discount : START -->
                                      <div class="form-group col-md-4">
                                        <label for="product_form_discount"
                                          >Discount</label
                                        >
                                        <input
                                          type="text"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'discount'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.discount
                                          "
                                          :title="product_errors.discount"
                                          class="form-control"
                                          v-model="product_form.discount"
                                          placeholder="Discount"
                                          id="product_form_discount"
                                        />
                                      </div>
                                      <!-- discount : END -->
                                    </div>
                                    <!-- price section : END -->
                                    <!-- price tax section : START -->
                                    <div class="form-row">
                                      <!-- Tax 1 : START -->
                                      <!-- tax_type : START -->
                                      <div class="form-group col-md-3">
                                        <label for="product_form_tax_type"
                                          >Tax Type</label
                                        >
                                        <select
                                          class="custom-select form-control mr-4"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'tax_type'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.tax_type
                                          "
                                          :title="product_errors.tax_type"
                                          v-model="product_form.tax_type"
                                          placeholder="Tax Type"
                                          id="product_form_tax_type"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                          >
                                            {{ tax.name }}
                                          </option>
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- tax_type : END -->
                                      <!-- Tax 1 : END -->

                                      <!-- Tax 2 : START -->
                                      <!-- tax_type : START -->
                                      <div class="form-group col-md-4">
                                        <label
                                          for="product_form_tax_type_secondary"
                                          >Tax Type</label
                                        >
                                        <select
                                          class="custom-select form-control mr-1"
                                          :disabled="
                                            product_form.tax_type === 'Other'
                                          "
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'tax_type_secondary'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.tax_type_secondary
                                          "
                                          :title="
                                            product_errors.tax_type_secondary
                                          "
                                          v-model="
                                            product_form.tax_type_secondary
                                          "
                                          placeholder="Tax Type"
                                          id="product_form_tax_type_secondary"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                          >
                                            {{ tax.name }}
                                          </option>
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- tax_type : END -->
                                      <!-- Tax 2 : END -->

                                      <!-- tax_amount : START -->
                                      <div class="form-group col-md-3">
                                        <label
                                          for="product_form_tax_amount_total"
                                          >Tax Amount</label
                                        >
                                        <input
                                          type="text"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'tax_amount_total'
                                            ),
                                          }"
                                          :readonly="
                                            product_form.tax_type !== 'Other'
                                          "
                                          @keyup="
                                            delete product_errors.tax_amount_total
                                          "
                                          :title="
                                            product_errors.tax_amount_total
                                          "
                                          v-model="
                                            product_form.tax_amount_total
                                          "
                                          placeholder="Tax Amount"
                                          class="form-control"
                                          id="product_form_tax_amount_total"
                                        />
                                      </div>
                                      <!-- tax_amount : END -->
                                      <div class="form-group col-md-1">
                                        <i
                                          v-show="price_recalculation"
                                          class="bx bx-loader-alt bx-spin bx-md p-75 text-primary"
                                        ></i>
                                      </div>
                                    </div>
                                    <!-- price tax section : END -->
                                    <div
                                      class="divider divider-dashed"
                                      type="button"
                                      data-toggle="collapse"
                                      data-target="#collapseProductCost"
                                      aria-expanded="false"
                                      aria-controls="collapseProductCost"
                                    >
                                      <div class="divider-text">More</div>
                                    </div>
                                    <!-- COST : START -->
                                    <div
                                      class="form-row collapse"
                                      id="collapseProductCost"
                                    >
                                      <!-- cost : START -->
                                      <div class="form-group col-md-4">
                                        <label for="product_form_cost"
                                          >{{productCostLabel}}</label
                                        >
                                        <input
                                          type="text"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'cost'
                                            ),
                                          }"
                                          @keyup="delete product_errors.cost"
                                          :title="product_errors.cost"
                                          v-model="product_form.cost"
                                          placeholder="Cost"
                                          class="form-control"
                                          id="product_form_cost"
                                        />
                                      </div>
                                      <!-- cost : END -->

                                      <!-- Tax 1 : START -->
                                      <!-- cost_tax_type : START -->
                                      <div class="form-group col-md-4">
                                        <label for="product_form_cost_tax_type"
                                          >Tax Type</label
                                        >
                                        <select
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'cost_tax_type'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.cost_tax_type
                                          "
                                          :title="product_errors.cost_tax_type"
                                          v-model="product_form.cost_tax_type"
                                          placeholder="Tax Type"
                                          id="product_form_cost_tax_type"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                          >
                                            {{ tax.name }}
                                          </option>
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- cost_tax_type : END -->
                                      <!-- Tax 1 : END -->

                                      <!-- Tax 2 : START -->
                                      <!-- cost_tax_type : START -->
                                      <div class="form-group col-md-4">
                                        <label
                                          for="product_form_cost_tax_type_secondary"
                                          >Tax Type</label
                                        >
                                        <select
                                          :disabled="
                                            product_form.cost_tax_type ===
                                              'Other'
                                          "
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'cost_tax_type_secondary'
                                            ),
                                          }"
                                          @keyup="
                                            delete product_errors.cost_tax_type_secondary
                                          "
                                          :title="
                                            product_errors.cost_tax_type_secondary
                                          "
                                          v-model="
                                            product_form.cost_tax_type_secondary
                                          "
                                          placeholder="Tax Type"
                                          id="product_form_cost_tax_type_secondary"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                          >
                                            {{ tax.name }}
                                          </option>
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- cost_tax_type : END -->
                                      <!-- Tax 2 : END -->

                                      <!-- tax_amount : START -->
                                      <div class="form-group col-md-4">
                                        <label
                                          for="product_form_tax_amount_total"
                                          >Tax Amount</label
                                        >
                                        <input
                                          type="text"
                                          :class="{
                                            'is-invalid': product_errors.hasOwnProperty(
                                              'cost_tax_amount_total'
                                            ),
                                          }"
                                          :readonly="
                                            product_form.cost_tax_type !==
                                              'Other'
                                          "
                                          @keyup="
                                            delete product_errors.cost_tax_amount_total
                                          "
                                          :title="
                                            product_errors.cost_tax_amount_total
                                          "
                                          v-model="
                                            product_form.cost_tax_amount_total
                                          "
                                          placeholder="Tax Amount"
                                          class="form-control"
                                          id="product_form_cost_tax_amount_total"
                                        />
                                      </div>
                                      <!-- tax_amount : END -->
                                      <div class="form-group col-md-1">
                                        <i
                                          v-show="cost_recalculation"
                                          class="bx bx-loader-alt bx-spin bx-md p-75 text-primary"
                                        ></i>
                                      </div>
                                    </div>
                                    <!-- COST : END-->
                                  </form>
                                </div>
                                <!-- Modal Body : END -->
                                <!-- modal footer : START -->
                                <div class="modal-footer">
                                  <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                  >
                                    Close
                                  </button>
                                  <button
                                    :disabled="
                                      cost_recalculation || price_recalculation
                                    "
                                    type="button"
                                    class="btn btn-primary"
                                    @click.prevent="
                                      saveWorkJobProduct(
                                        current_work_order_job_id
                                      )
                                    "
                                  >
                                    Save
                                  </button>
                                </div>
                                <!-- modal footer : END -->
                              </div>
                            </div>
                          </div>
                          <!-- Product Update Create : START -->
                        </div>
                      </div>
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
import Toast from "../../../library/Toast";
import vSelect from "vue-select";

import { mapGetters, mapActions } from "vuex";
export default {
  name: "WorkOrderJob",
  components: {
    "v-select": vSelect,
  },
  props: {
    id: { required: false },
    allUsers: { required: true },
    app_metadata: { required: true },
  },
  data() {
    return {
      load: true,
      cost_recalculation: false,
      price_recalculation: false,
      work_job_listing: [],
      errors: [],
      form: {
        title: "",
        description: "",
        issue: "",
        reason: "",
        solution: "",
        job_card_information: "",
      },
      selected_product_name: "",
      work_order_job_id: null,
      current_work_order_job_id: null,
      product_errors: [],
      product_id: null,
      product_listing: [],
      product_form: {
        name: "",
        item_code: "",
        description: "",
        category: "",
        type: "",
        vendor_code: "",
        vendor_name: "",
        unit: "",
        quantity: 1,

        price: 0,
        discount: 0,
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        sub_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,

        cost_sub_total: 0,

        additional_information: "",
        job_card_information: "",
        warranty: "",
      },
      work_order_label: "Create Work Job",
    };
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("product", ["getTypes", "getCategories"]),
    ...mapGetters("vendor", ["allVendors"]),
    productPriceLabel(){
      return this.product_form.type === 'Labour' ? 'Price Per Hour' : 'Price Per Unit';
    },
    productCostLabel(){
      return this.product_form.type === 'Labour' ? 'Cost Per Hour' : 'Cost Per Unit';
    },
    productHourOrQuantityLabel(){
      return this.product_form.type === 'Labour' ? 'Hours' : 'Quantities';
    },
    createIndexForProducts() {
      if (this.product_listing.length > 0) {
        for (var index in this.product_listing) {
          // this.product_listing[index].label = "Hello World";
          let label =
            this.product_listing[index].name != "" &&
            this.product_listing[index].name != null
              ? `${this.product_listing[index].name} `
              : "";

          label +=
            this.product_listing[index].item_code != "" &&
            this.product_listing[index].item_code != null
              ? ` < ${this.product_listing[index].item_code} >`
              : "";

          this.product_listing[index].label = label;
        }

        this.$forceUpdate();
      }
      return this.product_listing;
    }
  },
  watch: {
    selected_product_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
         this.getProduct(newVal);
        }
      }
    },
    "product_form.quantity": function(newVal, oldVal) {
      const vm = this;
      this.product_form.quantity = this.$helpers.coerceToNumberString(
        this.product_form.quantity
      );

      let quantity = parseFloat(this.product_form.quantity);

      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.product_form.price,
            vm.product_form.discount,
            quantity,
            vm.product_form.tax_type,
            vm.product_form.tax_type_secondary,
            vm.product_form.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
              sub_total: "sub_total",
            }
          );

          vm.calculateLineTotal(
            vm.product_form.cost,
            0,
            quantity,
            vm.product_form.cost_tax_type,
            vm.product_form.cost_tax_type_secondary,
            vm.product_form.cost_tax_amount_total,
            {
              cost_tax_percentage: "tax_percentage",
              cost_tax_amount: "tax_amount",
              cost_tax_percentage_secondary: "tax_percentage_secondary",
              cost_tax_amount_secondary: "tax_amount_secondary",
              cost_tax_amount_total: "tax_amount_total",
              cost_sub_total: "sub_total",
            },
            "cost_recalculation"
          );
        }, 400);
      }
    },
    "product_form.price": function(newVal, oldVal) {
      const vm = this;
      this.product_form.price = this.$helpers.coerceToNumberString(
        this.product_form.price
      );
      let price = parseFloat(this.product_form.price);
      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            price,
            vm.product_form.discount,
            vm.product_form.quantity,
            vm.product_form.tax_type,
            vm.product_form.tax_type_secondary,
            vm.product_form.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
              sub_total: "sub_total",
            }
          );
        }, 400);
      }
    },
    "product_form.discount": function(newVal, oldVal) {
      const vm = this;
      this.product_form.discount = this.$helpers.coerceToNumberString(
        this.product_form.discount
      );
      let discount = parseFloat(this.product_form.discount);
      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.product_form.price,
            discount,
            vm.product_form.quantity,
            vm.product_form.tax_type,
            vm.product_form.tax_type_secondary,
            vm.product_form.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
              sub_total: "sub_total",
            }
          );
        }, 400);
      }
    },
    "product_form.tax_type": function(newVal, oldVal) {
      const vm = this;
      this.product_form.tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.product_form.price,
            vm.product_form.discount,
            vm.product_form.quantity,
            vm.product_form.tax_type,
            vm.product_form.tax_type_secondary,
            vm.product_form.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_type_secondary: "tax_type_secondary",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
              sub_total: "sub_total",
            }
          );
        }, 400);
      }
    },
    "product_form.tax_type_secondary": function(newVal, oldVal) {
      const vm = this;
      this.product_form.tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.product_form.price,
            vm.product_form.discount,
            vm.product_form.quantity,
            vm.product_form.tax_type,
            vm.product_form.tax_type_secondary,
            vm.product_form.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
              sub_total: "sub_total",
            }
          );
        }, 400);
      }
    },
    "product_form.cost": function(newVal, oldVal) {
      const vm = this;
      this.product_form.cost = this.$helpers.coerceToNumberString(
        this.product_form.cost
      );
      let cost = parseFloat(this.product_form.cost);
      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            cost,
            0,
            vm.product_form.quantity,
            vm.product_form.cost_tax_type,
            vm.product_form.cost_tax_type_secondary,
            vm.product_form.cost_tax_amount_total,
            {
              cost_tax_percentage: "tax_percentage",
              cost_tax_amount: "tax_amount",
              cost_tax_percentage_secondary: "tax_percentage_secondary",
              cost_tax_amount_secondary: "tax_amount_secondary",
              cost_tax_amount_total: "tax_amount_total",
              cost_sub_total: "sub_total",
            },
            "cost_recalculation"
          );
        }, 400);
      }
    },
    "product_form.cost_tax_type": function(newVal, oldVal) {
      const vm = this;
      this.product_form.cost_tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.product_form.cost,
            0,
            vm.product_form.quantity,
            vm.product_form.cost_tax_type,
            vm.product_form.cost_tax_type_secondary,
            vm.product_form.cost_tax_amount_total,
            {
              cost_tax_percentage: "tax_percentage",
              cost_tax_amount: "tax_amount",
              cost_tax_type_secondary: "tax_type_secondary",
              cost_tax_percentage_secondary: "tax_percentage_secondary",
              cost_tax_amount_secondary: "tax_amount_secondary",
              cost_tax_amount_total: "tax_amount_total",
              cost_sub_total: "sub_total",
            },
            "cost_recalculation"
          );
        }, 400);
      }
    },
    "product_form.cost_tax_type_secondary": function(newVal, oldVal) {
      const vm = this;
      this.product_form.cost_tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.product_form.cost,
            0,
            vm.product_form.quantity,
            vm.product_form.cost_tax_type,
            vm.product_form.cost_tax_type_secondary,
            vm.product_form.cost_tax_amount_total,
            {
              cost_tax_percentage: "tax_percentage",
              cost_tax_amount: "tax_amount",
              cost_tax_percentage_secondary: "tax_percentage_secondary",
              cost_tax_amount_secondary: "tax_amount_secondary",
              cost_tax_amount_total: "tax_amount_total",
              cost_sub_total: "sub_total",
            },
            "cost_recalculation"
          );
        }, 400);
      }
    },
    businessTaxSettings: function(newVal, oldVal) {
      if (this.load == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1"
        );

        if (selected_tax.length > 0) {
          this.product_form.tax_type = this.product_form.cost_tax_type =
            selected_tax[0].name;
          if (selected_tax.length > 1) {
            this.product_form.tax_type_secondary = this.product_form.cost_tax_type_secondary =
              selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("product", ["commitCategories"]),
    ...mapActions("vendor", ["getAllVendors"]),
    async productListing() {
      let request_url =
        window.location.origin + "/api/business/products?paginate=false";
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.product_listing = data.product_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the products");
        });
    },
    getProduct(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/products/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.product_errors = [];
          for (const key in this.product_form) {
            if (this.product_form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.product_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the product";
          console.log(error);
          // const toast = new Toast(error);
          // toast.fire();
          // toast.fail(error);
          // toast.close();
        });
    },
    async calculateLineTotal(
      amount,
      discount = 0,
      quantity = 1,
      tax_type,
      tax_type_secondary,
      tax_amount_total,
      fields,
      loader_field = "price_recalculation"
    ) {
      const vm = this;
      vm.$data[loader_field] = true;
      if (this.load == false) {
        let formData = new FormData();
        formData.append("amount", amount);
        formData.append("discount", discount);
        formData.append("quantity", quantity);
        formData.append("tax_type", tax_type);
        formData.append("tax_type_secondary", tax_type_secondary);
        formData.append("tax_amount_total", tax_amount_total);

        await axios({
          method: "post",
          url: window.location.origin + "/api/business/calculator/line-total",
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
            // 'X-Requested-With' : 'XMLHttpRequest',
          },
        })
          .then(({ data }) => {
            for (const [key, value] of Object.entries(fields)) {
              this.product_form[key] = data[value];
            }

            vm.$data[loader_field] = false;
          })
          .catch(({ response }) => {
            let error = "There were some errors in calculating the taxes";
            const toast = new Toast(error);
            toast.fire();
            toast.fail(error);
            toast.close();
            vm.$data[loader_field] = false;
          });
      }
    },
    async deleteWorkJob(id) {
      const vm = this;

      let message = "Deleting work job...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/work-orders/${this.$route.params.id}/work-jobs/delete/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          let success_message = `Work Job has been deleted.`;
          toast.success(success_message);
          toast.close();

          vm.getAllWorkJobsForAnOrder();
        })
        .catch((error) => {
          let error_message = `Error in deleting work job.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    async resetWorkOrderForm() {
      this.form.title = this.form.description = this.form.issue = this.form.reason = this.form.solution = this.form.job_card_information =
        "";

      this.work_order_job_id = null;
      this.work_order_label = "Create Work Job";
      this.errors = [];
    },
    async resetProductForm() {
      let set_empty_string = [
        "name",
        "item_code",
        "description",
        "category",
        "type",
        "vendor_code",
        "vendor_name",
        "unit",
        "tax_type",
        "tax_type_secondary",
        "cost_tax_type",
        "cost_tax_type_secondary",
        "additional_information",
        "job_card_information",
        "warranty",
      ];

      for (const key in set_empty_string) {
        this.product_form[set_empty_string[key]] = "";
      }

      let set_default_zero = [
        "quantity",
        "price",
        "discount",
        "tax_percentage",
        "tax_amount",
        "tax_amount_secondary",
        "tax_percentage_secondary",
        "tax_amount_total",
        "sub_total",
        "cost",
        "cost_tax_percentage",
        "cost_tax_amount",
        "cost_tax_amount_secondary",
        "cost_tax_percentage_secondary",
        "cost_tax_amount_total",
        "cost_sub_total",
      ];

      for (const key in set_default_zero) {
        this.product_form[set_default_zero[key]] = "";
      }

      this.product_form.quantity = 1;
      this.product_id = null;
      this.product_errors = [];

      this.selected_product_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      if (selected_tax.length > 0) {
        this.product_form.tax_type = selected_tax[0].name;
        this.product_form.cost_tax_type = selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.product_form.tax_type_secondary = selected_tax[1].name;
          this.product_form.cost_tax_type_secondary = selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.product_form.tax_percentage = "";
          this.product_form.cost_tax_percentage = "";
        } else {
          this.product_form.tax_percentage = selected_tax[0].value;
          this.product_form.cost_tax_percentage = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.product_form.tax_percentage_secondary = 0;
            this.product_form.cost_tax_percentage_secondary = 0;
          } else {
            this.product_form.tax_percentage_secondary = selected_tax[1].value;
            this.product_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.product_form.tax_percentage_secondary = 0;
          this.product_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.product_form.tax_percentage = "";
        this.product_form.cost_tax_percentage = "";
      }

      this.current_work_order_job_id = null;
    },
    async openEditWorkJob(id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/work-orders/${this.$route.params.id}/work-jobs/show/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //Reset the errors
          this.errors = [];

          //Set all available Form Fields
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          this.work_order_job_id = data.id;
          this.work_order_label = "Update Work Job";
          $("#workJobModal").modal("show");
        })
        .catch((error) => {
          let error_message = `Error in fetching work job.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    async getAllWorkJobsForAnOrder() {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/work-orders/${this.$route.params.id}/work-jobs`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.work_job_listing = data.work_job_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async saveWorkJob() {
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      formData.append("work_order_id", this.$route.params.id);

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id &&
        this.$helpers.isNumeric(this.work_order_job_id);

      //success message setting
      let success_message = update_request
        ? `Work Order Updated.`
        : `New Work Order Created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/work-orders/${this.$route.params.id}/work-jobs/update/${this.work_order_job_id}`
        : `${request_url}/api/business/work-orders/${this.$route.params.id}/work-jobs/create`;

      //message during request
      let request_message = update_request
        ? `Updating Work Order ...`
        : `Creating New Work Order ...`;

      const toast = new Toast(request_message);
      toast.fire();

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
          toast.success(data.success);
          toast.close();

          //TODO : Relisting

          document.getElementById("workJobModalClose").click();
          vm.getAllWorkJobsForAnOrder();
        })
        .catch(function(error) {
          let update_request = vm.$route.params.id && vm.work_order_job_id;
          let error_message = update_request
            ? "There were errors while updating the work job."
            : "There were some errors while creating new work job.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    async saveWorkJobProduct(work_job_id) {
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.product_form) {
        if (
          this.product_form.hasOwnProperty(key) &&
          this.product_form[key] != null
        ) {
          formData.append(key, this.product_form[key]);
        }
      }

      formData.append("work_job_id", work_job_id);

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$helpers.isNumeric(this.product_id);

      //success message setting
      let success_message = update_request
        ? `Product Updated.`
        : `New Product Created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/work-jobs/${work_job_id}/common/products/update/${this.product_id}`
        : `${request_url}/api/business/work-jobs/${work_job_id}/common/products/create`;

      //message during request
      let request_message = update_request
        ? `Updating Product ...`
        : `Creating New Product ...`;

      const toast = new Toast(request_message);
      toast.fire();

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
          toast.success(data.success);
          toast.close();

          //TODO : Relisting

          document.getElementById("workJobProductModalClose").click();
          this.getAllProductsForAWorkJob(work_job_id);
        })
        .catch(function(error) {
          let update_request = vm.$route.params.id && vm.product_id;
          let error_message = update_request
            ? "There were errors while updating the product."
            : "There were some errors while creating new product.";

          vm.$data.product_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    async openEditWorkJobProduct(work_job_id, product_id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/work-jobs/${work_job_id}/common/products/show/${product_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //Reset the errors
          this.product_errors = [];

          //Set all available Form Fields
          for (const key in this.product_form) {
            if (
              this.product_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.product_form[key] = data[key];
            }
          }

          this.current_work_order_job_id = work_job_id;
          this.product_id = product_id;
          //this.work_order_label = "Update Work Job";
          $("#workJobProductModal").modal("show");
        })
        .catch((error) => {
          let error_message = `Error in fetching product details.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    async getAllProductsForAWorkJob(work_job_id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/work-jobs/${work_job_id}/common/products`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          let index = vm.work_job_listing
            .map((ob) => ob.id)
            .indexOf(work_job_id);
          vm.work_job_listing[index]["products"] = data;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async deleteProductForAWorkJob(work_job_id, product_id) {
      const vm = this;
      let message = "Deleting product from work job...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/work-jobs/${work_job_id}/common/products/delete/${product_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          let success_message = `Product has been deleted.`;
          toast.success(success_message);
          toast.close();

          this.getAllProductsForAWorkJob(work_job_id);
        })
        .catch(({ response }) => {
          let error_message = `Error in deleting product.`;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  async created() {
    await this.productListing();
    await this.getBusinessTaxSettings();
    await this.commitCategories();
    await this.getAllVendors();
    await this.getAllWorkJobsForAnOrder();
  },
  async mounted() {
    const vm = this;
    jQuery("#workJobModal").on("hidden.bs.modal", function() {
      vm.resetWorkOrderForm();
    });

    jQuery("#workJobProductModal").on("hidden.bs.modal", function() {
      vm.resetProductForm();
    });
  },
};
</script>
