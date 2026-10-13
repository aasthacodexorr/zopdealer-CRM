import axios from "axios";

const namespaced = true;

const state = {
  modules: ["Inventory", "Lead", "Customer", "Deal"],
  common_placeholders: [
    { label: "Business Name", value: "Business Name" },
    { label: "Business Phone", value: "Business Phone" },
    { label: "Business Email", value: "Business Email" },
    { label: "Business Address", value: "Business Address" },
    { label: "Business Website", value: "Business Website" },
    { label: "Business Review Link", value: "Business Review Link" },
  ],
  inventory_placeholders: [
    { label: "Inventory Year", value: "Inventory Year" },
    { label: "Inventory Make", value: "Inventory Make" },
    { label: "Inventory Model", value: "Inventory Model" },
    { label: "Inventory Trim", value: "Inventory Trim" },
    { label: "Inventory Stock Number", value: "Inventory Stock Number" },
    { label: "Inventory VIN", value: "Inventory VIN" },
    { label: "Inventory Odometer", value: "Inventory Odometer" },
  ],
  lead_placeholders: [
    { label: "Lead First Name", value: "Lead First Name" },
    { label: "Lead Full Name", value: "Lead Full Name" },
    { label: "Lead Mobile", value: "Lead Mobile" },
    { label: "Lead Email", value: "Lead Email" },
    { label: "Lead Assignee", value: "Lead Assignee" },
    { label: "Inventory Year", value: "Inventory Year" },
    { label: "Inventory Make", value: "Inventory Make" },
    { label: "Inventory Model", value: "Inventory Model" },
    { label: "Inventory Trim", value: "Inventory Trim" },
    { label: "Inventory Stock Number", value: "Inventory Stock Number" },
    { label: "Inventory VIN", value: "Inventory VIN" },
    { label: "Inventory Odometer", value: "Inventory Odometer" },
  ],
  customer_placeholders: [
    { label: "Customer First Name", value: "Customer First Name" },
    { label: "Customer Full Name", value: "Customer Full Name" },
    { label: "Customer Mobile", value: "Customer Mobile" },
    { label: "Customer Email", value: "Customer Email" },
    { label: "Personalized Review Link", value: "Personalized Review Link" },
    {
      label: "Personalized Short Review Link",
      value: "Personalized Short Review Link",
    },
  ],
  deal_placeholders: [
    { label: "Customer First Name", value: "Customer First Name" },
    { label: "Customer Full Name", value: "Customer Full Name" },
    { label: "Customer Mobile", value: "Customer Mobile" },
    { label: "Customer Email", value: "Customer Email" },
    { label: "Personalized Review Link", value: "Personalized Review Link" },
    {
      label: "Personalized Short Review Link",
      value: "Personalized Short Review Link",
    },
    { label: "Deal Assignee Name", value: "Deal Assignee Name" },
    { label: "Deal Approver Name", value: "Deal Approver Name" },
    { label: "Inventory Year", value: "Inventory Year" },
    { label: "Inventory Make", value: "Inventory Make" },
    { label: "Inventory Model", value: "Inventory Model" },
    { label: "Inventory Trim", value: "Inventory Trim" },
    { label: "Inventory Stock Number", value: "Inventory Stock Number" },
    { label: "Inventory VIN", value: "Inventory VIN" },
    { label: "Inventory Odometer", value: "Inventory Odometer" },
  ],
  common_email_placeholders: [
    { label: "Business Email", value: "Business Email" },
  ],
  inventory_email_placeholders: [],
  lead_email_placeholders: [
    { label: "Lead Email", value: "Lead Email" },
    { label: "Lead Assignee Email", value: "Lead Assignee Email" },
  ],
  customer_email_placeholder: [
    { label: "Customer Email", value: "Customer Email" },
  ],
  deal_email_placeholder: [
    { label: "Customer Email", value: "Customer Email" },
    { label: "Deal Assignee Email", value: "Deal Assignee Email" },
    { label: "Deal Approver Email", value: "Deal Approver Email" },
  ],
  common_mobile_placeholders: [
    { label: "Business Mobile", value: "Business Mobile" },
  ],
  inventory_mobile_placeholders: [],
  lead_mobile_placeholders: [
    { label: "Lead Mobile", value: "Lead Mobile" },
    { label: "Lead Assignee Mobile", value: "Lead Assignee Mobile" },
  ],
  customer_mobile_placeholder: [
    { label: "Customer Mobile", value: "Customer Mobile" },
  ],
  deal_mobile_placeholder: [
    { label: "Customer Mobile", value: "Customer Mobile" },
    { label: "Deal Assignee Mobile", value: "Deal Assignee Mobile" },
    { label: "Deal Approver Mobile", value: "Deal Approver Mobile" },
  ]
};

const getters = {
  getAvailableWorkFlowModules: (state) => state.modules,
  getAvailableCommonPlaceholders: (state) => state.common_placeholders,
  getAvailableInventoryPlaceholders: (state) => state.inventory_placeholders,
  getAvailableLeadPlaceholders: (state) => state.lead_placeholders,
  getAvailableCustomerPlaceholders: (state) => state.customer_placeholders,
  getAvailableDealPlaceholders: (state) => state.deal_placeholders,
  
  getAvailableCommonEmailPlaceholders: (state) => state.common_email_placeholders,
  getAvailableInventoryEmailPlaceholders: (state) => state.inventory_email_placeholders,
  getAvailableLeadEmailPlaceholders: (state) => state.lead_email_placeholders,
  getAvailableCustomerEmailPlaceholders: (state) => state.customer_email_placeholder,
  getAvailableDealEmailPlaceholders: (state) => state.deal_email_placeholder,

  getAvailableCommonMobilePlaceholders: (state) => state.common_mobile_placeholders,
  getAvailableInventoryMobilePlaceholders: (state) => state.inventory_mobile_placeholders,
  getAvailableLeadMobilePlaceholders: (state) => state.lead_mobile_placeholders,
  getAvailableCustomerMobilePlaceholders: (state) => state.customer_mobile_placeholder,
  getAvailableDealMobilePlaceholders: (state) => state.deal_mobile_placeholder,
};

export default {
  namespaced,
  state,
  getters,
};
