<template>
  <div>
    <div class="app-content content fixed-height-block" v-show="load">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'email-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i
                @click="goBack()"
                class="bx bx-caret-left"
                style="font-size: 1.4em"
              ></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="email-create"
              data-toggle="tab"
              href="#email-create"
              aria-controls="lead-view"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ button_title }}</span>
            </a>
          </li>

          <div
            class="btn-feild-box"
            v-if="action !== 'show' && user_email_integrated"
          >
            <button
              class="btn btn-primary mr-1"
              @click.prevent="composeEmail()"
            >
              Send
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content" v-show="load">
      <div class="content-overlay"></div>
      <div class="" v-if="user_email_integrated">
        <div class="content-body">
          <!-- form layout section start -->
          <div class="tab-content">
            <!-- single tab content goes here : START -->
            <div
              class="tab-pane active"
              id="email-create-tab"
              aria-labelledby="email-create"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="email-add-section">
                <div class="row match-height">
                  <div class="col-md-12 col-12">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12 text-right mb-1">
                              <i
                                style="cursor: pointer"
                                @click="replyTo"
                                v-if="action == 'show'"
                                title="Reply To"
                                class="
                                  bx bx-reply bx-md bx-border-circle
                                  btn btn-primary
                                "
                              ></i>
                              <i
                                style="cursor: pointer"
                                @click="replyToAll"
                                v-if="action == 'show'"
                                title="Reply To All"
                                class="
                                  bx bx-reply-all bx-md bx-border-circle
                                  btn btn-primary
                                "
                              ></i>
                              <i
                                style="cursor: pointer"
                                @click="forward"
                                v-if="action == 'show'"
                                title="Forward"
                                class="
                                  bx
                                  bx-subdirectory-right
                                  bx-md
                                  bx-border-circle
                                  btn btn-primary
                                "
                              ></i>
                            </div>
                          </div>
                          <form class="form form-horizontal webapp-form">
                            <div class="form-body">
                              <!-- row : START -->
                              <div class="row">
                                <div
                                  class="col-md-4 col-xs-12"
                                  v-if="action == 'show'"
                                >
                                  <label>From</label>
                                </div>
                                <div
                                  class="col-md-8 form-group"
                                  v-if="action == 'show'"
                                >
                                  <fieldset class="form-group">
                                    <input
                                      type="text"
                                      class="form-control"
                                      v-model="form.from"
                                      :readonly="
                                        action == 'show' ? true : false
                                      "
                                    />
                                  </fieldset>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                  <label
                                    >To
                                    <sup
                                      style="font-size: 1.4em"
                                      class="text-danger"
                                      >*</sup
                                    >(Comma-Seperated)</label
                                  >
                                </div>
                                <div class="col-md-8 form-group">
                                  <fieldset class="form-group">
                                    <input
                                      type="text"
                                      class="form-control"
                                      v-model="form.to"
                                      id="to"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('to'),
                                      }"
                                      @keyup="delete errors.to"
                                      :title="errors.to"
                                      :readonly="
                                        action == 'show' ? true : false
                                      "
                                    />
                                  </fieldset>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                  <label>Cc (Comma-Seperated)</label>
                                </div>
                                <div class="col-md-8 form-group">
                                  <fieldset class="form-group">
                                    <input
                                      type="text"
                                      class="form-control"
                                      v-model="form.cc"
                                      id="cc"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('cc'),
                                      }"
                                      @keyup="delete errors.cc"
                                      :title="errors.cc"
                                      :readonly="
                                        action == 'show' ? true : false
                                      "
                                    />
                                  </fieldset>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                  <label
                                    >Subject
                                    <sup
                                      style="font-size: 1.4em"
                                      class="text-danger"
                                      >*</sup
                                    ></label
                                  >
                                </div>
                                <div class="col-md-8 form-group">
                                  <fieldset class="form-group">
                                    <input
                                      type="text"
                                      class="form-control"
                                      v-model="form.subject"
                                      id="subject"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('subject'),
                                      }"
                                      @keyup="delete errors.subject"
                                      :title="errors.subject"
                                      :readonly="
                                        action == 'show' ? true : false
                                      "
                                    />
                                  </fieldset>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                  <label
                                    >Body
                                    <sup
                                      style="font-size: 1.4em"
                                      class="text-danger"
                                      >*</sup
                                    ></label
                                  >
                                </div>
                                <div class="col-md-8 form-group">
                                  <fieldset class="form-group">
                                    <quill-editor
                                      v-model="form.body"
                                      ref="myQuillEditor"
                                      :options="editorOption"
                                    ></quill-editor>
                                  </fieldset>
                                </div>

                                <!-- attachment : START -->

                                <div
                                  class="col-md-4 col-xs-12"
                                  v-if="action !== 'show'"
                                >
                                  <label
                                    >Attachment
                                    <sup
                                      style="font-size: 1.4em"
                                      class="text-danger"
                                      >*</sup
                                    ></label
                                  >
                                </div>
                                <div
                                  class="col-md-8 form-group"
                                  v-if="action !== 'show'"
                                >
                                  <div class="custom-file">
                                    <input
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('attachment'),
                                      }"
                                      :title="errors.attachment"
                                      type="file"
                                      ref="attachment"
                                      @change="saveFile($event)"
                                      class="custom-file-input"
                                      id="attachment"
                                    />
                                    <label
                                      class="custom-file-label"
                                      id="attachment_label"
                                      for="attachment"
                                      >Choose Attachment</label
                                    >
                                  </div>
                                </div>
                                <!-- attachment : END -->
                              </div>
                              <!-- row : END -->
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <!-- single tab content goes here : END -->
          </div>
        </div>
      </div>

      <div v-else class="alert alert-danger" role="alert">
        It seems your email id is not integrated.
        <router-link
          title="User Integration"
          tag="a"
          :to="{ name: 'settings-user-integration' }"
          class="btn btn-link"
          exact
        >
          Click Here To Integrate
        </router-link>
      </div>
    </div>
    <!-- END: Content-->
    <div
      class="page-loader"
      style="display: block; position: absolute; top: 0"
      v-if="!load"
    >
      <div class="lds-grid">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

export default {
  name: "Email",
  props: ["id", "shortpath", "email_address", "subject", "body"],
  components: {
    quillEditor,
  },
  data() {
    return {
      user_email_integrated: true,
      title: "Compose Email",
      button_title: "Compose",
      action: "compose",
      load: false,
      errors: [],
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ script: "sub" }, { script: "super" }],
            [{ indent: "-1" }, { indent: "+1" }],
            [{ direction: "rtl" }],
            ["link", "image"],
            [{ size: ["small", false, "large", "huge"] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ color: [] }, { background: [] }],
            [{ align: [] }],
            ["clean"],
          ],
        },
      },
      form: {
        body: "",
        from: "",
        subject: "",
        to: "",
        cc: "",
        bcc: "",
        replyTo: "",
        attachment: null,
      },
    };
  },
  watch: {
    action: function (o, n) {
      if (o != n) {
        let flag = action == "show" ? true : false;
        this.$refs.myQuillEditor.quill.enable(!flag);
      }
    },
  },
  methods: {
    saveFile(event) {
      this.errors = [];
      this.form.attachment = event.target.files[0];
    },
    goBack() {
      this.$router.back();
    },
    composeEmail() {
      const vm = this;
      //create data
      let skip_keys = ["from", "attachment"];
      let formData = new FormData();
      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          this.form[key] != null &&
          !skip_keys.includes(key)
        ) {
          formData.append(key, this.form[key]);
        }
      }

      if (this.form.attachment != null) {
        formData.append(
          "attachment",
          this.form.attachment,
          this.form.attachment.name
        );
      }

      //message during request
      let request_message = "Sending email ...";

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url:
          window.location.origin +
          "/api/business/conversation/email/lead/compose",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          let success = "Email has been sent.";

          if (data.hasOwnProperty("success")) {
            success = data.success;
          }

          toast.success(success);
          toast.close();

          setTimeout(() => {
            vm.$router.back();
          }, 1000);
        })
        .catch(function (error) {
          let error_message = "There was error in sending the email.";

          if (error.response.data.hasOwnProperty("errors")) {
            error_message = error.response.data.errors;
          } else {
            vm.$data.errors = error.response.data;
          }

          toast.fail(error_message);
          toast.close();
        });
    },
    replyTo() {
      //Check the inbox and populate to accordingly
      if (this.shortpath.search(/SENT/i) >= 0) {
        this.form.to = this.form.to;
        this.form.cc = "";
      } else {
        this.form.to = this.form.from;
        this.form.cc = "";
      }

      this.action = "compose";
      this.title = "Compose Mail";

      this.form.body =
        "<br> ----- Replying to the email ----- <br>" + this.form.body;
      this.$refs.myQuillEditor.quill.enable(true);
      this.form.attachment = null;

      //populate To
      //populate Subject
      //populate Body
      //change action
    },
    replyToAll() {
      if (this.shortpath.search(/SENT/i) >= 0) {
        this.form.to = this.form.to;
        this.form.cc = this.form.cc;
      } else {
        this.form.to = this.form.from;
        this.form.cc = this.form.cc;
      }

      this.action = "compose";
      this.title = "Compose Mail";

      this.form.body =
        "<br> ----- Replying to all recipients of the email ----- <br>" +
        this.form.body;
      this.$refs.myQuillEditor.quill.enable(true);
      this.form.attachment = null;

      //Check the inbox and populate to and cc accordingly
      //populate To
      //populate CC
      //populate Subject
      //populate Body
    },
    forward() {
      this.form.to = "";
      this.form.cc = "";

      this.action = "compose";
      this.title = "Compose Mail";
      this.form.body =
        "<br> ----- Forwarding the email ----- <br>" + this.form.body;
      this.$refs.myQuillEditor.quill.enable(true);
      this.form.attachment = null;
      //populate Subject
      //populate Body
      //change action
    },
    getEmail() {
      const vm = this;

      let formData = new FormData();
      formData.append("mailbox", this.shortpath);

      axios({
        method: "post",
        url:
          window.location.origin +
          `/api/business/conversation/email/lead/show/${this.id}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.load = true;
          this.button_title = "Show Email";
          this.action = "show";
          this.$refs.myQuillEditor.quill.enable(false);
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          vm.load = true;

          if (vm.action == "compose") {
            if (vm.email_address) {
              vm.form.to = vm.email_address;
            }

            if (vm.subject) {
              vm.form.subject = vm.subject;
            }

            if (vm.body) {
              vm.form.body = vm.body;
            }
          }
        });
    },
    getUserEmailIntegrationStatus() {
      const vm = this;
      let request_url =
        window.location.origin + "/api/business/user/integrations/email";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          vm.user_email_integrated = true;
        })
        .catch(({ response }) => {
          vm.user_email_integrated = false;
        });
    },
  },
  created() {},
  mounted() {
    this.getEmail();
    this.getUserEmailIntegrationStatus();
  },
};
</script>
