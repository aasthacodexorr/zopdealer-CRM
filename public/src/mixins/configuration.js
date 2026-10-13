import Toast from "../library/Toast";
export default {
  data() {
    return {
      submit: true,
      form: {},
    };
  },
  methods: {
    async getModuleConfiguration(zd_module = "global") {
      try {
        let { data } = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/configurations/${zd_module}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });

        let json_keys = ["deal_jacket_organizer_template"];

        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];

            if (json_keys.includes(element.key)) {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = JSON.parse(element.value))
                : {};
            } else {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();

      let readonly_keys = ["zop_studio_available_credit"];
      for (const key in this.form) {
        if (!readonly_keys.includes(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let json_keys = ["deal_jacket_organizer_template"];
      for (const key in this.form) {
        if (json_keys.includes(key)) {
          formData.append(key, JSON.stringify(this.form[key]));
        }
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
  },
};
