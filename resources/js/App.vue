<template>
  <div class="min-h-screen flex flex-col bg-stone-50 dark:bg-stone-950 text-stone-900 dark:text-stone-100 font-sans selection:bg-red-600 selection:text-white transition-colors duration-200 overflow-x-hidden w-full">

    <!-- IF IN ADMIN VIEW -->
    <template v-if="currentView === 'admin' && currentUser">
      <AdminDashboard
        :settings="settings"
        :user="currentUser"
        :is-dark-mode="isDarkMode"
        @toggle-dark-mode="toggleDarkMode"
        @switch-to-shop="currentView = 'shop'"
        @logged-out="onLoggedOut"
        @refresh-products="fetchProducts"
        @refresh-settings="onSettingsRefreshed"
      />
    </template>

    <!-- MAIN STOREFRONT VIEW -->
    <template v-else>
      <!-- Navbar -->
      <Navbar
        :settings="settings"
        :cart-count="cartItemCount"
        :is-admin="currentUser && !!currentUser.is_admin"
        :current-user="currentUser"
        :is-dark-mode="isDarkMode"
        @toggle-dark-mode="toggleDarkMode"
        @open-cart="isCartOpen = true"
        @open-track="openTrackModal('')"
        @open-bulk="isBulkOpen = true"
        @open-admin-login="isLoginOpen = true"
        @logout="handleCustomerLogout"
        @scroll-to="scrollToSection"
        @navigate="handleNavigate"
      />

      <!-- Hero Banner -->
      <HeroBanner
        :settings="settings"
        @scroll-to="scrollToSection"
      />

      <!-- Apple Varieties Catalog Section -->
      <section id="catalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">

        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 dark:bg-red-950/70 text-red-800 dark:text-red-300 text-xs font-semibold mb-2">
              <span>FRESH HARVEST 2026</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 dark:text-stone-100 font-serif-title tracking-tight">
              Tree-Ripened Mountain Varieties
            </h2>
            <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mt-1 max-w-xl">
              Plucked at optimal brix sweetness and packed immediately in individual foam nets.
            </p>
          </div>

          <!-- Category Filter Pills & Search -->
          <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search bar -->
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search variety, origin..."
                class="w-full sm:w-60 pl-9 pr-4 py-2.5 rounded-2xl bg-white dark:bg-stone-900 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 dark:focus:ring-red-950 transition-all shadow-2xs"
              />
              <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Categories Dropdown / Buttons -->
            <select
              v-model="selectedCategory"
              class="px-4 py-2.5 rounded-2xl bg-white dark:bg-stone-900 border border-stone-300 dark:border-stone-700 text-xs font-bold text-stone-700 dark:text-stone-200 outline-none focus:border-red-500 shadow-2xs cursor-pointer"
            >
              <option value="all">All Apple Varieties ({{ appleProducts.length }})</option>
              <option v-for="cat in uniqueCategories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>
        </div>

        <!-- Products Grid -->
        <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <ProductCard
            v-for="product in filteredProducts"
            :key="product.id"
            :product="product"
            :farmer-phone="settings.whatsapp"
            @view-detail="openProductDetail"
            @add-to-cart="handleAddToCart"
          />
        </div>

        <!-- Empty Filter Results -->
        <div v-else class="text-center py-16 bg-white dark:bg-stone-900 rounded-3xl border border-stone-200 dark:border-stone-800 p-8 shadow-2xs">
          <div class="text-4xl mb-3">🍏</div>
          <h3 class="font-bold text-stone-800 dark:text-stone-100 text-lg">No varieties matched your search</h3>
          <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Try resetting the search keyword or filter.</p>
          <button @click="searchQuery = ''; selectedCategory = 'all'" class="mt-4 px-4 py-2 rounded-xl bg-stone-900 dark:bg-stone-800 text-white text-xs font-bold cursor-pointer">
            Reset Filters
          </button>
        </div>

      </section>

      <!-- Orchard Delicacies & Other Farm Products Section -->
      <OtherProductsSection
        v-if="otherProducts.length > 0"
        :products="otherProducts"
        :settings="settings"
        @view-detail="openProductDetail"
        @add-to-cart="handleAddToCart"
      />

      <!-- Orchard Story & Natural vs Wax Comparison -->
      <OrchardStory :settings="settings" />

      <!-- Footer -->
      <FooterSection
        :settings="settings"
        :is-dark-mode="isDarkMode"
        @toggle-dark-mode="toggleDarkMode"
        @scroll-to="scrollToSection"
        @open-track="isTrackOpen = true"
        @open-bulk="isBulkOpen = true"
        @open-admin-login="isLoginOpen = true"
      />

      <!-- Modals and Drawers -->
      <ProductDetailModal
        :product="selectedProduct"
        :farmer-phone="settings.whatsapp"
        :farmer-name="settings.farmer_name"
        @close="selectedProduct = null"
        @add-to-cart="handleAddToCart"
      />

      <CartDrawer
        :is-open="isCartOpen"
        :items="cartItems"
        :free-shipping-min="Number(settings.free_shipping_min_order) || 1500"
        :enable-free-shipping="settings.enable_free_shipping === undefined || settings.enable_free_shipping === '1' || settings.enable_free_shipping === true || settings.enable_free_shipping === 'true'"
        :shipping-rate="Number(settings.default_shipping_fee) || 120"
        @close="isCartOpen = false"
        @remove-item="removeCartItem"
        @update-qty="updateCartQty"
        @proceed-checkout="openCheckout"
      />

      <CheckoutModal
        :is-open="isCheckoutOpen"
        :items="cartItems"
        :total-amount="cartTotalAmount"
        :settings="settings"
        :current-user="currentUser"
        @close="isCheckoutOpen = false"
        @order-placed="onOrderPlaced"
      />

      <OrderSuccessModal
        :order="confirmedOrder"
        :whatsapp-url="confirmedOrderWhatsAppUrl"
        @close="confirmedOrder = null"
        @track-order="openTrackWithOrder"
      />

      <TrackOrderModal
        :is-open="isTrackOpen"
        :initial-query="trackOrderQuery"
        :current-user="currentUser"
        @close="isTrackOpen = false"
        @open-login="isLoginOpen = true"
      />

      <BulkInquiryModal
        :is-open="isBulkOpen"
        :settings="settings"
        @close="isBulkOpen = false"
      />

      <AdminLoginModal
        :is-open="isLoginOpen"
        @close="isLoginOpen = false"
        @logged-in="onLoggedIn"
      />
    </template>

  </div>
</template>

<script>
import axios from 'axios';
import Navbar from './components/Navbar.vue';
import HeroBanner from './components/HeroBanner.vue';
import ProductCard from './components/ProductCard.vue';
import ProductDetailModal from './components/ProductDetailModal.vue';
import CartDrawer from './components/CartDrawer.vue';
import CheckoutModal from './components/CheckoutModal.vue';
import OrderSuccessModal from './components/OrderSuccessModal.vue';
import TrackOrderModal from './components/TrackOrderModal.vue';
import BulkInquiryModal from './components/BulkInquiryModal.vue';
import OrchardStory from './components/OrchardStory.vue';
import OtherProductsSection from './components/OtherProductsSection.vue';
import FooterSection from './components/FooterSection.vue';
import AdminDashboard from './components/admin/AdminDashboard.vue';
import AdminLoginModal from './components/admin/AdminLoginModal.vue';

export default {
  components: {
    Navbar,
    HeroBanner,
    ProductCard,
    OtherProductsSection,
    ProductDetailModal,
    CartDrawer,
    CheckoutModal,
    OrderSuccessModal,
    TrackOrderModal,
    BulkInquiryModal,
    OrchardStory,
    FooterSection,
    AdminDashboard,
    AdminLoginModal
  },
  data() {
    return {
      currentView: 'shop',
      settings: {},
      products: [],
      cartItems: [],
      currentUser: null,

      // Modals
      selectedProduct: null,
      isCartOpen: false,
      isCheckoutOpen: false,
      confirmedOrder: null,
      confirmedOrderWhatsAppUrl: '',
      isTrackOpen: false,
      trackOrderQuery: '',
      isBulkOpen: false,
      isLoginOpen: false,

      // Filters
      searchQuery: '',
      selectedCategory: 'all',

      // Dark Mode
      isDarkMode: false
    }
  },
  computed: {
    cartItemCount() {
      return this.cartItems.reduce((acc, item) => acc + item.quantity, 0);
    },
    cartSubtotal() {
      return this.cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },
    cartShippingFee() {
      const isFreeEnabled = this.settings.enable_free_shipping === undefined || 
                            this.settings.enable_free_shipping === '1' || 
                            this.settings.enable_free_shipping === true || 
                            this.settings.enable_free_shipping === 'true';
      const freeMin = Number(this.settings.free_shipping_min_order) || 1500;
      const defaultFee = Number(this.settings.default_shipping_fee) || 120;
      if (this.cartSubtotal === 0) return 0;
      if (isFreeEnabled && freeMin > 0 && this.cartSubtotal >= freeMin) return 0;
      return defaultFee;
    },
    cartTotalAmount() {
      return this.cartSubtotal + this.cartShippingFee;
    },
    appleProducts() {
      if (!Array.isArray(this.products)) return [];
      return this.products.filter(p => !p.product_type || p.product_type === 'apple');
    },
    otherProducts() {
      if (!Array.isArray(this.products)) return [];
      return this.products.filter(p => p.product_type === 'other');
    },
    uniqueCategories() {
      const cats = new Set(this.appleProducts.map(p => p.category).filter(Boolean));
      return Array.from(cats);
    },
    filteredProducts() {
      return this.appleProducts.filter(p => {
        const matchesCat = this.selectedCategory === 'all' || p.category === this.selectedCategory;
        const q = this.searchQuery.toLowerCase();
        const matchesSearch = !q ||
          p.name.toLowerCase().includes(q) ||
          (p.tagline && p.tagline.toLowerCase().includes(q)) ||
          (p.origin && p.origin.toLowerCase().includes(q));
        return matchesCat && matchesSearch;
      });
    }
  },
  mounted() {
    this.initTheme();
    Promise.all([
      this.fetchSettings(),
      this.fetchProducts(),
      this.checkAuth(),
    ]);
    this.loadCartFromStorage();
  },
  methods: {
    async fetchSettings() {
      try {
        const res = await axios.get('/api/settings');
        if (res.data.success) {
          this.settings = res.data.data;
        }
      } catch (err) {
        console.error(err);
      }
    },
    async fetchProducts() {
      try {
        const res = await axios.get('/api/products');
        if (res.data.success) {
          const raw = res.data.data;
          this.products = Array.isArray(raw) ? raw : (raw && typeof raw === 'object' ? Object.values(raw) : []);
        }
      } catch (err) {
        console.error(err);
      }
    },
    async checkAuth() {
      try {
        const res = await axios.get('/api/auth/me');
        if (res.data.authenticated) {
          this.currentUser = res.data.user;
        }
      } catch (err) {}
    },
    loadCartFromStorage() {
      try {
        const saved = localStorage.getItem('apple_farm_cart');
        if (saved) {
          this.cartItems = JSON.parse(saved);
        }
      } catch (e) {}
    },
    saveCartToStorage() {
      try {
        localStorage.setItem('apple_farm_cart', JSON.stringify(this.cartItems));
      } catch (e) {}
    },
    handleAddToCart(payload) {
      // Check if same product & box already exists in cart
      const existing = this.cartItems.find(
        item => item.product.id === payload.product.id && item.box.size === payload.box.size
      );

      if (existing) {
        existing.quantity += payload.quantity;
      } else {
        this.cartItems.push(payload);
      }

      this.saveCartToStorage();
      this.isCartOpen = true;
    },
    removeCartItem(index) {
      this.cartItems.splice(index, 1);
      this.saveCartToStorage();
    },
    updateCartQty({ index, qty }) {
      if (qty <= 0) {
        this.removeCartItem(index);
      } else {
        this.cartItems[index].quantity = qty;
        this.saveCartToStorage();
      }
    },
    openProductDetail(prod) {
      this.selectedProduct = prod;
    },
    openCheckout() {
      this.isCartOpen = false;
      this.isCheckoutOpen = true;
    },
    openTrackModal(query = '') {
      this.trackOrderQuery = query || '';
      this.isTrackOpen = true;
    },
    openTrackWithOrder(orderNumber) {
      this.confirmedOrder = null;
      this.openTrackModal(orderNumber);
    },
    onOrderPlaced({ order, whatsapp_url }) {
      this.confirmedOrder = order;
      this.confirmedOrderWhatsAppUrl = whatsapp_url;
      this.isCheckoutOpen = false;
      this.cartItems = [];
      this.saveCartToStorage();
    },
    onLoggedIn(user) {
      this.currentUser = user;
      if (user && user.is_admin) {
        this.currentView = 'admin';
      } else {
        this.currentView = 'shop';
      }
    },
    async handleCustomerLogout() {
      try {
        await axios.post('/api/auth/logout');
      } catch (e) {}
      this.currentUser = null;
      this.currentView = 'shop';
    },
    onLoggedOut() {
      this.currentUser = null;
      this.currentView = 'shop';
    },
    onSettingsRefreshed(newSettings) {
      this.settings = newSettings;
    },
    handleNavigate(view) {
      if (view === 'admin') {
        if (this.currentUser && this.currentUser.is_admin) {
          this.currentView = 'admin';
        } else {
          this.isLoginOpen = true;
        }
      } else {
        this.currentView = 'shop';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    },
    scrollToSection(id) {
      this.currentView = 'shop';
      this.$nextTick(() => {
        const el = document.getElementById(id);
        if (el) {
          el.scrollIntoView({ behavior: 'smooth' });
        }
      });
    },
    initTheme() {
      const saved = localStorage.getItem('apple_farm_theme');
      if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        this.isDarkMode = true;
        document.documentElement.classList.add('dark');
      } else {
        this.isDarkMode = false;
        document.documentElement.classList.remove('dark');
      }
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      if (this.isDarkMode) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('apple_farm_theme', 'dark');
      } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('apple_farm_theme', 'light');
      }
    }
  }
}
</script>
