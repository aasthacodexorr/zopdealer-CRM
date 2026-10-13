import Vuex from "vuex";
import Vue from "vue";
import inventoryView from "./modules/inventoryView";
import inventory from "./modules/inventory";
import leadResponse from "./modules/leadResponse";
import leadResponseView from "./modules/leadResponseView";
import vendorView from "./modules/vendorView";
import users from "./modules/users";
import vendor from "./modules/vendor";
import configuration from "./modules/configuration";
import customerView from "./modules/customerView";
import customer from "./modules/customer";
import dealView from "./modules/dealView";
import deal from "./modules/deal";
import calendarView from "./modules/calendarView";
import dealReport from "./modules/dealReport";
import dealReportView from "./modules/dealReportView";
import inventoryReportView from "./modules/inventoryReportView";
import leadReportView from "./modules/leadReportView";
import facebookMarketPlaceView from "./modules/facebookMarketPlaceView";
import craigslistView from "./modules/craigslistView";
import SocialConnectView from "./modules/SocialConnectView";
import paymentView from "./modules/paymentView";
import payment from "./modules/payment";
import product from "./modules/product";
import productView from "./modules/productView";
import workOrder from "./modules/workOrder";
import workOrderView from "./modules/workOrderView";
import review from "./modules/review";
import reviewView from "./modules/reviewView";
import reviewFeedback from "./modules/reviewFeedback";
import reviewFeedbackView from "./modules/reviewFeedbackView";
import baseView from "./modules/baseView";
import workFlow from "./modules/workFlow";
import paymentReportView from "./modules/paymentReportView";
import inventoryCost from "./modules/inventoryCost";
import inventoryCostView from "./modules/inventoryCostView";
import inventoryPurchase from "./modules/inventoryPurchase";
import inventoryPurchaseView from "./modules/inventoryPurchaseView";
import voucher from "./modules/voucher";
import voucherView from "./modules/voucherView";
import customerReportView from "./modules/customerReportView";
import facebookMarketPlace from "./modules/facebookMarketPlace";
import testDriveCustomerView from "./modules/testDriveCustomerView";
import testDriveCustomer from "./modules/testDriveCustomer";
import testDrive from "./modules/testDrive";
import testDriveView from "./modules/testDriveView";
import dVIView from "./modules/dVIView";
import dVI from "./modules/dVI";
// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  modules: {
    inventoryView,
    inventory,
    leadResponse,
    leadResponseView,
    vendorView,
    users,
    vendor,
    configuration,
    customerView,
    customer,
    dealView,
    deal,
    calendarView,
    dealReport,
    dealReportView,
    inventoryReportView,
    leadReportView,
    facebookMarketPlaceView,
    craigslistView,
    SocialConnectView,
    paymentView,
    payment,
    product,
    productView,
    workOrder,
    workOrderView,
    review,
    reviewView,
    reviewFeedback,
    reviewFeedbackView,
    baseView,
    workFlow,
    paymentReportView,
    inventoryCost,
    inventoryCostView,
    inventoryPurchase,
    inventoryPurchaseView,
    voucher,
    voucherView,
    customerReportView,
    facebookMarketPlace,
    testDrive,
    testDriveView,
    testDriveCustomerView,
    testDriveCustomer,
    dVIView,
    dVI
  },
});
