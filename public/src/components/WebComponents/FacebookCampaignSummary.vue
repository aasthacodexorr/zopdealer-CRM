<template>
  <div v-show="isFBCampaignSummaryAvailable">
    <!-- Start of summary : START -->
    <!-- <div class="col-xs-12 col-md-12 row" v-show="isAuthorized">  -->
    <h2 class="col-xs-12 col-md-12 d-inline-block mb-2">
      Facebook Campaign Overview
       <button class="btn btn-light-secondary mr-1" @click.prevent="getSummary">
          <i class='bx bx-sync'></i>
        </button>
    </h2>
    <div class="col-xs-12 col-md-12 row mb-5">
      <div class="col-xs-12 col-md-3 form-group d-inline-block">
        <date-picker
          v-model="date"
          range
          valueType="format"
          :confirm="true"
          @change="getSummary"
        ></date-picker>
      </div>
      <div class="col-xs-12 col-md-6 form-group d-inline-block">
        <select
          class="custom-select form-control"
          id=""
          v-model="fbcampdefault"
          style="width: auto"
          @change="getSummary"
        >
          <option value="" selected>-- Select Campaign --</option>
          <option v-for="(item, key) in campaigns" :value="key" :key="key">{{
            item
          }}</option>
        </select>
      </div>

<!--      <div class="col-xs-12 col-md-3 form-group d-inline-block">        -->
<!--        <h5 class="card-title" v-if="budget !== 0">Total Budget : ${{ budget }}</h5>    -->
<!--      </div>-->

      <h5 class="col-xs-12 d-block col-12 p-1">
        {{ summary.campaign_name }} {{ summary.campaign_id }}
      </h5>
      <!-- <div
        class="row col-12 m-1 mt-0"
        style="margin-top: 0px !important;"
      > -->
        <div class="col-xl-3 col-md-3 col-sm-6">
          <div class="card text-center border shadow-none">
            <div class="card-body">
              <p class="text-muted mb-0 line-ellipsis">REACH</p>
              <h6 class="mb-0 text-primary statistics-count">
                {{ summary.reach }}
              </h6>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-6">
          <div class="card text-center border shadow-none">
            <div class="card-body">
              <p class="text-muted mb-0 line-ellipsis">IMPRESSIONS</p>
              <h6 class="mb-0 text-primary statistics-count">
                {{ summary.impressions }}
              </h6>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-6">
          <div class="card text-center border shadow-none">
            <div class="card-body">
              <p class="text-muted mb-0 line-ellipsis">CLICKS</p>
              <h6 class="mb-0 text-primary statistics-count">
                {{ summary.clicks }}
              </h6>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-6">
          <div class="card text-center border shadow-none">
            <div class="card-body">
              <p class="text-muted mb-0 line-ellipsis">Total Leads</p>
              <h6 class="mb-0 text-primary statistics-count">
                {{ summary.lead }}
              </h6>
            </div>
          </div>
        </div>

<!--        <div class="col-xl-3 col-md-3 col-sm-6">-->
<!--          <div class="card text-center border shadow-none">-->
<!--            <div class="card-body">-->
<!--              <p class="text-muted mb-0 line-ellipsis">Spend</p>-->
<!--              <h6 class="mb-0 text-primary statistics-count">-->
<!--                {{ summary.spend }}-->
<!--              </h6>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        <div class="col-xl-3 col-md-3 col-sm-6">-->
<!--          <div class="card text-center border shadow-none">-->
<!--            <div class="card-body">-->
<!--              <p class="text-muted mb-0 line-ellipsis">CPM</p>-->
<!--              <h6 class="mb-0 text-primary statistics-count">-->
<!--                {{ summary.cpm }}-->
<!--              </h6>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        <div class="col-xl-3 col-md-3 col-sm-6">-->
<!--          <div class="card text-center border shadow-none">-->
<!--            <div class="card-body">-->
<!--              <p class="text-muted mb-0 line-ellipsis">CPC</p>-->
<!--              <h6 class="mb-0 text-primary statistics-count">-->
<!--                {{ summary.cpc }}-->
<!--              </h6>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        <div class="col-xl-3 col-md-3 col-sm-6">-->
<!--          <div class="card text-center border shadow-none">-->
<!--            <div class="card-body">-->
<!--              <p class="text-muted mb-0 line-ellipsis">CPL</p>-->
<!--              <h6 class="mb-0 text-primary statistics-count">-->
<!--                {{ summary.cost_per_lead }}-->
<!--              </h6>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
      <!-- </div> -->
    </div>
    <!-- End of summary : END -->
  </div>
</template>
<script>
import moment from "moment";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Toast from "../../library/Toast";

export default {
  name: "FacebookCampaignSummary",
  props: {
    format: moment().format("YYYY-MM-DD"),
  },
  components: {
    DatePicker,
  },
  data() {
    return {
      date: [],
      campaigns: {},
      fbcampdefault: "",
      //budget: "0",
      summary: {
        campaign_id: "",
        campaign_name: "",
        reach: 0,
        spend: 0,
        impressions: 0,
        cpm: 0,
        clicks: 0,
        cpc: 0,
        lead: 0,
        cost_per_lead: 0,
      },
      global: {
        fbads_account_id: "",
        fbads_value: "",
      }
    };
  },
  computed: {
    isFBCampaignSummaryAvailable() {
      return this.global.fbads_account_id.length > 1;
    }
  },
  methods: {
    async getGlobalConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      await axios({
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
            this.global.hasOwnProperty(element.key)
              ? (this.global[element.key] = element.value)
              : null;
          }
        }
      });
    },
    async getCampaigns() {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/campaigns`,
      })
        .then(({ data }) => {
          this.campaigns = data;
        })
        .catch(() => {});
    },
    summaryTransformers(data, key) {
      return data
        .filter((action) => action.action_type === key)
        .map((action) => action.value)
        .reduce((action, ZERO) => action.value || ZERO, 0);
    },
    resetFacebookSummaryData() {
      this.summary["campaign_id"] = this.summary["campaign_name"] = "";
      const attributes = [
        "reach",
        "spend",
        "impressions",
        "cpm",
        "clicks",
        "cpc",
        "lead",
        "cost_per_lead",
      ];

      for (const attribute of attributes) {
        this.summary[attribute] = 0;
      }
    },
    async getSummary() {
      let start_date = this.date[0] ? this.date[0] : "";
      let end_date = this.date[1] ? this.date[1] : "";
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/campaigns/allstats?cid=${this.fbcampdefault}&tsince=${start_date}&tuntil=${end_date}`,
      })
        .then(({ data }) => {
            this.budget = data.budget;
          if (data.data.length === 0 || data.length === 0) {
            this.resetFacebookSummaryData();
          } else {
            this.summary["campaign_id"] =
              data.data.length > 1 ? "" : data.data[0].campaign_id;
            this.summary["campaign_name"] =
              data.data.length > 1 ? "" : data.data[0].campaign_name;
            this.summary["reach"] = data.summary.reach ;
            // this.summary["spend"] = this.$helpers.moneyFormatter(
            //   data.summary.spend
            // );
            this.summary["impressions"] = data.summary.impressions;
            // this.summary["cpm"] = this.$helpers.moneyFormatter(
            //   data.summary.cpm
            // );
            this.summary["clicks"] = data.summary.clicks;
            // this.summary["cpc"] = this.$helpers.moneyFormatter(
            //   data.summary.cpc
            // );
            this.summary["lead"] = this.summaryTransformers(
              data.summary.actions,
              "lead"
            );

            // this.summary["cost_per_lead"] = this.$helpers.moneyFormatter(
            //   this.summaryTransformers(
            //     data.summary.cost_per_action_type,
            //     "lead"
            //   )
            // );

            // this.summary["budget"] = this.$helpers.moneyFormatter(
            //   data.summary.budget
            // );

          }
        })
        .catch(() => {
          this.resetFacebookSummaryData();
        });
    },
  },
  async created() {
    await this.getGlobalConfiguration();
    await this.getCampaigns();
    await this.getSummary();
  },
};
</script>
<style lang="scss" scoped>
.statistics-count {
  letter-spacing: 1px;
}
</style>