<template>
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="col-6">
          <div class="card">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h4 class="card-title">Regional Seeds</h4>
            </div>
            <div class="card-content pl-2">
              <!-- Bill Of Sale : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="bill-of-sale"
                      value="setBillOfSale"
                      v-model="documents"
                    />
                    <label class="form-check-label" for="bill-of-sale">
                      Bill Of Sale
                    </label>
                  </div>
                </div>
              </div>
              <!-- Bill Of Sale : END -->

              <!-- Bill Of Sale : START -->
              <div
                class="form-group row"
                v-if="region === 'BritishColumbiaGeneric'"
              >
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="bill-of-sale-wth-finance"
                      value="setBillOfSaleWithFinance"
                      v-model="documents"
                    />
                    <label
                      class="form-check-label"
                      for="bill-of-sale-wth-finance"
                    >
                      Bill Of Sale (With Finance)
                    </label>
                  </div>
                </div>
              </div>
              <!-- Bill Of Sale : END -->

              <!-- Vehicle Purchase Documents : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="vehicle-purchase-documents"
                      value="setPurchaseDocuments"
                      v-model="documents"
                    />
                    <label
                      class="form-check-label"
                      for="vehicle-purchase-documents"
                    >
                      Vehicle Public And Dealer Purchase Documents
                    </label>
                  </div>
                </div>
              </div>
              <!-- Vehicle Purchase Documents : END -->

              <!-- Vehicle Disclosures : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="vehicle-disclosure"
                      value="setVehicleDisclosures"
                      v-model="documents"
                    />
                    <label class="form-check-label" for="vehicle-disclosure">
                      Vehicle Disclosure Forms & Mapping
                    </label>
                  </div>
                </div>
              </div>
              <!-- Vehicle Disclosures : END -->

              <!-- Internal Inspection : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="internal-inspection"
                      value="setInternalInspection"
                      v-model="documents"
                    />
                    <label class="form-check-label" for="internal-inspection">
                      Internal Inspection Form & Mapping
                    </label>
                  </div>
                </div>
              </div>
              <!-- Internal Inspection : END -->

              <!-- Trade In : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="trade-in"
                      value="setTradeIn"
                      v-model="documents"
                    />
                    <label class="form-check-label" for="trade-in">
                      Trade In
                    </label>
                  </div>
                </div>
              </div>
              <!-- Trade In : END -->

              <!-- Trade In Disclosures : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="trade-in-disclosures"
                      value="setTradeInDisclosures"
                      v-model="documents"
                    />
                    <label class="form-check-label" for="trade-in-disclosures">
                      Trade In Disclosures Forms & Mapping
                    </label>
                  </div>
                </div>
              </div>
              <!-- Trade In Disclosures : END -->

              <!-- Tax Rates : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="regional-tax-rates"
                      value="setTaxeRates"
                      v-model="documents"
                    />
                    <label class="form-check-label" for="regional-tax-rates">
                      Tax Rates
                    </label>
                  </div>
                </div>
              </div>
              <!-- Tax Rates : END -->

              <!-- Region : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Select Region</label>
                  <select class="form-control" v-model="region">
                    <option value="">-- Select Region --</option>
                    <option value="OntarioGeneric">Ontario Generic</option>
                    <option value="OntarioUCDA">Ontario UCDA</option>
                    <option value="AlbertaGeneric">Alberta Generic</option>
                    <option value="BritishColumbiaGeneric">
                      British Columbia Generic
                    </option>
                  </select>
                </div>
              </div>
              <!-- Region : END -->

              <!-- Button : START -->
              <div class="form-group row" v-show="documents && region">
                <div class="col-sm-12">
                  <button class="btn btn-success" @click.prevent="seed">
                    Submit
                  </button>
                </div>
              </div>
              <!-- Button : END-->
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
  props: ["application", "app_metadata"],
  name: "SettingsAccountRegion",
  data() {
    return {
      documents: [],
      region: "",
    };
  },
  methods: {
    async seed() {
      const vm = this;
      let formData = new FormData();

      if (Array.isArray(this.documents)) {
        formData.append("documentMethods", JSON.stringify(this.documents));
      } else {
        formData.append("documentMethods", "[]");
      }

      const toast = new Toast(
        `Seeding the regional settings for ${this.region}...`
      );
      toast.fire();

      await axios({
        method: "post",
        url: `${window.location.origin}/api/business/regions/${this.region}/seed`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          this.documents = [];
          this.region = "";
        })
        .catch(function (error) {
          toast.fail(
            `There were some errors while seeding for region ${this.region}`
          );
          toast.close();
          this.documents = [];
          this.region = "";
        });
    },
  },
};
</script>
