<template>
  <div role="tabpanel" class="tab-pane" id="setting-account-socialconnect" aria-labelledby="SocialConnect Setting"
    aria-expanded="true">
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font">Social Connect Settings</span>
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <ul class="p-0 float-right d-flex">
            <li class="d-inline-block">
              <button class="btn btn-light-secondary mr-1" @click.prevent="getDashboard"><i
                  class='mr-1 bx bx-sync'></i>Refresh</button>
              <button type="submit" :disabled="!submit" @click.prevent="saveConfiguration" class="btn btn-primary"> Save
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div v-if="isSocialConnectAvailable !== -1" class="list-right-task-list list-group ps p-0"
      style="overflow-y:auto !important;">
      <div class="content-wrapper mt-0 pb-1 pt-2">
        <div class="list-image-content">
          <div class="row">
            <div class="card-deck">
              <div class="card" style="width: 30rem;">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="bx bxl-facebook-square"> </i>FACEBOOK
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">Connected Page:</h6>
                  <div class="card-text">
                    <h3>{{ facebookConnectioNDetails }}</h3>
                  </div>
                  <div class="card-footer pl-0">
                    <a href="#" class="btn btn-outline-success" @click="getFacebookLink">CONNECT</a>
                    <a href="#" class="btn btn-outline-danger" @click="unlinkFacebook">UNLINK</a>
                  </div>
                </div>
              </div>
              <div class="card" style="width: 30rem;">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="bx bxl-instagram"></i>INSTAGRAM
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">
                    Connected Account:
                  </h6>
                  <p class="card-text"></p>
                  <h3>{{ instagramConnectioNDetails }}</h3>
                  <div class="card-footer pl-0">
                    <a href="#" class="btn btn-outline-success" @click="getInstagramLink">CONNECT</a>
                    <a href="#" class="btn btn-outline-danger" @click="unlinkInstagram">UNLINK</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
   
      <div class="content-wrapper mt-0 pb-1 pt-2">
        <div class="list-image-content">
          <form class="webapp-form">
            <div class="row">
              <div class="col-md-12 p-1 mb-1 bg-secondary text-white">
                Enable If you want Social Posting to be done automatically.
              </div>
              <div class="col-md-4">
                <label>Enable Automatic Feed Run</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" v-model="form.feed_automatic" :true-value="'1'"
                    :false-value="'0'" name="Financing-Special" id="feed_automatic_switch" />
                  <label class="custom-control-label" for="feed_automatic_switch">
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>


    </div>
    <!--ELSE START-->
    <div class="content-wrapper mt-0 pb-1 pt-2" v-else>
      <div class="list-image-content">
        <div class="row">
          <h2>
            You are not authorized to acess this page. Please contact admin.
          </h2>
        </div>
      </div>
    </div>
    <!--ELSE END-->

  </div>
  <!-- END-->
</template>
<script>
import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountSocialConnect",
  data() {
    return {
      submit: true,
      form: {
        feed_automatic: "0",
      },
      list: {},
      active_syndication: [],
    };

  },
  computed: {
    isSocialConnectAvailable() {
      return this.active_syndication.findIndex((element) => {
        return element["slug"] === "socialconnect";
      });
    },
    facebookConnectioNDetails() {
      if (this.list.hasOwnProperty("facebook")) {
        if (
          this.list.facebook.hasOwnProperty("name") &&
          this.list.facebook.hasOwnProperty("pageid")
        ) {
          return `${this.list.facebook.name}  (${this.list.facebook.pageid})`;
        }
      }
      return ``;
    },
    instagramConnectioNDetails() {
      if (this.list.hasOwnProperty("instagram")) {
        if (
          this.list.instagram.hasOwnProperty("business_name") &&
          this.list.instagram.hasOwnProperty("instagram_username")
        ) {
          return `${this.list.instagram.business_name}  (${this.list.instagram.instagram_username})`;
        }
      }
      return ``;
    },
  },
  methods: {
    async getModuleConfiguration(module = "global") {
      let request_url =
        window.location.origin + "/api/business/configurations/" + module;

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
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
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
    activeSyndication() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/configurations/active/syndication",
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              this.active_syndication = JSON.parse(element["value"]);
            }
          }
        }
      });
    },
    unlinkFacebook() {
      if (confirm("Do you really want to proceed?")) {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: `${window.location.origin}/api/business/socialconnect/unlinkFacebook`,
        })
          .then(({ data }) => {
            if (data.hasOwnProperty("url")) {
              window.open(data["url"], "_blank");
            }
          })
          .catch(() => { });
        this.getDashboard();
      }
    },
    unlinkInstagram() {
      if (confirm("Do you really want to proceed?")) {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: `${window.location.origin}/api/business/socialconnect/unlinkInstagram`,
        })
          .then(({ data }) => {
            if (data.hasOwnProperty("url")) {
              window.open(data["url"], "_blank");
            }
          })
          .catch(() => { });
        this.getDashboard();
      }
    },
    getInstagramLink() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/socialconnect/getInstagramUrl`,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("url")) {
            window.open(data["url"], "_blank");
          }
        })
        .catch(() => { });
    },
    getFacebookLink() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/socialconnect/getFacebookUrl`,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("url")) {
            window.open(data["url"], "_blank");
          }
        })
        .catch(() => { });
    },
    getDashboard() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/socialconnect/dashboard`,
      })
        .then(({ data }) => {
          this.list = data.accounts;
        })
        .catch(() => { });
    },
  },
  created() {
    this.getDashboard();
    this.activeSyndication();
  },
  async mounted() {
    await this.getModuleConfiguration();
  },
};
</script>
