<template>
  <div
    id="sms-conversation-widget"
    class="row d-none d-sm-block"
    v-if="tenantApp && phone_no && isConnected"
  >
    <h3 class="btn-primary col-12 text-center">
      {{ formatPhoneNumber }}
      <i
        @click.prevent="changeValidatorDisplay()"
        class="bx dropdown-button"
        :class="[show_sms_conversation ? 'bx-chevron-down' : 'bx-chevron-up']"
      ></i>
    </h3>
    <div
      class="row"
      id="sms-conversation-window"
      v-if="!conversationIsEmpty"
      :class="{ 'd-none': !show_sms_conversation }"
      style="max-height: 300px; overflow-y: auto"
    >
      <template v-for="sms_instance in conversation">
        <div
          :key="sms_instance.sid"
          v-if="sms_instance.direction === 'outbound-api'"
          class="col-sm-11 ml-1 mt-25 mb-25 sent pb-2 pt-75 pr-75 pl-75"
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
          :key="sms_instance.sid + `_else`"
          class="col-sm-11 mt-25 mb-25 recieved pb-2 pt-75 pl-75"
          v-else-if="sms_instance.direction === 'Incoming'"
        >
          {{ sms_instance.body }}
          <span class="status"> {{ dateTime(sms_instance.created_at) }}</span>
        </div>
      </template>
    </div>
    <div
      class="row"
      :class="{ 'd-none': !show_sms_conversation }"
      v-else-if="isConnected && conversationIsEmpty"
    >
      <div class="col-12 text-center">
        <i class="bx bx-chat bx-lg text-primary"></i>
      </div>
      <p
        class="col-12 text-center text-primary"
        style="font-weight: 800; letter-spacing: 1px"
      >
        It's empty here. Why don't we start the conversation with the potential
        customer ?
      </p>
    </div>
    <div
      class="row"
      :class="{ 'd-none': !show_sms_conversation }"
      v-else-if="!isConnected"
    >
      <div class="col-12 text-center">
        <i class="bx bxs-message-dots bx-lg text-primary"></i>
      </div>
      <p
        class="col-12 text-center text-primary"
        style="font-weight: 800; letter-spacing: 1px"
      >
        Seems there aren't enough credits available to send SMS.
      </p>
    </div>
    <div
      class="row"
      :class="{ 'd-none': !show_sms_conversation }"
      v-if="isConnected"
    >
      <div class="col-sm-12">
        <textarea
          style="resize: none"
          v-model="form.text"
          name=""
          id=""
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
          class="m-25 btn btn-primary float-right"
        >
          SEND
        </button>
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
    phone_no: { required: false },
    time_zone: String,
  },
  name: "SMSConversation",
  data() {
    return {
      show_sms_conversation: false,
      connected: false,
      submit_available: true,
      form: {
        text: "",
      },
      conversation: [],
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
    socketListener(data) {
      const vm = this;
      if (data.hasOwnProperty("user_id")) {
        if (
          data.user_id === "*" &&
          data.subject == `SMS:UPDATE:${vm.phone_no}`
        ) {
          vm.conversation.map((sms_instance, i) => {
            if (sms_instance.id == data.content.id) {
              vm.conversation[i]["status"] = data.content.status;
              vm.conversation[i]["from"] = data.content.from;
              vm.conversation[i]["to"] = data.content.to;

              if (document.getElementById("sms-conversation-window") || false) {
                var div = document.getElementById("sms-conversation-window");
                div.scrollTop = div.scrollHeight + 10;
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

              if (document.getElementById("sms-conversation-window") || false) {
                var div = document.getElementById("sms-conversation-window");
                div.scrollTop = div.scrollHeight + 10;
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

          if (document.getElementById("sms-conversation-window") || false) {
            var div = document.getElementById("sms-conversation-window");
            div.scrollTop = div.scrollHeight + 10;
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

          if (this.conversation.length > 0) {
            this.show_sms_conversation = true;
          }

          vm.updateRead();
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
  async created() {
    this.socket = io(
      `wss://zopnotify.com:3001/` + window.global.app_metadata.organization
    );

    this.socket.on("notification", this.socketListener);
  },
  async mounted() {
    if (document.getElementById("sms-conversation-window") || false) {
      var div = document.getElementById("sms-conversation-window");
      div.scrollTop = div.scrollHeight + 10;
    }
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
<style lang="scss" scoped>
#sms-conversation-widget {
  background: #f2eeee;
  width: 300px;
  position: fixed;
  bottom: 4em;
  right: 4em;
  padding: 1em;
  z-index: 999;
  padding-top: 4em;

  .sent {
    background: #e5f1ff;
    font-size: 0.9em;
    font-weight: 800;
    border-radius: 10px 0px 0px 10px;

    .status {
      position: absolute;
      right: 1em;
      bottom: 0.5em;
      font-size: 0.75em;
      font-style: italic;
    }
  }

  .recieved {
    background: #f2f2f2;
    font-size: 0.9em;
    font-weight: 800;
    border-radius: 0px 10px 10px 0px;

    .status {
      position: absolute;
      left: 1em;
      bottom: 0.5em;
      font-size: 0.75em;
      font-style: italic;
    }
  }
}

#sms-conversation-widget h3 {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  line-height: 2em;
}

#sms-conversation-widget h3 i.dropdown-button {
  float: right;
  margin-top: 0.2em;
  cursor: pointer;
  font-size: 1.4em;
}
</style>