<template>
  <div v-if="order" class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden my-8 p-6 sm:p-8 text-center animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Top Success Icon -->
      <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-3xl mx-auto mb-4 ring-8 ring-emerald-50 dark:ring-emerald-900/20">
        🍎
      </div>

      <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 mb-2">
        ORDER RECEIVED & LOGGED
      </span>

      <h3 class="text-2xl font-extrabold text-stone-900 dark:text-stone-100 font-serif-title">
        Thank You for Your Order!
      </h3>

      <p class="text-xs sm:text-sm text-stone-600 dark:text-stone-400 mt-1 max-w-md mx-auto">
        Your fresh Himalayan apple boxes are queued for plucking and packing in Kinnaur.
      </p>

      <!-- Order ID Badge -->
      <div class="bg-stone-50 dark:bg-stone-800/80 border border-stone-200 dark:border-stone-700/80 rounded-2xl p-4 my-6 text-left space-y-2.5">
        <div class="flex items-center justify-between border-b border-stone-200 dark:border-stone-700 pb-2">
          <span class="text-xs text-stone-500 dark:text-stone-400 font-medium">Order Number:</span>
          <div class="flex items-center gap-2">
            <span class="text-sm font-extrabold text-red-600 dark:text-red-400 font-mono tracking-wider">{{ order.order_number }}</span>
            <button 
              @click="copyOrderNumber" 
              type="button"
              class="px-2 py-0.5 text-[10px] rounded-md bg-stone-200 dark:bg-stone-700 hover:bg-stone-300 dark:hover:bg-stone-600 text-stone-700 dark:text-stone-300 transition-colors font-medium cursor-pointer"
            >
              {{ copied ? '✓ Copied' : 'Copy' }}
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between text-xs">
          <span class="text-stone-500 dark:text-stone-400">Customer:</span>
          <span class="font-bold text-stone-800 dark:text-stone-200">{{ order.customer_name }} ({{ order.customer_phone }})</span>
        </div>

        <div class="flex items-center justify-between text-xs">
          <span class="text-stone-500 dark:text-stone-400">Delivery To:</span>
          <span class="font-bold text-stone-800 dark:text-stone-200 truncate max-w-[200px]">{{ order.city }}, {{ order.state }}</span>
        </div>

        <div class="flex items-center justify-between text-xs">
          <span class="text-stone-500 dark:text-stone-400">Shipping:</span>
          <span class="font-bold text-stone-800 dark:text-stone-200">{{ order.shipping_method || 'Standard Mountain Express' }}</span>
        </div>

        <div class="flex items-center justify-between text-xs">
          <span class="text-stone-500 dark:text-stone-400">Payment:</span>
          <span class="font-bold text-stone-800 dark:text-stone-200 uppercase">
            {{ order.payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'UPI Payment' }}
          </span>
        </div>

        <div class="flex items-center justify-between text-xs border-t border-stone-200 dark:border-stone-700 pt-2 font-bold">
          <span class="text-stone-700 dark:text-stone-300">Total Payable:</span>
          <span class="text-base font-extrabold text-stone-900 dark:text-stone-100">₹{{ order.total_amount }}</span>
        </div>
      </div>

      <!-- Quick UPI Scan Reminder if UPI Order -->
      <div v-if="order.payment_method === 'upi_whatsapp'" class="mb-5 p-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl text-left flex items-center gap-3">
        <div class="bg-white p-1 rounded-lg border border-emerald-300 shrink-0">
          <img 
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent('upi://pay?pa=kinnaurfarm@okhdfcbank&pn=Kinnaur Orchards&am=' + order.total_amount + '&cu=INR')" 
            alt="UPI QR" 
            class="w-16 h-16 object-contain"
          />
        </div>
        <div class="text-xs space-y-0.5">
          <div class="font-bold text-emerald-950 dark:text-emerald-200">UPI Payment QR Ready</div>
          <p class="text-[11px] text-emerald-800 dark:text-emerald-300">
            Scan to pay <strong>₹{{ order.total_amount }}</strong>, then click below to send your confirmation on WhatsApp.
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-2.5">
        <!-- Big WhatsApp Button -->
        <a 
          :href="whatsappUrl" 
          target="_blank" 
          class="w-full py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
          <span>Share Order Details on WhatsApp</span>
        </a>

        <!-- Track This Order Button -->
        <button 
          @click="$emit('track-order', order.order_number)" 
          type="button"
          class="w-full py-3 px-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
          <span>Track Order Status Live</span>
        </button>

        <!-- Close / Continue -->
        <button 
          @click="$emit('close')" 
          class="w-full py-2.5 px-4 rounded-2xl bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 font-semibold text-xs sm:text-sm transition-colors cursor-pointer"
        >
          Back to Orchard Store
        </button>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    order: {
      type: Object,
      default: null
    },
    whatsappUrl: {
      type: String,
      default: '#'
    }
  },
  data() {
    return {
      copied: false
    }
  },
  methods: {
    copyOrderNumber() {
      if (!this.order?.order_number) return;
      navigator.clipboard.writeText(this.order.order_number);
      this.copied = true;
      setTimeout(() => {
        this.copied = false;
      }, 2000);
    }
  }
}
</script>

