import Toast from "../library/Toast";
export default {
  data() {
    return {
      form: {
        is_private: "1",
      },
    };
  },
  methods: {
    getActivityConfiguration() {
      let request_url = `${window.location.origin}/api/business/configurations/active/activity`;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              const element = data[key];
              if (element.key == "activity_default_is_private") {
                this.form.is_private = element.value;
              }
            }
          }
        })
        .catch(() => {});
    },
    changeActivityIsPrivateDefault() {
      const vm = this;
      let formData = new FormData();
      formData.append("activity_default_is_private", this.form.is_private);

      let request_url = window.location.origin + "/api/business/configurations";

      let message = "Saving Settings ...";
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
          let success_message = "Finished Saving.";
          toast.success(success_message);
          toast.close();
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();
        });
    },
  },
};
