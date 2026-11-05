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

      <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300 flex items-center justify-center font-bold text-lg">
          🔍
        </div>
        <div>
          <h3 class="text-xl font-bold text-stone-900 dark:text-stone-100 font-serif-title">Track Fresh Apple Shipment</h3>
          <p class="text-xs text-stone-500 dark:text-stone-400">Enter your 10-digit Mobile Number or Order ID</p>
        </div>
      </div>

      <!-- Search Box -->
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
        No active shipment found with this ID or Phone number. Please check and try again, or WhatsApp the farmer directly.
      </div>

      <!-- Order Tracking Result -->
      <div v-if="order" class="space-y-6 pt-2">
        <div class="p-4 rounded-2xl bg-stone-50 dark:bg-stone-800/80 border border-stone-200 dark:border-stone-700 text-xs space-y-1.5">
          <div class="flex items-center justify-between font-bold text-stone-900 dark:text-stone-100">
            <span>Order #{{ order.order_number }}</span>
            <span class="px-2.5 py-0.5 rounded-full text-[11px] uppercase font-bold"
              :class="{
                'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300': order.status === 'pending',
                'bg-blue-100 text-blue-800 dark:bg-blue-950/70 dark:text-blue-300': order.status === 'confirmed',
                'bg-purple-100 text-purple-800 dark:bg-purple-950/70 dark:text-purple-300': order.status === 'packed',
                'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/70 dark:text-indigo-300': order.status === 'dispatched',
                'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300': order.status === 'delivered',
                'bg-red-100 text-red-800 dark:bg-red-950/70 dark:text-red-300': order.status === 'cancelled',
              }">
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
              :class="isStepReached('packed') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
              {{ isStepReached('packed') ? '✓' : '•' }}
            </span>
            <div class="text-xs font-bold" :class="isStepReached('packed') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
              Tree-Plucked & Mesh Box Packed
            </div>
            <div class="text-[11px] text-stone-500 dark:text-stone-400">Individual shock-proof foam protection applied</div>
          </div>

          <!-- Step 3: Dispatched -->
          <div class="relative">
            <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
              :class="isStepReached('dispatched') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
              {{ isStepReached('dispatched') ? '✓' : '•' }}
            </span>
            <div class="text-xs font-bold" :class="isStepReached('dispatched') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
              Dispatched from Himachal Pradesh
            </div>
            <div class="text-[11px] text-stone-500 dark:text-stone-400">In transit via express temperature-safe vehicle</div>
          </div>

          <!-- Step 4: Delivered -->
          <div class="relative">
            <span class="absolute -left-6 top-0.5 w-4.5 h-4.5 rounded-full ring-4 ring-white dark:ring-stone-900 flex items-center justify-center text-white text-[10px]"
              :class="isStepReached('delivered') ? 'bg-emerald-600' : 'bg-stone-300 dark:bg-stone-700'">
              {{ isStepReached('delivered') ? '✓' : '•' }}
            </span>
            <div class="text-xs font-bold" :class="isStepReached('delivered') ? 'text-stone-900 dark:text-stone-100' : 'text-stone-400 dark:text-stone-500'">
              Delivered to Doorstep
            </div>
            <div class="text-[11px] text-stone-500 dark:text-stone-400">Enjoy fresh, crunchy Himalayan sweetness!</div>
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
    }
  },
  data() {
    return {
      query: '',
      order: null,
      isLoading: false,
      notFound: false
    }
  },
  watch: {
    isOpen(newVal) {
      if (newVal) {
        if (this.initialQuery) {
          this.query = this.initialQuery;
          this.trackOrder();
        }
      } else {
        this.notFound = false;
      }
    },
    initialQuery(newVal) {
      if (newVal && this.isOpen) {
        this.query = newVal;
        this.trackOrder();
      }
    }
  },
  methods: {
    async trackOrder() {
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
    isStepReached(step) {
      if (!this.order) return false;
      const sequence = ['pending', 'confirmed', 'packed', 'dispatched', 'delivered'];
      const currentIndex = sequence.indexOf(this.order.status);
      const targetIndex = sequence.indexOf(step);
      return currentIndex >= targetIndex;
    }
  }
}
</script>

