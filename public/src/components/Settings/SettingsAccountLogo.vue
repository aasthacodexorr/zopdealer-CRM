<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-logo"
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
                      >Account Logo</span
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
          <div class="col-md-12" v-show="!submit">
            Loading Saved Configurations ...
          </div>

          <div class="list-image-content" v-show="submit">
            <!-- START -->
            <div class="form-row">
                <label class="col-md-6" for="">Logo <small v-if="logoAvailable">CURRENT {{ form.business_logo }}</small></label>
              <div class="form-group col-md-6">
                <div class="custom-file">
                  <input
                    type="file"
                    class="form-control custom-file-input"
                    @change="changeImage"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('file'),
                    }"
                    @keyup="delete errors.file"
                    :title="errors.file"
                    id="logoFile"
                  />
                  <label class="custom-file-label" for="logoFile"
                    >Choose file</label
                  >
                </div>
              </div>
            </div>

            <!-- END -->
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountLogo",
  data() {
    return {
      submit: true,
      form: {
        business_logo: "",
      },
      save_form: {
        file: null,
      },
      errors: [],
    };
  },
  computed:{
    logoAvailable(){
        return this.form.business_logo.length > 0
    }
  },
  methods: {
    changeImage($event) {
      this.save_form.file = $event.target.files[0];
    },
    async getGlobalConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      await axios({
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
              ? (this.form[element.key] = element.value)
              : null;
          }
        }
      });
    },
    async saveConfiguration() {
      let message = "Uploading Logo ...";
      const toast = new Toast(message);
      toast.fire();

      const vm = this;
      let formData = new FormData();
      formData.append("file", this.save_form.file, this.save_form.file.name);

      await axios({
        method: "post",
        data: formData,
        url: window.location.origin + "/api/business/account/logo",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.getGlobalConfiguration();
        })
        .catch((error) => {
          let returnObj = error.response.data;
          for (const key in returnObj) {
            if (returnObj.hasOwnProperty(key)) {
              const element = returnObj[key];
              toast.fail(element);
            }
          }

          toast.close();
        });
    },
  },
  async mounted() {
    this.submit = false;
    await this.getGlobalConfiguration();
    this.submit = true;
  },
};
</script>
