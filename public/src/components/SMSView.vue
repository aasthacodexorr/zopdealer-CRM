<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" id="dashboard" v-if="tenantApp">
    <div class="content-overlay"></div>
    <div
      class="content-wrapper p-0"
      style="background: rgba(242, 238, 238, 0.58) none repeat scroll 0% 0%"
    >
      <div class="content-header row no-gutters">
        <div
          class="col-sm-12 col-md-3 p-0"
          style="
            background: #efefef;
            height: 90vh;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            overflow-y: auto;
          "
        >
          <ul class="sms-conversation-list list-group">
            <li
              :class="[single_conversation.customer_number == phone_no ? 'bg-light text-dark' : '']"
              @click="updateReadCountAndPhoneNumber(single_conversation, index)"
              v-for="(single_conversation, index) in conversations"
              :key="single_conversation.customer_number + `_conversation_list`"
              class="list-group-item pr-50"
            >
              <div>
                <span>{{
                  formatConversationPhoneNumber(single_conversation.customer_number)
                }}</span>
                <span
                  v-show="
                    single_conversation.unread != '0' &&
                    single_conversation.customer_number !== phone_no
                  "
                  class="float-right badge badge-primary badge-pill"
                  >{{ single_conversation.unread }}</span
                >
              </div>
              <div class="message-summary">
                {{ single_conversation.body | truncate(150, "...") }}
              </div>
            </li>
          </ul>
        </div>
        <div class="col-sm-12 col-md-9 p-0" id="sms-conversation-box">
          <h2
            v-show="`${phone_no}`.length === 10"
            class="col-12 btn-secondary col-12 p-1"
          >
            {{ formatPhoneNumber }}
          </h2>
          <div
            class="row no-gutters p-1"
            id="sms-conversation-box-section"
            style="height: 55vh; align-content: baseline; overflow-y: auto"
            v-if="!conversationIsEmpty"
            :class="{ 'd-none': !show_sms_conversation }"
          >
            <template v-for="sms_instance in conversation">
              <div
                :key="sms_instance.sid"
                v-if="sms_instance.direction === 'outbound-api'"
                class="
                  col-sm-12
                  mb-50
                  mt-50
                  col-md-8
                  offset-md-4
                  sent
                  pb-3
                  pt-1
                  pr-1
                  pl-1
                "
              >
                {{ sms_instance.body }}
                <span
                  class="status"
                  :title="`${sms_instance.status}`.toUpperCase()"
                  style="cursor: pointer"
                  ><i
                    :class="sentStatusIcon(sms_instance.status)"
                    class="bx mr-1"
                    style="position: relative; top: 0.25em"
                  ></i>
                  {{ dateTime(sms_instance.created_at) }}</span
                >
              </div>

              <div
                :key="sms_instance.sid + `_incoming`"
                class="
                  col-sm-12
                  mb-50
                  mt-50
                  col-md-8
                  recieved
                  pb-3
                  pt-1
                  pr-1
                  pl-1
                "
                v-else-if="sms_instance.direction === 'Incoming'"
              >
                {{ sms_instance.body }}
                <span class="status">
                  {{ dateTime(sms_instance.created_at) }}</span
                >
              </div>
            </template>
          </div>

          <div
            class="row p-1"
            :class="{ 'd-none': !show_sms_conversation }"
            style="height: 55vh; align-content: center; justify-content: center"
            v-else-if="
              isConnected && conversationIsEmpty && phoneNumberSelected
            "
          >
            <div class="col-12 text-center">
              <i
                class="bx bx-chat bx-lg text-primary"
                style="font-size: 5em !important"
              ></i>
            </div>
            <h4
              class="col-6 text-center text-primary"
              style="font-weight: 800; letter-spacing: 1px; margin: auto"
            >
              It's empty here. Why don't we start the conversation with the
              potential customer ?
            </h4>
          </div>

          <div
            class="row p-1"
            :class="{ 'd-none': !show_sms_conversation }"
            style="height: 80vh; align-content: center; justify-content: center"
            v-else-if="!isConnected"
          >
            <div class="col-12 text-center">
              <i
                class="bx bxs-conversation bx-lg text-primary"
                style="font-size: 5em !important"
              ></i>
            </div>
            <h3
              class="col-12 text-center text-primary"
              style="
                font-weight: 800;
                letter-spacing: 1px;
                margin: auto;
                text-transform: capitalize;
              "
            >
              Seems there aren't enough credits available to send SMS.
            </h3>
          </div>

          <div
            class="row p-1"
            :class="{ 'd-none': !show_sms_conversation }"
            style="height: 80vh; align-content: center; justify-content: center"
            v-else
          >
            <div class="col-12 text-center">
              <i
                class="bx bx-chat bx-lg text-primary"
                style="font-size: 5em !important"
              ></i>
            </div>
            <h4
              class="col-6 text-center text-primary"
              style="font-weight: 800; letter-spacing: 1px; margin: auto"
            >
              Click on the contact from the list to start a conversation.
            </h4>
          </div>

          <div
            class="row p-1"
            :class="{ 'd-none': !show_sms_conversation }"
            v-if="isConnected && phoneNumberSelected"
          >
            <div class="col-sm-12">
              <textarea
                style="resize: none"
                name=""
                id=""
                v-model="form.text"
                class="form-control"
              ></textarea>
            </div>
            <div class="col-sm-12">
              <span
                class="float-left p-75"
                :class="[remainingChar < 0 ? 'text-danger' : '']"
              >
                {{ remainingChar }} / 140 Characters
              </span>
              <button
                @click="composeNewSMS"
                :disabled="disableSubmitButton"
                class="m-50 btn btn-primary float-right"
              >
                SEND
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { io } from "socket.io-client";
export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
    time_zone: String,
  },
  name: "SMSView",
  data() {
    return {
      phone_no: null,
      show_sms_conversation: true,
      connected: false,
      submit_available: true,
      conversation_index: null,
      form: {
        text: "",
      },
      conversation: [],
      conversations: [],
    };
  },
  watch: {
    phone_no: function (newVal, oldVal) {
      const vm = this;

      if (newVal !== oldVal && newVal !== null) {
        if (
          window.global.app_metadata.organization &&
          `${this.phone_no}`.length === 10
        ) {
          this.getConversation();

          if (this.socket.hasListeners("notification")) {
            this.socket.removeListener("notification", this.socketListener);
          }

          this.socket.on("notification", this.socketListener);
        }
      }
    },
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    remainingChar() {
      return 140 - (this.form.text.length || 0);
    },
    formatPhoneNumber() {
      return this.$helpers.formatPhoneNumber(`${this.phone_no}`);
    },
    conversationIsEmpty() {
      return this.conversation.length <= 0;
    },
    phoneNumberSelected() {
      return this.phone_no !== null;
    },
    isConnected() {
      return this.connected;
    },
    disableSubmitButton() {
      return (
        this.remainingChar < 0 ||
        this.remainingChar > 135 ||
        !this.submit_available
      );
    },
  },
  methods: {
    formatConversationPhoneNumber(phone) {
      return this.$helpers.formatPhoneNumber(`${phone}`);
    },
    socketListener(data) {
      const vm = this;

      vm.getAllConversations();

      if (data.hasOwnProperty("user_id")) {
        console.log(`SMS:UPDATE:${this.phone_no}`);
        if (
          data.user_id === "*" &&
          data.subject == `SMS:UPDATE:${vm.phone_no}`
        ) {
          vm.conversation.map((sms_instance, i) => {
            if (sms_instance.id == data.content.id) {
              vm.conversation[i]["status"] = data.content.status;
              vm.conversation[i]["from"] = data.content.from;
              vm.conversation[i]["to"] = data.content.to;

              if (
                document.getElementById("sms-conversation-box-section") ||
                false
              ) {
                var div = document.getElementById(
                  "sms-conversation-box-section"
                );
                div.scrollTop = div.scrollHeight;
              }
            }
          });
        }

        console.log(`SMS:REPLY:${vm.phone_no}`);
        if (
          data.user_id === "*" &&
          data.subject == `SMS:REPLY:${vm.phone_no}`
        ) {
          for (const key in vm.conversation) {
            console.log(vm.conversation[key]);
            if (
              vm.conversation[key]["direction"] === "outbound-api" &&
              vm.conversation[key]["from"] == data.content.to
            ) {
              vm.conversation.push(data.content);
              vm.updateRead();

              if (
                document.getElementById("sms-conversation-box-section") ||
                false
              ) {
                var div = document.getElementById(
                  "sms-conversation-box-section"
                );
                div.scrollTop = div.scrollHeight;
              }

              break;
            }
          }
        }
      }
      //console.log(data);
    },
    changeValidatorDisplay() {
      this.show_sms_conversation = !this.show_sms_conversation;
    },
    composeNewSMS() {
      const vm = this;
      let formData = new FormData();
      formData.append("to", this.phone_no);
      formData.append("body", this.form.text);
      vm.submit_available = false;

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/conversation/sms/compose`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.conversation.push({
            id: data.sms_id,
            sid: data.sms_id,
            body: this.form.text,
            to: null,
            from: null,
            direction: "outbound-api",
            status: "queued",
          });
          this.form.text = "";
          vm.submit_available = true;

          if (
            document.getElementById("sms-conversation-box-section") ||
            false
          ) {
            var div = document.getElementById("sms-conversation-box-section");
            div.scrollTop = div.scrollHeight;
          }
        })
        .catch(function (error) {
          //Show Connection Error here
          console.log(error);
          vm.submit_available = true;
        })
        .finally(function () {
          vm.submit_available = true;
        });
    },
    dateTime(datetime) {
      return this.$helpers.changeUTCtoLocalDate(
        datetime,
        "MM/DD/YYYY hh:mm:ss A",
        this.time_zone
      );
    },
    sentStatusIcon(status) {
      switch (status) {
        case "queued":
          return "bx-loader-alt bx-spin text-info bx-sm";
          break;

        case "delivered":
          return "bx-check-double text-success bx-sm";
          break;

        case "sent":
          return "bx-check text-success bx-sm";
          break;
        default:
          return "bxs-error text-danger bx-sm";
          break;
      }
    },
    getConversation() {
      const vm = this;
      let formData = new FormData();
      formData.append("to", this.phone_no);

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/conversation/sms`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.conversation = data.sms_conversation_list;
          this.connected = true;
          this.updateRead();
        })
        .catch(function (error) {
          //Show Connection Error here
          if (
            error.response.data.errors ===
            `You do not have permission to access the resource.`
          ) {
            vm.connected = false;
          }
        });
    },
    updateRead() {
      const vm = this;
      let formData = new FormData();
      formData.append("to", this.phone_no);

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/conversation/sms/status/read`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {})
        .catch(function (error) {});
    },
    updateReadCountAndPhoneNumber(conversation, index) {
      this.phone_no = conversation.customer_number;
      this.conversations[index]["unread"] = 0;
      this.conversation_index = index;
    },
    getAllConversations() {
      const vm = this;

      axios({
        method: "get",
        url: `${window.location.origin}/api/business/conversation/sms/all?paginate=false`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.conversations = data.sms_conversations_list;

          this.conversations = this.conversations.splice(0, 50);

          for (const key in this.conversations) {
            this.conversations[key].body = this.conversations[key].body.replace(
              /(\|\|)(.*)/gi,
              ""
            );
          }
          this.connected = true;

          if (this.conversation_index !== null) {
            this.conversations[this.conversation_index]["unread"] = 0;
          }
        })
        .catch(function (error) {
          //Show Connection Error here
          if (
            error.response.data.errors ===
            `You do not have permission to access the resource.`
          ) {
            vm.connected = false;
          }
        });
    },
  },
  created() {
    this.socket = io(
      `wss://zopnotify.com:3001/` + window.global.app_metadata.organization
    );
    this.socket.on("notification", this.socketListener);

    this.getAllConversations();
  },
  beforeDestroy() {
    if (this.socket.hasListeners("notification")) {
      this.socket.removeListener("notification", this.socketListener);
    }

    this.socket.disconnect();
    this.socket.close();
  },
};
</script>
<style lang="scss">
.sms-conversation-list {
  li {
    list-style-type: none;
  }

  .list-group-item {
    font-size: 1.2em;
    span {
      font-weight: 800;
      letter-spacing: 1px;
    }

    .message-summary {
      font-size: 0.7em;
      font-style: italic;
      margin-top: 0.25em;
    }
  }

  .unread {
    background: #eee;
    font-weight: 600 !important;
  }
}

#sms-conversation-box {
  h2 {
    font-weight: 800;
    letter-spacing: 1px;
  }

  .sent {
    background: #e5f1ff;
    font-size: 0.9em;
    font-weight: 800;
    border-radius: 10px 0px 0px 10px;
    height: fit-content;
    text-align: right;
  }
  .sent .status {
    position: absolute;
    right: 1em;
    bottom: 0.5em;
    font-size: 0.75em;
    font-style: italic;
  }

  .recieved {
    background: #e3e2e2;
    font-size: 0.9em;
    font-weight: 800;
    border-radius: 0px 10px 10px 0px;
    height: fit-content;
  }
  .recieved .status {
    position: absolute;
    left: 1em;
    bottom: 0.5em;
    font-size: 0.75em;
    font-style: italic;
  }
}
</style>