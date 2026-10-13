<template>
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div
        class="content-header row"
        v-show="enable_import == '1' || enable_purge == '1'"
      >
        <h2 class="col-12 pb-1 border-bottom" v-show="enable_import == '1'">
          Import Inventory
          <input
            class="btn btn-link float-right"
            ref="fileInput"
            type="file"
            @change="onFileChanged($event)"
          />
          <button
            v-show="selectedFile"
            class="btn btn-primary float-right"
            @click="onUpload"
          >
            Upload File
          </button>
        </h2>
        <h3 class="col-12 border-bottom pt-1 pb-1" v-show="enable_purge == '1'">
          Delete Inventories
          <button class="btn btn-danger float-right" @click="deleteInventory">
            Delete
          </button>
          <input
            type="password"
            placeholder="Password"
            v-model="password"
            class="float-right form-control d-inline-block col-4"
          />
        </h3>
        <div class="col-12" v-show="enable_inventory_purge_assets == '1'">
          <button @click="purgeInventoryAssets" class="btn btn-danger">
            Delete Inventory Images & Files
          </button>
        </div>
        <div class="col-12" v-show="enable_inventories_sold_button == '1'">
          <button @click="markInventoriesAsSold" class="btn btn-primary">
            Mark Inventories As Sold
          </button>
        </div>
        <div
          class="col-12"
          v-show="enable_inventories_unpublished_button == '1'"
        >
          <button
            @click="markAllInventoriesAsUnpublished"
            class="btn btn-secondary"
          >
            Mark Inventories As Unpublished
          </button>
        </div>
        <div class="col-12">
          <table class="table">
            <thead>
              <tr>
                <th>Stock Number</th>
                <th>Messages</th>
              </tr>
            </thead>
            <tbody>
              <tr
                :class="classcss"
                v-for="(message, index) in messages"
                :key="index"
              >
                <td>{{ index }}</td>
                <td>
                  <span
                    v-for="single_message in message"
                    :key="single_message + index"
                  >
                    {{ single_message }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
export default {
  props: ["application", "app_metadata"],
  name: "Inventory",
  data() {
    return {
      selectedFile: null,
      password: "",
      enable_purge: "0",
      enable_inventory_purge_assets: "0",
      enable_import: "0",
      enable_inventories_sold_button: "0",
      enable_inventories_unpublished_button: "0",
      messages: [],
      classcss: "",
    };
  },
  methods: {
    getPurgeConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "enable_purge") {
              this.enable_purge = element.value;
            } else if (element.key == "enable_import") {
              this.enable_import = element.value;
            } else if (element.key == "enable_inventories_sold_button") {
              this.enable_inventories_sold_button = element.value;
            } else if (element.key == "enable_inventory_purge_assets") {
              this.enable_inventory_purge_assets = element.value;
            } else if (element.key == "enable_inventories_unpublished_button") {
              this.enable_inventories_unpublished_button = element.value;
            }
          }
        }
      });
    },
    onFileChanged(event) {
      this.selectedFile = event.target.files[0];
    },
    onUpload() {
      const vm = this;
      let formData = new FormData();
      formData.append("file", this.selectedFile, this.selectedFile.name);

      let message = "Uploading File ...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        data: formData,
        url: window.location.origin + "/api/business/import/inventory",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          let success_message = "Finished Uploading File.";
          this.messages = data;
          this.classcss = "table-success";
          toast.success(success_message);
          toast.close();
        })
        .catch((error) => {
          let data = error.response.data;
          this.messages = data;
          this.classcss = "table-danger";
          let error_message = `There were errors in uploading the file.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteInventory() {
      //save or update the purchase data
      const vm = this;
      //create data
      let formData = new FormData();
      formData.append("password", this.password);

      //success message setting
      let success_message = `All inventories deleted.`;

      //message during request
      let request_message = `Deleted inventories...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: window.location.origin + "/api/business/inventory/purge",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data;
          error_message = Object.keys(error_message).reduce((res, v) => {
            return `${res} ${error_message[v]}`;
          }, "");
          console.log(error.response.data);
          toast.fail(error_message);
          toast.close();
        });
    },
    markInventoriesAsSold() {
      const vm = this;
      let formData = new FormData();
      let success_message = `All inventories marked as sold.`;
      let request_message = `Marking inventories as sold...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url:
          window.location.origin + "/api/business/inventory/status/update/sold",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data.errors;
          toast.fail(error_message);
          toast.close();
        });
    },
    markAllInventoriesAsUnpublished() {
      const vm = this;
      let formData = new FormData();
      let success_message = `All inventories marked as unpublished.`;
      let request_message = `Marking inventories as unpublished...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url:
          window.location.origin +
          "/api/business/inventory/status/update/unpublished",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data.errors;
          toast.fail(error_message);
          toast.close();
        });
    },
    purgeInventoryAssets() {
      //save or update the purchase data
      const vm = this;
      //create data
      let formData = new FormData();

      //message during request
      let request_message = `Deleting inventory images & files...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: window.location.origin + "/api/business/inventory/assets/purge",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data.errors;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  created() {
    this.getPurgeConfiguration();
  },
};
</script>
