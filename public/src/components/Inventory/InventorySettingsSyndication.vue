<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="syndication-setting"
    aria-labelledby="stacked-pill-1"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                    >Syndication</span
                  >
                </p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="list-right-task-syndication list-group ps p-0">
      <div class="content-wrapper mt-0 p-0">
        <div class="list-image-content" style="height:70vh;overflow:auto;">
          <table
            id="table-extended-transactions"
            class="list-page-content-p mt-0 mb-0"
            style="width: 100%;"
          >
            <thead>
              <tr class="bg-grey">
                <th></th>
                <th class="left-set" style="width: 200px;"></th>
                <th class="left-set">Label</th>
                <th class="left-set" style="visibility: hidden;">Slug</th>
                <th class="left-set" style="width: 50px;">Default</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="syndication_company in active_syndication"
                :key="syndication_company.id"
              >
                <td>
                  <p
                    :class="[
                      syndication_company.status == '1' ? 'bg-green' : 'bg-red',
                    ]"
                    class="rounded-circle round-box"
                  ></p>
                </td>
                <td>
                  <span
                    ><img
                      style="width: 100px;"
                      :src="
                        checkForImageUrlOfSyndication(syndication_company.slug)
                      "
                      :alt="syndication_company.label"
                      class="img-responsive"
                      srcset=""
                  /></span>
                </td>
                <td>{{ syndication_company.label }}</td>
                <td style="visibility: hidden;">
                  {{ syndication_company.slug }}
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <span
                      class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                      role="menu"
                    ></span>
                    <div
                      class="dropdown-menu dropdown-menu-right"
                      x-placement="bottom-end"
                      style="
                        position: absolute;
                        will-change: transform;
                        top: 0px;
                        left: 0px;
                        transform: translate3d(19px, 19px, 0px);
                      "
                    >
                      <a
                        class="dropdown-item"
                        style="cursor: pointer;"
                        @click.prevent="
                          changeStatus(syndication_company.slug, '1')
                        "
                        >Enabled</a
                      >
                      <a
                        class="dropdown-item"
                        style="cursor: pointer;"
                        @click.prevent="
                          changeStatus(syndication_company.slug, '0')
                        "
                        >Disabled</a
                      >
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- datatable ends -->
      </div>
    </div>
  </div>
</template>

<script>
import Toast from "../../library/Toast";

export default {
  name: "InventorySettingsSyndication",
  data() {
    return {
      errors: [],
      page: 1,
      number_of_items: 10,
      main_domain: "",
      syndication_companies_list: "",
      active_syndication: "",
      pager: "",
      global: window.global,
    };
  },
  methods: {
    changeStatus(slug, flag) {
      let message = "Saving changes for syndication company ...";
      const toast = new Toast(message);
      toast.fire();

      const vm = this;
      let formData = new FormData();

      formData.append("status", flag);

      axios({
        method: "post",
        data: formData,
        url:
          window.location.origin +
          `/api/syndication/companies/update/status/${slug}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          vm.activeSyndication();
        })
        .catch((error) => {
          let returnObj = error.response.data;
          for (const key in returnObj) {
            if (returnObj.hasOwnProperty(key)) {
              const element = returnObj[key];
              toast.fail(element);
            }
          }

          toast.close();
        });
    },
    checkForImageUrlOfSyndication(slug) {
      const vm = this;
      let response = "";
      if (this.active_syndication.length > 0) {
        this.syndication_companies_list.some((x) => {
          if (x["slug"] == slug) {
            response = this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, x["image_url"]);
          }
        });
      }
      return response;
    },
    activeSyndication() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/configurations/active/syndication",
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              this.active_syndication = JSON.parse(element["value"]);
            }
          }
        }
      });
    },
    syndicationCompanyList() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: this.main_domain + "/api/syndication/companies/all?paginate=false",
      }).then((response) => {
        vm.$data.syndication_companies_list =
          response.data.syndication_companies_list;
        vm.$data.pager = response.data.pager;
      });
    },
  },
  mounted() {
    const main_domain = psl.parse(window.location.host);
    this.main_domain = "https://" + main_domain.domain;
    this.syndicationCompanyList();
    this.activeSyndication();
  },
};
</script>

<style lang="scss" scoped></style>
