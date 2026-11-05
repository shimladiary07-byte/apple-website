<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity" @click="$emit('close')"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
      <div class="w-screen max-w-md bg-white dark:bg-stone-900 shadow-2xl flex flex-col border-l border-stone-200 dark:border-stone-800">
        
        <!-- Header -->
        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center justify-between bg-stone-50/70 dark:bg-stone-900">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-950/70 flex items-center justify-center text-red-600 dark:text-red-400 font-bold">
              🍎
            </div>
            <div>
              <h3 class="font-bold text-stone-900 dark:text-stone-100 font-serif-title">Your Orchard Box Cart</h3>
              <p class="text-xs text-stone-500 dark:text-stone-400">{{ items.length }} variety boxes selected</p>
            </div>
          </div>

          <button @click="$emit('close')" class="p-2 rounded-xl text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 hover:bg-stone-200 dark:hover:bg-stone-800 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Free Delivery Progress Bar -->
        <div v-if="enableFreeShipping && freeShippingMin > 0" class="px-5 py-3 bg-amber-50/80 dark:bg-amber-950/40 border-b border-amber-200 dark:border-amber-800 text-xs text-amber-900 dark:text-amber-300">
          <div class="flex items-center justify-between mb-1 font-semibold">
            <span>{{ freeShippingMessage }}</span>
            <span>₹{{ subtotal }} / ₹{{ freeShippingMin }}</span>
          </div>
          <div class="w-full bg-amber-200 dark:bg-amber-900/60 rounded-full h-1.5 overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-amber-600 h-1.5 rounded-full transition-all duration-500" :style="{ width: freeShippingProgress + '%' }"></div>
          </div>
        </div>
        <div v-else class="px-5 py-2.5 bg-stone-100 dark:bg-stone-850 border-b border-stone-200 dark:border-stone-800 text-xs text-stone-600 dark:text-stone-400 flex items-center justify-between font-semibold">
          <span>📦 Direct Orchard Delivery nationwide</span>
          <span>From ₹{{ shippingRate }}</span>
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4">
          <div v-if="items.length === 0" class="h-full flex flex-col items-center justify-center text-center py-12 text-stone-400 dark:text-stone-500 space-y-4">
            <div class="w-20 h-20 rounded-3xl bg-stone-100 dark:bg-stone-800 flex items-center justify-center text-4xl">
              🍏
            </div>
            <div>
              <h4 class="font-bold text-stone-700 dark:text-stone-200 text-base">Your cart is empty</h4>
              <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Add crisp mountain apple boxes directly plucked from the tree.</p>
            </div>
            <button @click="$emit('close')" class="px-5 py-2.5 rounded-xl bg-stone-900 hover:bg-stone-800 dark:bg-stone-800 dark:hover:bg-stone-700 text-white text-xs font-semibold shadow-xs cursor-pointer">
              Explore Apple Varieties
            </button>
          </div>

          <div 
            v-for="(item, index) in items" 
            :key="index" 
            class="flex gap-3 p-3.5 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-800/80 shadow-2xs hover:border-stone-300 dark:hover:border-stone-700 transition-all"
          >
            <img 
              :src="item.product.image_url" 
              :alt="item.product.name" 
              class="w-18 h-18 rounded-xl object-cover bg-stone-100 dark:bg-stone-700 shrink-0"
            />

            <div class="flex-1 flex flex-col justify-between">
              <div>
                <div class="flex items-start justify-between gap-1">
                  <h4 class="text-sm font-bold text-stone-900 dark:text-stone-100">{{ item.product.name }}</h4>
                  <button @click="$emit('remove-item', index)" class="text-stone-400 hover:text-red-600 transition-colors p-0.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
                <div class="text-[11px] text-stone-500 dark:text-stone-400 font-medium">{{ item.box.size }}</div>
              </div>

              <div class="flex items-center justify-between mt-2 pt-2 border-t border-stone-100 dark:border-stone-700">
                <span class="text-xs font-bold text-stone-900 dark:text-stone-100">₹{{ item.price * item.quantity }}</span>

                <!-- Quantity selector -->
                <div class="flex items-center gap-2 border border-stone-200 dark:border-stone-700 rounded-lg p-0.5 bg-stone-50 dark:bg-stone-900">
                  <button @click="$emit('update-qty', { index, qty: item.quantity - 1 })" class="w-6 h-6 rounded flex items-center justify-center text-stone-600 dark:text-stone-300 hover:bg-white dark:hover:bg-stone-800 font-bold text-xs cursor-pointer">
                    -
                  </button>
                  <span class="text-xs font-bold w-4 text-center text-stone-900 dark:text-stone-100">{{ item.quantity }}</span>
                  <button @click="$emit('update-qty', { index, qty: item.quantity + 1 })" class="w-6 h-6 rounded flex items-center justify-center text-stone-600 dark:text-stone-300 hover:bg-white dark:hover:bg-stone-800 font-bold text-xs cursor-pointer">
                    +
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer / Checkout Trigger -->
        <div v-if="items.length > 0" class="p-5 border-t border-stone-200 dark:border-stone-800 bg-stone-50/70 dark:bg-stone-900/90 space-y-3">
          <div class="space-y-1.5 text-xs text-stone-600 dark:text-stone-300">
            <div class="flex justify-between">
              <span>Boxes Subtotal:</span>
              <span class="font-bold text-stone-900 dark:text-stone-100">₹{{ subtotal }}</span>
            </div>
            <div class="flex justify-between">
              <span>Shipping Fee:</span>
              <span v-if="shippingFee === 0" class="text-emerald-700 dark:text-emerald-400 font-bold">FREE (Himalaya Express)</span>
              <span v-else class="font-bold text-stone-900 dark:text-stone-100">₹{{ shippingFee }}</span>
            </div>
            <div class="flex justify-between text-sm font-extrabold text-stone-900 dark:text-stone-100 pt-1 border-t border-stone-200 dark:border-stone-800">
              <span>Total Payable:</span>
              <span class="text-lg text-red-600 dark:text-red-400">₹{{ totalAmount }}</span>
            </div>
          </div>

          <button 
            @click="$emit('proceed-checkout')" 
            class="w-full py-3.5 px-4 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>Proceed to Quick Checkout</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>

          <p class="text-[11px] text-center text-stone-500">
            🔒 Safe & Direct Farmer Delivery • Cash on Delivery / UPI
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    items: {
      type: Array,
      default: () => []
    },
    freeShippingMin: {
      type: Number,
      default: 1500
    },
    enableFreeShipping: {
      type: Boolean,
      default: true
    },
    shippingRate: {
      type: Number,
      default: 120
    }
  },
  computed: {
    subtotal() {
      return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },
    shippingFee() {
      if (this.subtotal === 0) return 0;
      if (this.enableFreeShipping && this.freeShippingMin > 0 && this.subtotal >= this.freeShippingMin) return 0;
      return this.shippingRate;
    },
    totalAmount() {
      return this.subtotal + this.shippingFee;
    },
    freeShippingProgress() {
      if (this.subtotal >= this.freeShippingMin) return 100;
      return Math.min(100, (this.subtotal / this.freeShippingMin) * 100);
    },
    freeShippingMessage() {
      if (this.subtotal >= this.freeShippingMin) {
        return "🎉 You unlocked FREE Nationwide Delivery!";
      }
      const diff = this.freeShippingMin - this.subtotal;
      return `Add ₹${diff} more to get FREE Delivery!`;
    }
  }
}
</script>

