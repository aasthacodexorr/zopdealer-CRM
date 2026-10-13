<template>
  <div
    class="tab-pane"
    id="lead-view-conversation"
    aria-labelledby="lead-view-conversation"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-view-main">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-0 mx-25">
                <h4 class="card-title col-12">Emails
                  <router-link title="Compose Email" tag="button"
                    :to="{ name : 'email', params: { email_address: email } }"
                    class="btn btn-info float-right" exact>
                            Compose Email
                  </router-link></h4>

                <div class="col-xs-12 col-12">
                  <p class="col-xs-12 col-12 text-danger" style="font-size:0.8em;">
                    Note: It may take from few seconds to minutes to load emails depending on your email service provider
                    and the size of the mailbox. This section is private.
                  </p>
                  <div class="table-responsive">
                    <table class="table inventory_costs">
                      <thead>
                        <tr>
                          <th class="p-0" scope="col-2">Subject</th>
                          <th class="p-0" scope="col">From</th>
                          <th class="p-0" scope="col">To</th>
                          <th class="p-0" scope="col">Date</th>
                        </tr>
                      </thead>
                      <tbody  v-if="all_email_conversation.length > 0">
                        <tr
                          v-for="conversation in all_email_conversation"
                          :key="conversation.id"
                        >
                          <td class="p-0">
                            <router-link title="Open Email" tag="a"
                             :to="{ name : 'email', params: { id: conversation.id, shortpath: conversation.shortpath } }" class="nav-link" exact>
                            {{ conversation.subject }}
                            </router-link>
                          </td>
                          <td class="p-0">{{ conversation.sender }}</td>
                          <td class="p-0">
                            {{ conversation.recipient }}
                          </td>
                          <td class="p-0">{{ conversation.date }}</td>
                        </tr>
                      </tbody>
                      <tbody v-else>
                          <tr><td class="text-center" colspan="4">{{email_loading_text}}</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Toast from "../../../../library/Toast";
export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    email: { required: true },
  },
  name: "EmailConversation",
  data() {
    return {
      errors: [],
      all_email_conversation: [],
      email_loading_text: "Loading ...",
    };
  },
  watch: {
    email: function (newValue, oldValue) {
      if (newValue && newValue !== oldValue) {
        this.getEmailConversation(this.email);
      }
    },
  },
  methods: {
    getEmailConversation(email) {
      const vm = this;

      let formData = new FormData();
      formData.append("recipient", email);

      axios({
        method: "post",
        url: window.location.origin + `/api/business/conversation/email/lead`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then((response) => {
          this.all_email_conversation = response.data;
          Event.fire("email_count", this.all_email_conversation.length);
        })
        .catch(({ response }) => {
          vm.email_loading_text = 'No Email Conversation Found.';
        });
    },
  },
  beforeDestroy() {
    Event.vue.$off("email_count", null);
  }
};
</script>
<style lang="scss">
.lead-note-buttons {
  i {
    transition-timing-function: linear;
    vertical-align: top;
    &:hover {
      font-size: 1.8em !important;
      cursor: pointer;
    }
  }
}
</style>
