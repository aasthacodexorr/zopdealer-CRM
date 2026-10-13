<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-tax"
    aria-labelledby="Account Tax Setting"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <form class="webapp-form">
        <div class="row">
          <div class="col-md-5 col-12">
            <ul class="pl-0">
              <li class="d-inline-block mr-1">
                <div class="content-list-box">
                  <p class="p-0 m-0">
                    <span
                      class="blue-color text-bold-400 title-sizeincrease-font"
                      >Tax Settings</span
                    >
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12">
            <ul class="p-0 float-right d-flex">
              <li class="d-inline-block">
                <button
                  type="reset"
                  class="d-none btn btn-light-secondary mr-1"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="!submit"
                  @click.prevent="saveConfiguration"
                  class="btn btn-primary"
                >
                  Save
                </button>
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>
    <div
      class="list-right-task-list list-group ps p-0"
      style="overflow-y:auto !important;"
    >
      <form class="webapp-form">
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="list-image-content">
            <div class="row contact-repeater">
              <div class="col-md-4">
                <button
                  class="btn btn-icon btn-primary"
                  @click="addAnotherTax"
                  type="button"
                >
                  <i class="bx bx-plus"></i>Add Another Tax
                </button>
              </div>
              <div class="col-md-7 form-group" data-repeater-list="contact">
                <!-- start Loop for vehicle tags -->
                <div class="row" v-for="n in this.count_taxes" :key="n">
                  <div class="col-md-1 col-12 form-group">
                    <div class="checkbox" style="vertical-align:sub">
                      <input
                        type="checkbox"
                        :true-value="'1'"
                        :false-value="'0'"
                        class="checkbox-input"
                        :id="'element' + n"
                        :disabled="
                          disableDefaultFlag &&
                            form.business_tax_settings[n - 1].default !== '1'
                        "
                        v-model="form.business_tax_settings[n - 1].default"
                        @click="changeDefaultOption(n - 1)"
                      />
                      <label :for="'element' + n"></label>
                    </div>
                  </div>
                  <div class="col-md-5 col-12 form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.business_tax_settings[n - 1].name"
                      placeholder="Name"
                    />
                  </div>
                  <div class="col-md-4 col-12 form-group">
                    <input
                      type="number"
                      class="form-control"
                      v-model="form.business_tax_settings[n - 1].value"
                      step="0.01"
                      placeholder="Per %"
                    />
                  </div>
                  <div class="col-md-2 col-12 form-group pl-0">
                    <button
                      class="btn btn-danger text-nowrap px-1"
                      @click="
                        form.business_tax_settings.splice(n - 1, 1);
                        count_taxes -= 1;
                      "
                      type="button"
                    >
                      <i class="bx bx-x"></i>
                    </button>
                  </div>
                </div>
                <!-- end Loop for vehicle tags -->
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "SettingsAccountTax",
  data() {
    return {
      submit: true,
      count_taxes: 0,
      form: {
        business_tax_settings: [],
      },
    };
  },
  computed: {
    disableDefaultFlag() {
      return this.form.business_tax_settings.filter((x) => x.default == "1")
        .length < 2
        ? false
        : true;
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    getAccountConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.form.hasOwnProperty(element.key)
              ? (this.form[element.key] = JSON.parse(element.value))
              : null;
          }
        }
        this.count_taxes = this.form.business_tax_settings.length;
      });
    },
    changeDefaultOption(n) {
    },
    addAnotherTax() {
      this.form.business_tax_settings.push({
        name: "",
        value: "",
        default: "0",
      });
      this.count_taxes++;
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, JSON.stringify(this.form[key]));
      }

      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving account settings...";
      const toast = new Toast(message);
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
          let success_message = "Finished saving configurations.";
          toast.success(success_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);

          vm.getBusinessTaxSettings();
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        });
    },
  },
  mounted() {
    this.getAccountConfiguration();
  },
};
</script>
