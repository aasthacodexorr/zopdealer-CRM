<template>
  <tr :id="'payment_' + item.id">
    <td class="tex-left" style="cursor: pointer;">
      <button
        v-show="!item.event_id"
        class="btn btn-link"
        @click.prevent="confirmDeleteAction(item.id)"
      >
        <i class="bx bx-trash text-danger"></i>
      </button>
    </td>
    <td class="text-left" style="cursor: pointer">
      <a v-if="item.receipt_url" target="_blank" :href="item.receipt_url"
        ><i class="bx bx-link-external btn btn-sm btn-secondary"></i
      ></a>
      <button
        class="btn btn-sm btn-primary"
        v-else-if="item.status === 'Charge'"
        @click.prevent="downloadReceiptPDF(item.id)"
      >
        <i class="bx bx-download"></i>
      </button>
      <button
        :title="`Issue Refund to ${item.name} for ${item.amount}`"
        class="btn btn-sm btn-danger"
        v-if="
          item.status === 'Charge' &&
            item.type === 'Credit' &&
            item.mode === 'Online' &&
            app_metadata.organization != 'zopdealer'
        "
        @click.prevent="
          openRefundModal(item.transaction_id, item.name, item.amount)
        "
      >
        <i class="bx bx-redo"></i>
      </button>
    </td>
    <template v-for="element in list">
      <td
        class="text-center"
        style="cursor: pointer"
        v-if="element.visibility == '1'"
        :key="item[element.column] + item.id + element.header"
      >
        <component
          v-if="element.column === 'date'"
          v-bind="{ date: item[element.column] }"
          v-bind:is="components['unixDate'].component"
        ></component>
        <component
          v-else-if="element.component == 'date'"
          v-bind="{ date: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="element.component == 'currency'"
          v-bind="{ amount: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="element.component == 'module'"
          v-bind="{ type: item[element.column], id: item['module_id'] }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else
          v-bind="{ text: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
      </td>
    </template>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";

var components = {
  unixDate: {
    name: "UnixDate",
    component: {
      props: {
        date: {
          required: false,
        },
      },
      computed: {
        parseDate() {
          return this.date == null || this.date == ""
            ? ""
            : moment(this.date).format("MM/DD/YYYY");
        },
      },
      template: "<span>{{parseDate}}</span>",
    },
  },
  date: {
    name: "Date",
    component: {
      props: {
        date: {
          required: false,
        },
      },
      computed: {
        parseDate() {
          return this.date == null || this.date == ""
            ? ""
            : moment(this.date).format("MMMM Do YYYY");
        },
      },
      template: "<span>{{parseDate}}</span>",
    },
  },
  text: {
    name: "Text",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        pascalCase() {
          return this.text == null || this.text == ""
            ? ""
            : this.text[0].toUpperCase() + this.text.substring(1);
        },
      },
      template: "<span>{{pascalCase}}</span>",
    },
  },
  module: {
    name: "Module",
    component: {
      props: {
        type: {
          required: false,
        },
        id: {
          required: false,
        },
      },
      computed: {
        moduleReference() {
          return this.type == null || this.type == ""
            ? ""
            : `${this.type.toUpperCase()} #${this.id}`;
        },
      },
      template: `<span>
                <router-link v-if="this.type === 'Lead' && this.id" 
                :to="{ name: 'lead-response-show', params: { id: this.id } }" >{{moduleReference}}
                </router-link>
                <router-link v-else-if="this.type === 'Deal' && this.id" 
                :to="{ name: 'deal-update', params: { id: this.id } }" >{{moduleReference}}
                </router-link>
                <span v-else>{{moduleReference}}</span>
                </span>`,
    },
  },
  currency: {
    name: "Currency",
    component: {
      props: {
        amount: {
          required: true,
        },
      },
      computed: {
        currency() {
          return this.amount == null || this.amount == ""
            ? ""
            : new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
              }).format(this.amount);
        },
      },
      template: "<span>{{currency}}</span>",
    },
  },
  phone: {
    name: "Phone",
    component: {
      props: {
        text: {
          required: false,
          default: "",
        },
      },
      computed: {
        phoneFormat() {
          x = this.text;
          x = x == null ? "" : x;
          var x = x.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);

          return !x[2]
            ? x[1]
            : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
        },
      },
      template:
        "<span><i class='bx bx-phone-call phone-icon'></i>{{phoneFormat}}</span>",
    },
  },
  email: {
    name: "Email",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        pascalCase() {
          return this.text == null || this.text == ""
            ? ""
            : this.text[0].toUpperCase() + this.text.substring(1);
        },
        showTemplate() {
          return this.text == null || this.text == "" ? false : true;
        },
      },
      template:
        "<span v-if='!showTemplate' ></span><span v-else><i class='bx bxs-inbox mail-icon'></i>{{pascalCase}}</span>",
    },
  },
  user: {
    name: "User",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        pascalCase() {
          return this.text == null || this.text == ""
            ? ""
            : this.text[0].toUpperCase() + this.text.substring(1);
        },
      },
      template: "<span>{{pascalCase}}</span>",
    },
  },
  badge: {
    name: "Badge",
    component: {
      props: {
        text: {
          required: true,
        },
      },
      template: `<span><span class='badge badge-light-success' :title="text" v-if="text === 'succeeded'">SUCCESS</span>
        <span class='badge badge-light-danger' :title="text" v-else-if="text === 'canceled'">FAILED</span>
        <span class='badge badge-light-warning' :title="text" v-else-if="text === 'processing'">IN PROCESS</span>
        <span class='badge badge-light-success' :title="text" v-else-if="text === 'Charge'">CHARGE</span>
        <span class='badge badge-light-danger' :title="text" v-else-if="text === 'Refund'">REFUND</span>
        <span class='badge badge-light-success' :title="text" v-else-if="text === 'Online'"><i style="vertical-align:text-top" class='bx bx-laptop'></i> ONLINE</span>
        <span class='badge badge-light-secondary' :title="text" v-else-if="text === 'Offline'"><i style="vertical-align:text-top" class='bx bx-building-house'></i> OFFLINE</span>
        <span class='badge badge-light-primary' :title="text" v-else-if="text === 'Credit'"><i style="vertical-align:text-top" class='bx bx-credit-card'></i> CREDIT</span>
        <span class='badge badge-light-info' :title="text" v-else-if="text === 'Cash'"><i style="vertical-align:text-top" class='bx bx-money'></i> CASH</span>
        <span class='badge badge-light-success' :title="text" v-else-if="text === 'Bank Transfer' || text === 'Email Transfer' || text === 'Draft'"><i style="vertical-align:text-top" class='bx bxs-bank'></i> {{text}}</span>
        <span class='badge badge-light-warning' :title="text" v-else-if="text === 'Cheque' || text === 'ACH' || text ==='Store Credit'"><i style="vertical-align:text-top" class='bx bxs-bank'></i> {{text}}</span>
        <span class='badge badge-light-secondary' :title="text" v-else>{{text}}</span>
        </span>`,
    },
  },
};
export default {
  name: "PaymentItem",
  props: {
    item: Object,
    childCheck: String,
    app_metadata: Object,
  },
  data() {
    return {
      checkValue: "0",
      components: components,
    };
  },
  watch: {
    childCheck: {
      // the callback will be called immediately after the start of the observation
      immediate: true,
      handler(val, oldVal) {
        this.checkValue = val;
      },
    },
  },
  computed: {
    ...mapGetters("paymentView", ["list"]),
  },
  methods: {
    ...mapActions("paymentView", ["commitOnlyEntities"]),
    pascalCase(string) {
      return string == null || string == ""
        ? ""
        : string[0].toUpperCase() + string.substring(1);
    },
    parseDate(date) {
      return date == null || date == ""
        ? ""
        : moment(date).format("MMMM Do YYYY");
    },
    openRefundModal(transaction_id, name = "", amount = 0) {
      Event.fire("event_payment_refund", { transaction_id, name, amount });
    },
    async downloadReceiptPDF(id) {
      const vm = this;
      await axios({
        method: "get",
        responseType: "blob",
        url: `${window.location.origin}/api/business/payments/pdf/receipt/generate/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], { type: "application/pdf" });
          const fileURL = URL.createObjectURL(file);
          window.open(fileURL);
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    confirmDeleteAction(id) {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the payment.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.deleteAPayment(id);
        }
      });
    },
    deleteAPayment(id) {
      const vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/payments/delete/${id}`,
      })
        .then((response) => {
          this.commitOnlyEntities();
          setTimeout(() => {
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Payment has been deleted.",
            });
          }, 1000);
        })
        .catch(function(error) {
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "There were some errors in deleting data.",
            });
          }, 1000);
        });
    },
  },
};
</script>

<style>
.phone-icon {
  color: #00c3ff;
  float: left;
  /* position: relative; */
  /* top: 0.2em; */
  /* padding-right: 1em; */
}

.mail-icon {
  color: #1cd216;
  float: left;
}
</style>
