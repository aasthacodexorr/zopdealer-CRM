<template>
  <div
    class="tab-pane"
    id="deal-template-worksheet-panel"
    aria-labelledby="Worksheet Tab"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="deal-create-section">
      <div class="row match-height"></div>

      <div class="col-12 col-xs-12 col-md-12">
        <!-- card : START -->
        <div class="card">
          <div class="card-content">
            <div class="card-body pb-1 mx-25 row">
              <h4 class="card-title col-8">
                <i
                  class="bx bxs-dollar-circle bx-sm"
                  style="padding-right: 1em"
                ></i>
                <span style="display: inline-block; vertical-align: top"
                  >Fees</span
                >
              </h4>
              <div class="col-4 text-right" v-show="feeCount < 5">
                <button
                  class="btn btn-primary text-bold-700"
                  data-toggle="modal"
                  data-target="#dealFeeModal"
                >
                  <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                  Add Fee
                </button>
              </div>
              <!-- row : START -->
              <div class="row col-12">
                <!-- deal fees : START -->
                <div
                  id="deal-fees-table"
                  class="content-wrapper mt-0 p-0 col-12"
                >
                  <table class="list-page-content-p mt-0 mb-0 w-100">
                    <thead>
                      <tr class="bg-grey">
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center" style="min-width: 75px">
                          Amount
                        </th>
                        <th class="text-center" style="min-width: 75px">
                          Tax Type
                        </th>
                        <th class="text-center" style="min-width: 75px">
                          Taxes
                        </th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="deal_replicator_fee in deal_replicator_fees"
                        :key="`deal_replicator_fee` + deal_replicator_fee.id"
                      >
                        <td class="text-center">
                          {{ deal_replicator_fee.name | truncate(25, "...") }}
                        </td>
                        <td class="text-center">
                          {{
                            deal_replicator_fee.description
                              | truncate(50, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_fee.amount
                            ).toLocaleString()}`
                          }}
                        </td>
                        <td class="text-center">
                          <p class="mb-0">{{ deal_replicator_fee.tax_type }}</p>
                          <p
                            class="mb-0"
                            v-show="
                              deal_replicator_fee.tax_type_secondary !== ''
                            "
                          >
                            {{ deal_replicator_fee.tax_type_secondary }}
                          </p>
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_fee.tax_amount_total
                            ).toLocaleString()}`
                          }}
                        </td>
                        <td class="text-center">
                          <div class="dropdown">
                            <span
                              class="
                                bx bx-dots-vertical-rounded
                                font-medium-3
                                dropdown-toggle
                                nav-hide-arrow
                                cursor-pointer
                              "
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
                                class="dropdown-item"
                                style="cursor: pointer"
                                @click.prevent="
                                  editDealFee(deal_replicator_fee.id)
                                "
                                >Edit</a
                              >
                              <a
                                class="dropdown-item"
                                style="cursor: pointer"
                                @click.prevent="
                                  deleteDealFee(deal_replicator_fee.id)
                                "
                                >Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- deal fees : END -->
              </div>
              <!-- row : END -->
            </div>
          </div>
        </div>
        <!-- card : END -->

        <!-- card : START -->
        <div class="card" v-show="isDealRetailOrWholesale == 0">
          <div class="card-content">
            <div class="card-body pb-1 mx-25 row">
              <h4 class="card-title col-8">
                <i class="bx bxs-widget bx-sm" style="padding-right: 1em;"></i>
                <span style="display:inline-block;vertical-align:top;"
                  >Accessories</span
                >
              </h4>
              <div class="col-4 text-right">
                <button
                  class="btn btn-primary text-bold-700"
                  data-toggle="modal"
                  data-target="#dealAccessoryModal"
                >
                  <i class="bx bx-list-plus" style="padding-right: 1em;"></i>
                  Add Accessory
                </button>
              </div>
              <!-- row : START -->
              <div class="row col-12">
                <!-- deal fees : START -->
                <div
                  id="deal-accessories-table"
                  class="content-wrapper mt-0 p-0 col-12"
                >
                  <table class="list-page-content-p mt-0 mb-0 w-100">
                    <thead>
                      <tr class="bg-grey">
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center" style="min-width:75px;">
                          Amount
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Tax Type
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Taxes
                        </th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="deal_replicator_accessory in deal_replicator_accessories"
                        :key="
                          `deal_replicator_accessory` +
                            deal_replicator_accessory.id
                        "
                      >
                        <td class="text-center">
                          {{
                            deal_replicator_accessory.name | truncate(25, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            deal_replicator_accessory.description
                              | truncate(50, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_accessory.amount
                            ).toLocaleString()}`
                          }}
                        </td>
                        <td class="text-center">
                          <p class="mb-0">
                            {{ deal_replicator_accessory.tax_type }}
                          </p>
                          <p
                            class="mb-0"
                            v-show="
                              deal_replicator_accessory.tax_type_secondary !==
                                ''
                            "
                          >
                            {{ deal_replicator_accessory.tax_type_secondary }}
                          </p>
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_accessory.tax_amount_total
                            ).toLocaleString()}`
                          }}
                        </td>
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
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  editDealAccessory(
                                    deal_replicator_accessory.id
                                  )
                                "
                                >Edit</a
                              >
                              <a
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  deleteDealAccessory(
                                    deal_replicator_accessory.id
                                  )
                                "
                                >Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- deal fees : END -->
              </div>
              <!-- row : END -->
            </div>
          </div>
        </div>
        <!-- card : END -->

        <!-- card : START -->
        <div class="card" v-show="isDealRetailOrWholesale == 0">
          <div class="card-content">
            <div class="card-body pb-1 mx-25 row">
              <h4 class="card-title col-8">
                <i
                  class="bx bxs-shield-alt-2 bx-sm"
                  style="padding-right: 1em;"
                ></i>
                <span style="display:inline-block;vertical-align:top;"
                  >Warranties</span
                >
              </h4>
              <div class="col-4 text-right" v-show="warrantyCount == 0">
                <button
                  class="btn btn-primary text-bold-700"
                  data-toggle="modal"
                  data-target="#dealWarrantyModal"
                >
                  <i class="bx bx-list-plus" style="padding-right: 1em;"></i>
                  Add Warranty
                </button>
              </div>
              <!-- row : START -->
              <div class="row col-12">
                <!-- deal fees : START -->
                <div
                  id="deal-warranties-table"
                  class="content-wrapper mt-0 p-0 col-12"
                >
                  <table class="list-page-content-p mt-0 mb-0 w-100">
                    <thead>
                      <tr class="bg-grey">
                        <th class="text-center">Company Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center" style="min-width:75px;">
                          Amount
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Tax Type
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Taxes
                        </th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="deal_replicator_warranty in deal_replicator_warranties"
                        :key="
                          `deal_replicator_warranty` +
                            deal_replicator_warranty.id
                        "
                      >
                        <td class="text-center">
                          {{
                            deal_replicator_warranty.company_name
                              | truncate(25, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            deal_replicator_warranty.description
                              | truncate(50, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_warranty.amount
                            ).toLocaleString()}`
                          }}
                        </td>
                        <td class="text-center">
                          <p class="mb-0">
                            {{ deal_replicator_warranty.tax_type }}
                          </p>
                          <p
                            class="mb-0"
                            v-show="
                              deal_replicator_warranty.tax_type_secondary !== ''
                            "
                          >
                            {{ deal_replicator_warranty.tax_type_secondary }}
                          </p>
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_warranty.tax_amount_total
                            ).toLocaleString()}`
                          }}
                        </td>
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
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  editDealWarranty(deal_replicator_warranty.id)
                                "
                                >Edit</a
                              >
                              <a
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  deleteDealWarranty(
                                    deal_replicator_warranty.id
                                  )
                                "
                                >Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- deal fees : END -->
              </div>
              <!-- row : END -->
            </div>
          </div>
        </div>
        <!-- card : END -->

        <!-- card : START -->
        <div class="card" v-show="isDealRetailOrWholesale == 0">
          <div class="card-content">
            <div class="card-body pb-1 mx-25 row">
              <h4 class="card-title col-8">
                <i
                  class="bx bxs-check-shield bx-sm"
                  style="padding-right: 1em;"
                ></i>
                <span style="display:inline-block;vertical-align:top;"
                  >Guarantees</span
                >
              </h4>
              <div class="col-4 text-right" v-show="guaranteeCount == 0">
                <button
                  class="btn btn-primary text-bold-700"
                  data-toggle="modal"
                  data-target="#dealGuaranteeModal"
                >
                  <i class="bx bx-list-plus" style="padding-right: 1em;"></i>
                  Add Guarantee
                </button>
              </div>
              <!-- row : START -->
              <div class="row col-12">
                <!-- deal fees : START -->
                <div
                  id="deal-guarantees-table"
                  class="content-wrapper mt-0 p-0 col-12"
                >
                  <table class="list-page-content-p mt-0 mb-0 w-100">
                    <thead>
                      <tr class="bg-grey">
                        <th class="text-center">Company Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center" style="min-width:75px;">
                          Amount
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Tax Type
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Taxes
                        </th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="deal_replicator_guarantee in deal_replicator_guarantees"
                        :key="
                          `deal_replicator_guarantee` +
                            deal_replicator_guarantee.id
                        "
                      >
                        <td class="text-center">
                          {{
                            deal_replicator_guarantee.company_name
                              | truncate(25, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            deal_replicator_guarantee.description
                              | truncate(50, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_guarantee.amount
                            ).toLocaleString()}`
                          }}
                        </td>
                        <td class="text-center">
                          <p class="mb-0">
                            {{ deal_replicator_guarantee.tax_type }}
                          </p>
                          <p
                            class="mb-0"
                            v-show="
                              deal_replicator_guarantee.tax_type_secondary !==
                                ''
                            "
                          >
                            {{ deal_replicator_guarantee.tax_type_secondary }}
                          </p>
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_guarantee.tax_amount_total
                            ).toLocaleString()}`
                          }}
                        </td>
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
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  editDealGuarantee(
                                    deal_replicator_guarantee.id
                                  )
                                "
                                >Edit</a
                              >
                              <a
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  deleteDealGuarantee(
                                    deal_replicator_guarantee.id
                                  )
                                "
                                >Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- deal fees : END -->
              </div>
              <!-- row : END -->
            </div>
          </div>
        </div>
        <!-- card : END -->

        <!-- card : START -->
        <div class="card" v-show="isDealRetailOrWholesale == 0">
          <div class="card-content">
            <div class="card-body pb-1 mx-25 row">
              <h4 class="card-title col-8">
                <i
                  class="bx bxs-first-aid bx-sm"
                  style="padding-right: 1em;"
                ></i>
                <span style="display:inline-block;vertical-align:top;"
                  >Insurances</span
                >
              </h4>
              <div class="col-4 text-right">
                <button
                  class="btn btn-primary text-bold-700"
                  data-toggle="modal"
                  data-target="#dealInsuranceModal"
                >
                  <i class="bx bx-list-plus" style="padding-right: 1em;"></i>
                  Add Insurance
                </button>
              </div>
              <!-- row : START -->
              <div class="row col-12">
                <!-- deal fees : START -->
                <div
                  id="deal-insurances-table"
                  class="content-wrapper mt-0 p-0 col-12"
                >
                  <table class="list-page-content-p mt-0 mb-0 w-100">
                    <thead>
                      <tr class="bg-grey">
                        <th class="text-center">Type</th>
                        <th class="text-center">Description</th>
                        <th class="text-center" style="min-width:75px;">
                          Amount
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Tax Type
                        </th>
                        <th class="text-center" style="min-width:75px;">
                          Taxes
                        </th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="deal_replicator_insurance in deal_replicator_insurances"
                        :key="
                          `deal_replicator_insurance` +
                            deal_replicator_insurance.id
                        "
                      >
                        <td class="text-center">
                          {{
                            deal_replicator_insurance.type | truncate(25, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            deal_replicator_insurance.description
                              | truncate(50, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_insurance.amount
                            ).toLocaleString()}`
                          }}
                        </td>
                        <td class="text-center">
                          <p class="mb-0">
                            {{ deal_replicator_insurance.tax_type }}
                          </p>
                          <p
                            class="mb-0"
                            v-show="
                              deal_replicator_insurance.tax_type_secondary !==
                                ''
                            "
                          >
                            {{ deal_replicator_insurance.tax_type_secondary }}
                          </p>
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_insurance.tax_amount_total
                            ).toLocaleString()}`
                          }}
                        </td>
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
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  editDealInsurance(
                                    deal_replicator_insurance.id
                                  )
                                "
                                >Edit</a
                              >
                              <a
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  deleteDealInsurance(
                                    deal_replicator_insurance.id
                                  )
                                "
                                >Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- deal fees : END -->
              </div>
              <!-- row : END -->
            </div>
          </div>
        </div>
        <!-- card : END -->

        <!-- card : START -->
        <div class="card" v-show="isDealRetailOrWholesale == 0">
          <div class="card-content">
            <div class="card-body pb-1 mx-25 row">
              <h4 class="card-title col-8">
                <i
                  class="bx bxs-credit-card-alt bx-sm"
                  style="padding-right: 1em;"
                ></i>
                <span style="display:inline-block;vertical-align:top;"
                  >Payments</span
                >
              </h4>
              <div class="col-4 text-right">
                <button
                  class="btn btn-primary text-bold-700"
                  data-toggle="modal"
                  data-target="#dealPaymentModal"
                >
                  <i class="bx bx-list-plus" style="padding-right: 1em;"></i>
                  Add Payment
                </button>
              </div>
              <!-- row : START -->
              <div class="row col-12">
                <!-- deal fees : START -->
                <div
                  id="deal-payments-table"
                  class="content-wrapper mt-0 p-0 col-12"
                >
                  <table class="list-page-content-p mt-0 mb-0 w-100">
                    <thead>
                      <tr class="bg-grey">
                        <th class="text-center">Type</th>
                        <th class="text-center">Description</th>
                        <th class="text-center" style="min-width:75px;">
                          Amount
                        </th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="deal_replicator_payment in deal_replicator_payments"
                        :key="
                          `deal_replicator_payment` + deal_replicator_payment.id
                        "
                      >
                        <td class="text-center">
                          {{
                            deal_replicator_payment.type | truncate(25, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            deal_replicator_payment.description
                              | truncate(50, "...")
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            `$ ${Number(
                              deal_replicator_payment.amount
                            ).toLocaleString()}`
                          }}
                        </td>
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
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  editDealPayment(deal_replicator_payment.id)
                                "
                                >Edit</a
                              >
                              <a
                                class="dropdown-item"
                                style="cursor: pointer;"
                                @click.prevent="
                                  deleteDealPayment(deal_replicator_payment.id)
                                "
                                >Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- deal fees : END -->
              </div>
              <!-- row : END -->
            </div>
          </div>
        </div>
        <!-- card : END -->
      </div>
    </section>
    <!-- form layout section end -->
    <!-- Model Deal Fees : START -->
    <div
      class="modal fade"
      id="dealFeeModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Fee Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_replicator_fees_modal_title }}
            </h5>
            <button
              type="button"
              id="dealFeeModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Fee : </label>
                  <v-select
                    taggable
                    placeholder="Search Fee Name"
                    label="name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_cost_name"
                    clearable
                    :options="cost_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_fees_name">Name : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'name'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.name"
                    :title="deal_replicator_fees_errors.name"
                    v-model="deal_replicator_fees_form.name"
                    id="deal_replicator_fees_name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <!-- name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_fees_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'description'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.description"
                    :title="deal_replicator_fees_errors.description"
                    v-model="deal_replicator_fees_form.description"
                    id="deal_replicator_fees_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_fees_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'amount'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.amount"
                    :title="deal_replicator_fees_errors.amount"
                    v-model="deal_replicator_fees_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_replicator_fees_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_fee_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'tax_type'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.tax_type"
                    :title="deal_replicator_fees_errors.tax_type"
                    v-model="deal_replicator_fees_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_fee_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
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

                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_fee_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_fees_errors.tax_type_secondary
                    "
                    :title="deal_replicator_fees_errors.tax_type_secondary"
                    v-model="deal_replicator_fees_form.tax_type_secondary"
                    :disabled="!enableDealFeeTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_fee_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary_tax_type : END -->

                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_fees_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'tax_amount_total'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.tax_amount_total"
                    :title="deal_replicator_fees_errors.tax_amount_total"
                    v-model="deal_replicator_fees_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealFeeTaxEditing"
                    class="form-control"
                    id="deal_replicator_fees_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealFeeCost"
                aria-expanded="false"
                aria-controls="collapseDealFeeCost"
              >
                 <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealFeeCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="fee_replicator_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'cost'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.cost"
                    :title="deal_replicator_fees_errors.cost"
                    v-model="deal_replicator_fees_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="fee_replicator_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="fee_cost_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'cost_tax_type'
                      ),
                    }"
                    @keyup="delete deal_replicator_fees_errors.cost_tax_type"
                    :title="deal_replicator_fees_errors.cost_tax_type"
                    v-model="deal_replicator_fees_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="fee_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
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

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="fee_replicator_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_fees_errors.cost_tax_type_secondary
                    "
                    :title="deal_replicator_fees_errors.cost_tax_type_secondary"
                    v-model="deal_replicator_fees_form.cost_tax_type_secondary"
                    :disabled="!enableDealFeeCostTaxEditing"
                    placeholder="Tax Type"
                    id="fee_replicator_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="fee_replicator__cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_fees_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_fees_errors.cost_tax_amount_total
                    "
                    :title="deal_replicator_fees_errors.cost_tax_amount_total"
                    v-model="deal_replicator_fees_form.cost_tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealFeeCostTaxEditing"
                    class="form-control"
                    id="fee_replicator__cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
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
              @click.prevent="saveDealFee"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Deal Fees : END -->

    <!-- Model Deal Accessories : START -->
    <div
      class="modal fade"
      id="dealAccessoryModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Accessory Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_replicator_accessories_modal_title }}
            </h5>
            <button
              type="button"
              id="dealAccessoryModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Accessory : </label>

                  <v-select
                    taggable
                    placeholder="Search Accessory Name"
                    label="name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_accessory_name"
                    clearable
                    :options="accessory_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_accessories_name">Name : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'name'
                      ),
                    }"
                    @keyup="delete deal_replicator_accessories_errors.name"
                    :title="deal_replicator_accessories_errors.name"
                    v-model="deal_replicator_accessories_form.name"
                    id="deal_replicator_accessories_name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <!-- name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_accessories_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'description'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_accessories_errors.description
                    "
                    :title="deal_replicator_accessories_errors.description"
                    v-model="deal_replicator_accessories_form.description"
                    id="deal_replicator_accessories_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'amount'
                      ),
                    }"
                    @keyup="delete deal_replicator_accessories_errors.amount"
                    :title="deal_replicator_accessories_errors.amount"
                    v-model="deal_replicator_accessories_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_replicator_accessories_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'tax_type'
                      ),
                    }"
                    @keyup="delete deal_replicator_accessories_errors.tax_type"
                    :title="deal_replicator_accessories_errors.tax_type"
                    v-model="deal_replicator_accessories_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_accessories_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                      >{{ tax.name }}</option
                    >
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_accessories_errors.tax_type_secondary
                    "
                    :title="
                      deal_replicator_accessories_errors.tax_type_secondary
                    "
                    v-model="
                      deal_replicator_accessories_form.tax_type_secondary
                    "
                    :disabled="!enableDealAccessoryTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_accessories_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                      >{{ tax.name }}</option
                    >
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_accessories_errors.tax_amount_total
                    "
                    :title="deal_replicator_accessories_errors.tax_amount_total"
                    v-model="deal_replicator_accessories_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealAccessoryTaxEditing"
                    class="form-control"
                    id="deal_replicator_accessories_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealAccessoryCost"
                aria-expanded="false"
                aria-controls="collapseDealAccessoryCost"
              >
                 <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealAccessoryCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'cost'
                      ),
                    }"
                    @keyup="delete deal_replicator_accessories_errors.cost"
                    :title="deal_replicator_accessories_errors.cost"
                    v-model="deal_replicator_accessories_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_replicator_accessories_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'cost_tax_type'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_accessories_errors.cost_tax_type
                    "
                    :title="deal_replicator_accessories_errors.cost_tax_type"
                    v-model="deal_replicator_accessories_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_accessories_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
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

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_accessories_errors.cost_tax_type_secondary
                    "
                    :title="
                      deal_replicator_accessories_errors.cost_tax_type_secondary
                    "
                    v-model="
                      deal_replicator_accessories_form.cost_tax_type_secondary
                    "
                    :disabled="!enableDealAccessoryCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_accessories_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_accessories_cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_accessories_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_accessories_errors.cost_tax_amount_total
                    "
                    :title="
                      deal_replicator_accessories_errors.cost_tax_amount_total
                    "
                    v-model="
                      deal_replicator_accessories_form.cost_tax_amount_total
                    "
                    placeholder="Tax Amount"
                    :readonly="enableDealAccessoryCostTaxEditing"
                    class="form-control"
                    id="deal_replicator_accessories_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
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
              @click.prevent="saveDealAccessory"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Deal Accessories : END -->

    <!-- Model Warranty : START -->
    <div
      class="modal fade"
      id="dealWarrantyModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Warranty Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_replicator_warranties_modal_title }}
            </h5>
            <button
              type="button"
              id="dealWarrantyModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Warranty : </label>
                  <v-select
                    taggable
                    placeholder="Search Warranty Name"
                    label="company_name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_warranty_name"
                    clearable
                    :options="warranty_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- company_name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_warranties_company_name"
                    >Company Name :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'company_name'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.company_name
                    "
                    :title="deal_replicator_warranties_errors.company_name"
                    v-model="deal_replicator_warranties_form.company_name"
                    id="deal_replicator_warranties_company_name"
                    placeholder="Company Name"
                  />
                </div>
              </div>
              <!-- company_name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_warranties_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'description'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.description
                    "
                    :title="deal_replicator_warranties_errors.description"
                    v-model="deal_replicator_warranties_form.description"
                    id="deal_replicator_warranties_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- duration : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_warranties_duration"
                    >Duration (Months):
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'duration'
                      ),
                    }"
                    @keyup="delete deal_replicator_warranties_errors.duration"
                    :title="deal_replicator_warranties_errors.duration"
                    v-model="deal_replicator_warranties_form.duration"
                    id="deal_replicator_warranties_duration"
                    placeholder="Duration"
                  />
                </div>
              </div>
              <!-- duration : END -->
              <!-- distance : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_warranties_distance"
                    >Distance :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'distance'
                      ),
                    }"
                    @keyup="delete deal_replicator_warranties_errors.distance"
                    :title="deal_replicator_warranties_errors.distance"
                    v-model="deal_replicator_warranties_form.distance"
                    id="deal_replicator_warranties_distance"
                    placeholder="Distance"
                  />
                </div>
              </div>
              <!-- distance : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranties_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'amount'
                      ),
                    }"
                    @keyup="delete deal_replicator_warranties_errors.amount"
                    :title="deal_replicator_warranties_errors.amount"
                    v-model="deal_replicator_warranties_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_replicator_warranties_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranty_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'tax_type'
                      ),
                    }"
                    @keyup="delete deal_replicator_warranties_errors.tax_type"
                    :title="deal_replicator_warranties_errors.tax_type"
                    v-model="deal_replicator_warranties_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_warranty_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                      >{{ tax.name }}</option
                    >
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranty_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.tax_type_secondary
                    "
                    :title="
                      deal_replicator_warranties_errors.tax_type_secondary
                    "
                    v-model="deal_replicator_warranties_form.tax_type_secondary"
                    :disabled="!enableDealWarrantyTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_warranty_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                      >{{ tax.name }}</option
                    >
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranties_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.tax_amount_total
                    "
                    :title="deal_replicator_warranties_errors.tax_amount_total"
                    v-model="deal_replicator_warranties_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealWarrantyTaxEditing"
                    class="form-control"
                    id="deal_replicator_warranties_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealWarrantyCost"
                aria-expanded="false"
                aria-controls="collapseDealWarrantyCost"
              >
                 <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealWarrantyCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranties_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'cost'
                      ),
                    }"
                    @keyup="delete deal_replicator_warranties_errors.cost"
                    :title="deal_replicator_warranties_errors.cost"
                    v-model="deal_replicator_warranties_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_replicator_warranties_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranties_cost_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'cost_tax_type'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.cost_tax_type
                    "
                    :title="deal_replicator_warranties_errors.cost_tax_type"
                    v-model="deal_replicator_warranties_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_warranties_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
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

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label
                    for="deal_replicator_warranties_cost_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.cost_tax_type_secondary
                    "
                    :title="
                      deal_replicator_warranties_errors.cost_tax_type_secondary
                    "
                    v-model="
                      deal_replicator_warranties_form.cost_tax_type_secondary
                    "
                    :disabled="!enableDeaWarrantyCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_warranties_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_warranties_cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_warranties_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_warranties_errors.cost_tax_amount_total
                    "
                    :title="
                      deal_replicator_warranties_errors.cost_tax_amount_total
                    "
                    v-model="
                      deal_replicator_warranties_form.cost_tax_amount_total
                    "
                    placeholder="Tax Amount"
                    :readonly="enableDeaWarrantyCostTaxEditing"
                    class="form-control"
                    id="deal_replicator_warranties_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
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
              @click.prevent="saveDealWarranty"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Warranty : END -->

    <!-- Model Guarantee : START -->
    <div
      class="modal fade"
      id="dealGuaranteeModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Guarantee Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_replicator_guarantees_modal_title }}
            </h5>
            <button
              type="button"
              id="dealGuaranteeModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Guarantee : </label>
                  <v-select
                    taggable
                    placeholder="Search Guarantee Name"
                    label="company_name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_guarantee_name"
                    clearable
                    :options="guarantee_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- company_name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_guarantees_company_name"
                    >Company Name :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'company_name'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.company_name
                    "
                    :title="deal_replicator_guarantees_errors.company_name"
                    v-model="deal_replicator_guarantees_form.company_name"
                    id="deal_replicator_guarantees_company_name"
                    placeholder="Company Name"
                  />
                </div>
              </div>
              <!-- company_name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_guarantees_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'description'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.description
                    "
                    :title="deal_replicator_guarantees_errors.description"
                    v-model="deal_replicator_guarantees_form.description"
                    id="deal_replicator_guarantees_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- duration : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_guarantees_duration"
                    >Duration (Days):
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'duration'
                      ),
                    }"
                    @keyup="delete deal_replicator_guarantees_errors.duration"
                    :title="deal_replicator_guarantees_errors.duration"
                    v-model="deal_replicator_guarantees_form.duration"
                    id="deal_replicator_guarantees_duration"
                    placeholder="Duration"
                  />
                </div>
              </div>
              <!-- duration : END -->
              <!-- distance : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_guarantees_distance"
                    >Distance :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'distance'
                      ),
                    }"
                    @keyup="delete deal_replicator_guarantees_errors.distance"
                    :title="deal_replicator_guarantees_errors.distance"
                    v-model="deal_replicator_guarantees_form.distance"
                    id="deal_replicator_guarantees_distance"
                    placeholder="Distance"
                  />
                </div>
              </div>
              <!-- distance : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guarantees_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'amount'
                      ),
                    }"
                    @keyup="delete deal_replicator_guarantees_errors.amount"
                    :title="deal_replicator_guarantees_errors.amount"
                    v-model="deal_replicator_guarantees_form.amount"
                    placeholder="Amount"
                    readonly
                    class="form-control"
                    id="deal_replicator_guarantees_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guaranty_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'tax_type'
                      ),
                    }"
                    @keyup="delete deal_replicator_guarantees_errors.tax_type"
                    :title="deal_replicator_guarantees_errors.tax_type"
                    v-model="deal_replicator_guarantees_form.tax_type"
                    readonly
                    placeholder="Tax Type"
                    id="deal_replicator_guaranty_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                      >{{ tax.name }}</option
                    >
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guarantee_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.tax_type_secondary
                    "
                    :title="
                      deal_replicator_guarantees_errors.tax_type_secondary
                    "
                    v-model="deal_replicator_guarantees_form.tax_type_secondary"
                    readonly
                    placeholder="Tax Type"
                    id="deal_replicator_guarantee_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                      >{{ tax.name }}</option
                    >
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guarantees_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.tax_amount_total
                    "
                    :title="deal_replicator_guarantees_errors.tax_amount_total"
                    v-model="deal_replicator_guarantees_form.tax_amount_total"
                    placeholder="Tax Amount"
                    readonly
                    class="form-control"
                    id="deal_replicator_guarantees_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealGuaranteeCost"
                aria-expanded="false"
                aria-controls="collapseDealGuaranteeCost"
              >
                 <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealGuaranteeCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guarantees_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'cost'
                      ),
                    }"
                    @keyup="delete deal_replicator_guarantees_errors.cost"
                    :title="deal_replicator_guarantees_errors.cost"
                    v-model="deal_replicator_guarantees_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_replicator_guarantees_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guarantees_cost_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'cost_tax_type'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.cost_tax_type
                    "
                    :title="deal_replicator_guarantees_errors.cost_tax_type"
                    v-model="deal_replicator_guarantees_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_guarantees_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
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

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label
                    for="deal_replicator_guarantees_cost_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.cost_tax_type_secondary
                    "
                    :title="
                      deal_replicator_guarantees_errors.cost_tax_type_secondary
                    "
                    v-model="
                      deal_replicator_guarantees_form.cost_tax_type_secondary
                    "
                    :disabled="!enableDealGuranteeCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_guarantees_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_guarantees_cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_guarantees_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_guarantees_errors.cost_tax_amount_total
                    "
                    :title="
                      deal_replicator_guarantees_errors.cost_tax_amount_total
                    "
                    v-model="
                      deal_replicator_guarantees_form.cost_tax_amount_total
                    "
                    placeholder="Tax Amount"
                    :readonly="enableDealGuranteeCostTaxEditing"
                    class="form-control"
                    id="deal_replicator_guarantees_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
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
              @click.prevent="saveDealGuarantee"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Guarantee : END -->

    <!-- Model Insurance : START -->
    <div
      class="modal fade"
      id="dealInsuranceModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Insurance Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_replicator_insurances_modal_title }}
            </h5>
            <button
              type="button"
              id="dealInsuranceModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Insurance : </label>

                  <v-select
                    taggable
                    placeholder="Search Insurance"
                    label="label"
                    :reduce="(cost) => cost.id"
                    v-model="selected_insurance_name"
                    clearable
                    :options="insurance_setting_listing"
                  ></v-select>
                </div>
              </div>

              <!-- vselect : END -->
              <!-- type : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_insurances_type">Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'type'
                      ),
                    }"
                    @keyup="delete deal_replicator_insurances_errors.type"
                    :title="deal_replicator_insurances_errors.type"
                    v-model="deal_replicator_insurances_form.type"
                    placeholder="Type"
                    id="deal_replicator_insurances_type"
                  >
                    <option value="" selected disabled>Type</option>
                    <option value="Life">Life</option>
                    <option value="Disability">Disability</option>
                    <option value="GAP">GAP</option>
                    <option value="Income">Income</option>
                    <option value="Walkaway">Walkaway</option>
                    <option value="Sickness">Sickness</option>
                    <option value="Injury">Injury</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>
              <!-- type : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_insurances_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'description'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_insurances_errors.description
                    "
                    :title="deal_replicator_insurances_errors.description"
                    v-model="deal_replicator_insurances_form.description"
                    id="deal_replicator_insurances_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- duration : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_insurances_duration"
                    >Duration :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'duration'
                      ),
                    }"
                    @keyup="delete deal_replicator_insurances_errors.duration"
                    :title="deal_replicator_insurances_errors.duration"
                    v-model="deal_replicator_insurances_form.duration"
                    id="deal_replicator_insurances_duration"
                    placeholder="Duration"
                  />
                </div>
              </div>
              <!-- duration : END -->
              <!-- deductible : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_insurances_deductible"
                    >Deductible :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'deductible'
                      ),
                    }"
                    @keyup="delete deal_replicator_insurances_errors.deductible"
                    :title="deal_replicator_insurances_errors.deductible"
                    v-model="deal_replicator_insurances_form.deductible"
                    id="deal_replicator_insurances_deductible"
                    placeholder="Deductible"
                  />
                </div>
              </div>
              <!-- deductible : END -->

              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_insurances_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'amount'
                      ),
                    }"
                    @keyup="delete deal_replicator_insurances_errors.amount"
                    :title="deal_replicator_insurances_errors.amount"
                    v-model="deal_replicator_insurances_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_replicator_insurances_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_fee_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'tax_type'
                      ),
                    }"
                    @keyup="delete deal_replicator_insurances_errors.tax_type"
                    :title="deal_replicator_insurances_errors.tax_type"
                    v-model="deal_replicator_insurances_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_fee_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                      >{{ tax.name }}</option
                    >
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_insurance_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_insurances_errors.tax_type_secondary
                    "
                    :title="
                      deal_replicator_insurances_errors.tax_type_secondary
                    "
                    v-model="deal_replicator_insurances_form.tax_type_secondary"
                    :disabled="!enableDealInsuranceTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_insurance_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                      >{{ tax.name }}</option
                    >
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_insurances_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_insurances_errors.tax_amount_total
                    "
                    :title="deal_replicator_insurances_errors.tax_amount_total"
                    v-model="deal_replicator_insurances_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealInsuranceTaxEditing"
                    class="form-control"
                    id="deal_replicator_insurances_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealInsuranceCost"
                aria-expanded="false"
                aria-controls="collapseDealInsuranceCost"
              >
                 <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealInsuranceCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_insurances_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'cost'
                      ),
                    }"
                    @keyup="delete deal_replicator_insurances_errors.cost"
                    :title="deal_replicator_insurances_errors.cost"
                    v-model="deal_replicator_insurances_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_replicator_insurances_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_insurances_cost_tax_type"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'cost_tax_type'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_insurances_errors.cost_tax_type
                    "
                    :title="deal_replicator_insurances_errors.cost_tax_type"
                    v-model="deal_replicator_insurances_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_replicator_insurances_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
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

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label
                    for="deal_replicator_insurances_cost_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_insurances_errors.cost_tax_type_secondary
                    "
                    :title="
                      deal_replicator_insurances_errors.cost_tax_type_secondary
                    "
                    v-model="
                      deal_replicator_insurances_form.cost_tax_type_secondary
                    "
                    :disabled="!enableDealInsuranceCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_replicator_insurances_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_replicator_insurances_cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_insurances_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_replicator_insurances_errors.cost_tax_amount_total
                    "
                    :title="
                      deal_replicator_insurances_errors.cost_tax_amount_total
                    "
                    v-model="
                      deal_replicator_insurances_form.cost_tax_amount_total
                    "
                    placeholder="Tax Amount"
                    :readonly="enableDealInsuranceCostTaxEditing"
                    class="form-control"
                    id="deal_replicator_insurances_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
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
              @click.prevent="saveDealInsurance"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Insurance : END -->

    <!-- Model Payment : START -->
    <div
      class="modal fade"
      id="dealPaymentModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Payment Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_replicator_payments_modal_title }}
            </h5>
            <button
              type="button"
              id="dealPaymentModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <!-- vselect : END -->
              <!-- type : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_payments_type">Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_replicator_payments_errors.hasOwnProperty(
                        'type'
                      ),
                    }"
                    @keyup="delete deal_replicator_payments_errors.type"
                    :title="deal_replicator_payments_errors.type"
                    v-model="deal_replicator_payments_form.type"
                    placeholder="Type"
                    id="deal_replicator_payments_type"
                  >
                    <option value="" selected disabled>Type</option>
                    <option value="Cash">Cash</option>
                    <option value="Debit">Debit</option>
                    <option value="Credit">Credit</option>
                    <option value="Cheque">Cheque</option>
                    <option value="EFT">EFT</option>
                  </select>
                </div>
              </div>
              <!-- type : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_replicator_payments_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_replicator_payments_errors.hasOwnProperty(
                        'description'
                      ),
                    }"
                    @keyup="delete deal_replicator_payments_errors.description"
                    :title="deal_replicator_payments_errors.description"
                    v-model="deal_replicator_payments_form.description"
                    id="deal_replicator_payments_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->

              <!-- downpayment or deposit : START -->
              <div class="form-row">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <label
                      for=""
                      style="inline-block;vertical-align:super;"
                      class="mr-1"
                      >Is Downpayment or Deposit ?
                    </label>
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        v-model="
                          deal_replicator_payments_form.is_downpayment_or_deposit
                        "
                        type="radio"
                        id="downpayment_payment"
                        value="0"
                      />
                      <label class="form-check-label" for="downpayment_payment"
                        >Downpayment</label
                      >
                    </div>
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        v-model="
                          deal_replicator_payments_form.is_downpayment_or_deposit
                        "
                        type="radio"
                        id="deposit_payment"
                        value="1"
                      />
                      <label class="form-check-label" for="deposit_payment"
                        >Deposit</label
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- downpayment or deposit : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-12">
                  <label for="deal_replicator_payments_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_replicator_payments_errors.hasOwnProperty(
                        'amount'
                      ),
                    }"
                    @keyup="delete deal_replicator_payments_errors.amount"
                    :title="deal_replicator_payments_errors.amount"
                    v-model="deal_replicator_payments_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_replicator_payments_amount"
                  />
                </div>
                <!-- amount : END -->
              </div>
              <!-- combindation : END -->
            </form>
          </div>
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
              @click.prevent="saveDealPayment"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Payment : END -->
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import Toast from "../../../library/Toast";

export default {
  name: "DealReplicatorWorksheet",
  props: {
    retail_or_wholesale_flag: { required: true },
  },
  data() {
    return {
      load: true,

      deal_replicator_fees_errors: [],
      deal_replicator_fees: [],
      deal_replicator_fees_modal_title: "Add Deal Fee",
      deal_replicator_fee_id: null,
      deal_replicator_fees_form: {
        name: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_replicator_accessories_errors: [],
      deal_replicator_accessories: [],
      deal_replicator_accessories_modal_title: "Add New Accessory",
      deal_replicator_accessories_id: null,
      deal_replicator_accessories_form: {
        name: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_replicator_warranties_errors: [],
      deal_replicator_warranties: [],
      deal_replicator_warranties_modal_title: "Add New Warranty",
      deal_replicator_warranties_id: null,
      deal_replicator_warranties_form: {
        company_name: "",
        duration: 0,
        distance: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_replicator_guarantees_errors: [],
      deal_replicator_guarantees: [],
      deal_replicator_guarantees_modal_title: "Add New Guarantee",
      deal_replicator_guarantees_id: null,
      deal_replicator_guarantees_form: {
        company_name: "",
        duration: 0,
        distance: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_replicator_insurances_errors: [],
      deal_replicator_insurances: [],
      deal_replicator_insurances_modal_title: "Add New Insurance",
      deal_replicator_insurances_id: null,
      deal_replicator_insurances_form: {
        type: "",
        amount: 0,
        deductible: 0,
        duration: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_replicator_payments_errors: [],
      deal_replicator_payments: [],
      deal_replicator_payments_modal_title: "Add New Payment",
      deal_replicator_payments_id: null,
      deal_replicator_payments_form: {
        amount: 0,
        description: "",
        type: "",
        is_downpayment_or_deposit: "0",
      },

      cost_setting_listing: [],
      selected_cost_name: "",

      warranty_setting_listing: [],
      selected_warranty_name: "",

      guarantee_setting_listing: [],
      selected_guarantee_name: "",

      selected_accessory_name: "",
      accessory_setting_listing: [],

      selected_insurance_name: "",
      insurance_setting_listing: [],
    };
  },
  components: {
    "v-select": vSelect,
  },
  watch: {
    selected_cost_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getCostSetting(newVal);
        }
      }
    },
    selected_warranty_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getWarrantySetting(newVal);
        }
      }
    },
    selected_guarantee_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getGuaranteeSetting(newVal);
        }
      }
    },
    selected_accessory_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getAccessorySetting(newVal);
        }
      }
    },
    selected_insurance_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getInsuranceSetting(newVal);
        }
      }
    },
    calculateDealFeeTax: function(newVal, oldVal) {
      console.log("");
    },
    calculateDealAccessoryTax: function(newVal, oldVal) {
      console.log("");
    },
    calculateDealWarrantyTax: function(newVal, oldVal) {
      console.log("");
    },
    calculateDealInsuranceTax: function(newVal, oldVal) {
      console.log("");
    },
    calculateDealGuaranteeTax: function() {
      console.log("");
    },
    businessTaxSettings: function(newVal, oldVal) {
      if (this.load == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1"
        );

        console.log(selected_tax);
        if (selected_tax.length > 0) {
          this.deal_replicator_fees_form.tax_type = selected_tax[0].name;
          this.deal_replicator_fees_form.cost_tax_type = selected_tax[0].name;

          this.deal_replicator_accessories_form.tax_type = selected_tax[0].name;
          this.deal_replicator_accessories_form.cost_tax_type =
            selected_tax[0].name;

          this.deal_replicator_warranties_form.tax_type = selected_tax[0].name;
          this.deal_replicator_warranties_form.cost_tax_type =
            selected_tax[0].name;

          this.deal_replicator_guarantees_form.tax_type = selected_tax[0].name;
          this.deal_replicator_guarantees_form.cost_tax_type =
            selected_tax[0].name;

          this.deal_replicator_insurances_form.tax_type = selected_tax[0].name;
          this.deal_replicator_insurances_form.cost_tax_type =
            selected_tax[0].name;

          //   this.deal_replicator_dealbreakdown_form.purchase_tax_type = selected_tax[0].name;

          if (selected_tax.length > 1) {
            this.deal_replicator_fees_form.tax_type_secondary =
              selected_tax[1].name;
            this.deal_replicator_fees_form.cost_tax_type = selected_tax[1].name;

            this.deal_replicator_accessories_form.tax_type_secondary =
              selected_tax[1].name;
            this.deal_replicator_accessories_form.cost_tax_type =
              selected_tax[1].name;

            this.deal_replicator_warranties_form.tax_type_secondary =
              selected_tax[1].name;
            this.deal_replicator_warranties_form.cost_tax_type =
              selected_tax[1].name;

            this.deal_replicator_guarantees_form.tax_type_secondary =
              selected_tax[1].name;
            this.deal_replicator_guarantees_form.cost_tax_type =
              selected_tax[1].name;

            this.deal_replicator_insurances_form.tax_type_secondary =
              selected_tax[1].name;
            this.deal_replicator_insurances_form.cost_tax_type =
              selected_tax[1].name;

            //   this.deal_replicator_dealbreakdown_form.purchase_tax_type_secondary = selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
    "deal_replicator_payments_form.amount": function(newVal, oldVal) {
      let amount =
        !isNaN(this.deal_replicator_payments_form.amount) &&
        this.deal_replicator_payments_form.amount != ""
          ? this.deal_replicator_payments_form.amount
          : 0;
      this.deal_replicator_payments_form.amount = amount;
    },
    "deal_replicator_guarantees_form.tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_guarantees_form.tax_percentage_secondary = 0;
        } else {
          this.deal_replicator_guarantees_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_guarantees_form.tax_percentage_secondary = 0;
      }
    },
    "deal_replicator_guarantees_form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_guarantees_form.tax_percentage = "";
        } else {
          this.deal_replicator_guarantees_form.tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_guarantees_form.tax_percentage = "";
      }
    },
    "deal_replicator_guarantees_form.cost_tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_guarantees_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_guarantees_form.cost_tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_guarantees_form.cost_tax_percentage = "";
      }
    },
    "deal_replicator_guarantees_form.cost_tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_guarantees_form.cost_tax_percentage_secondary =
            "";
        } else {
          this.deal_replicator_guarantees_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_guarantees_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_replicator_insurances_form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_insurances_form.tax_percentage = "";
        } else {
          this.deal_replicator_insurances_form.tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_insurances_form.tax_percentage = "";
      }
    },
    "deal_replicator_insurances_form.tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_insurances_form.tax_percentage_secondary = 0;
        } else {
          this.deal_replicator_insurances_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_insurances_form.tax_percentage_secondary = 0;
      }
    },
    "deal_replicator_insurances_form.cost_tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_insurances_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_insurances_form.cost_tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_insurances_form.cost_tax_percentage = "";
      }
    },
    "deal_replicator_insurances_form.cost_tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_insurances_form.cost_tax_percentage_secondary =
            "";
        } else {
          this.deal_replicator_insurances_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_insurances_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_replicator_warranties_form.duration": function(newVal, oldVal) {
      let duration =
        !isNaN(this.deal_replicator_warranties_form.duration) &&
        this.deal_replicator_warranties_form.duration != ""
          ? parseFloat(this.deal_replicator_warranties_form.duration)
          : 0;
      this.deal_replicator_warranties_form.duration = duration;
    },
    "deal_replicator_insurances_form.duration": function(newVal, oldVal) {
      let duration =
        !isNaN(this.deal_replicator_insurances_form.duration) &&
        this.deal_replicator_insurances_form.duration != ""
          ? parseFloat(this.deal_replicator_insurances_form.duration)
          : 0;
      this.deal_replicator_insurances_form.duration = duration;
    },
    "deal_replicator_insurances_form.deductible": function(newVal, oldVal) {
      let deductible =
        !isNaN(this.deal_replicator_insurances_form.deductible) &&
        this.deal_replicator_insurances_form.deductible != ""
          ? parseFloat(this.deal_replicator_insurances_form.deductible)
          : 0;
      this.deal_replicator_insurances_form.deductible = deductible;
    },
    "deal_replicator_fees_form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_fees_form.tax_percentage = "";
        } else {
          this.deal_replicator_fees_form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_replicator_fees_form.tax_percentage = "";
      }
    },
    "deal_replicator_fees_form.tax_type_secondary": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_fees_form.tax_percentage_secondary = 0;
        } else {
          this.deal_replicator_fees_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_fees_form.tax_percentage_secondary = 0;
      }
    },
    "deal_replicator_fees_form.cost_tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_fees_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_fees_form.cost_tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_fees_form.cost_tax_percentage = "";
      }
    },
    "deal_replicator_fees_form.cost_tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_fees_form.cost_tax_percentage_secondary = "";
        } else {
          this.deal_replicator_fees_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_fees_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_replicator_accessories_form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_accessories_form.tax_percentage = "";
        } else {
          this.deal_replicator_accessories_form.tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_accessories_form.tax_percentage = "";
      }
    },
    "deal_replicator_accessories_form.tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_accessories_form.tax_percentage_secondary = 0;
        } else {
          this.deal_replicator_accessories_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_accessories_form.tax_percentage_secondary = 0;
      }
    },
    "deal_replicator_accessories_form.cost_tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_accessories_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_accessories_form.cost_tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_accessories_form.cost_tax_percentage = "";
      }
    },
    "deal_replicator_accessories_form.cost_tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_accessories_form.cost_tax_percentage_secondary =
            "";
        } else {
          this.deal_replicator_accessories_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_accessories_form.cost_tax_percentage_secondary =
          "";
      }
    },
    "deal_replicator_warranties_form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_warranties_form.tax_percentage = "";
        } else {
          this.deal_replicator_warranties_form.tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_warranties_form.tax_percentage = "";
      }
    },
    "deal_replicator_warranties_form.tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_warranties_form.tax_percentage_secondary = 0;
        } else {
          this.deal_replicator_warranties_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_warranties_form.tax_percentage_secondary = 0;
      }
    },
    "deal_replicator_warranties_form.cost_tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_warranties_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_warranties_form.cost_tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_warranties_form.cost_tax_percentage = "";
      }
    },
    "deal_replicator_warranties_form.cost_tax_type_secondary": function(
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_warranties_form.cost_tax_percentage_secondary =
            "";
        } else {
          this.deal_replicator_warranties_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_replicator_warranties_form.cost_tax_percentage_secondary = "";
      }
    },
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    isDealRetailOrWholesale() {
      return parseInt(this.retail_or_wholesale_flag);
    },
    feeCount() {
      return this.deal_replicator_fees.length;
    },
    warrantyCount() {
      return this.deal_replicator_warranties.length;
    },
    guaranteeCount() {
      return this.deal_replicator_guarantees.length;
    },
    enableDealFeeTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_fees_form.tax_percentage) &&
          this.deal_replicator_fees_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && !this.load) {
          this.deal_replicator_fees_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealFeeCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_fees_form.cost_tax_percentage) &&
          this.deal_replicator_fees_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_replicator_fees_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealAccessoryTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_accessories_form.tax_percentage) &&
          this.deal_replicator_accessories_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && !this.load) {
          this.deal_replicator_accessories_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealAccessoryCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_accessories_form.cost_tax_percentage) &&
          this.deal_replicator_accessories_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_replicator_accessories_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealWarrantyTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_warranties_form.tax_percentage) &&
          this.deal_replicator_warranties_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && !this.load) {
          this.deal_replicator_warranties_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDeaWarrantyCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_warranties_form.cost_tax_percentage) &&
          this.deal_replicator_warranties_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_replicator_warranties_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealGuranteeCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_guarantees_form.cost_tax_percentage) &&
          this.deal_replicator_guarantees_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_replicator_guarantees_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealInsuranceTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_insurances_form.tax_percentage) &&
          this.deal_replicator_insurances_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && !this.load) {
          this.deal_replicator_insurances_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealInsuranceCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.deal_replicator_insurances_form.cost_tax_percentage) &&
          this.deal_replicator_insurances_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_replicator_insurances_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    calculateDealFeeTax: {
      get: function() {
        let amount =
          !isNaN(this.deal_replicator_fees_form.amount) &&
          this.deal_replicator_fees_form.amount != ""
            ? this.deal_replicator_fees_form.amount
            : 0;
        this.deal_replicator_fees_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_replicator_fees_form.tax_percentage) &&
          this.deal_replicator_fees_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_replicator_fees_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_replicator_fees_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_replicator_fees_form.tax_amount = this.$helpers.round(tax, 3);

        let tax_secondary =
          !isNaN(this.deal_replicator_fees_form.tax_percentage_secondary) &&
          this.deal_replicator_fees_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(
                this.deal_replicator_fees_form.tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_replicator_fees_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_replicator_fees_form.tax_type == "Other") {
          this.deal_replicator_fees_form.tax_amount = 0;
          this.deal_replicator_fees_form.tax_amount_secondary = 0;
          tax = !isNaN(this.deal_replicator_fees_form.tax_amount_total)
            ? parseFloat(this.deal_replicator_fees_form.tax_amount_total)
            : 0;
        }

        this.deal_replicator_fees_form.tax_amount_total = this.$helpers.round(tax, 3);

        let cost =
          !isNaN(this.deal_replicator_fees_form.cost) &&
          this.deal_replicator_fees_form.cost != ""
            ? this.deal_replicator_fees_form.cost
            : 0;
        this.deal_replicator_fees_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_replicator_fees_form.cost_tax_percentage) &&
          this.deal_replicator_fees_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(this.deal_replicator_fees_form.cost_tax_percentage) *
              0.01
            : parseFloat(this.deal_replicator_fees_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_replicator_fees_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(
            this.deal_replicator_fees_form.cost_tax_percentage_secondary
          ) &&
          this.deal_replicator_fees_form.cost_tax_percentage_secondary != ""
            ? cost *
              parseFloat(
                this.deal_replicator_fees_form.cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_replicator_fees_form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.deal_replicator_fees_form.cost_tax_type == "Other") {
          this.deal_replicator_fees_form.cost_tax_amount = 0;
          this.deal_replicator_fees_form.cost_tax_amount_secondary = 0;
          cost_tax = !isNaN(
            this.deal_replicator_fees_form.cost_tax_amount_total
          )
            ? parseFloat(this.deal_replicator_fees_form.cost_tax_amount_total)
            : 0;
        }

        this.deal_replicator_fees_form.cost_tax_amount_total = this.$helpers.round(cost_tax, 3);
      },
    },
    calculateDealAccessoryTax: {
      get: function() {
        let amount =
          !isNaN(this.deal_replicator_accessories_form.amount) &&
          this.deal_replicator_accessories_form.amount != ""
            ? this.deal_replicator_accessories_form.amount
            : 0;
        this.deal_replicator_accessories_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_replicator_accessories_form.tax_percentage) &&
          this.deal_replicator_accessories_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_replicator_accessories_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_replicator_accessories_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_replicator_accessories_form.tax_amount = this.$helpers.round(
          tax,
          3
        );

        let tax_secondary =
          !isNaN(
            this.deal_replicator_accessories_form.tax_percentage_secondary
          ) &&
          this.deal_replicator_accessories_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(
                this.deal_replicator_accessories_form.tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_replicator_accessories_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_replicator_accessories_form.tax_type == "Other") {
          this.deal_replicator_accessories_form.tax_amount = 0;
          this.deal_replicator_accessories_form.tax_amount_secondary = 0;
          tax = !isNaN(this.deal_replicator_accessories_form.tax_amount_total)
            ? parseFloat(this.deal_replicator_accessories_form.tax_amount_total)
            : 0;
        }

        this.deal_replicator_accessories_form.tax_amount_total = this.$helpers.round(tax, 3);

        let cost =
          !isNaN(this.deal_replicator_accessories_form.cost) &&
          this.deal_replicator_accessories_form.cost != ""
            ? this.deal_replicator_accessories_form.cost
            : 0;
        this.deal_replicator_accessories_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_replicator_accessories_form.cost_tax_percentage) &&
          this.deal_replicator_accessories_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(
                this.deal_replicator_accessories_form.cost_tax_percentage
              ) *
              0.01
            : parseFloat(this.deal_replicator_accessories_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_replicator_accessories_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(
            this.deal_replicator_accessories_form.cost_tax_percentage_secondary
          ) &&
          this.deal_replicator_accessories_form.cost_tax_percentage_secondary !=
            ""
            ? cost *
              parseFloat(
                this.deal_replicator_accessories_form
                  .cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_replicator_accessories_form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.deal_replicator_accessories_form.cost_tax_type == "Other") {
          this.deal_replicator_accessories_form.cost_tax_amount = 0;
          this.deal_replicator_accessories_form.cost_tax_amount_secondary = 0;
          cost_tax = !isNaN(
            this.deal_replicator_accessories_form.cost_tax_amount_total
          )
            ? parseFloat(
                this.deal_replicator_accessories_form.cost_tax_amount_total
              )
            : 0;
        }

        this.deal_replicator_accessories_form.cost_tax_amount_total = this.$helpers.round(cost_tax, 3);
      },
    },
    calculateDealWarrantyTax: {
      get: function() {
        let amount =
          !isNaN(this.deal_replicator_warranties_form.amount) &&
          this.deal_replicator_warranties_form.amount != ""
            ? this.deal_replicator_warranties_form.amount
            : 0;
        this.deal_replicator_warranties_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_replicator_warranties_form.tax_percentage) &&
          this.deal_replicator_warranties_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_replicator_warranties_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_replicator_warranties_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_replicator_warranties_form.tax_amount = this.$helpers.round(
          tax,
          3
        );

        let tax_secondary =
          !isNaN(
            this.deal_replicator_warranties_form.tax_percentage_secondary
          ) &&
          this.deal_replicator_warranties_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(
                this.deal_replicator_warranties_form.tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_replicator_warranties_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_replicator_warranties_form.tax_type == "Other") {
          this.deal_replicator_warranties_form.tax_amount = 0;
          this.deal_replicator_warranties_form.tax_amount_secondary = 0;
          tax = !isNaN(this.deal_replicator_warranties_form.tax_amount_total)
            ? parseFloat(this.deal_replicator_warranties_form.tax_amount_total)
            : 0;
        }

        this.deal_replicator_warranties_form.tax_amount_total = this.$helpers.round(tax, 3);

        let cost =
          !isNaN(this.deal_replicator_warranties_form.cost) &&
          this.deal_replicator_warranties_form.cost != ""
            ? this.deal_replicator_warranties_form.cost
            : 0;
        this.deal_replicator_warranties_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_replicator_warranties_form.cost_tax_percentage) &&
          this.deal_replicator_warranties_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(
                this.deal_replicator_warranties_form.cost_tax_percentage
              ) *
              0.01
            : parseFloat(this.deal_replicator_warranties_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_replicator_warranties_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(
            this.deal_replicator_warranties_form.cost_tax_percentage_secondary
          ) &&
          this.deal_replicator_warranties_form.cost_tax_percentage_secondary !=
            ""
            ? cost *
              parseFloat(
                this.deal_replicator_warranties_form
                  .cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_replicator_warranties_form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.deal_replicator_warranties_form.cost_tax_type == "Other") {
          this.deal_replicator_warranties_form.cost_tax_amount = 0;
          this.deal_replicator_warranties_form.cost_tax_amount_secondary = 0;
          cost_tax = !isNaN(
            this.deal_replicator_warranties_form.cost_tax_amount_total
          )
            ? parseFloat(
                this.deal_replicator_warranties_form.cost_tax_amount_total
              )
            : 0;
        }

        this.deal_replicator_warranties_form.cost_tax_amount_total = this.$helpers.round(cost_tax, 3);
      },
    },
    calculateDealInsuranceTax: {
      get: function() {
        let amount =
          !isNaN(this.deal_replicator_insurances_form.amount) &&
          this.deal_replicator_insurances_form.amount != ""
            ? this.deal_replicator_insurances_form.amount
            : 0;
        this.deal_replicator_insurances_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_replicator_insurances_form.tax_percentage) &&
          this.deal_replicator_insurances_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_replicator_insurances_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_replicator_insurances_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_replicator_insurances_form.tax_amount = this.$helpers.round(
          tax,
          3
        );

        let tax_secondary =
          !isNaN(
            this.deal_replicator_insurances_form.tax_percentage_secondary
          ) &&
          this.deal_replicator_insurances_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(
                this.deal_replicator_insurances_form.tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_replicator_insurances_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_replicator_insurances_form.tax_type == "Other") {
          this.deal_replicator_insurances_form.tax_amount = 0;
          this.deal_replicator_insurances_form.tax_amount_secondary = 0;
          tax = !isNaN(this.deal_replicator_insurances_form.tax_amount_total)
            ? parseFloat(this.deal_replicator_insurances_form.tax_amount_total)
            : 0;
        }

        this.deal_replicator_insurances_form.tax_amount_total = this.$helpers.round(tax, 3);

        let cost =
          !isNaN(this.deal_replicator_insurances_form.cost) &&
          this.deal_replicator_insurances_form.cost != ""
            ? this.deal_replicator_insurances_form.cost
            : 0;
        this.deal_replicator_insurances_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_replicator_insurances_form.cost_tax_percentage) &&
          this.deal_replicator_insurances_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(
                this.deal_replicator_insurances_form.cost_tax_percentage
              ) *
              0.01
            : parseFloat(this.deal_replicator_insurances_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_replicator_insurances_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(
            this.deal_replicator_insurances_form.cost_tax_percentage_secondary
          ) &&
          this.deal_replicator_insurances_form.cost_tax_percentage_secondary !=
            ""
            ? cost *
              parseFloat(
                this.deal_replicator_insurances_form
                  .cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_replicator_insurances_form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.deal_replicator_insurances_form.cost_tax_type == "Other") {
          this.deal_replicator_insurances_form.cost_tax_amount = 0;
          this.deal_replicator_insurances_form.cost_tax_amount_secondary = 0;
          cost_tax = !isNaN(
            this.deal_replicator_insurances_form.cost_tax_amount_total
          )
            ? parseFloat(
                this.deal_replicator_insurances_form.cost_tax_amount_total
              )
            : 0;
        }

        this.deal_replicator_insurances_form.cost_tax_amount_total = this.$helpers.round(cost_tax, 3);
      },
    },
    calculateDealGuaranteeTax: {
      get: function() {
        let cost =
          !isNaN(this.deal_replicator_guarantees_form.cost) &&
          this.deal_replicator_guarantees_form.cost != ""
            ? this.deal_replicator_guarantees_form.cost
            : 0;
        this.deal_replicator_guarantees_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_replicator_guarantees_form.cost_tax_percentage) &&
          this.deal_replicator_guarantees_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(
                this.deal_replicator_guarantees_form.cost_tax_percentage
              ) *
              0.01
            : parseFloat(this.deal_replicator_guarantees_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_replicator_guarantees_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(
            this.deal_replicator_guarantees_form.cost_tax_percentage_secondary
          ) &&
          this.deal_replicator_guarantees_form.cost_tax_percentage_secondary !=
            ""
            ? cost *
              parseFloat(
                this.deal_replicator_guarantees_form
                  .cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_replicator_guarantees_form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.deal_replicator_guarantees_form.cost_tax_type == "Other") {
          this.deal_replicator_guarantees_form.cost_tax_amount = 0;
          this.deal_replicator_guarantees_form.cost_tax_amount_secondary = 0;
          cost_tax = !isNaN(
            this.deal_replicator_guarantees_form.cost_tax_amount_total
          )
            ? parseFloat(
                this.deal_replicator_guarantees_form.cost_tax_amount_total
              )
            : 0;
        }

        this.deal_replicator_guarantees_form.cost_tax_amount_total = this.$helpers.round(cost_tax, 3);
      },
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    getCostSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/setting/fees/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_replicator_fees_form) {
            if (
              this.deal_replicator_fees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_fees_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the fee setting";
          console.log(error);
        });
    },
    costSettingListing() {
      let request_url =
        window.location.origin + "/api/business/setting/fees?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.cost_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the cost settings");
        });
    },
    getAccessorySetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/setting/accessories/show/" +
          id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_replicator_accessories_form) {
            if (
              this.deal_replicator_accessories_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_accessories_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the accessory setting";
          console.log(error);
        });
    },
    accessorySettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/accessories?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.accessory_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the accessory settings");
        });
    },
    getInsuranceSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/setting/insurances/show/" +
          id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_replicator_insurances_form) {
            if (
              this.deal_replicator_insurances_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_insurances_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the insurance setting";
          console.log(error);
        });
    },
    insuranceSettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/insurances?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.insurance_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the insurance settings");
        });
    },
    resetDealAccessoryForm() {
      this.deal_replicator_accessories_form.name = "";
      this.deal_replicator_accessories_form.amount = 0;
      this.deal_replicator_accessories_form.description = "";
      this.deal_replicator_accessories_form.tax_type = "";
      this.deal_replicator_accessories_form.tax_percentage = 0;
      this.deal_replicator_accessories_form.tax_amount = 0;

      this.deal_replicator_accessories_modal_title = "Add New Accessory";

      this.deal_replicator_accessories_form.tax_type_secondary = "";
      this.deal_replicator_accessories_form.tax_amount_secondary = 0;
      this.deal_replicator_accessories_form.tax_percentage_secondary = 0;
      this.deal_replicator_accessories_form.tax_amount_secondary = 0;

      this.deal_replicator_accessories_errors = [];
      this.deal_replicator_accessories_id = null;

      this.deal_replicator_accessories_form.cost = 0;

      this.deal_replicator_accessories_form.cost_tax_type = "";
      this.deal_replicator_accessories_form.cost_tax_percentage = 0;
      this.deal_replicator_accessories_form.cost_tax_amount = 0;

      this.deal_replicator_accessories_form.cost_tax_type_secondary = "";
      this.deal_replicator_accessories_form.cost_tax_amount_secondary = 0;
      this.deal_replicator_accessories_form.cost_tax_percentage_secondary = 0;

      this.deal_replicator_accessories_form.cost_tax_amount_total = 0;

      this.selected_accessory_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_replicator_accessories_form.tax_type = selected_tax[0].name;
        this.deal_replicator_accessories_form.cost_tax_type =
          selected_tax[0].name;
        if (selected_tax.length > 1) {
          this.deal_replicator_accessories_form.tax_type_secondary =
            selected_tax[1].name;
          this.deal_replicator_accessories_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_accessories_form.tax_percentage = "";
          this.deal_replicator_accessories_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_accessories_form.tax_percentage =
            selected_tax[0].value;
          this.deal_replicator_accessories_form.cost_tax_percentage =
            selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_replicator_accessories_form.tax_percentage_secondary = 0;
            this.deal_replicator_accessories_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_replicator_accessories_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_replicator_accessories_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_replicator_accessories_form.tax_percentage_secondary = 0;
          this.deal_replicator_accessories_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_replicator_accessories_form.tax_percentage = "";
        this.deal_replicator_accessories_form.cost_tax_percentage = "";
      }
    },
    resetDealInsuranceForm() {
      this.deal_replicator_insurances_form.type = "";
      this.deal_replicator_insurances_form.amount = 0;
      this.deal_replicator_insurances_form.deductible = 0;
      this.deal_replicator_insurances_form.duration = 0;
      this.deal_replicator_insurances_form.description = "";
      this.deal_replicator_insurances_form.tax_type = "";
      this.deal_replicator_insurances_form.tax_percentage = 0;
      this.deal_replicator_insurances_form.tax_amount = 0;

      this.deal_replicator_insurances_form.tax_type_secondary = "";
      this.deal_replicator_insurances_form.tax_amount_secondary = 0;
      this.deal_replicator_insurances_form.tax_percentage_secondary = 0;
      this.deal_replicator_insurances_form.tax_amount_secondary = 0;

      this.deal_replicator_insurances_modal_title = "Add New Insurance";

      this.deal_replicator_insurances_errors = [];
      this.deal_replicator_insurances_id = null;

      this.deal_replicator_insurances_form.cost = 0;

      this.deal_replicator_insurances_form.cost_tax_type = "";
      this.deal_replicator_insurances_form.cost_tax_percentage = 0;
      this.deal_replicator_insurances_form.cost_tax_amount = 0;

      this.deal_replicator_insurances_form.cost_tax_type_secondary = "";
      this.deal_replicator_insurances_form.cost_tax_amount_secondary = 0;
      this.deal_replicator_insurances_form.cost_tax_percentage_secondary = 0;

      this.deal_replicator_insurances_form.cost_tax_amount_total = 0;

      this.selected_insurance_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_replicator_insurances_form.tax_type = selected_tax[0].name;
        this.deal_replicator_insurances_form.cost_tax_type =
          selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.deal_replicator_insurances_form.tax_type_secondary =
            selected_tax[1].name;
          this.deal_replicator_insurances_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_insurances_form.tax_percentage = "";
          this.deal_replicator_insurances_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_insurances_form.tax_percentage =
            selected_tax[0].value;
          this.deal_replicator_insurances_form.cost_tax_percentage =
            selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_replicator_insurances_form.tax_percentage_secondary = 0;
            this.deal_replicator_insurances_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_replicator_insurances_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_replicator_insurances_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_replicator_insurances_form.tax_percentage_secondary = 0;
          this.deal_replicator_insurances_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_replicator_insurances_form.tax_percentage = "";
        this.deal_replicator_insurances_form.cost_tax_percentage = "";
      }
    },
    resetDealPaymentForm() {
      this.deal_replicator_payments_form.amount = 0;
      this.deal_replicator_payments_form.description = "";
      this.deal_replicator_payments_form.type = "";
      this.deal_replicator_payments_form.is_downpayment_or_deposit = "0";

      this.deal_replicator_payments_modal_title = "Add New Payment";

      this.deal_replicator_payments_errors = [];
      this.deal_replicator_payments_id = null;
    },
    resetDealFeeForm() {
      this.deal_replicator_fees_form.name = "";
      this.deal_replicator_fees_form.amount = 0;
      this.deal_replicator_fees_form.description = "";
      this.deal_replicator_fees_form.tax_type = "";
      this.deal_replicator_fees_form.tax_percentage = 0;
      this.deal_replicator_fees_form.tax_amount = 0;

      this.deal_replicator_fees_modal_title = "Add New Expense";

      this.deal_replicator_fees_form.tax_type_secondary = "";
      this.deal_replicator_fees_form.tax_amount_secondary = 0;
      this.deal_replicator_fees_form.tax_percentage_secondary = 0;
      this.deal_replicator_fees_form.tax_amount_secondary = 0;

      this.deal_replicator_fees_form.cost = 0;

      this.deal_replicator_fees_form.cost_tax_type = "";
      this.deal_replicator_fees_form.cost_tax_percentage = 0;
      this.deal_replicator_fees_form.cost_tax_amount = 0;

      this.deal_replicator_fees_form.cost_tax_type_secondary = "";
      this.deal_replicator_fees_form.cost_tax_amount_secondary = 0;
      this.deal_replicator_fees_form.cost_tax_percentage_secondary = 0;

      this.deal_replicator_fees_form.cost_tax_amount_total = 0;

      this.deal_replicator_fees_errors = [];
      this.deal_replicator_fee_id = null;

      this.selected_cost_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_replicator_fees_form.tax_type = selected_tax[0].name;
        this.deal_replicator_fees_form.cost_tax_type = selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.deal_replicator_fees_form.tax_type_secondary =
            selected_tax[1].name;
          this.deal_replicator_fees_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_fees_form.tax_percentage = "";
          this.deal_replicator_fees_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_fees_form.tax_percentage = selected_tax[0].value;
          this.deal_replicator_fees_form.cost_tax_percentage =
            selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_replicator_fees_form.tax_percentage_secondary = 0;
            this.deal_replicator_fees_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_replicator_fees_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_replicator_fees_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_replicator_fees_form.tax_percentage_secondary = 0;
          this.deal_replicator_fees_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_replicator_fees_form.tax_percentage = "";
        this.deal_replicator_fees_form.cost_tax_percentage = "";
      }
    },
    resetDealWarrantyForm() {
      this.deal_replicator_warranties_form.company_name = "";
      this.deal_replicator_warranties_form.amount = 0;
      this.deal_replicator_warranties_form.description = "";
      this.deal_replicator_warranties_form.tax_type = "";
      this.deal_replicator_warranties_form.tax_percentage = 0;
      this.deal_replicator_warranties_form.tax_amount = 0;
      this.deal_replicator_warranties_form.duration = 0;
      this.deal_replicator_warranties_form.distance = "";

      this.deal_replicator_warranties_modal_title = "Add New Warranty";

      this.deal_replicator_warranties_form.tax_type_secondary = "";
      this.deal_replicator_warranties_form.tax_amount_secondary = 0;
      this.deal_replicator_warranties_form.tax_percentage_secondary = 0;
      this.deal_replicator_warranties_form.tax_amount_secondary = 0;

      this.deal_replicator_warranties_form.cost = 0;

      this.deal_replicator_warranties_form.cost_tax_type = "";
      this.deal_replicator_warranties_form.cost_tax_percentage = 0;
      this.deal_replicator_warranties_form.cost_tax_amount = 0;

      this.deal_replicator_warranties_form.cost_tax_type_secondary = "";
      this.deal_replicator_warranties_form.cost_tax_amount_secondary = 0;
      this.deal_replicator_warranties_form.cost_tax_percentage_secondary = 0;

      this.deal_replicator_warranties_form.cost_tax_amount_total = 0;

      this.deal_replicator_warranties_errors = [];
      this.deal_replicator_warranties_id = null;

      this.selected_warranty_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_replicator_warranties_form.tax_type = selected_tax[0].name;
        this.deal_replicator_warranties_form.cost_tax_type =
          selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.deal_replicator_warranties_form.tax_type_secondary =
            selected_tax[1].name;
          this.deal_replicator_warranties_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_warranties_form.tax_percentage = "";
          this.deal_replicator_warranties_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_warranties_form.tax_percentage =
            selected_tax[0].value;
          this.deal_replicator_warranties_form.cost_tax_percentage =
            selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_replicator_warranties_form.tax_percentage_secondary = 0;
            this.deal_replicator_warranties_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_replicator_warranties_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_replicator_warranties_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_replicator_warranties_form.tax_percentage_secondary = 0;
          this.deal_replicator_warranties_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_replicator_warranties_form.tax_percentage = "";
        this.deal_replicator_warranties_form.cost_tax_percentage = "";
      }
    },
    resetDealGuaranteeForm() {
      this.deal_replicator_guarantees_form.company_name = "";
      this.deal_replicator_guarantees_form.amount = 0;
      this.deal_replicator_guarantees_form.description = "";
      this.deal_replicator_guarantees_form.tax_type = "";
      this.deal_replicator_guarantees_form.tax_percentage = 0;
      this.deal_replicator_guarantees_form.tax_amount = 0;
      this.deal_replicator_guarantees_form.duration = 0;
      this.deal_replicator_guarantees_form.distance = "";

      this.deal_replicator_guarantees_form.tax_type_secondary = "";
      this.deal_replicator_guarantees_form.tax_amount_secondary = 0;
      this.deal_replicator_guarantees_form.tax_percentage_secondary = 0;
      this.deal_replicator_guarantees_form.tax_amount_secondary = 0;

      this.deal_replicator_guarantees_form.cost = 0;

      this.deal_replicator_guarantees_form.cost_tax_type = "";
      this.deal_replicator_guarantees_form.cost_tax_percentage = 0;
      this.deal_replicator_guarantees_form.cost_tax_amount = 0;

      this.deal_replicator_guarantees_form.cost_tax_type_secondary = "";
      this.deal_replicator_guarantees_form.cost_tax_amount_secondary = 0;
      this.deal_replicator_guarantees_form.cost_tax_percentage_secondary = 0;

      this.deal_replicator_guarantees_form.cost_tax_amount_total = 0;

      this.deal_replicator_guarantees_modal_title = "Add New Guarantee";

      this.deal_replicator_guarantees_errors = [];
      this.deal_replicator_guarantees_id = null;

      this.selected_guarantee_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_replicator_guarantees_form.tax_type = selected_tax[0].name;
        this.deal_replicator_guarantees_form.cost_tax_type =
          selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.deal_replicator_guarantees_form.tax_type_secondary =
            selected_tax[1].name;
          this.deal_replicator_guarantees_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_replicator_guarantees_form.tax_percentage = "";
          this.deal_replicator_guarantees_form.cost_tax_percentage = "";
        } else {
          this.deal_replicator_guarantees_form.tax_percentage =
            selected_tax[0].value;
          this.deal_replicator_guarantees_form.cost_tax_percentage =
            selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_replicator_guarantees_form.tax_percentage_secondary = 0;
            this.deal_replicator_guarantees_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_replicator_guarantees_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_replicator_guarantees_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_replicator_guarantees_form.tax_percentage_secondary = 0;
          this.deal_replicator_guarantees_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_replicator_guarantees_form.tax_percentage = "";
        this.deal_replicator_guarantees_form.cost_tax_percentage = "";
      }
    },
    async getDealFees(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/model-deals/" +
          deal_id +
          "/common/fees",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_replicator_fees = data;
        })
        .catch(({ response }) => {
          this.deal_replicator_fees = [];
        });
    },
    editDealFee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/fees/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_replicator_fees_errors = [];
          for (const key in this.deal_replicator_fees_form) {
            if (
              this.deal_replicator_fees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_fees_form[key] = data[key];
            }
          }

          this.deal_replicator_fee_id = data.id;
          this.deal_replicator_fees_modal_title = "Update Deal Fee";

          jQuery("#dealFeeModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching deal fee.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealFee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/fees/delete/`;
      url += id;

      let message = "Deleting deal fee...";
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
          let success_message = `Deal fee has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getDealFees(vm.$route.params.id);
            })();
          }
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting deal fee.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    async saveDealFee() {
      //save or update the deal fee
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_replicator_fees_form) {
        if (
          this.deal_replicator_fees_form.hasOwnProperty(key) &&
          this.deal_replicator_fees_form[key] != null
        ) {
          formData.append(key, this.deal_replicator_fees_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_replicator_fee_id) &&
        this.deal_replicator_fee_id != null;

      //success message setting
      let success_message = update_request
        ? `Deal fee updated.`
        : `Deal fee added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/${this.$route.params.id}/common/fees/update/${this.deal_replicator_fee_id}`
        : `${request_url}/api/business/model-deals/${this.$route.params.id}/common/fees/create`;

      //message during request
      let request_message = update_request
        ? `Updating deal fee ...`
        : `Adding new deal fee ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
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
          vm.resetDealFeeForm();
          document.getElementById("dealFeeModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getDealFees(vm.$route.params.id);
            })();
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.deal_replicator_fee_id) &&
            vm.deal_replicator_fee_id != null;

          let error_message = update_request
            ? "There were errors while updating the deal fee."
            : "There were some errors while adding deal fee.";

          vm.$data.deal_replicator_fees_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    editDealAccessory(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/accessories/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_replicator_accessories_errors = [];
          for (const key in this.deal_replicator_accessories_form) {
            if (
              this.deal_replicator_accessories_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_accessories_form[key] = data[key];
            }
          }

          this.deal_replicator_accessories_id = data.id;
          this.deal_replicator_accessories_modal_title = "Update Accessory";

          jQuery("#dealAccessoryModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching accessory.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealAccessory(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/accessories/delete/`;
      url += id;

      let message = "Deleting accessory...";
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
          let success_message = `Accessory has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getAccessories(vm.$route.params.id);
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting accessory.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    async getAccessories(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/model-deals/" +
          deal_id +
          "/common/accessories",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_replicator_accessories = data;
        })
        .catch(({ response }) => {
          this.deal_replicator_accessories = [];
          console.log(response);
        });
    },
    saveDealAccessory() {
      //save or update the deal fee
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_replicator_accessories_form) {
        if (
          this.deal_replicator_accessories_form.hasOwnProperty(key) &&
          this.deal_replicator_accessories_form[key] != null
        ) {
          formData.append(key, this.deal_replicator_accessories_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_replicator_accessories_id) &&
        this.deal_replicator_accessories_id != null;

      //success message setting
      let success_message = update_request
        ? `Accessory updated.`
        : `Accessory added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/${this.$route.params.id}/common/accessories/update/${this.deal_replicator_accessories_id}`
        : `${request_url}/api/business/model-deals/${this.$route.params.id}/common/accessories/create`;

      //message during request
      let request_message = update_request
        ? `Updating accessory ...`
        : `Adding new accessory ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
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
          vm.resetDealAccessoryForm();
          document.getElementById("dealAccessoryModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getAccessories(vm.$route.params.id);
            })();
          }
        })
        .catch(function(error) {
          let update_request = !isNaN(vm.deal_fee_id) && vm.deal_fee_id != null;

          let error_message = update_request
            ? "There were errors while updating the accessory."
            : "There were some errors while adding accessory.";

          vm.$data.deal_replicator_accessories_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    editDealWarranty(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/warranties/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_replicator_warranties_errors = [];
          for (const key in this.deal_replicator_warranties_form) {
            if (
              this.deal_replicator_warranties_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_warranties_form[key] = data[key];
            }
          }

          this.deal_replicator_warranties_id = data.id;
          this.deal_replicator_warranties_modal_title = "Update Warranty";

          jQuery("#dealWarrantyModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching warranty.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealWarranty(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/warranties/delete/`;
      url += id;

      let message = "Deleting warranty...";
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
          let success_message = `Warranty has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getWarranties(vm.$route.params.id);
            })();
          }
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting warranty.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealWarranty() {
      //save or update the warranty
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_replicator_warranties_form) {
        if (
          this.deal_replicator_warranties_form.hasOwnProperty(key) &&
          this.deal_replicator_warranties_form[key] != null
        ) {
          formData.append(key, this.deal_replicator_warranties_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_replicator_warranties_id) &&
        this.deal_replicator_warranties_id != null;

      //success message setting
      let success_message = update_request
        ? `Warranty updated.`
        : `Warranty added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/${this.$route.params.id}/common/warranties/update/${this.deal_replicator_warranties_id}`
        : `${request_url}/api/business/model-deals/${this.$route.params.id}/common/warranties/create`;

      //message during request
      let request_message = update_request
        ? `Updating warranty ...`
        : `Adding new warranty ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
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
          vm.resetDealWarrantyForm();
          document.getElementById("dealWarrantyModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getWarranties(vm.$route.params.id);
            })();
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.deal_replicator_warranties_id) &&
            vm.deal_replicator_warranties_id != null;

          let error_message = update_request
            ? "There were errors while updating the warranty."
            : "There were some errors while adding warranty.";

          vm.$data.deal_replicator_warranties_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    editDealGuarantee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/guarantee/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_replicator_guarantees_errors = [];
          for (const key in this.deal_replicator_guarantees_form) {
            if (
              this.deal_replicator_guarantees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_guarantees_form[key] = data[key];
            }
          }

          this.deal_replicator_guarantees_id = data.id;
          this.deal_replicator_guarantees_modal_title = "Update Guarantee";

          jQuery("#dealGuaranteeModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching guarantee.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealGuarantee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/guarantee/delete/`;
      url += id;

      let message = "Deleting guarantee...";
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
          let success_message = `Guarantee has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getGuarantees(vm.$route.params.id);
            })();
          }
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting guarantee.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealGuarantee() {
      //save or update the warranty
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_replicator_guarantees_form) {
        if (
          this.deal_replicator_guarantees_form.hasOwnProperty(key) &&
          this.deal_replicator_guarantees_form[key] != null
        ) {
          formData.append(key, this.deal_replicator_guarantees_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_replicator_guarantees_id) &&
        this.deal_replicator_guarantees_id != null;

      //success message setting
      let success_message = update_request
        ? `Guarantee updated.`
        : `Guarantee added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/${this.$route.params.id}/common/guarantee/update/${this.deal_replicator_guarantees_id}`
        : `${request_url}/api/business/model-deals/${this.$route.params.id}/common/guarantee/create`;

      //message during request
      let request_message = update_request
        ? `Updating guarantee ...`
        : `Adding new guarantee ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
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
          vm.resetDealGuaranteeForm();
          document.getElementById("dealGuaranteeModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getGuarantees(vm.$route.params.id);
              // await document.getElementById("deal-guarantees-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});
            })();
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.deal_replicator_guarantees_id) &&
            vm.deal_replicator_guarantees_id != null;

          let error_message = update_request
            ? "There were errors while updating the guarantee."
            : "There were some errors while adding guarantee.";

          vm.$data.deal_replicator_guarantees_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealInsurance() {
      //save or update the insurance
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_replicator_insurances_form) {
        if (
          this.deal_replicator_insurances_form.hasOwnProperty(key) &&
          this.deal_replicator_insurances_form[key] != null
        ) {
          formData.append(key, this.deal_replicator_insurances_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_replicator_insurances_id) &&
        this.deal_replicator_insurances_id != null;

      //success message setting
      let success_message = update_request
        ? `Insurance updated.`
        : `Insurance added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/${this.$route.params.id}/common/insurances/update/${this.deal_replicator_insurances_id}`
        : `${request_url}/api/business/model-deals/${this.$route.params.id}/common/insurances/create`;

      //message during request
      let request_message = update_request
        ? `Updating insurance ...`
        : `Adding new insurance ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
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
          vm.resetDealInsuranceForm();
          document.getElementById("dealInsuranceModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getInsurances(vm.$route.params.id);
            })();
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.deal_replicator_insurances_id) &&
            vm.deal_replicator_insurances_id != null;

          let error_message = update_request
            ? "There were errors while updating the insurance."
            : "There were some errors while adding insurance.";

          vm.$data.deal_replicator_insurances_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealPayment() {
      //save or update the payment
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_replicator_payments_form) {
        if (
          this.deal_replicator_payments_form.hasOwnProperty(key) &&
          this.deal_replicator_payments_form[key] != null
        ) {
          formData.append(key, this.deal_replicator_payments_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_replicator_payments_id) &&
        this.deal_replicator_payments_id != null;

      //success message setting
      let success_message = update_request
        ? `Payment updated.`
        : `Payment added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/${this.$route.params.id}/common/payments/update/${this.deal_replicator_payments_id}`
        : `${request_url}/api/business/model-deals/${this.$route.params.id}/common/payments/create`;

      //message during request
      let request_message = update_request
        ? `Updating payment ...`
        : `Adding new payment ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
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
          vm.resetDealPaymentForm();
          document.getElementById("dealPaymentModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getPayments(vm.$route.params.id);
            })();
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.deal_replicator_payments_id) &&
            vm.deal_replicator_payments_id != null;

          let error_message = update_request
            ? "There were errors while updating the payment."
            : "There were some errors while adding payment.";

          vm.$data.deal_replicator_payments_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    async getGuarantees(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/model-deals/" +
          deal_id +
          "/common/guarantee",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_replicator_guarantees = data;
        })
        .catch(({ response }) => {
          this.deal_replicator_guarantees = [];
          console.log(response);
        });
    },
    editDealInsurance(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/insurances/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_replicator_insurances_errors = [];
          for (const key in this.deal_replicator_insurances_form) {
            if (
              this.deal_replicator_insurances_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_insurances_form[key] = data[key];
            }
          }

          this.deal_replicator_insurances_id = data.id;
          this.deal_replicator_insurances_modal_title = "Update Insurance";

          jQuery("#dealInsuranceModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching insurance.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    editDealPayment(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/payments/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_replicator_payments_errors = [];
          for (const key in this.deal_replicator_payments_form) {
            if (
              this.deal_replicator_payments_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_payments_form[key] = data[key];
            }
          }

          this.deal_replicator_payments_id = data.id;
          this.deal_replicator_payments_modal_title = "Update Payment";

          jQuery("#dealPaymentModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching payment.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealPayment(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/payments/delete/`;
      url += id;

      let message = "Deleting payment...";
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
          let success_message = `Payment has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getPayments(vm.$route.params.id);
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting payment.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    async getPayments(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/model-deals/" +
          deal_id +
          "/common/payments",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_replicator_payments = data;
        })
        .catch(({ response }) => {
          this.deal_replicator_payments = [];
          console.log(response);
        });
    },
    deleteDealInsurance(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/model-deals/${vm.$route.params.id}/common/insurances/delete/`;
      url += id;

      let message = "Deleting insurance...";
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
          let success_message = `Insurance has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-template-update") {
            (async () => {
              await vm.getInsurances(vm.$route.params.id);
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting insurance.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    async getInsurances(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/model-deals/" +
          deal_id +
          "/common/insurances",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_replicator_insurances = data;
        })
        .catch(({ response }) => {
          this.deal_replicator_insurances = [];
          console.log(response);
        });
    },
    async getWarranties(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/model-deals/" +
          deal_id +
          "/common/warranties",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_replicator_warranties = data;
        })
        .catch(({ response }) => {
          this.deal_replicator_warranties = [];
          console.log(response);
        });
    },
    getWarrantySetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin + "/api/business/setting/warranty/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_replicator_warranties_form) {
            if (
              this.deal_replicator_warranties_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_warranties_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the warranty setting";
          console.log(error);
        });
    },
    getGuaranteeSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin + "/api/business/setting/guarantee/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_replicator_guarantees_form) {
            if (
              this.deal_replicator_guarantees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_replicator_guarantees_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the guarantee setting";
          console.log(error);
        });
    },
    warrantySettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/warranty?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.warranty_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the warranty settings");
        });
    },
    guaranteeSettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/guarantee?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.guarantee_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the guarantee settings");
        });
    },
  },
  created() {
    this.getBusinessTaxSettings();
    if (this.$route.params.id && this.$route.name == "deal-template-update") {
      this.getDealFees(this.$route.params.id);
      this.getAccessories(this.$route.params.id);
      this.getWarranties(this.$route.params.id);
      this.getGuarantees(this.$route.params.id);
      this.getInsurances(this.$route.params.id);
      this.getPayments(this.$route.params.id);
      this.costSettingListing();
      this.warrantySettingListing();
      this.guaranteeSettingListing();
      this.accessorySettingListing();
      this.insuranceSettingListing();
    }
  },
  mounted() {
    const vm = this;
    jQuery("#dealFeeModal").on("hidden.bs.modal", function() {
      vm.resetDealFeeForm();
    });

    jQuery("#dealAccessoryModal").on("hidden.bs.modal", function() {
      vm.resetDealAccessoryForm();
    });

    jQuery("#dealWarrantyModal").on("hidden.bs.modal", function() {
      vm.resetDealWarrantyForm();
    });

    jQuery("#dealGuaranteeModal").on("hidden.bs.modal", function() {
      vm.resetDealGuaranteeForm();
    });

    jQuery("#dealInsuranceModal").on("hidden.bs.modal", function() {
      vm.resetDealInsuranceForm();
    });

    jQuery("#dealPaymentModal").on("hidden.bs.modal", function() {
      vm.resetDealPaymentForm();
    });
  },
};
</script>
