<template>
  <div
    role="tabpanel"
    class="tab-pane active"
    id="user-settings-profile"
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
                      >User Profile</span
                    >
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12">
            <ul class="p-0 float-right d-flex">
              <li class="d-inline-block">
                <button type="reset" class="d-none btn btn-light-secondary mr-1">
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="!submit"
                  @click.prevent="saveProfile"
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
                <label>Name</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="name"
                  type="text"
                  class="form-control"
                  v-model="form.name"
                  :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'name'
                            ),
                          }"
                  @change="delete errors.name"
                  :title="errors.name"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Title</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="title"
                  v-model="form.title"
                  :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'title'
                            ),
                          }"
                  @change="delete errors.title"
                  :title="errors.title"
                />
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-4">
                <label>Registration Number</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="registration_number"
                  v-model="form.registration_number"
                  :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'registration_number'
                            ),
                          }"
                  @change="delete errors.registration_number"
                  :title="errors.registration_number"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Communication Phone Number</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="phone"
                  v-model="form.phone"
                  :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'phone'
                            ),
                          }"
                  @change="delete errors.phone"
                  :title="errors.phone"
                  
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Communication Email</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="email"
                  v-model="form.email"
                  :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'email'
                            ),
                          }"
                  @change="delete errors.email"
                  :title="errors.email"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Timezone</label>
              </div>
              <div class="col-md-8 form-group">
                <v-select
                        id="filter-timezone"
                        placeholder="Timezone"
                        v-model="form.timezone"
                        :options="getTimeZones"
                      >
                </v-select>
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
import vSelect from "vue-select";

export default {
  name: "UserSettingsProfile",
  data() {
    return {
      submit: true,
      errors: [],
      form: {
        name : "",
        title : "",
        registration_number : "",
        phone : "",
        email : "",
        timezone: ""
      },
    };
  },
  components: {
    "v-select": vSelect
  },
  computed : {
    getTimeZones(){
      return moment.tz.names();
    }
  },
  methods: {
    getProfileDetails(){
      let request_url =
        window.location.origin + "/api/business/user";

      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {

        for (const key in data){
          data = data[key];
        }
        
        for (const key in this.form) {
          console.log(key);
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
      }).catch(({ response }) => {
          let error = "There were some errors in retrieving the profile.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
      });

    },
    saveProfile() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
         formData.append(key, this.form[key]);
      }

      let request_url = window.location.origin + "/api/business/user";
      let message = "Saving user profile...";
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
          toast.success(data.success);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        })
        .catch((error) => {
          let error_message = "There were some errors in saving user profile.";
          toast.fail(error_message);
          toast.close();

          vm.$data.errors = error.response.data;

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        });


    },
  },
  mounted() {
    this.getProfileDetails();
  },
};
</script>
