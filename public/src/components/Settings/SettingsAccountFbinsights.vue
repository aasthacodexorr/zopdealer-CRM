<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-fbinsights"
    aria-labelledby="FB Insights Setting"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                    >FB Insights Settings</span
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
                class="btn btn-light-secondary btn-outline-success mr-1"
                @click.prevent="getCampaigns"
              >
                <i class="mr-1 bx bx-sync"></i>Update Campaign
              </button>             
            </li>              
            <li class="d-inline-block">
              <button
                class="btn btn-light-secondary mr-1"
                @click.prevent="getModuleConfiguration('fbads')"
              >
                <i class="mr-1 bx bx-sync"></i>Refresh Status
              </button>
            </li>           
          </ul>
        </div>
      </div>
    </div>

    <div
      class="list-right-task-list list-group ps p-0"
      style="overflow-y:auto !important;"
      >
      <div class="content-wrapper mt-0 pb-1 pt-2">
        <div class="list-image-content">
          <div class="row">
            <div
              class="card-deck"
              v-show="isFbadsDetailsAvailable()"
            >
              <div class="card" style="width: 70rem;">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="bx bxl-facebook"></i>Facebook AdsInsights
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">
                    Connected Account:
                  </h6>
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col"></th>
                        <th scope="col" style="width:50px;">Campaign ID</th>
                        <th scope="col">Campaign Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(title,item) in fbads_insights_account" :key="item" >
                        <td class="left-space-box">
                          <div class="form-check form-check-inline">
                            <div class="checkbox">
                              <!-- v-model="form.default_fbads_insights" -->
                              <input
                                class="checkbox-input d-none"                                
                                type="checkbox"
                                :id="item" 
                                :value="item" 
                                :checked="isItemSelected(item)" 
                              @change="updateSelectedItems(item)"                                
                              />
                              <label
                                :for= "item"
                                class="form-check-label"
                                style="cursor: pointer;"
                              ></label>
                            </div>
                          </div>
                                              
                        </td>
                        <td>
                          <small>{{ item }}</small>
                        </td>
                        <td>
                          <h6>{{ title }}</h6>
                        </td>
                        
                      </tr>
                    </tbody>
                  </table>

                  <div class="card-footer pl-0">
                   <a
                      href="#"
                      class="btn btn-outline-success"
                      @click="saveDefaultFbads"
                      >UPDATE PREFERENCE</a
                    >                    
                  </div>                  

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--ELSE START-->
    <!-- <div class="content-wrapper mt-0 pb-1 pt-2" v-else>
      <div class="list-image-content">
        <div class="row">
          <h2>
            You are not authorized to acess this page. Please contact admin.
          </h2>
        </div>
      </div>
    </div> -->
    <!--ELSE END-->
  </div>
  <!-- END-->
</template>
<script>
import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountFbinsights",
  data() {
    return {
      submit: true,
      fbads_insights_account: [],
      selectedItems: [],
      form: {
        fbads_value: "",
      },
    };
  }, 
  // computed: {
  //   // isFacebookConnectAvailable() {
  //   //   return this.form.enable_reviews == "1";
  //   // },
    
  
  // },
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
            
            if (element.key === "default_fbads_insights") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.selectedItems = JSON.parse(element.value);
              } 
            } 

            if (element.key === "fbads_insights_account") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.fbads_insights_account = JSON.parse(element.value);
              } else {
                this.fbads_insights_account = [];
              }
            } else {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        }
      });
    },
    isItemSelected(itemId) {
      return this.selectedItems.includes(itemId);
    },     
    updateSelectedItems(itemId) {
      if (this.selectedItems.includes(itemId)) {
        this.selectedItems = this.selectedItems.filter(id => id !== itemId);
      } else {
        this.selectedItems.push(itemId);
      }
    }, 
     async  isFbadsDetailsAvailable() {
      return true;
      if (typeof this.fbads_insights_account === "object") {

      const count = Object.keys(this.fbads_insights_account).length;
      alert("My Count"+count);
      console.log("COUNT"+count);

        // if (Array.isArray(this.fbads_insights_account)) {
        //   if (this.fbads_insights_account.length > 0) {
        //     return true;
        //   }
        // }
      }

      return false;
    },    
    async saveDefaultFbads() {
    
      const vm = this;
      let formData = new FormData();
      formData.append('default_fbads_insights', JSON.stringify(this.selectedItems));
      
      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving FBads campaigns...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          let success_message = data.success;
          toast.success(success_message);
          toast.close();
        })
        .catch((error) => {
          let error_message =
            "There were some errors in saving default FBads campaigns.";
          toast.fail(error_message);
          toast.close();
        });
    },
    async getCampaigns() {

      let message = "Updating Facebook Campaigns...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/get-campaigns`,
      })
        .then(({ data }) => {
          let success_message = data.success;
          toast.success(success_message);
          toast.close();
        })
        .catch((error) => {
           let error_message =
            "There were some errors in saving default FBads campaigns.";
          toast.fail(error_message);
          toast.close();
        });
    }        
  },
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration();
    await this.getModuleConfiguration("fbads");
    this.submit = true;
  },
};
</script>
