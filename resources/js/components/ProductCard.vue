<template>
  <div class="bg-white dark:bg-stone-900 rounded-3xl border border-stone-200/90 dark:border-stone-800 overflow-hidden shadow-xs hover:shadow-xl hover:border-red-200 dark:hover:border-red-900/60 transition-all duration-300 flex flex-col group">

    <!-- Product Image & Badges -->
    <div class="relative h-56 sm:h-64 overflow-hidden bg-stone-100 dark:bg-stone-800 cursor-pointer" @click="$emit('view-detail', product)">
      <img
        :src="product.image_url || 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?q=80&w=800&auto=format&fit=crop'"
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500"
      />

      <!-- Top badges -->
      <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start">
        <span v-if="product.grade" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 dark:bg-stone-900/90 text-stone-800 dark:text-stone-200 shadow-xs backdrop-blur-xs border border-stone-100 dark:border-stone-700">
          ⭐ {{ product.grade.split(' ')[0] }} Grade
        </span>
        <span v-if="product.is_featured" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500 text-white shadow-xs">
          🔥 Most Loved
        </span>
      </div>

      <div class="absolute top-3 right-3">
        <span v-if="product.stock_status === 'in_stock'" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-600 text-white shadow-xs">
          {{ product.product_type === 'other' ? 'In Stock' : 'Plucking Now' }}
        </span>
        <span v-else-if="product.stock_status === 'pre_order'" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-600 text-white shadow-xs">
          Pre-Order
        </span>
        <span v-else class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-stone-700 text-white shadow-xs">
          Out of Stock
        </span>
      </div>

      <div class="absolute bottom-3 left-3 right-3 bg-stone-900/80 backdrop-blur-xs text-white text-[11px] px-3 py-1.5 rounded-xl flex items-center justify-between border border-stone-800">
        <span class="truncate">📍 {{ product.origin }}</span>
        <span v-if="product.price_per_kg > 0" class="shrink-0 text-red-300 font-semibold">₹{{ product.price_per_kg }}/kg</span>
      </div>
    </div>

    <!-- Product Body -->
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
      <div>
        <div class="flex items-start justify-between gap-2">
          <h3
            @click="$emit('view-detail', product)"
            class="text-lg font-bold text-stone-900 dark:text-stone-100 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors cursor-pointer font-serif-title"
          >
            {{ product.name }}
          </h3>
          <span class="text-xs px-2 py-0.5 rounded-md bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 font-medium shrink-0">
            {{ product.category }}
          </span>
        </div>

        <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 line-clamp-2 leading-relaxed">
          {{ product.tagline || product.description }}
        </p>

        <!-- Taste Profile / Grade Profile -->
        <div v-if="!product.product_type || product.product_type === 'apple'" class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-stone-100 dark:border-stone-800 text-[11px]">
          <div class="flex items-center gap-1.5 text-stone-600 dark:text-stone-300">
            <span class="text-stone-400 font-medium">Crunch:</span>
            <span class="font-semibold text-stone-800 dark:text-stone-200">{{ product.crispness || 'High' }}</span>
          </div>
          <div class="flex items-center gap-1.5 text-stone-600 dark:text-stone-300">
            <span class="text-stone-400 font-medium">Sweetness:</span>
            <span class="font-semibold text-stone-800 dark:text-stone-200">{{ product.sweetness || 'Sweet' }}</span>
          </div>
        </div>
        <div v-else class="flex items-center justify-between gap-2 mt-3 pt-3 border-t border-stone-100 dark:border-stone-800 text-[11px] text-stone-600 dark:text-stone-300">
          <span class="text-stone-400 font-medium">Purity Promise:</span>
          <span class="font-semibold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
            100% Pure Natural
          </span>
        </div>

        <!-- Box / Packaging Selection -->
        <div class="mt-4">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-1.5">
            Select Pack / Size Option:
          </label>
          <div class="space-y-1.5">
            <div
              v-for="(box, idx) in availableBoxes"
              :key="idx"
              @click="selectedBoxIndex = idx"
              :class="[
                'p-2.5 rounded-xl border text-xs flex items-center justify-between cursor-pointer transition-all',
                selectedBoxIndex === idx
                  ? 'border-red-600 dark:border-red-500 bg-red-50/60 dark:bg-red-950/40 text-red-950 dark:text-red-200 font-semibold ring-1 ring-red-500 shadow-2xs'
                  : 'border-stone-200 dark:border-stone-700/80 hover:border-stone-300 dark:hover:border-stone-600 text-stone-700 dark:text-stone-300 bg-stone-50/50 dark:bg-stone-800/50'
              ]"
            >
              <div class="flex items-center gap-2">
                <span class="w-3.5 h-3.5 rounded-full border flex items-center justify-center shrink-0"
                  :class="selectedBoxIndex === idx ? 'border-red-600 bg-red-600' : 'border-stone-400 dark:border-stone-600'">
                  <span v-if="selectedBoxIndex === idx" class="w-1.5 h-1.5 rounded-full bg-white"></span>
                </span>
                <span>{{ box.size }}</span>
              </div>
              <span class="font-bold text-stone-900 dark:text-stone-100">₹{{ box.price }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Price & Actions -->
      <div class="pt-3 border-t border-stone-100 dark:border-stone-800 space-y-2.5">
        <div class="flex items-baseline justify-between">
          <div>
            <span class="text-xs text-stone-500 dark:text-stone-400">Selected Box:</span>
            <div class="text-2xl font-extrabold text-stone-900 dark:text-stone-100">
              ₹{{ currentPrice }}
            </div>
          </div>
          <div class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800">
            Tree-Plucked Fresh
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2">
          <!-- Add to Cart -->
          <button
            @click="addToCart"
            :disabled="product.stock_status === 'out_of_stock'"
            class="w-full py-2.5 px-3 rounded-xl bg-stone-900 hover:bg-stone-800 dark:bg-stone-800 dark:hover:bg-stone-700 disabled:bg-stone-300 dark:disabled:bg-stone-800 text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 cursor-pointer disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Add Box</span>
          </button>

          <!-- Direct 1-Click WhatsApp Order -->
          <a
            :href="whatsappDirectUrl"
            target="_blank"
            class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 cursor-pointer text-center"
          >
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>WhatsApp</span>
          </a>
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
      required: true
    },
    farmerPhone: {
      type: String,
      default: '919816054321'
    }
  },
  data() {
    return {
      selectedBoxIndex: 0
    }
  },
  computed: {
    availableBoxes() {
      if (this.product.box_options && Array.isArray(this.product.box_options) && this.product.box_options.length > 0) {
        return this.product.box_options;
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
      return this.currentBox ? this.currentBox.price : (this.product.price_per_kg * 5);
    },
    whatsappDirectUrl() {
      const clean = (this.farmerPhone || '').replace(/[^0-9]/g, '');
      const msg = `Namaste! Mujhe *${this.product.name}* ka *${this.currentBox.size}* (₹${this.currentPrice}) order karna hai. Please availability confirm karein! 🍎`;
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
    }
  }
}
</script>
