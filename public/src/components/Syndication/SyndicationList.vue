<template>
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="content-wrapper mt-7 pt-1 pr-0 pl-0">
        <div class="content-body">
          <div class="card shadow-none mb-0">
            <h3 class="col-12 border-bottom pt-1 pb-1">
              Show List
              <button
                class="btn btn-primary float-right"
                @click.prevent="viewList"
              >
                Show
              </button>
              <input
                type="password"
                placeholder="Password"
                v-model="password"
                class="float-right form-control d-inline-block col-4"
              />
            </h3>
            <div class="col-12 row">
              <div class="table-responsive tab-wid check-bulk p-2">
                <table class="list-page-content-p mt-0 mb-0 col-12">
                  <thead>
                    <tr class="bg-grey">
                      <th class="left-set">Synd ID</th>
                      <th class="left-set">Client ID</th>
                      <th class="left-set">Status</th>
                      <th class="left-set">Domain Name</th>
                      <th class="left-set">Source</th>
                      <th class="left-set">File Name</th>
                      <th class="left-set">Folder</th>
                      <th class="left-set">Identity ID</th>
                      <th class="left-set">Source</th>
                      <!-- <th class="left-set">Updated At</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(single_syndication, index) in list"
                      :key="single_syndication.index"
                    >
                      <td>{{ single_syndication.syndication_feed_id }}</td>
                      <td>{{ single_syndication.client_id }}</td>
                      <td>{{ single_syndication.status }}</td>
                      <td>{{ single_syndication.domain_name }}</td>
                      <td>{{ single_syndication.source }}</td>
                      <td>{{ single_syndication.file_name }}</td>
                      <td>{{ single_syndication.folder }}</td>
                      <td>{{ single_syndication.identity_id }}</td>
                      <td>
                        <a
                          :href="single_syndication.url"
                          target="_blank"
                          rel="noopener noreferrer"
                          >Verify</a
                        >
                      </td>
                      <!-- <td>{{parseDateTime(single_syndication.updated_at)}}</td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
const moment = require("moment");
export default {
  name: "SyndicationList",
  props: ["application", "app_metadata"],
  data() {
    return {
      list: [],
      password: "",
    };
  },
  methods: {
    parseDateTime(date) {
      return date == null || date == ""
        ? ""
        : moment(date).format("MMMM Do YYYY hh:mm:ss");
    },
    async viewList() {
      const vm = this;

      let formData = new FormData();
      formData.append("password", this.password);

      const toast = new Toast(`Loading list...`);
      toast.fire();

      await axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/syndication/listing`,
      })
        .then(({ data }) => {
          this.list = data.data;
          toast.success(data.msg);
          toast.close();
        })
        .catch(() => {
          toast.fail(`There were some errors in loading list.`);
          toast.close();
        });
    },
  },
};
</script>
<style scoped></style>
