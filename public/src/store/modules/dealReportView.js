import axios from "axios";

const namespaced = true;

const state = {
  id: 1,
  heading: "Deal Reports",
  entities: [],
  pager: "",
  sort_column: { created_at: "DESC" },
  load: false,
  number_of_entities: 100,
  current_page: 1,
  request_url: `${window.location.origin}/api/business/user/deal-report`,
  current_entity: null,
  search_string: "",
  search: false,
  compact: false,

  view: [
    {
      header: "DEAL ID",
      column: "deal_id",
      component: "text",
      visibility: "1",
    },
    {
      header: "DEAL STATUS",
      column: "status",
      component: "badge",
      visibility: "1",
    },
    {
      header: "DEAL TYPE",
      column: "type",
      component: "badge",
      visibility: "1",
    },
    {
      header: "DEAL DATE",
      column: "date",
      component: "date",
      visibility: "1",
    },
    {
      header: "RT-WHL",
      column: "retail_or_wholesale_flag",
      component: "rtl_wh_flag",
      visibility: "1",
    },
    {
      header: "DELIVERY DATE",
      column: "delivery_date_time",
      component: "date",
      visibility: "1",
    },
    {
      header: "APPROVED BY",
      column: "approved_by",
      component: "user",
      visibility: "1",
    },
    {
      header: "SALESPERSON",
      column: "salesperson",
      component: "user",
      visibility: "1",
    },
    {
      header: "YEAR",
      column: "year",
      component: "text",
      visibility: "1",
    },
    {
      header: "MAKE",
      column: "make",
      component: "text",
      visibility: "1",
    },
    {
      header: "MODEL",
      column: "model",
      component: "text",
      visibility: "1",
    },
    {
      header: "VIN",
      column: "vin",
      component: "text",
      visibility: "1",
    },
    {
      header: "STOCK NO",
      column: "stock_no",
      component: "text",
      visibility: "1",
    },
    {
      header: "ODOMETER",
      column: "odometer",
      component: "distance",
      visibility: "0",
    },
    {
      header: "EXTERIOR COLOR",
      column: "exterior_color",
      component: "text",
      visibility: "1",
    },
    {
      header: "INTERIOR COLOR",
      column: "interior_color",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER NAME",
      column: "customer_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER PHONE",
      column: "customer_phone",
      component: "phone",
      visibility: "0",
    },
    {
      header: "CUSTOMER MOBILE",
      column: "customer_mobile",
      component: "phone",
      visibility: "0",
    },
    {
      header: "CUSTOMER EMAIL",
      column: "customer_email",
      component: "email",
      visibility: "0",
    },
    {
      header: "CUSTOMER COMPANY NAME",
      column: "customer_company_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "CUSTOMER APT",
      column: "customer_apt",
      component: "text",
      visibility: "0",
    },
    {
      header: "CUSTOMER STREET",
      column: "customer_street_address",
      component: "text",
      visibility: "0",
    },
    {
      header: "CUSTOMER CITY",
      column: "customer_city",
      component: "text",
      visibility: "0",
    },
    {
      header: "CUSTOMER PROVINCE",
      column: "customer_province",
      component: "text",
      visibility: "0",
    },
    {
      header: "CUSTOMER POSTAL CODE",
      column: "customer_postal_code",
      component: "text",
      visibility: "0",
    },
    {
      header: "CUSTOMER COUNTRY",
      column: "customer_country",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR NAME",
      column: "vendor_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR PHONE",
      column: "vendor_phone",
      component: "phone",
      visibility: "0",
    },
    {
      header: "VENDOR MOBILE",
      column: "vendor_mobile",
      component: "phone",
      visibility: "0",
    },
    {
      header: "VENDOR EMAIL",
      column: "vendor_email",
      component: "email",
      visibility: "0",
    },
    {
      header: "VENDOR COMPANY NAME",
      column: "vendor_company_name",
      component: "text",
      visibility: "1",
    },
    {
      header: "VENDOR APT",
      column: "vendor_apt",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR STREET",
      column: "vendor_street_address",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR CITY",
      column: "vendor_city",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR PROVINCE",
      column: "vendor_province",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR POSTAL CODE",
      column: "vendor_postal_code",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR COUNTRY",
      column: "vendor_country",
      component: "text",
      visibility: "0",
    },
    {
      header: "VENDOR PURCHASE DATE",
      column: "vendor_purchase_date",
      component: "date",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE PRICE",
      column: "vendor_purchase_price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE DISCOUNT",
      column: "vendor_purchase_discount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VENDOR PURCHASE PRIMARY TAX",
      column: "vendor_purchase_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "VENDOR PURCHASE SECONDARY TAX",
      column: "vendor_purchase_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "VENDOR PURCHASE TOTAL TAX",
      column: "vendor_purchase_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST",
      column: "additional_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST DISCOUNT",
      column: "additional_cost_discount",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST PRIMARY TAX",
      column: "additional_cost_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "ADDITIONAL COST SECONDARY TAX",
      column: "additional_cost_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "ADDITIONAL COST TOTAL TAX",
      column: "additional_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ADDITIONAL COST TOTAL",
      column: "additional_cost_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "SELLING PRICE",
      column: "selling_price",
      component: "currency",
      visibility: "1",
    },
    {
      header: "SELLING PRICE PRIMARY TAX",
      column: "selling_price_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "SELLING PRICE SECONDARY TAX",
      column: "selling_price_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "SELLING PRICE TOTAL TAX",
      column: "selling_price_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "EXTENDED WARRANTY",
      column: "extended_warranty",
      component: "currency",
      visibility: "1",
    },
    {
      header: "EXTENDED WARRANTY PRIMARY TAX",
      column: "extended_warranty_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "EXTENDED WARRANTY SECONDARY TAX",
      column: "extended_warranty_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "EXTENDED WARRANTY TOTAL TAX",
      column: "extended_warranty_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "EXTENDED WARRANTY COST",
      column: "extended_warranty_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "EXTENDED WARRANTY COST PRIMARY TAX",
      column: "extended_warranty_cost_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "EXTENDED WARRANTY COST SECONDARY TAX",
      column: "extended_warranty_cost_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "EXTENDED WARRANTY COST TOTAL TAX",
      column: "extended_warranty_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "GUARANTEE COST",
      column: "guarantee_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "GUARANTEE COST PRIMARY TAX",
      column: "guarantee_cost_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "GUARANTEE COST SECONDARY TAX",
      column: "guarantee_cost_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "GUARANTEE COST TOTAL TAX",
      column: "guarantee_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "FEES",
      column: "fees",
      component: "currency",
      visibility: "1",
    },
    {
      header: "FEES PRIMARY TAX",
      column: "fees_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "FEES SECONDARY TAX",
      column: "fees_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "FEES TOTAL TAX",
      column: "fees_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "FEES COST",
      column: "fees_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "FEES COST PRIMARY TAX",
      column: "fees_cost_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "FEES COST SECONDARY TAX",
      column: "fees_cost_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "FEES COST TOTAL TAX",
      column: "fees_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ACCESSORIES",
      column: "accessories",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ACCESSORIES PRIMARY TAX",
      column: "accessories_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "ACCESSORIES SECONDARY TAX",
      column: "accessories_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "ACCESSORIES TOTAL TAX",
      column: "accessories_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ACCESSORIES COST",
      column: "accessories_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "ACCESSORIES COST PRIMARY TAX",
      column: "accessories_cost_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "ACCESSORIES COST SECONDARY TAX",
      column: "accessories_cost_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "ACCESSORIES COST TOTAL TAX",
      column: "accessories_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TRADE VALUE",
      column: "trade_value",
      component: "currency",
      visibility: "1",
    },
    {
      header: "NET DIFFERENCE",
      column: "net_difference",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TOTAL TAX",
      column: "total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "LICENCE FEE",
      column: "licence_fee",
      component: "currency",
      visibility: "1",
    },
    {
      header: "GASOLINE",
      column: "gasoline",
      component: "currency",
      visibility: "1",
    },
    {
      header: "LIEN PAYOUT",
      column: "lien_payout",
      component: "currency",
      visibility: "1",
    },
    {
      header: "SUBTOTAL",
      column: "subtotal",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PAYMENT TOTAL",
      column: "payment_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "PAYMENT DETAILS",
      column: "payment_details",
      component: "text",
      visibility: "1",
    },
    {
      header: "INSURANCE",
      column: "insurance_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "INSURANCE PRIMARY TAX",
      column: "insurance_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "INSURANCE SECONDARY TAX",
      column: "insurance_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "INSURANCE TOTAL TAX",
      column: "insurance_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "INSURANCE COST",
      column: "insurance_total_cost",
      component: "currency",
      visibility: "1",
    },
    {
      header: "INSURANCE COST PRIMARY TAX",
      column: "insurance_cost_primary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "INSURANCE COST SECONDARY TAX",
      column: "insurance_cost_secondary_tax",
      component: "currency",
      visibility: "0",
    },
    {
      header: "INSURANCE COST TOTAL TAX",
      column: "insurance_cost_total_tax",
      component: "currency",
      visibility: "1",
    },
    {
      header: "LIEN REGISTRATION FEE",
      column: "lien_registration_fee",
      component: "currency",
      visibility: "1",
    },
    {
      header: "BALANCE DUE",
      column: "total_balance_due",
      component: "currency",
      visibility: "1",
    },
    {
      header: "FINANCE COMMISSION",
      column: "finance_commission_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "SALES COMMISSIONS",
      column: "sale_commissions_total",
      component: "currency",
      visibility: "1",
    },
    {
      header: "VEHICLE PROFIT LOSS",
      column: "vehicle_profit_loss",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TOTAL PROFIT LOSS",
      column: "total_profit_loss",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TAXES COLLECTED",
      column: "total_taxes_collected",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TAXES PAID",
      column: "total_taxes_paid",
      component: "currency",
      visibility: "1",
    },
    {
      header: "TAXES DIFFERENCE",
      column: "total_taxes_difference",
      component: "currency",
      visibility: "1",
    },
  ],
  filters: {
    type: "",
    retail_or_wholesale_flag: "",
    status: "",
    approved_by: "",
    salesperson: "",
    date_start: "",
    date_end: "",
    delivery_date_start: "",
    delivery_date_end: "",
  },
};

const getters = {
  list: (state) => state.view,

  getViewId: (state) => state.id,
  getViewTitle: (state) => state.heading,
  getEntities: (state) => state.entities,
  getPager: (state) => state.pager,
  getSortColumn: (state) => state.sort_column,
  getLoadState: (state) => state.load,
  getEntitiesCount: (state) => state.number_of_entities,
  getCurrentPage: (state) => state.current_page,
  getRequestUrl: (state) => state.request_url,
  getCurrentEntity: (state) => state.current_entity,
  getSearchString: (state) => state.search_string,
  getSearch: (state) => state.search,
  getCompact: (state) => state.compact,
  getFilters: (state) => state.filters,
};

const actions = {
  async updateView({ commit }, payload) {
    commit("updateList", payload);
  },

  async commitViewId({ commit }, payload) {
    commit("updateViewId", payload);
  },
  async commitViewTitle({ commit }, payload) {
    commit("updateViewTitle", payload);
  },
  async commitViewEntities({ commit }, payload) {
    commit("updateViewEntities", payload);
  },
  async commitViewPager({ commit }, payload) {
    commit("updateViewPager", payload);
  },
  async commitSortColumn({ commit }, payload) {
    commit("updateSortColumn", payload);
  },
  async commitLoadState({ commit }, payload) {
    commit("updateLoadState", payload);
  },
  async commitEntitiesCount({ commit }, payload) {
    commit("updateEntitiesCount", payload);
  },
  async commitCurrentPage({ commit }, payload) {
    commit("updateCurrentPage", payload);
  },
  async commitRequestUrl({ commit }, payload) {
    commit("updateRequestUrl", payload);
  },
  async commitOnlyEntities({ commit }) {
    commit("fetchOnlyEntities");
  },
  async commitCurrentEntity({ commit }, payload) {
    commit("setCurrentEntity", payload);
  },
  async commitSearchState({ commit }, payload) {
    commit("updateSearchState", payload);
  },
  async commitSearchString({ commit }, payload) {
    commit("updateSearchString", payload);
  },
  async commitCompactFlag({ commit }, payload) {
    commit("updateCompactFlag", payload);
  },
  async commitFilters({ commit }, payload) {
    commit("updateFilters", payload);
  },
};

const mutations = {
  updateList: (state, list) => (state.view = list),

  updateViewId: (state, id) => (state.id = id),
  updateViewTitle: (state, heading) => (state.heading = heading),
  updateViewEntities: (state, entities) => (state.entities = entities),
  updateViewPager: (state, pager) => (state.pager = pager),
  updateSortColumn: (state, sort_column) => (state.sort_column = sort_column),
  updateLoadState: (state, load) => (state.load = load),
  updateEntitiesCount: (state, number_of_entities) =>
    (state.number_of_entities = number_of_entities),
  updateCurrentPage: (state, current_page) =>
    (state.current_page = current_page),
  updateRequestUrl: (state, request_url) => (state.request_url = request_url),
  setCurrentEntity: (state, current_entity) =>
    (state.current_entity = current_entity),
  updateSearchState: (state, search) => (state.search = search),
  updateSearchString: (state, search_string) =>
    (state.search_string = search_string),
  updateCompactFlag: (state, compact) => (state.compact = compact),
  updateFilters: (state, filter_obj) =>
    (state.filters[filter_obj.name] = filter_obj.value),
  fetchOnlyEntities: (state) => {
    if (state.load) {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: state.request_url,
      })
        .then((response) => {
          state.entities = response.data.deal_listing;
        })
        .catch(() => {});
    }
  },
};

export default {
  namespaced,
  state,
  getters,
  actions,
  mutations,
};
