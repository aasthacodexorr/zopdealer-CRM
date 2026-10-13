import VueRouter from "vue-router";

let routes = [
  {
    path: "/",
    component: require("./components/App").default,
  },
  {
    path: "/calendar",
    name: "calendar",
    component: require("./components/Calendar").default,
  },
  {
    path: "/sms",
    name: "sms",
    component: require("./components/SMSView").default,
  },
  {
    path: "/client/create",
    name: "client-add",
    component: require("./components/Client/ClientAdd").default,
  },
  {
    path: "/clients",
    name: "clients",
    component: require("./components/Client/ClientList").default,
  },
  {
    path: "/inventory",
    name: "inventory-list",
    props: true,
    component: require("./components/Inventory/InventoryList").default,
  },
  {
    path: "/inventory/settings",
    name: "inventory-settings",
    component: require("./components/Inventory/InventorySettings").default,
  },
  {
    path: "/inventory/create",
    name: "inventory-create",
    component: require("./components/Inventory/InventoryManage").default,
  },
  {
    path: "/inventory/update/:id",
    name: "inventory-update",
    component: require("./components/Inventory/InventoryManage").default,
  },
  {
    path: "/inventory/updates/pricing",
    name: "inventory-updates-pricing",
    component: require("./components/Inventory/InventoryBulkPricing").default,
  },
  {
    path: "/inventory/views/create",
    name: "inventory-view-create",
    component: require("./components/AddCustomView").default,
  },
  {
    path: "/inventory/views/edit/:id",
    name: "inventory-view-update",
    component: require("./components/AddCustomView").default,
  },
  {
    path: "/leads/template/create",
    name: "lead-template-create",
    component: require("./components/Lead/LeadCreateTemplate").default,
  },
  {
    path: "/leads/template/update/:id",
    name: "lead-template-update",
    component: require("./components/Lead/LeadCreateTemplate").default,
  },
  {
    path: "/leads/templates",
    name: "lead-template-list",
    component: require("./components/Lead/LeadListTemplate").default,
  },
  {
    path: "/leads/responses/views/create",
    name: "lead-response-view-create",
    component: require("./components/Lead/Response/LeadAddCustomView").default,
  },
  {
    path: "/leads/responses/views/edit/:id",
    name: "lead-response-view-update",
    component: require("./components/Lead/Response/LeadAddCustomView").default,
  },
  {
    path: "/leads/responses",
    name: "lead-responses",
    props: true,
    component: require("./components/Lead/Response/LeadResponseList").default,
  },
  {
    path: "/leads/responses/show/:id",
    name: "lead-response-show",
    props: true,
    component: require("./components/Lead/Response/View/LeadViewMaster")
      .default,
  },
  {
    path: "/leads/responses/create",
    name: "lead-response-create",
    component: require("./components/Lead/Response/View/LeadViewMaster")
      .default,
  },
  {
    path: "/leads/responses/edit/:id",
    name: "lead-response-update",
    props: true,
    component: require("./components/Lead/Response/View/LeadViewMaster")
      .default,
  },
  {
    path: "/syndications/companies/",
    name: "syndication-company-list",
    props: true,
    component: require("./components/Syndication/SyndicationCompaniesList")
      .default,
  },
  {
    path: "/syndications/manage/companies",
    name: "manage-syndication-companies",
    props: true,
    component: require("./components/Syndication/ManageSyndicationCompaniesForClient")
      .default,
  },
  {
    path: "/syndications/history",
    name: "syndication-history",
    props: true,
    component: require("./components/Syndication/SyndicationHistory").default,
  },
  {
    path: "/syndications/list",
    name: "syndication-list",
    props: true,
    component: require("./components/Syndication/SyndicationList").default,
  },
  {
    path: "/syndications/actions",
    name: "syndication-action",
    props: true,
    component: require("./components/Syndication/SyndicationAction").default,
  },
  {
    path: "/invoice/list",
    name: "invoice-list",
    props: true,
    component: require("./components/Invoice/InvoiceList").default,
  },
  {
    path: "/settings/account",
    name: "settings-account",
    props: true,
    component: require("./components/Settings/SettingsAccount").default,
  },
  {
    path: "/account/actions",
    name: "account-action",
    props: true,
    component: require("./components/Settings/AccountAction").default,
  },
  {
    path: "/vendor",
    name: "vendor-list",
    component: require("./components/Vendor/VendorList").default,
  },
  {
    path: "/vendor/create",
    name: "vendor-create",
    component: require("./components/Vendor/VendorAdd").default,
  },
  {
    path: "/vendor/edit/:id",
    name: "vendor-update",
    props: true,
    component: require("./components/Vendor/VendorAdd").default,
  },
  {
    path: "/test-drive",
    name: "test-drive-list",
    component: require("./components/TestDrive/TestDriveList").default,
  },
  {
    path: "/test-drive/:id",
    name: "test-drive-detail",
    props: true,
    component: require("./components/TestDrive/TestDriveDetail").default,
  },
  {
    path: "/test-drives-customer",
    name: "test-drive-customer-list",
    component: require("./components/TestDriveCustomer/TestDriveCustomerList")
      .default,
  },
  {
    path: "/customer",
    name: "customer-list",
    component: require("./components/Customer/CustomerList").default,
  },
  {
    path: "/customer/create",
    name: "customer-create",
    component: require("./components/Customer/View/CustomerManage").default,
  },
  {
    path: "/customer/update/:id",
    name: "customer-update",
    component: require("./components/Customer/View/CustomerManage").default,
    props: true,
  },
  {
    path: "/deal/create",
    name: "deal-create",
    component: require("./components/Deal/View/DealManage").default,
    props: true,
  },
  {
    path: "/deal/update/:id",
    name: "deal-update",
    component: require("./components/Deal/View/DealManage").default,
    props: true,
  },
  {
    path: "/deals",
    name: "deals",
    component: require("./components/Deal/DealList").default,
  },
  {
    path: "/deals/views/create",
    name: "deal-view-create",
    component: require("./components/Deal/DealAddCustomView").default,
  },
  {
    path: "/deals/views/edit/:id",
    name: "deal-view-update",
    component: require("./components/Deal/DealAddCustomView").default,
  },
  {
    path: "/deal-reports",
    name: "deal-reports",
    component: require("./components/Deal/DealReportList").default,
  },
  {
    path: "/user/settings/account",
    name: "settings-user-account",
    props: true,
    component: require("./components/Settings/UserSettingsAccount").default,
  },
  {
    path: "/user/settings/integration",
    name: "settings-user-integration",
    props: true,
    component: require("./components/Settings/UserSettingsIntegration").default,
  },
  {
    path: "/deal-reports/views/create",
    name: "deal-report-view-create",
    component: require("./components/Deal/DealReportAddCustomView").default,
  },
  {
    path: "/deal-reports/views/edit/:id",
    name: "deal-report-view-update",
    component: require("./components/Deal/DealReportAddCustomView").default,
  },
  {
    path: "/inventory-reports",
    name: "inventory-reports",
    component: require("./components/Inventory/InventoryReportList").default,
  },
  {
    path: "/inventory-reports/views/create",
    name: "inventory-report-view-create",
    component: require("./components/Inventory/InventoryReportAddCustomView")
      .default,
  },
  {
    path: "/inventory-reports/views/edit/:id",
    name: "inventory-report-view-update",
    component: require("./components/Inventory/InventoryReportAddCustomView")
      .default,
  },
  {
    path: "/reports",
    name: "reports",
    component: require("./components/LandingReport").default,
  },
  {
    path: "/lead-reports",
    name: "lead-reports",
    component: require("./components/Lead/LeadReportList").default,
  },
  {
    path: "/lead-reports/views/create",
    name: "lead-report-view-create",
    component: require("./components/Lead/LeadReportAddCustomView").default,
  },
  {
    path: "/lead-reports/views/edit/:id",
    name: "lead-report-view-update",
    component: require("./components/Lead/LeadReportAddCustomView").default,
  },
  {
    path: "/conversation/email",
    name: "email",
    component: require("./components/Conversation/Email").default,
    props: true,
  },
  {
    props: true,
    path: "/deal/templates",
    name: "deal-templates",
    component: require("./components/Deal/DealReplicator").default,
  },
  {
    path: "/deal/templates/create",
    name: "deal-template-create",
    component: require("./components/Deal/DealReplicatorView/DealReplicatorMaster")
      .default,
  },
  {
    path: "/deal/templates/update/:id",
    name: "deal-template-update",
    component: require("./components/Deal/DealReplicatorView/DealReplicatorMaster")
      .default,
    props: true,
  },
  {
    path: "/import/inventory",
    name: "import-inventory",
    props: true,
    component: require("./components/Import/Inventory").default,
  },
  {
    path: "/facebook-marketplace",
    name: "facebook-marketplace-list",
    component: require("./components/FacebookMarketPlace/FacebookMarketPlaceList")
      .default,
  },
  {
    path: "/craigslist",
    name: "craigslist-list",
    component: require("./components/Craigslist/CraigslistList").default,
  },
  {
    path: "/socialconnect/dashboard",
    name: "manage-socialconnect",
    props: true,
    component: require("./components/SocialConnect/SocialConnectList").default,
  },
  {
    path: "/payment",
    name: "payment-list",
    component: require("./components/Payment/PaymentList").default,
  },
  {
    path: "/payment/views/create",
    name: "payment-view-create",
    component: require("./components/Payment/PaymentAddCustomView").default,
  },
  {
    path: "/payment/views/edit/:id",
    name: "payment-view-update",
    component: require("./components/Payment/PaymentAddCustomView").default,
  },
  {
    path: "/product/create",
    name: "product-create",
    component: require("./components/Product/ProductAdd").default,
  },
  {
    path: "/product/edit/:id",
    name: "product-update",
    props: true,
    component: require("./components/Product/ProductAdd").default,
  },
  {
    path: "/product",
    name: "product-list",
    props: true,
    component: require("./components/Product/ProductList").default,
  },
  {
    path: "/product/views",
    name: "product-view",
    component: require("./components/Product/ProductAddCustomView").default,
  },
  {
    path: "/work-order/create",
    name: "work-order-create",
    component: require("./components/WorkOrder/View/WorkOrderManage").default,
  },
  {
    path: "/work-order/update/:id",
    name: "work-order-update",
    component: require("./components/WorkOrder/View/WorkOrderManage").default,
    props: true,
  },
  {
    path: "/work-order/settings",
    name: "work-order-settings",
    component: require("./components/WorkOrder/View/WorkOrderSettings").default,
  },
  {
    path: "/work-order",
    name: "work-order-list",
    props: true,
    component: require("./components/WorkOrder/WorkOrderList").default,
  },
  {
    path: "/work-order/views",
    name: "work-order-view",
    component: require("./components/WorkOrder/WorkOrderCustomView").default,
  },
  {
    path: "/super/actions",
    name: "super-action",
    props: true,
    component: require("./components/Settings/SuperAction").default,
  },
  {
    path: "/account/users",
    name: "user-management",
    props: true,
    component: require("./components/Settings/UsersManagement").default,
  },
  {
    path: "/settings/account/region",
    name: "settings-account-region",
    props: true,
    component: require("./components/Settings/SettingsAccountRegion").default,
  },
  {
    path: "/review",
    name: "review-list",
    props: true,
    component: require("./components/Review/ReviewList").default,
  },
  {
    path: "/review/views",
    name: "review-view",
    component: require("./components/Review/ReviewAddCustomView").default,
  },
  {
    path: "/review-feedback",
    name: "review-feedback-list",
    props: true,
    component: require("./components/ReviewFeedback/ReviewFeedbackList")
      .default,
  },
  {
    path: "/review-feedback/views",
    name: "review-feedback-view",
    component: require("./components/ReviewFeedback/ReviewFeedbackAddCustomView")
      .default,
  },
  {
    path: "/review/settings",
    name: "review-settings",
    component: require("./components/Review/ReviewSettings").default,
  },
  {
    path: "/settings/v2/account",
    name: "settings-account-v2",
    props: true,
    component: require("./components/Account/AccountLanding").default,
  },
  {
    path: "/workflow-template/list",
    name: "workflow-template-list",
    component: require("./components/WorkFlowTemplate/TemplateList").default,
  },
  {
    path: "/workflow-template/create",
    name: "workflow-template-create",
    component: require("./components/WorkFlowTemplate/View/WorkFlowTemplateManage")
      .default,
  },
  {
    path: "/workflow-template/update/:id",
    name: "workflow-template-update",
    component: require("./components/WorkFlowTemplate/View/WorkFlowTemplateManage")
      .default,
  },
  {
    path: "/update-center",
    name: "update-center",
    component: require("./components/UpdateCenter").default,
  },
  {
    path: "/download-center",
    name: "download-center",
    component: require("./components/DownloadCenter").default,
  },
  {
    path: "/schedule-template/create",
    name: "schedule-template-create",
    props: true,
    component: require("./components/ScheduleTemplate/View/ScheduleTemplateCreate")
      .default,
  },
  {
    path: "/schedule-template/update/:id",
    name: "schedule-template-update",
    props: true,
    component: require("./components/ScheduleTemplate/View/ScheduleTemplateCreate")
      .default,
  },
  {
    path: "/schedule-template/list",
    name: "schedule-template-list",
    component: require("./components/ScheduleTemplate/TemplateList").default,
  },
  {
    path: "/digital-retailing/settings/finance",
    name: "digital-retailing-settings-finance",
    component: require("./components/DigitalRetail/Settings/Finance").default,
  },
  {
    path: "/digital-retailing/settings/configurations",
    name: "digital-retailing-settings-configuration",
    component: require("./components/DigitalRetail/Settings/Configuration")
      .default,
  },
  {
    path: "/payment-reports",
    name: "payment-reports",
    component: require("./components/PaymentReport/PaymentReportList").default,
  },
  {
    path: "/payment-reports/views/create",
    name: "payment-report-view-create",
    component: require("./components/PaymentReport/PaymentReportAddCustomView")
      .default,
  },
  {
    path: "/payment-reports/views/edit/:id",
    name: "payment-report-view-update",
    component: require("./components/PaymentReport/PaymentReportAddCustomView")
      .default,
  },
  {
    path: "/inventory-expense-reports",
    name: "inventory-expense-reports",
    component: require("./components/InventoryExpenseReport/InventoryExpenseReportList")
      .default,
  },
  {
    path: "/inventory-expense-reports/views",
    name: "inventory-expense-report-view",
    component: require("./components/InventoryExpenseReport/InventoryExpenseReportAddCustomView")
      .default,
  },
  {
    path: "/inventory-purchase-reports",
    name: "inventory-purchase-reports",
    component: require("./components/InventoryPurchaseReport/InventoryPurchaseReportList")
      .default,
  },
  {
    path: "/inventory-purchase-reports/views",
    name: "inventory-purchase-report-view",
    component: require("./components/InventoryPurchaseReport/InventoryPurchaseReportAddCustomView")
      .default,
  },
  {
    path: "/voucher-template/create",
    name: "voucher-template-create",
    component: require("./components/VoucherTemplate/VoucherTemplateCreate")
      .default,
  },
  {
    path: "/voucher-template/update/:id",
    name: "voucher-template-update",
    component: require("./components/VoucherTemplate/VoucherTemplateCreate")
      .default,
  },
  {
    path: "/voucher-template/list",
    name: "voucher-template-list",
    component: require("./components/VoucherTemplate/VoucherTemplateList")
      .default,
  },
  {
    path: "/voucher",
    name: "voucher-list",
    component: require("./components/Voucher/VoucherList").default,
  },
  {
    path: "/vouchers/views",
    name: "voucher-view",
    component: require("./components/Voucher/VoucherAddCustomView").default,
  },
  {
    path: "/customer-reports",
    name: "customer-reports",
    component: require("./components/CustomerReport/CustomerReportList")
      .default,
  },
  {
    path: "/customer-reports/views",
    name: "customer-report-view",
    component: require("./components/CustomerReport/CustomerReportAddCustomView")
      .default,
  },
  {
    path: "/dealer-track/settings/configurations",
    name: "dealer-track-settings-configuration",
    component: require("./components/DealerTrack/Settings/Configuration")
      .default,
  },
  {
    path: "/tenant-account/settings",
    name: "tenant-account-settings",
    component: require("./components/Settings/TenantAccount").default,
  },
  {
    path: "/tenant-account/phaeton-settings",
    name: "tenant-account-phaeton-settings",
    component: require("./components/Settings/PhaetonWebsite").default,
  },
  {
    path: "/inventory/import",
    name: "inventory-import",
    props: true,
    component: require("./components/Inventory/InventoryImport").default,
  },
  {
    path: "/inventory/updates/description",
    name: "inventory-updates-description",
    component: require("./components/Inventory/InventoryBulkDescription")
      .default,
  },
  {
    path: "/import/inventory/third-party",
    name: "third-party-inventory",
    props: true,
    component: require("./components/Import/ThirdPartyInventory").default,
  },
  {
    path: "/deal/import",
    name: "deal-import",
    props: true,
    component: require("./components/Deal/DealImport").default,
  },
  {
    path: "/tenant-account/website-settings",
    name: "tenant-account-website-settings",
    component: require("./components/Settings/WebsiteSetting").default,
  },
  ,
  {
    path: "/tenant-account/ai-studio-settings",
    name: "tenant-account-ai-studio-settings",
    component: require("./components/Settings/ZopStudioSetting").default,
  },
  {
    path: "/dvi-template/list",
    name: "dvi-template-list",
    component: require("./components/DVITemplate/DVITemplateList").default,
  },
  {
    path: "/dvi-template/create",
    name: "dvi-template-create",
    component: require("./components/DVITemplate/DVITemplateCreate").default,
  },
  {
    path: "/dvi-template/update/:id",
    name: "dvi-template-update",
    component: require("./components/DVITemplate/DVITemplateCreate").default,
  },
  {
    path: "/dvi-template-item/list",
    name: "dvi-template-item-list",
    component: require("./components/DVITemplate/DVITemplateItemList").default,
  },
  {
    path: "/dvi-template-item/create",
    name: "dvi-template-item-create",
    component: require("./components/DVITemplate/DVITemplateItemCreate")
      .default,
  },
  {
    path: "/dvi-template-item/update/:id",
    name: "dvi-template-item-update",
    component: require("./components/DVITemplate/DVITemplateItemCreate")
      .default,
  },
  {
    path: "/tenant-account/pbs-settings",
    name: "tenant-account-pbs-settings",
    component: require("./components/Settings/PBSSetting").default,
  },
  {
    path: "/dvi",
    name: "dvi",
    component: require("./components/DVI/DVIList").default,
  },
  {
    path: "/dvi/views/create",
    name: "dvi-view-create",
    component: require("./components/DVI/DVIAddCustomView").default,
  },
  {
    path: "/dvi/views/edit/:id",
    name: "dvi-view-update",
    component: require("./components/DVI/DVIAddCustomView").default,
  },
  {
    path: "/dvi/update/:id",
    name: "dvi-update",
    component: require("./components/DVI/DVICreate").default,
  },
  {
    path: "/tenant-account/extension-settings",
    name: "tenant-account-extension-settings",
    component: require("./components/Settings/SettingsAccountExtension").default,
  },
  {
    path: "/tenant-account/deal-jacket-template",
    name: "tenant-account-deal-jacket-template",
    component: require("./components/Settings/DealJacket").default,
  }
];

export default new VueRouter({
  routes,
  linkActiveClass: "active",
});
