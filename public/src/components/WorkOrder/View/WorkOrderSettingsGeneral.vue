<template>
  <div
    role="tabpanel"
    class="tab-pane active"
    id="work-order-settings-general"
    aria-labelledby="stacked-pill-1"
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
                      >Setting</span
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
            <div class="row">
              <div class="col-md-4">
                <label>List of Mechanics</label>
              </div>
            </div>
            <div class="row contact-repeater">
              <div class="col-md-4">
                <button
                  class="btn btn-icon btn-primary"
                  @click="count_of_mechanics += 1"
                  type="button"
                >
                  <i class="bx bx-plus"></i>Add New Mechanic
                </button>
              </div>
              <div class="col-md-5 form-group" data-repeater-list="contact">
                <!-- start Loop for mechanics -->
                <div class="row" v-for="n in this.count_of_mechanics" :key="n">
                  <div class="col-md-10 col-12 form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.list_of_mechanics[n - 1]"
                    />
                  </div>
                  <div class="col-md-2 col-12 form-group pl-0">
                    <button
                      class="btn btn-danger text-nowrap px-1"
                      @click="
                        form.list_of_mechanics.splice(n - 1, 1);
                        count_of_mechanics -= 1;
                      "
                      type="button"
                    >
                      <i class="bx bx-x"></i>
                    </button>
                  </div>
                </div>
                <!-- end Loop for mechanics -->
              </div>
            </div>
          </div>

          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>List of Product Categories</label>
              </div>
            </div>
            <div class="row contact-repeater">
              <div class="col-md-4">
                <button
                  class="btn btn-icon btn-primary"
                  @click="count_of_product_categories += 1"
                  type="button"
                >
                  <i class="bx bx-plus"></i>Add Product Categories
                </button>
              </div>
              <div class="col-md-5 form-group" data-repeater-list="contact">
                <!-- start Loop for product categories -->
                <div class="row" v-for="n in this.count_of_product_categories" :key="n">
                  <div class="col-md-10 col-12 form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.product_categories[n - 1]"
                    />
                  </div>
                  <div class="col-md-2 col-12 form-group pl-0">
                    <button
                      class="btn btn-danger text-nowrap px-1"
                      @click="
                        form.product_categories.splice(n - 1, 1);
                        count_of_product_categories -= 1;
                      "
                      type="button"
                    >
                      <i class="bx bx-x"></i>
                    </button>
                  </div>
                </div>
                <!-- end Loop for product categories -->
              </div>
            </div>
          </div>

          
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";

export default {
  name: "WorkOrderSettingsGeneral",
  data() {
    return {
      count_of_mechanics: 0,
      count_of_product_categories: 0,
      submit: true,
      form: {
        list_of_mechanics: [],
        product_categories: [],
      },
    };
  },
  methods: {
    getGlobalConfigurations() {
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
        const array_attributes = ["list_of_mechanics", "product_categories"];
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (array_attributes.includes(element.key) && element.value != "") {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = JSON.parse(element.value))
                : null;
            } else {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        }
        if (this.form.list_of_mechanics == "") {
          this.form.list_of_mechanics = [];
        } else {
          this.count_of_mechanics = this.form.list_of_mechanics.length;
        }

        if (this.form.product_categories == "") {
          this.form.product_categories = [];
        } else {
          this.count_of_product_categories = this.form.product_categories.length;
        }
      });
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();

      const array_attributes = ["list_of_mechanics", "product_categories"];
      
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && !array_attributes.includes(key)) {
          formData.append(key, this.form[key]);
        }
      }

      formData.append(
        "list_of_mechanics",
        JSON.stringify(this.form["list_of_mechanics"])
      );

      formData.append(
        "product_categories",
        JSON.stringify(this.form["product_categories"])
      );

      let request_url = window.location.origin + "/api/business/configurations";

      let message = "Saving Work Order Settings ...";
      const toast = new Toast(message);
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
          let success_message = "Finished Saving Configurations";
          toast.success(success_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
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
  created() {
    this.getGlobalConfigurations();
  },
};
</script>
<style lang="scss">
#text-editor-settings-1 .ql-formats:not(:last-child) {
  display: none;
}
</style>
