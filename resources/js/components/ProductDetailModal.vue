<template>
  <div v-if="product" class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-3xl bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-100 dark:border-stone-800 overflow-hidden my-8 animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Close Button -->
      <button 
        @click="$emit('close')" 
        class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/90 dark:bg-stone-800/90 hover:bg-stone-100 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 flex items-center justify-center shadow-md transition-all cursor-pointer"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <div class="grid grid-cols-1 md:grid-cols-2">
        
        <!-- Left: Image & Badge -->
        <div class="relative bg-stone-100 dark:bg-stone-800 h-72 md:h-full min-h-[320px]">
          <img 
            :src="product.image_url || 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?q=80&w=800&auto=format&fit=crop'" 
            :alt="product.name" 
            class="w-full h-full object-cover"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-stone-900/80 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-600 w-fit mb-1">
              {{ product.category }}
            </span>
            <div class="text-sm font-medium text-stone-200">📍 {{ product.origin }}</div>
            <div class="text-xs text-stone-300">Harvest: {{ product.harvest_season }}</div>
          </div>
        </div>

        <!-- Right: Details & Purchase Options -->
        <div class="p-6 sm:p-8 flex flex-col justify-between space-y-6">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                {{ product.grade || 'AAA Grade' }}
              </span>
              <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                100% Wax-Free
              </span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-stone-900 dark:text-stone-100 font-serif-title leading-tight">
              {{ product.name }}
            </h2>

            <p class="text-xs sm:text-sm text-stone-600 dark:text-stone-300 mt-2 leading-relaxed">
              {{ product.description || product.tagline }}
            </p>

            <!-- Details Profile -->
            <div v-if="product.product_type === 'other'" class="bg-amber-50/70 dark:bg-amber-950/40 rounded-2xl p-4 mt-4 border border-amber-200/80 dark:border-amber-800/60 space-y-2">
              <div class="text-xs font-bold text-amber-900 dark:text-amber-300 uppercase tracking-wider">Farm Fresh Delicacy</div>
              <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                  <span class="text-amber-700 dark:text-amber-400 block text-[11px]">Category:</span>
                  <span class="font-bold text-amber-950 dark:text-amber-200">{{ product.category || 'Farm Product' }}</span>
                </div>
                <div>
                  <span class="text-amber-700 dark:text-amber-400 block text-[11px]">Origin:</span>
                  <span class="font-bold text-amber-950 dark:text-amber-200">{{ product.origin || 'Kinnaur Farm' }}</span>
                </div>
              </div>
            </div>
            <div v-else class="bg-stone-50 dark:bg-stone-800/60 rounded-2xl p-4 mt-4 border border-stone-200/80 dark:border-stone-700 space-y-2">
              <div class="text-xs font-bold text-stone-800 dark:text-stone-200 uppercase tracking-wider">Taste & Texture Profile</div>
              <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                  <span class="text-stone-500 dark:text-stone-400 block text-[11px]">Crispness:</span>
                  <span class="font-bold text-stone-900 dark:text-stone-100">{{ product.crispness || 'Very High' }}</span>
                </div>
                <div>
                  <span class="text-stone-500 dark:text-stone-400 block text-[11px]">Sweetness:</span>
                  <span class="font-bold text-stone-900 dark:text-stone-100">{{ product.sweetness || 'Sweet & Juicy' }}</span>
                </div>
              </div>
            </div>

            <!-- Box Sizes -->
            <div class="mt-4">
              <label class="block text-xs font-bold uppercase text-stone-500 dark:text-stone-400 mb-2">{{ product.product_type === 'other' ? 'Choose Pack / Size:' : 'Choose Box Size:' }}</label>
              <div class="space-y-2">
                <div 
                  v-for="(box, idx) in availableBoxes" 
                  :key="idx" 
                  @click="selectedBoxIndex = idx"
                  :class="[
                    'p-3 rounded-xl border text-xs sm:text-sm flex items-center justify-between cursor-pointer transition-all',
                    selectedBoxIndex === idx 
                      ? 'border-red-600 dark:border-red-500 bg-red-50/60 dark:bg-red-950/40 text-red-950 dark:text-red-200 font-bold ring-2 ring-red-500/20 shadow-xs' 
                      : 'border-stone-200 dark:border-stone-700 hover:border-stone-300 dark:hover:border-stone-600 text-stone-700 dark:text-stone-300 bg-white dark:bg-stone-800'
                  ]"
                >
                  <div class="flex items-center gap-2.5">
                    <span class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                      :class="selectedBoxIndex === idx ? 'border-red-600 bg-red-600' : 'border-stone-400 dark:border-stone-600'">
                      <span v-if="selectedBoxIndex === idx" class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    </span>
                    <span>{{ box.size }}</span>
                  </div>
                  <span class="font-extrabold text-stone-900 dark:text-stone-100">₹{{ box.price }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Actions -->
          <div class="pt-4 border-t border-stone-200 dark:border-stone-800 space-y-3">
            <div class="flex items-baseline justify-between">
              <div>
                <span class="text-xs text-stone-500 dark:text-stone-400">{{ product.product_type === 'other' ? 'Price:' : 'Box Total:' }}</span>
                <div class="text-3xl font-extrabold text-stone-900 dark:text-stone-100">₹{{ currentPrice }}</div>
              </div>
              <div class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Padded Shock-proof Packing
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <button 
                @click="addToCart" 
                class="w-full py-3 px-4 rounded-2xl bg-stone-900 hover:bg-stone-800 dark:bg-stone-800 dark:hover:bg-stone-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer"
              >
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Add to Cart</span>
              </button>

              <a 
                :href="whatsappDirectUrl" 
                target="_blank"
                class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer text-center"
              >
                <span>Order on WhatsApp</span>
              </a>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    product: {
      type: Object,
      default: null
    },
    farmerPhone: {
      type: String,
      default: '919816054321'
    },
    farmerName: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      selectedBoxIndex: 0
    }
  },
  computed: {
    availableBoxes() {
      if (!this.product) return [];
      if (this.product.box_options && Array.isArray(this.product.box_options) && this.product.box_options.length > 0) {
        return this.product.box_options;
      }
      if (this.product.product_type === 'other') {
        return [
          { size: 'Standard Pack', price: this.product.price_per_kg }
        ];
      }
      return [
        { size: '5 Kg Box (~20-25 Apples)', price: this.product.price_per_kg * 5 },
        { size: '10 Kg Family Box (~45 Apples)', price: this.product.price_per_kg * 10 - 100 }
      ];
    },
    currentBox() {
      return this.availableBoxes[this.selectedBoxIndex] || this.availableBoxes[0];
    },
    currentPrice() {
      return this.currentBox ? this.currentBox.price : 0;
    },
    whatsappDirectUrl() {
      if (!this.product || !this.currentBox) return '#';
      const clean = (this.farmerPhone || '').replace(/[^0-9]/g, '');
      const firstName = this.farmerName ? this.farmerName.split(' ')[0] : 'Farmer';
      const msg = `Namaste ${firstName} ji! Mujhe *${this.product.name}* ka *${this.currentBox.size}* (₹${this.currentPrice}) order karna hai. 🍎`;
      return `https://wa.me/${clean}?text=${encodeURIComponent(msg)}`;
    }
  },
  methods: {
    addToCart() {
      this.$emit('add-to-cart', {
        product: this.product,
        box: this.currentBox,
        price: this.currentPrice,
        quantity: 1
      });
      this.$emit('close');
    }
  }
}
</script>

