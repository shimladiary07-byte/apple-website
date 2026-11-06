<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-xl bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden my-8 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Close Button -->
      <button 
        @click="$emit('close')" 
        class="absolute top-4 right-4 p-2 text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-xl transition-colors cursor-pointer"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <!-- Modal Header -->
      <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300 flex items-center justify-center font-bold text-lg">
          📦
        </div>
        <div>
          <h3 class="text-xl font-bold text-stone-900 dark:text-stone-100 font-serif-title">
            {{ currentUser ? 'My Apple Orders & Tracking' : 'Track Fresh Apple Shipment' }}
          </h3>
          <p class="text-xs text-stone-500 dark:text-stone-400">
            {{ currentUser ? `Logged in as ${currentUser.name} (${currentUser.email})` : 'Check live harvest, packing and courier status' }}
          </p>
        </div>
      </div>

      <!-- Navigation Tabs (if Logged in) -->
      <div v-if="currentUser" class="flex p-1 bg-stone-100 dark:bg-stone-800 rounded-2xl mb-5 text-xs font-bold">
        <button
          type="button"
          @click="switchToTab('my-orders')"
          :class="[
            'flex-1 py-2 rounded-xl transition-all cursor-pointer text-center flex items-center justify-center gap-1.5',
            activeTab === 'my-orders' 
              ? 'bg-white dark:bg-stone-700 text-stone-900 dark:text-stone-100 shadow-xs' 
              : 'text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100'
          ]"
        >
          <span>📋 My Orders</span>
          <span v-if="myOrders.length > 0" class="px-1.5 py-0.2 rounded-full bg-red-100 dark:bg-red-950/80 text-red-700 dark:text-red-300 text-[10px]">
            {{ myOrders.length }}
          </span>
        </button>
        <button
          type="button"
          @click="switchToTab('search')"
          :class="[
            'flex-1 py-2 rounded-xl transition-all cursor-pointer text-center',
            activeTab === 'search' 
              ? 'bg-white dark:bg-stone-700 text-stone-900 dark:text-stone-100 shadow-xs' 
              : 'text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100'
          ]"
        >
          🔍 Search by Order ID / Phone
        </button>
      </div>

      <!-- Guest Login Callout Banner (if not logged in) -->
      <div v-if="!currentUser" class="mb-4 p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-900 flex items-center justify-between text-xs">
        <div class="flex items-center gap-2 text-stone-700 dark:text-stone-300">
          <span>💡</span>
          <span>Already have an account?</span>
        </div>
        <button 
          type="button" 
          @click="$emit('close'); $emit('open-login')" 
          class="font-bold text-red-600 dark:text-red-400 hover:underline cursor-pointer"
        >
          Log In for auto tracking
        </button>
      </div>

      <!-- TAB 1: LOGGED-IN MY ORDERS LIST -->
      <div v-if="currentUser && activeTab === 'my-orders'">
        
        <!-- Loading State -->
        <div v-if="isLoadingMyOrders" class="py-12 text-center text-xs text-stone-500 dark:text-stone-400 space-y-2">
          <div class="inline-block animate-spin text-2xl">⏳</div>
          <p>Fetching your orders from the mountain farm...</p>
        </div>

        <!-- Detail View of Selected Order -->
        <div v-else-if="selectedOrder" class="space-y-4">
          <div class="flex items-center justify-between pb-2 border-b border-stone-200 dark:border-stone-800">
            <button 
              @click="selectedOrder = null" 
              class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline flex items-center gap-1 cursor-pointer"
            >
              ← Back to all orders
            </button>
            <span class="text-xs font-bold text-stone-500 dark:text-stone-400">
              Placed on {{ formatDate(selectedOrder.created_at) }}
            </span>
          </div>

          <!-- Order Summary Card -->
          <div class="p-4 rounded-2xl bg-stone-50 dark:bg-stone-800/80 border border-stone-200 dark:border-stone-700 text-xs space-y-2">
            <div class="flex items-center justify-between font-bold text-stone-900 dark:text-stone-100">
              <span class="text-sm font-serif-title font-bold">Order #{{ selectedOrder.order_number }}</span>
              <span class="px-2.5 py-0.5 rounded-full text-[11px] uppercase font-bold"
                :class="statusBadgeClass(selectedOrder.status)">
                Status: {{ selectedOrder.status }}
              </span>
            </div>

            <!-- Items -->
            <div class="text-stone-700 dark:text-stone-300 pt-1 border-t border-stone-200 dark:border-stone-700">
              <div v-for="(it, idx) in selectedOrder.items" :key="idx" class="flex justify-between py-0.5">
                <span>• {{ it.product_name }} ({{ it.box_size }}) × {{ it.quantity }}</span>
                <span class="font-semibold">₹{{ it.price * it.quantity }}</span>
              </div>
            </div>

            <div class="flex justify-between pt-1 border-t border-stone-200 dark:border-stone-700 text-stone-500 dark:text-stone-400">
              <span>Destination: {{ selectedOrder.customer_name }}, {{ selectedOrder.city }} ({{ selectedOrder.pincode }})</span>
              <span class="font-bold text-stone-900 dark:text-stone-100">Total: ₹{{ selectedOrder.total_amount }}</span>
            </div>
            
            <div v-if="selectedOrder.tracking_courier" class="mt-2 pt-2 border-t border-stone-200 dark:border-stone-700 font-medium text-stone-700 dark:text-stone-300 flex items-center justify-between">
              <span>Courier: {{ selectedOrder.tracking_courier }}</span>
              <span class="font-mono bg-white dark:bg-stone-900 px-2 py-0.5 rounded border border-stone-300 dark:border-stone-600 text-stone-900 dark:text-stone-100">AWB: {{ selectedOrder.tracking_number }}</span>
            </div>
          </div>

          <!-- Step-by-Step Shipment Timeline -->
          <div class="relative pl-6 space-y-6 pt-3 before:absolute before:left-2 before:top-4 before:bottom-2 before:w-0.5 before:bg-stone-200 dark:before:bg-stone-800">
            
            <!-- Step 1: Placed -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full bg-emerald-600 ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]">✓</span>
              <div class="text-xs font-bold text-stone-900 dark:text-stone-100">Order Placed & Confirmed</div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Received by mountain orchard team</div>
            </div>

            <!-- Step 2: Packed -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
                :class="isStepReached(selectedOrder.status, 'packed') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
                {{ isStepReached(selectedOrder.status, 'packed') ? '✓' : '•' }}
              </span>
              <div class="text-xs font-bold" :class="isStepReached(selectedOrder.status, 'packed') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
                Tree-Plucked & Mesh Box Packed
              </div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Individual shock-proof foam nets applied</div>
            </div>

            <!-- Step 3: Dispatched -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
                :class="isStepReached(selectedOrder.status, 'dispatched') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
                {{ isStepReached(selectedOrder.status, 'dispatched') ? '✓' : '•' }}
              </span>
              <div class="text-xs font-bold" :class="isStepReached(selectedOrder.status, 'dispatched') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
                Dispatched from Himachal Pradesh
              </div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">In transit via express temperature-safe vehicle</div>
            </div>

            <!-- Step 4: Delivered -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
                :class="isStepReached(selectedOrder.status, 'delivered') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
                {{ isStepReached(selectedOrder.status, 'delivered') ? '✓' : '•' }}
              </span>
              <div class="text-xs font-bold" :class="isStepReached(selectedOrder.status, 'delivered') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
                Delivered to Doorstep
              </div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Enjoy fresh, crunchy Himalayan sweetness!</div>
            </div>

          </div>
        </div>

        <!-- Orders Cards List -->
        <div v-else-if="myOrders.length > 0" class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
          <div 
            v-for="ord in myOrders" 
            :key="ord.id" 
            @click="selectedOrder = ord"
            class="p-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-stone-50/70 dark:bg-stone-850 hover:border-red-400 dark:hover:border-red-600 hover:shadow-xs transition-all cursor-pointer group"
          >
            <div class="flex items-center justify-between gap-2 mb-1.5">
              <span class="font-bold text-stone-900 dark:text-stone-100 text-xs sm:text-sm">
                #{{ ord.order_number }}
              </span>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
                :class="statusBadgeClass(ord.status)">
                {{ ord.status }}
              </span>
            </div>

            <p class="text-xs text-stone-600 dark:text-stone-300 line-clamp-1 mb-2">
              {{ (ord.items || []).map(i => i.product_name + ' (' + i.quantity + ')').join(', ') }}
            </p>

            <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400 pt-2 border-t border-stone-200 dark:border-stone-800">
              <span>Total: <strong class="text-stone-800 dark:text-stone-200 font-bold">₹{{ ord.total_amount }}</strong></span>
              <span class="text-red-600 dark:text-red-400 font-bold flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                View Timeline →
              </span>
            </div>
          </div>
        </div>

        <!-- Empty Orders State -->
        <div v-else class="text-center py-10 px-4 bg-stone-50 dark:bg-stone-850 rounded-2xl border border-stone-200 dark:border-stone-800 space-y-3">
          <div class="text-3xl">🍏</div>
          <h4 class="font-bold text-stone-800 dark:text-stone-200 text-sm">No Orders Placed Yet</h4>
          <p class="text-xs text-stone-500 dark:text-stone-400 max-w-sm mx-auto">
            You haven't placed an order using <strong>{{ currentUser.email }}</strong> yet.
          </p>
          <button 
            type="button" 
            @click="activeTab = 'search'" 
            class="mt-2 text-xs font-bold text-red-600 dark:text-red-400 hover:underline cursor-pointer"
          >
            Search by Phone Number or Order ID instead →
          </button>
        </div>

      </div>

      <!-- TAB 2: MANUAL SEARCH BY ORDER ID / PHONE -->
      <div v-if="!currentUser || activeTab === 'search'">
        <form @submit.prevent="trackOrder" class="flex gap-2 mb-6">
          <input 
            v-model="query" 
            type="text" 
            required 
            placeholder="e.g. 9820198201 or APL-2026-9041" 
            class="flex-1 px-4 py-3 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 focus:ring-2 focus:ring-red-100 dark:focus:ring-red-950 text-xs sm:text-sm outline-none"
          />
          <button 
            type="submit" 
            :disabled="isLoading"
            class="px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer disabled:bg-stone-300"
          >
            <span v-if="!isLoading">Track</span>
            <span v-else>Searching...</span>
          </button>
        </form>

        <!-- Error State -->
        <div v-if="notFound" class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 text-xs text-center">
          No active shipment found with this ID or Phone number. Please verify and try again, or WhatsApp the farmer directly.
        </div>

        <!-- Manual Search Result -->
        <div v-if="order" class="space-y-6 pt-2">
          <div class="p-4 rounded-2xl bg-stone-50 dark:bg-stone-800/80 border border-stone-200 dark:border-stone-700 text-xs space-y-1.5">
            <div class="flex items-center justify-between font-bold text-stone-900 dark:text-stone-100">
              <span>Order #{{ order.order_number }}</span>
              <span class="px-2.5 py-0.5 rounded-full text-[11px] uppercase font-bold"
                :class="statusBadgeClass(order.status)">
                Status: {{ order.status }}
              </span>
            </div>
            <div class="text-stone-500 dark:text-stone-400">Destination: {{ order.customer_name }}, {{ order.city }}, {{ order.state }}</div>
            <div class="text-stone-500 dark:text-stone-400">Total: ₹{{ order.total_amount }} ({{ order.payment_method ? order.payment_method.toUpperCase() : 'COD' }})</div>
            
            <div v-if="order.tracking_courier" class="mt-2 pt-2 border-t border-stone-200 dark:border-stone-700 font-medium text-stone-700 dark:text-stone-300 flex items-center justify-between">
              <span>Courier: {{ order.tracking_courier }}</span>
              <span class="font-mono bg-white dark:bg-stone-900 px-2 py-0.5 rounded border border-stone-300 dark:border-stone-600 text-stone-900 dark:text-stone-100">AWB: {{ order.tracking_number }}</span>
            </div>
          </div>

          <!-- Visual Timeline -->
          <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-stone-200 dark:before:bg-stone-800">
            
            <!-- Step 1: Placed -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full bg-emerald-600 ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]">✓</span>
              <div class="text-xs font-bold text-stone-900 dark:text-stone-100">Order Placed & Confirmed</div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Received by mountain orchard team</div>
            </div>

            <!-- Step 2: Packed -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
                :class="isStepReached(order.status, 'packed') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
                {{ isStepReached(order.status, 'packed') ? '✓' : '•' }}
              </span>
              <div class="text-xs font-bold" :class="isStepReached(order.status, 'packed') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
                Tree-Plucked & Mesh Box Packed
              </div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Individual shock-proof foam protection applied</div>
            </div>

            <!-- Step 3: Dispatched -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
                :class="isStepReached(order.status, 'dispatched') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
                {{ isStepReached(order.status, 'dispatched') ? '✓' : '•' }}
              </span>
              <div class="text-xs font-bold" :class="isStepReached(order.status, 'dispatched') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
                Dispatched from Himachal Pradesh
              </div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">In transit via express temperature-safe vehicle</div>
            </div>

            <!-- Step 4: Delivered -->
            <div class="relative">
              <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
                :class="isStepReached(order.status, 'delivered') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
                {{ isStepReached(order.status, 'delivered') ? '✓' : '•' }}
              </span>
              <div class="text-xs font-bold" :class="isStepReached(order.status, 'delivered') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
                Delivered to Doorstep
              </div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Enjoy fresh, crunchy Himalayan sweetness!</div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    initialQuery: {
      type: String,
      default: ''
    },
    currentUser: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      activeTab: 'my-orders',
      query: '',
      order: null, // manual search order
      selectedOrder: null, // order chosen from user's list
      myOrders: [],
      isLoading: false,
      isLoadingMyOrders: false,
      notFound: false
    };
  },
  watch: {
    isOpen(newVal) {
      if (newVal) {
        if (this.initialQuery) {
          this.activeTab = 'search';
          this.query = this.initialQuery;
          this.trackOrder();
        } else if (this.currentUser) {
          this.activeTab = 'my-orders';
          this.selectedOrder = null;
          this.fetchMyOrders();
        } else {
          this.activeTab = 'search';
        }
      } else {
        this.notFound = false;
      }
    },
    initialQuery(newVal) {
      if (newVal && this.isOpen) {
        this.activeTab = 'search';
        this.query = newVal;
        this.trackOrder();
      }
    },
    currentUser(newVal) {
      if (newVal && this.isOpen) {
        this.fetchMyOrders();
      }
    }
  },
  methods: {
    switchToTab(tab) {
      this.activeTab = tab;
      this.selectedOrder = null;
      if (tab === 'my-orders' && this.myOrders.length === 0) {
        this.fetchMyOrders();
      }
    },
    async fetchMyOrders() {
      if (!this.currentUser) return;
      this.isLoadingMyOrders = true;
      try {
        const res = await axios.get('/api/orders/my-orders');
        if (res.data.success && Array.isArray(res.data.data)) {
          this.myOrders = res.data.data;
          // If customer has only 1 order, open it directly so they see status immediately!
          if (this.myOrders.length === 1) {
            this.selectedOrder = this.myOrders[0];
          }
        }
      } catch (err) {
        console.error('Failed to load my orders', err);
      } finally {
        this.isLoadingMyOrders = false;
      }
    },
    async trackOrder() {
      if (!this.query) return;
      this.isLoading = true;
      this.notFound = false;
      this.order = null;

      try {
        const res = await axios.get('/api/orders/track', {
          params: { query: this.query }
        });
        if (res.data.success) {
          this.order = res.data.data;
        }
      } catch (err) {
        this.notFound = true;
      } finally {
        this.isLoading = false;
      }
    },
    isStepReached(orderStatus, step) {
      if (!orderStatus) return false;
      const sequence = ['pending', 'confirmed', 'packed', 'dispatched', 'delivered'];
      const currentIndex = sequence.indexOf(orderStatus);
      const targetIndex = sequence.indexOf(step);
      return currentIndex >= targetIndex;
    },
    statusBadgeClass(status) {
      switch (status) {
        case 'pending':
          return 'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300';
        case 'confirmed':
          return 'bg-blue-100 text-blue-800 dark:bg-blue-950/70 dark:text-blue-300';
        case 'packed':
          return 'bg-purple-100 text-purple-800 dark:bg-purple-950/70 dark:text-purple-300';
        case 'dispatched':
          return 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/70 dark:text-indigo-300';
        case 'delivered':
          return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300';
        case 'cancelled':
          return 'bg-red-100 text-red-800 dark:bg-red-950/70 dark:text-red-300';
        default:
          return 'bg-stone-100 text-stone-800 dark:bg-stone-800 dark:text-stone-300';
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr);
      return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
    }
  }
};
</script>
