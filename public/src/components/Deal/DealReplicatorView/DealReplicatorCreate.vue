<template>
  <div
    class="tab-pane active"
    id="deal-template-create"
    aria-labelledby="Deal Create or Update"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="deal-template-section">
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
                  <div class="row">
                    <div class="col-md-3">
                      <label>Title <span class="text-danger">*</span> </label>
                    </div>
                    <div class="col-md-7 form-group">
                      <input
                        type="text"
                        class="form-control"
                        id="title"
                        name="title"
                        v-model="form.title"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('title'),
                        }"
                        @keyup="delete errors.title"
                        :title="errors.title"
                        aria-describedby="Title"
                        placeholder="Title"
                      />
                      <small
                        id="last-name-error"
                        class="form-text invalid-feedback"
                        >{{ errors.title }}</small
                      >
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <label>Description </label>
                    </div>
                    <div class="col-md-7 form-group">
                      <fieldset>
                        <div class="input-group">
                          <textarea
                            name="description"
                            v-model="form.description"
                            class="form-control"
                            placeholder="Description of Template"
                            id="description"
                          ></textarea>
                        </div>
                      </fieldset>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <label
                        >Retail Or Wholesale
                        <span class="text-danger">*</span></label
                      >
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
                  </div>
                  <div class="row">
                    <h6 class="h6 heading text-danger">NOTE: Changing deal template type will affect the worksheet.</h6>
                  </div>
                </div>
                <!-- Details Section : END -->
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
export default {
  name: "DealReplicatorCreate",
  props: {
    id: { required: false },
    app_metadata: { required: true },
  },
  data() {
    return {
      errors: [],
      form: {
        title: "",
        description: "",
        retail_or_wholesale_flag: 0,
      },
    };
  },
  computed: {
    tabTitle() {
      return this.$route.params.id && this.$route.name == "deal-template-update"
        ? "Update Template Deal"
        : "Create Template Deal";
    },
  },
  watch:{
     "form.retail_or_wholesale_flag": function (newVal, oldVal) {
       Event.fire("change_retail_or_wholesale", newVal);
     }
  },
  methods: {
    async getDealTemplate(id) {
      const vm = this;
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/model-deals/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          console.log(response.data.errors);
          let error = response.data.errors;
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    save() {
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "deal-template-update";

      //success message setting
      let success_message = update_request
        ? `Deal Template updated successfully.`
        : `New Deal Template added..`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/model-deals/update/${this.$route.params.id}`
        : `${request_url}/api/business/model-deals/create`;

      //message during request
      let request_message = update_request
        ? `Updating deal template ...`
        : `Adding new deal template ...`;

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
          toast.success(data.success);
          toast.close();

          let update_request =
            vm.$route.params.id && vm.$route.name == "deal-template-update";

          setTimeout(function () {
            Event.fire("save_deal_template_complete", data);
          }, 500);
        })
        .catch(function (error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "deal-template-update";
          let error_message = update_request
            ? "There were errors while updating the deal template."
            : "There were some errors while adding new deal template.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
          setTimeout(function () {
            Event.fire("save_deal_template_complete", null);
          }, 500);
        });
    },
  },
  created() {
    if (this.$route.params.id && this.$route.name == "deal-template-update") {
      this.getDealTemplate(this.$route.params.id);
    }
  },
  mounted() {
    const vm = this;
    Event.listen("save_deal_template", (data) => {
      vm.save();
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_deal_template", null);
  },
};
</script>