<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="mainApp">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <form
        class="webapp-form needs-validation"
        novalidate
        v-on:submit.prevent="save()"
      >
        <section id="add-user-list">
          <div
            class="border-bottom mt-3 bg-white header-navbar fixed-top"
            style="background-color: transparent;"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="col-md-4 col-12">
                    <h3 class="content-header-title float-left mt-2 mb-0">
                      Add Client
                    </h3>
                  </div>
                  <div class="col-md-8 col-12">
                    <ul
                      class="ml-0 mt-1 list-group list-group-horizontal text-right"
                    >
                      <li class="d-inline-block">
                        <div
                          class="btn-feild-box"
                          style="top: 16px; right: 15px;"
                        >
                          <ul>
                            <li class="d-inline-block">
                              <button
                                type="reset"
                                class="btn btn-light-secondary mr-1 d-none"
                              >
                                Cancel
                              </button>
                              <button
                                button
                                type="submit"
                                :disabled="!submit_button"
                                class="btn btn-primary"
                              >
                                Save
                              </button>
                            </li>
                            <li class="d-inline-block mr-1"></li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="content-wrapper mt-7 pt-1">
            <div class="row">
              <div class="col-md-12 col-12">
                <div class="pt-1 pb-1 border-bottom">
                  <div class="row">
                    <div class="col-md-3">
                      <label
                        >Business Name
                        <sub class="fixed-color-set">*</sub></label
                      >
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="name"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('name'),
                            }"
                            @keydown="delete errors.name"
                            :title="errors.name"
                            class="form-control"
                            v-model="form.name"
                            name=""
                            placeholder="Business Name"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Email <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="email"
                            id="email"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('email'),
                            }"
                            @keydown="delete errors.email"
                            :title="errors.email"
                            v-model="form.email"
                            class="form-control"
                            name="email"
                            placeholder="Email"
                            required
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Password </label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="password"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('password'),
                            }"
                            @keydown="delete errors.password"
                            :title="errors.password"
                            v-model="form.password"
                            class="form-control"
                            name="password"
                            placeholder="Default 'password'"
                            required
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Telephone</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="telephone"
                            v-model="form.telephone"
                            class="form-control"
                            name="telephone"
                            placeholder="Telephone"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Alternate Telephone</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="alternate_telephone"
                            v-model="form.alternate_telephone"
                            class="form-control"
                            name="alternate_telephone"
                            placeholder="Alternate Telephone"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label
                        >Subdomain (In Lower Case)<sub class="fixed-color-set">*</sub></label
                      >
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="subdomain"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('subdomain'),
                            }"
                            @keydown="delete errors.subdomain"
                            :title="errors.subdomain"
                            v-model="form.subdomain"
                             @input="form.subdomain=$event.target.value.toLowerCase()"
                            class="form-control"
                            name="subdomain"
                            placeholder="Subdomain"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Street <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="street"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('street'),
                            }"
                            @keydown="delete errors.street"
                            :title="errors.street"
                            v-model="form.street"
                            class="form-control"
                            name="street"
                            placeholder="Street"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>City <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="city"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('city'),
                            }"
                            @keydown="delete errors.city"
                            :title="errors.city"
                            v-model="form.city"
                            class="form-control"
                            name="city"
                            placeholder="City"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>State <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="state"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('state'),
                            }"
                            @keydown="delete errors.state"
                            :title="errors.state"
                            v-model="form.state"
                            class="form-control"
                            name="state"
                            placeholder="State"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Zip <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="zip"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('zip'),
                            }"
                            @keydown="delete errors.zip"
                            :title="errors.zip"
                            v-model="form.zip"
                            class="form-control"
                            name="zip"
                            placeholder="Zip"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label
                        >Connection Group
                        <sub class="fixed-color-set">*</sub></label
                      >
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            min="0"
                            id="connection_group"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'connection_group'
                              ),
                            }"
                            @keydown="delete errors.connection_group"
                            :title="errors.connection_group"
                            v-model="form.connection_group"
                            class="form-control"
                            name="connection_group"
                            placeholder="Connection Group"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Adf Email</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="email"
                            id="adf_email"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('adf_email'),
                            }"
                            @keydown="delete errors.adf_email"
                            :title="errors.adf_email"
                            v-model="form.adf_email"
                            class="form-control"
                            name="adf_email"
                            placeholder="Adf Email"
                            required
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
                
                <div class="pt-1 pb-1 border-bottom" style="display:none;">
                  <div class="row">
                    <div class="col-md-3">
                      <label>Slide Limit</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="number"
                            id="slide_limit"
                            class="form-control"
                            min="0"
                            v-model="form.slide_limit"
                            name="slide_limit"
                            placeholder="Slide Limit"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Gallery Limit</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="number"
                            id="gallery_limit"
                            class="form-control"
                            min="0"
                            v-model="form.gallery_limit"
                            name="gallery_limit"
                            placeholder="Gallery Limit"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Blog Limit</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="number"
                            id="blog_limit"
                            class="form-control"
                            min="0"
                            v-model="form.blog_limit"
                            name="blog_limit"
                            placeholder="Blog Limit"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Review Limit</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="number"
                            id="review_limit"
                            class="form-control"
                            min="0"
                            v-model="form.review_limit"
                            name="review_limit"
                            placeholder="Review Limit"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Team Limit</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="number"
                            id="team_limit"
                            class="form-control"
                            min="0"
                            v-model="form.team_limit"
                            name="team_limit"
                            placeholder="Team Limit"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- form layout section end -->
      </form>
    </div>
  </div>
  <!-- END: Content-->
</template>

<script>
import Toast from "../../library/Toast";

export default {
  name: "ClientAdd",
  props: ["application"],
  data() {
    return {
      submit_button: true,
      form: {
        name: "",
        email: "",
        password: "",
        telephone: "",
        alternate_telephone: "",
        subdomain: "",
        street: "",
        city: "",
        state: "",
        zip: "",
        adf_email: "",
        connection_group: "default",
        slide_limit: 0,
        gallery_limit: 0,
        blog_limit: 0,
        review_limit: 0,
        team_limit: 0,
      },
      errors: [],
    };
  },
  computed: {
    mainApp() {
      return this.application == "main";
    },
  },
  methods: {
    save() {
      const vm = this;
      this.submit_button = false;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let message = "Creating Client. Wait till we finish it ...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        url: window.location.origin + "/api/clients/create",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then((response) => {
          //vm.$data.SubmitDisable = true;

          let success_message = "New client added successfully.";
          toast.success(success_message);
          toast.close();

          vm.$data.form = {
            name: "",
            email: "",
            password: "",
            telephone: "",
            alternate_telephone: "",
            subdomain: "",
            street: "",
            city: "",
            state: "",
            zip: "",
            adf_email: "",
            connection_group: "default",
            slide_limit: 0,
            gallery_limit: 0,
            blog_limit: 0,
            review_limit: 0,
            team_limit: 0,
          };
          vm.$data.submit_button = true;
        })
        .catch(function (error) {
          // handle error
          vm.$data.errors = error.response.data;

          let error_message =
            "There were some errors while creating new client.";
          toast.fail(error_message);
          toast.close();
          vm.$data.submit_button = true;
        });
    },
  },
};
</script>

<style scoped></style>
