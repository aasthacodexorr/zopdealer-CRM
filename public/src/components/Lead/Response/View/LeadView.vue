<template>
  <div
    class="tab-pane active"
    id="lead-view"
    aria-labelledby="lead-view"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-view-main" class="lv-page">
      <!-- Hero : START -->
      <div class="lv-hero">
        <div class="lv-hero-main">
          <span class="lv-avatar" :style="avatarStyle">{{ initials }}</span>
          <div class="lv-hero-text">
            <h2 class="lv-name">{{ leadName || "Unnamed lead" }}</h2>
            <div class="lv-hero-meta">
              <span v-if="form.status" class="lv-pill" :class="statusPillClass">{{
                form.status
              }}</span>
              <span v-if="form.source" class="lv-chip text-capitalize"
                ><i class="bx bx-purchase-tag-alt"></i>{{ form.source }}</span
              >
              <span v-if="form.created_at" class="lv-chip lv-chip--muted"
                ><i class="bx bx-calendar"></i>Created {{ createdAt }}</span
              >
            </div>
          </div>
        </div>
        <div class="lv-hero-side">
          <div class="lv-assignee" :class="{ 'is-empty': !form.assignee }">
            <span class="lv-assignee-label">Assigned to</span>
            <span class="lv-assignee-value">
              <i class="bx bx-user-circle"></i>{{ assigneeName }}
            </span>
          </div>
          <div class="lv-quick-actions">
            <a
              v-if="phoneFormat != ''"
              class="lv-action-btn"
              :href="'tel:' + form.phone"
              title="Call lead"
              ><i class="bx bx-phone-call"></i><span>Call</span></a
            >
            <a
              v-if="form.email"
              class="lv-action-btn"
              :href="'mailto:' + form.email"
              title="Email lead"
              ><i class="bx bx-envelope"></i><span>Email</span></a
            >
          </div>
        </div>
      </div>
      <!-- Hero : END -->

      <div class="row lv-grid">
        <div class="col-12 col-xl-8">
          <!-- Lead Details : START -->
          <div class="lv-card">
            <div class="lv-card-header">
              <h4 class="lv-card-title">Lead Details</h4>
            </div>
            <div class="lv-detail-grid">
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-blue"
                  ><i class="bx bx-user"></i
                ></span>
                <div>
                  <span class="lv-label">Full name</span>
                  <span class="lv-value">{{ leadName || "—" }}</span>
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-teal"
                  ><i class="bx bx-phone-call"></i
                ></span>
                <div>
                  <span class="lv-label">Phone</span>
                  <a
                    v-if="phoneFormat != ''"
                    class="lv-value lv-link"
                    :href="'tel:' + form.phone"
                    >{{ phoneFormat }}</a
                  >
                  <span v-else class="lv-value lv-empty">—</span>
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-green"
                  ><i class="bx bx-envelope"></i
                ></span>
                <div class="lv-min-0">
                  <span class="lv-label">Email</span>
                  <a
                    v-if="form.email"
                    class="lv-value lv-link lv-truncate"
                    :href="'mailto:' + form.email"
                    :title="form.email"
                    >{{ form.email }}</a
                  >
                  <span v-else class="lv-value lv-empty">—</span>
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-amber"
                  ><i class="bx bx-purchase-tag-alt"></i
                ></span>
                <div>
                  <span class="lv-label">Source</span>
                  <span class="lv-value text-capitalize">{{
                    form.source || "—"
                  }}</span>
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-purple"
                  ><i class="bx bx-bookmarks"></i
                ></span>
                <div>
                  <span class="lv-label">Status</span>
                  <span class="lv-value">
                    <span
                      v-if="form.status"
                      class="lv-pill"
                      :class="statusPillClass"
                      >{{ form.status }}</span
                    >
                    <span v-else class="lv-empty">—</span>
                  </span>
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-blue"
                  ><i class="bx bx-user-check"></i
                ></span>
                <div>
                  <span class="lv-label">Assignee</span>
                  <span
                    class="lv-value"
                    :class="{ 'lv-empty': !form.assignee }"
                    >{{ assigneeName }}</span
                  >
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-gray"
                  ><i class="bx bx-calendar-plus"></i
                ></span>
                <div>
                  <span class="lv-label">Created</span>
                  <span class="lv-value">{{ createdAt || "—" }}</span>
                </div>
              </div>
              <div class="lv-detail">
                <span class="lv-detail-icon lv-icon-gray"
                  ><i class="bx bx-time-five"></i
                ></span>
                <div>
                  <span class="lv-label">Last updated</span>
                  <span class="lv-value">{{ updatedAt || "—" }}</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Lead Details : END -->

          <!-- More Details : START -->
          <div class="lv-card">
            <div class="lv-card-header">
              <h4 class="lv-card-title">More Details</h4>
              <span class="lv-card-hint" v-if="otherDataEntries.length"
                >{{ otherDataEntries.length }}
                {{ otherDataEntries.length == 1 ? "field" : "fields" }}</span
              >
            </div>
            <div class="lv-kv-grid" v-if="otherDataEntries.length">
              <div
                class="lv-kv"
                v-for="entry in otherDataEntries"
                :key="'other_data_' + entry.key"
              >
                <span class="lv-label text-capitalize">{{
                  formatHeader(entry.key)
                }}</span>
                <span class="lv-value lv-prewrap">{{ entry.value }}</span>
              </div>
            </div>
            <div class="lv-empty-state" v-else>
              <i class="bx bx-detail"></i>
              <span>No additional details were submitted with this lead.</span>
            </div>

            <div
              class="lv-advisory"
              v-if="priceAdvisory"
              :key="'price_advisory'"
            >
              <h5 class="lv-subtitle text-capitalize">
                {{ formatHeader("price_advisory") }}
              </h5>
              <div class="lv-advisory-body" v-html="priceAdvisory"></div>
            </div>
          </div>
          <!-- More Details : END -->
        </div>

        <div class="col-12 col-xl-4">
          <!-- Vehicle : START -->
          <div class="lv-card lv-vehicle" v-if="hasVehicle">
            <div class="lv-card-header">
              <h4 class="lv-card-title">Vehicle of Interest</h4>
              <router-link
                class="lv-card-link"
                :to="{
                  name: 'inventory-update',
                  params: { id: form.inventory_id },
                }"
                exact
                >Open <i class="bx bx-right-arrow-alt"></i
              ></router-link>
            </div>
            <router-link
              class="lv-photo"
              :to="{
                name: 'inventory-update',
                params: { id: form.inventory_id },
              }"
              exact
              title="Open vehicle in inventory"
            >
              <img
                :src="activeImage"
                :alt="vehicleTitle"
                @error="onImageError"
              />
              <span class="lv-photo-count" v-if="website.images.length > 1"
                ><i class="bx bx-image"></i> {{ active_image + 1 }} /
                {{ website.images.length }}</span
              >
            </router-link>
            <div class="lv-thumbs" v-if="website.images.length > 1">
              <button
                type="button"
                class="lv-thumb"
                v-for="(image, index) in website.images"
                :key="'vehicle_image_' + index"
                :class="{ active: index === active_image }"
                @click="active_image = index"
                :aria-label="'Show photo ' + (index + 1)"
              >
                <img :src="image" alt="" loading="lazy" />
              </button>
            </div>
            <div class="lv-vehicle-body">
              <h3 class="lv-vehicle-title">{{ vehicleTitle }}</h3>
              <p class="lv-vehicle-trim" v-if="website.trim">
                {{ website.trim }}
              </p>
              <div class="lv-spec-grid">
                <div class="lv-spec lv-spec--wide">
                  <span class="lv-label">VIN</span>
                  <span class="lv-value lv-mono">{{ website.vin || "—" }}</span>
                </div>
                <div class="lv-spec">
                  <span class="lv-label">Stock #</span>
                  <span class="lv-value lv-mono">{{
                    website.stock_no || "—"
                  }}</span>
                </div>
                <div class="lv-spec">
                  <span class="lv-label">Year</span>
                  <span class="lv-value">{{ website.year || "—" }}</span>
                </div>
                <div class="lv-spec">
                  <span class="lv-label">Make</span>
                  <span class="lv-value">{{ website.make || "—" }}</span>
                </div>
                <div class="lv-spec">
                  <span class="lv-label">Model</span>
                  <span class="lv-value">{{ website.model || "—" }}</span>
                </div>
                <div class="lv-spec">
                  <span class="lv-label">Trim</span>
                  <span class="lv-value">{{ website.trim || "—" }}</span>
                </div>
                <div class="lv-spec">
                  <span class="lv-label">Odometer</span>
                  <span class="lv-value"
                    >{{ formattedOdometer }} {{ website.km_miles }}</span
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="lv-card lv-vehicle-empty" v-else>
            <div class="lv-card-header">
              <h4 class="lv-card-title">Vehicle of Interest</h4>
            </div>
            <div class="lv-empty-state">
              <i class="bx bx-car"></i>
              <span>No vehicle is linked to this lead.</span>
            </div>
          </div>
          <!-- Vehicle : END -->
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Toast from "../../../../library/Toast";

export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    time_zone: String,
  },
  name: "LeadView",
  data() {
    return {
      active_image: 0,
      website: {
        placeholder: "",
        images: [],
        vin: "",
        year: "",
        make: "",
        model: "",
        trim: "",
        stock_no: "",
        odometer: "",
        km_miles: "",
      },
      form: {
        first_name: "",
        last_name: "",
        phone: "",
        email: "",
        inventory_id: "",
        stock_no: "",
        source: "",
        status: "",
        other_data: "",
        assignee: "",
        created_at: "",
        updated_at: "",
      },
    };
  },
  computed: {
    leadName() {
      return this.form.last_name
        ? this.form.first_name + " " + this.form.last_name
        : this.form.first_name;
    },
    phoneFormat() {
      var x = this.form.phone
        .replace(/\D/g, "")
        .match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      return !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
    },
    assigneeName() {
      if (!this.form.assignee) return "Unassigned";
      return (
        this.$helpers.assigneeName(this.allUsers, this.form.assignee) ||
        "Unassigned"
      );
    },
    createdAt() {
      return this.$helpers.changeUTCtoLocalDate(
        this.form.created_at,
        "MM/DD/YYYY hh:mm:ss A",
        this.time_zone
      );
    },
    updatedAt() {
      if (!this.form.updated_at) return "";
      return this.$helpers.changeUTCtoLocalDate(
        this.form.updated_at,
        "MM/DD/YYYY hh:mm:ss A",
        this.time_zone
      );
    },
    initials() {
      const letters =
        String(this.form.first_name || "").trim().charAt(0) +
        String(this.form.last_name || "").trim().charAt(0);
      return letters ? letters.toUpperCase() : "?";
    },
    avatarStyle() {
      const palette = [
        ["#e6efff", "#2f62d6"],
        ["#e3f6e8", "#1e8a43"],
        ["#fff3d1", "#a26a00"],
        ["#efe8ff", "#6b46c1"],
        ["#def6f3", "#11806f"],
        ["#fde6ef", "#b83268"],
      ];
      const value = String(this.leadName || "");
      let hash = 0;
      for (let i = 0; i < value.length; i++) {
        hash = (hash * 31 + value.charCodeAt(i)) >>> 0;
      }
      const [background, color] = palette[hash % palette.length];
      return { background, color };
    },
    statusPillClass() {
      const value = String(this.form.status || "").toLowerCase();
      if (value === "won") return "lv-pill--green";
      if (value === "new") return "lv-pill--blue";
      if (value.includes("appointment")) return "lv-pill--purple";
      if (value.includes("junk") || value.includes("lost")) return "lv-pill--red";
      if (value.includes("interested") || value.includes("follow up"))
        return "lv-pill--amber";
      if (value === "contacted" || value.includes("future"))
        return "lv-pill--teal";
      return "lv-pill--gray";
    },
    /** other_data as a list, skipping empty values and the price advisory (rendered separately) */
    otherDataEntries() {
      const data = this.form.other_data;
      if (!data || typeof data !== "object") return [];
      return Object.keys(data)
        .filter(
          (key) =>
            key !== "price_advisory" && data[key] != null && data[key] !== ""
        )
        .map((key) => ({
          key,
          value:
            typeof data[key] === "object"
              ? JSON.stringify(data[key])
              : data[key],
        }));
    },
    priceAdvisory() {
      const data = this.form.other_data;
      return data && typeof data === "object" && data.price_advisory
        ? data.price_advisory
        : "";
    },
    hasVehicle() {
      return this.website.vin != null && this.website.vin != "";
    },
    vehicleTitle() {
      return [this.website.year, this.website.make, this.website.model]
        .filter((part) => part)
        .join(" ");
    },
    activeImage() {
      return this.website.images.length
        ? this.website.images[this.active_image] || this.website.images[0]
        : this.website.placeholder;
    },
    formattedOdometer() {
      const value = parseInt(String(this.website.odometer || "").replace(/\D/g, ""));
      return isNaN(value) ? this.website.odometer || "—" : value.toLocaleString();
    },
  },
  methods: {
    formatHeader(text) {
      return text.replace(/_/g, " ");
    },
    onImageError(event) {
      if (this.website.placeholder && event.target.src !== this.website.placeholder) {
        event.target.src = this.website.placeholder;
      }
    },
    getPlaceholder() {
      axios({
        method: "get",
        url: window.location.origin + "/api/website/image/placeholder/",
      }).then(({ data }) => {
        this.website.placeholder = data.image_url;
        this.website.placeholder = this.$helpers.parseAbsoluteOrRelativeUrl(
          window.global.cdn,
          this.website.placeholder
        );
      });
    },
    getInventory(inventory_id) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory/show/" +
          inventory_id,
      }).then(({ data }) => {
        this.website.vin = data.vin;
        this.website.year = data.year;
        this.website.make = data.make;
        this.website.model = data.model;
        this.website.trim = data.trim;
        this.website.odometer = data.odometer;
        this.website.km_miles = data.km_miles;
        this.website.stock_no = data.stock_no;
      });
    },
    findByStockNumber(stock_no) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory?zm_stock_no[]=" +
          stock_no,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("inventory")) {
            if (data["inventory"].hasOwnProperty("0")) {
              this.website.vin = data["inventory"]["0"].vin;
              this.website.year = data["inventory"]["0"].year;
              this.website.make = data["inventory"]["0"].make;
              this.website.model = data["inventory"]["0"].model;
              this.website.trim = data["inventory"]["0"].trim;
              this.website.odometer = data["inventory"]["0"].odometer;
              this.website.km_miles = data["inventory"]["0"].km_miles;
              this.website.stock_no = data["inventory"]["0"].stock_no;
              if (data["inventory"]["0"].image_urls != null) {
                this.website.images = data["inventory"]["0"].image_urls
                  .split(";")
                  .filter((url) => url)
                  .map((url) =>
                    this.$helpers.parseAbsoluteOrRelativeUrl(
                      window.global.cdn,
                      url
                    )
                  );
                this.active_image = 0;

                this.website.placeholder =
                  this.website.images[0] || this.website.placeholder;
              }
            }
          }
        })
        .catch(() => {});
    },
    getImage(inventory_id) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory/images/" +
          inventory_id,
      }).then(({ data }) => {
        if (data.hasOwnProperty("gallery")) {
          if (data["gallery"].length > 0) {
            this.website.placeholder = data["gallery"][0]["image_url"];

            this.website.placeholder = this.$helpers.parseAbsoluteOrRelativeUrl(
              window.global.cdn,
              this.website.placeholder
            );
          }
        }
      });
    },
    getLeadResponse(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/leads/responses/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.form = data;
          this.form.other_data = JSON.parse(data["other_data"]);
          if (data["stock_no"] != "" && data["stock_no"] != null) {
            this.findByStockNumber(data["stock_no"]);
            //this.getImage(data["inventory_id"]);
          }

          Event.fire("get_lead_email", vm.form.email);
          Event.fire("get_lead_phone", vm.form.phone);
          Event.fire("get_lead_converted_flag", data["is_converted"]);
          Event.fire("get_lead_name", vm.leadName);
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the lead";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
  mounted() {
    this.getPlaceholder();
    this.getLeadResponse(this.id);
  },
  beforeUnmount() {
    Event.vue.$off("save_complete", null);
    Event.vue.$off("save_lead", null);
    Event.vue.$off("get_lead_email", null);
    Event.vue.$off("get_lead_phone", null);
    Event.vue.$off("get_lead_name", null);
    Event.vue.$off("get_lead_converted_flag", null);
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Lead detail — View tab. Scoped to #lead-view-main.lv-page (the Notes,
   Email and Attachments tabs reuse the #lead-view-main id, so the class matters).
   ========================================================================== */
#lead-view-main.lv-page {
  --lv-border: #e6eaf0;
  --lv-surface: #ffffff;
  --lv-text: #1f2937;
  --lv-text-2: #4b5563;
  --lv-muted: #8a94a6;
  --lv-primary: #5a8dee;
  --lv-primary-soft: #eef4ff;
  --lv-radius: 14px;

  /* bottom padding keeps content clear of the fixed Print / Convert / Edit bar */
  padding: 1.5rem 1.75rem 6rem;
  background: #f6f8fb;
  min-height: 100%;
  color: var(--lv-text);

  /* ---------- Hero ---------- */
  .lv-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.25rem;
    flex-wrap: wrap;
    padding: 1.4rem 1.5rem;
    margin-bottom: 1.25rem;
    background: var(--lv-surface);
    border: 1px solid var(--lv-border);
    border-radius: var(--lv-radius);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  }
  .lv-hero-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 0;
  }
  .lv-avatar {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 600;
    letter-spacing: 0.02em;
  }
  .lv-hero-text {
    min-width: 0;
  }
  .lv-name {
    margin: 0 0 0.45rem;
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--lv-text);
    line-height: 1.2;
  }
  .lv-hero-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  .lv-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.28rem 0.65rem;
    border-radius: 7px;
    background: #f1f4f8;
    color: var(--lv-text-2);
    font-size: 0.85rem;
    font-weight: 500;
    i {
      font-size: 1rem;
    }
  }
  .lv-chip--muted {
    background: transparent;
    color: var(--lv-muted);
    padding-left: 0.2rem;
  }
  .lv-hero-side {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
  }
  .lv-assignee {
    display: flex;
    flex-direction: column;
    padding: 0.45rem 0.9rem;
    border-radius: 10px;
    background: var(--lv-primary-soft);
    &.is-empty {
      background: #fff3d1;
      .lv-assignee-value {
        color: #a26a00;
      }
    }
  }
  .lv-assignee-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--lv-muted);
  }
  .lv-assignee-value {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-weight: 600;
    color: #2f62d6;
    i {
      font-size: 1.15rem;
    }
  }
  .lv-quick-actions {
    display: flex;
    gap: 0.5rem;
  }
  .lv-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    height: 40px;
    padding: 0 0.95rem;
    border-radius: 10px;
    border: 1px solid var(--lv-border);
    background: var(--lv-surface);
    color: var(--lv-text-2);
    font-weight: 500;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i {
      font-size: 1.15rem;
      color: var(--lv-primary);
    }
    &:hover {
      border-color: var(--lv-primary);
      background: var(--lv-primary-soft);
      color: var(--lv-text);
    }
  }

  /* ---------- Cards ---------- */
  .lv-grid {
    margin-left: -0.6rem;
    margin-right: -0.6rem;
    > [class*="col-"] {
      padding-left: 0.6rem;
      padding-right: 0.6rem;
    }
  }
  .lv-card {
    background: var(--lv-surface);
    border: 1px solid var(--lv-border);
    border-radius: var(--lv-radius);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    margin-bottom: 1.25rem;
    overflow: hidden;
  }
  .lv-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.4rem;
    border-bottom: 1px solid var(--lv-border);
  }
  .lv-card-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--lv-text);
  }
  .lv-card-hint {
    font-size: 0.85rem;
    color: var(--lv-muted);
  }
  .lv-card-link {
    display: inline-flex;
    align-items: center;
    gap: 0.15rem;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--lv-primary);
    i {
      font-size: 1.1rem;
    }
    &:hover {
      text-decoration: underline;
    }
  }

  .lv-label {
    display: block;
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--lv-muted);
    margin-bottom: 0.15rem;
  }
  .lv-value {
    display: block;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--lv-text);
    word-break: break-word;
  }
  .lv-link {
    color: var(--lv-text);
    &:hover {
      color: var(--lv-primary);
      text-decoration: underline;
    }
  }
  .lv-empty {
    color: var(--lv-muted);
    font-weight: 400;
  }
  .lv-min-0 {
    min-width: 0;
  }
  .lv-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .lv-mono {
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.02em;
  }
  .lv-prewrap {
    white-space: pre-wrap;
  }

  /* Lead details grid */
  .lv-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem 1.5rem;
    padding: 1.3rem 1.4rem 1.4rem;
  }
  .lv-detail {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    min-width: 0;
  }
  .lv-detail-icon {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
  }
  .lv-icon-blue { background: #e6efff; color: #2f62d6; }
  .lv-icon-teal { background: #def6f3; color: #11806f; }
  .lv-icon-green { background: #e3f6e8; color: #1e8a43; }
  .lv-icon-amber { background: #fff3d1; color: #a26a00; }
  .lv-icon-purple { background: #efe8ff; color: #6b46c1; }
  .lv-icon-gray { background: #f1f4f8; color: #5b6575; }

  /* Status pill */
  .lv-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.28rem 0.7rem;
    border-radius: 7px;
    font-size: 0.85rem;
    font-weight: 500;
    line-height: 1.2;
    white-space: nowrap;
  }
  .lv-pill--blue { background: #e6efff; color: #2f62d6; }
  .lv-pill--green { background: #e3f6e8; color: #1e8a43; }
  .lv-pill--amber { background: #fff3d1; color: #a26a00; }
  .lv-pill--red { background: #fde6e6; color: #c23434; }
  .lv-pill--purple { background: #efe8ff; color: #6b46c1; }
  .lv-pill--teal { background: #def6f3; color: #11806f; }
  .lv-pill--gray { background: #eef1f5; color: #5b6575; }

  /* More details */
  .lv-kv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0;
    padding: 0.4rem 1.4rem 1.2rem;
  }
  .lv-kv {
    padding: 0.85rem 0;
    border-bottom: 1px dashed #e6eaf0;
    padding-right: 1rem;
  }
  .lv-advisory {
    border-top: 1px solid var(--lv-border);
    padding: 1.1rem 1.4rem 1.4rem;
  }
  .lv-subtitle {
    margin: 0 0 0.8rem;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--lv-text);
  }
  .lv-advisory-body {
    overflow-x: auto;
  }
  .lv-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.4rem;
    padding: 2.2rem 1rem;
    color: var(--lv-muted);
    text-align: center;
    i {
      font-size: 2.2rem;
      color: #c3cad6;
    }
  }

  /* ---------- Vehicle ---------- */
  .lv-photo {
    position: relative;
    display: block;
    aspect-ratio: 16 / 10;
    background: #f1f4f8;
    overflow: hidden;
    cursor: pointer;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.3s ease;
    }
    &:hover img {
      transform: scale(1.03);
    }
  }
  .lv-photo-count {
    position: absolute;
    right: 10px;
    bottom: 10px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    background: rgba(17, 24, 39, 0.65);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 500;
  }
  .lv-thumbs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
    padding: 0.75rem 1.1rem 0;
  }
  .lv-thumb {
    padding: 0;
    border: 2px solid transparent;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f4f8;
    aspect-ratio: 16 / 10;
    cursor: pointer;
    opacity: 0.75;
    transition: opacity 0.15s, border-color 0.15s;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    &:hover {
      opacity: 1;
    }
    &.active {
      border-color: var(--lv-primary);
      opacity: 1;
    }
  }
  .lv-vehicle-body {
    padding: 1rem 1.25rem 1.3rem;
  }
  .lv-vehicle-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--lv-text);
  }
  .lv-vehicle-trim {
    margin: 0.15rem 0 0;
    color: var(--lv-muted);
    font-size: 0.9rem;
  }
  .lv-spec-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.6rem;
    margin-top: 1rem;
  }
  .lv-spec {
    padding: 0.6rem 0.75rem;
    border-radius: 10px;
    background: #f6f8fb;
    min-width: 0;
    .lv-value {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }
  .lv-spec--wide {
    grid-column: 1 / -1;
  }

  @media (max-width: 767.98px) {
    padding: 0.75rem 1rem 6rem;
    .lv-hero {
      padding: 1.1rem;
    }
    .lv-name {
      font-size: 1.35rem;
    }
    .lv-avatar {
      width: 52px;
      height: 52px;
      font-size: 1.15rem;
    }
  }
}
</style>
