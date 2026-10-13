<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="isAuthorized">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <h1 class="col-12 ml-2">Reports <span><a href="https://help.zopmedia.com/portal/en/kb/zopsoftware/reports" target="_blank" class="btn-link"><i class='bx bxs-help-circle bx-sm'></i></a></span></h1>
        <div class="col-12">
          <div class="card-group">
            <!-- inventory card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'inventory-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Inventory Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> In Stock Inventory Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Sold Inventory Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Inventory Purchase Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Inventory Purchase & Expense Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Garage Register</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Customized Inventory Reports</li>
                  </ul>
                </div>
              </div>
            </router-link>
            <!-- inventory card : END -->

             <!-- deal card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'deal-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Deal Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Today Days Deal Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Yesterday Days Deal Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Last 7 Days Deal Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Last 30 Days Deal Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Sales Report Monthly & Yearly</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Customized Deal Reports</li>
                  </ul>
                  <div class="text-danger"><b>NOTE: It takes upto 24 hours for latest changes to reflect in Deals Report.</b></div>
                </div>
              </div>
            </router-link>
            <!-- deal card : END -->

            <!-- lead card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'lead-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Lead Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> New Lead Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Won Lead Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Contact In Future Lead Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Lost Lead Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Third Party Lead Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Customized Lead Reports</li>
                  </ul>
                </div>
              </div>
            </router-link>
            <!-- lead card : END -->

            <!-- customer card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'customer-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Customer Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> All Customers Reports</li>
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> Customized Lead Reports</li>
                  </ul>
                </div>
              </div>
            </router-link>
            <!-- customer card : END -->

            

            <!-- standalone inventory expense report card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'inventory-expense-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Inventory Expense Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> All Inventory Expenses</li>
                  </ul>
                </div>
              </div>
            </router-link>
            <!-- standalone inventory expense report card : END -->

            <!-- standalone inventory purchase report card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'inventory-purchase-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Inventory Purchase Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> All Inventory Purchases</li>
                  </ul>
                </div>
              </div>
            </router-link>
            <!-- standalone inventory purchase report card : END -->

            <!-- payment card : START -->
            <router-link tag="div" style="cursor:pointer;" class="col-xl-4 col-md-4 col-sm-12" :to="{ name: 'payment-reports' }" exact>
              <div class="card  border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                    <h3 class="mb-0" style="font-weight:600">Payment Reports</h3></div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                      <li class="p-25"><i style="margin-top:-2px" class='bx bx-chevron-right text-primary bx-sm'></i> All Payments - Last 30 Days</li>
                  </ul>
                </div>
              </div>
            </router-link>
            <!-- payment card : END -->

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
export default {
  props: ["application", "app_metadata"],
  name: "LandingReport",
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
};
</script>