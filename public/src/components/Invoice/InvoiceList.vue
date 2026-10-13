<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="tenantApp && isAuthorized && form.show_billing =='1'">
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="webapp-form">
        <section id="invoice-table-transactions">
          <div
            class="border-bottom mt-3 bg-white header-navbar set-dable-layer"
            style="background-color: transparent; z-index: 2"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="col-xs-8 col-md-8">
                    <h2 class="h1 mt-1 mb-1 col-12">Invoice History</h2>
                  </div>
                  <div class="col-xs-4 col-md-4">
                    <button
                      class="mt-2 mb-1 btn btn-info"
                      @click="updateSubscriptions"
                    >
                      Refresh Subscription
                    </button>
                    <button
                      class="mt-2 mb-1 btn btn-info"
                      @click="updateInvoices"
                    >
                      Refresh Invoices
                    </button>
                  </div>
                </div>
                <div class="row">
                  <div class="table-responsive tab-wid check-bulk p-2">
                    <table class="table">
                      <thead class="thead-dark">
                        <tr class="bg-grey">
                          <th class="left-set">Invoice#</th>
                          <th class="left-set">Invoice Date</th>
                          <th class="left-set">Invoice Amount</th>
                          <th class="left-set">Subscription#</th>
                          <th class="left-set">Status</th>
                          <th class="left-set">Download/Print</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(invoice_history, index) in list.invoice"
                          :key="invoice_history.invoice_id"
                        >
                          <td>{{ invoice_history.invoice_no }}</td>
                          <td>{{ invoice_history.invoice_date }}</td>
                          <td>{{ invoice_history.amount }}</td>
                          <td>{{ invoice_history.subscription_no }}</td>
                          <td>
                            {{ invoice_history.status.toLocaleUpperCase() }}
                          </td>
                          <td>
                            <!-- <a :href="invoice_history.invoice_fullid" target="_blank" rel="noopener noreferrer">DOWNLOAD INVOICE</a> -->
                            <button
                              class="mt-2 mb-1 btn btn-info"
                              @click="
                                downloadInvoice(invoice_history.invoice_fullid)
                              "
                            >
                              Download Invoice
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import Toast from "../../library/Toast";
export default {
  props: ["application", "app_metadata"],
  name: "InvoiceList",
  data() {
    return {
      list: {},
      form:{
        show_billing: "0"
      }
    };
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    isAuthorized() {
      if (
        this.app_metadata.role == "Super Owner" ||
        this.app_metadata.role == "Owner"
      ) {
        return true;
      } else false;
    }
  },
  methods: {
    downloadInvoice($id) {
      const toast = new Toast(`Downloading Invoice...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/invoice/download/` + $id,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("url")) {
            window.open(data["url"], "_blank");
          }
          toast.success(data.success);
          toast.close();
        })
        .catch(() => {
          toast.fail(`There were some errors in updating subscription.`);
          toast.close();
        });
    },

    updateSubscriptions() {
      //const vm = this;
      const toast = new Toast(`Updating Subsricptions...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/subscription/update`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch(() => {
          toast.fail(`There were some errors in updating subscription.`);
          toast.close();
        });
    },
    updateInvoices() {
      const vm = this;
      const toast = new Toast(`Updating latest invoices...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/invoice/update`,
      })
        .then(({ data }) => {
          toast.success(data.msg);
          toast.close();
          vm.getInvoiceHistory();
        })
        .catch(() => {
          toast.fail(`There were some errors in updating invoice.`);
          toast.close();
        });
    },
  async getInvoiceHistory() {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/invoice`,
      })
        .then(({ data }) => {
          this.list = data.data;
        })
        .catch(() => {});
    },
  async getConfigurations() {
        let request_url =
          window.location.origin + "/api/business/configurations/global";

        await axios({
          method: "get",
          url: request_url,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        }).then(({ data }) => {
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              const element = data[key];
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        });
      }    
  },
  async created() {    
    await this.getConfigurations();
    await this.getInvoiceHistory();

  },
};
</script>

<style lang="scss" scoped></style>