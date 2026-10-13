<template>
  <div>
    <div class="content-wrapper mt-0 p-0">
      <div class="row">
        <div class="col-md-12 col-12">
          <h4 class="content-header-title float-left pl-2 pb-2 mb-0">
            {{ header }}
          </h4>
        </div>
      </div>
      <div class="">
        <div class="">
          <table class="list-page-content-p mt-0 mb-0 w-100">
            <thead>
              <tr class="bg-grey">
                <th style="width: 20px"></th>
                <th class="text-center">Image</th>
                <th class="text-center">File Name</th>
                <th class="text-center" v-if="type == 'static'">Index</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="image in images" :key="`image_${type}_${image.id}`">
                <td>
                  <p
                    :class="[image.active == '1' ? 'bg-green' : 'bg-red']"
                    class="rounded-circle round-box"
                  ></p>
                </td>
                <td class="text-center">
                  <img
                    :src="parseAbsoluteOrRelativeUrl(image.image_url)"
                    class="img-responsive"
                    style="width: 50px; height: 50px"
                  />
                </td>
                <td class="text-center">
                  {{
                    image.image_url.substring(
                      image.image_url.lastIndexOf("/") + 1
                    )
                  }}
                </td>
                <td class="text-center" v-if="type == 'static'">
                  {{ image.index }}
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
                        v-show="type !== 'overlay'"
                        style="cursor: pointer"
                        @click.prevent="changeActivation(image.id, '1')"
                        >Enabled</a
                      >
                      <a
                        class="dropdown-item"
                        style="cursor: pointer"
                        @click.prevent="changeActivation(image.id, '0')"
                        >Disabled</a
                      >
                      <a
                        class="dropdown-item"
                        style="cursor: pointer"
                        @click.prevent="deleteImage(image.id)"
                        >Delete</a
                      >
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col p-1">
          <div
            class="dataTables_paginate paging_simple_numbers text-right"
            id="table-extended-transactions_paginate"
          >
            <ul class="pagination">
              <li
                style="cursor: pointer; padding-right: 0.2em"
                class="paginate_button page-item previous"
                @click="paginationWithUrl(pager.previous)"
                :class="{ disabled: pager.previous == null }"
                id="table-extended-transactions_previous"
              >
                <a
                  aria-controls="table-extended-transactions"
                  data-dt-idx="0"
                  tabindex="0"
                  class="page-link"
                  >&lt;</a
                >
              </li>
              <li
                style="cursor: pointer; padding-right: 0.2em"
                class="paginate_button page-item"
                :class="{ active: n === page }"
                v-show="n >= page - 1 && n <= page + 1"
                v-for="n in pager.pageCount"
                :key="'nav_id' + n"
              >
                <a
                  aria-controls="table-extended-transactions"
                  @click="pagination(n)"
                  :data-dt-idx="n"
                  tabindex="0"
                  class="page-link"
                  >{{ n }}</a
                >
              </li>
              <li
                style="cursor: pointer; padding-right: 0.2em"
                class="paginate_button page-item next"
                :class="{ disabled: pager.next == null }"
                id="table-extended-transactions_next"
              >
                <a
                  aria-controls="table-extended-transactions"
                  @click="paginationWithUrl(pager.next)"
                  data-dt-idx="3"
                  tabindex="0"
                  class="page-link"
                  >&gt;</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";

export default {
  props: {
    type: String,
  },
  name: "InventorySettingsImageTable",
  data() {
    return {
      images: [],
      pager: {},
      page: 1,
    };
  },
  computed: {
    header() {
      if (this.type == "static") {
        return "Static Images";
      } else if (this.type == "placeholder") {
        return "Placeholder Images";
      } else {
        return "Overlay Images";
      }
    },
  },
  methods: {
    parseAbsoluteOrRelativeUrl(image_url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(
        window.global.cdn,
        image_url
      );
    },
    imagesList() {
      let request_url =
        window.location.origin + "/api/business/image?type=" + this.type;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      }).then(({ data }) => {
        this.images = data.images;
        this.pager = data.pager;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    paginationWithUrl(url) {
      const vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.images = response.data.images;
        this.pager = response.data.pager;
        this.page = this.pager.currentPage;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    pagination(n) {
      const vm = this;
      let url = window.location.origin;
      url += "/api/business/image?type=" + this.type + "&page=";
      url += n;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.images = response.data.images;
        this.pager = response.data.pager;
        this.page = this.pager.currentPage;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    changeActivation(id, value) {
      const vm = this;

      let message = "Changing the status...";
      const toast = new Toast(message);
      toast.fire();

      let url = window.location.origin;
      url += `/api/business/image/active/`;
      url += id;

      let formData = new FormData();
      formData.append("active", value);

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          this.pagination(this.page);
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
    deleteImage(id) {
      const vm = this;

      let message = "Deleting image...";
      const toast = new Toast(message);
      toast.fire();

      let url = window.location.origin;
      url += `/api/business/image/delete/`;
      url += id;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          if (this.page == 1) {
            this.pagination(this.page);
          } else if (this.page > 1) {
            if (this.images.length == 1) {
              this.pagination(this.page - 1);
            } else {
              this.pagination(this.page);
            }
          }
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
  },
  created() {
    this.imagesList();
    if (this.type == "static") {
      Event.listen("refresh.images.static", (data) => {
        this.pagination(1);
      });
    } else if (this.type == "overlay") {
      Event.listen("refresh.images.overlay", (data) => {
        this.pagination(1);
      });
    } else if (this.type == "placeholder") {
      Event.listen("refresh.images.placeholder", (data) => {
        this.pagination(1);
      });
    }
  },
};
</script>
