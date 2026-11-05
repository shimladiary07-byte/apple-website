<template>
  <section id="other-products" class="w-full py-16 bg-gradient-to-b from-stone-50 via-amber-50/20 to-stone-50 dark:from-stone-950 dark:via-stone-900/40 dark:to-stone-950 border-t border-stone-200/80 dark:border-stone-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/70 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs font-semibold mb-2">
            <span>🍯 HIMALAYAN FARM DELICACIES</span>
          </div>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 dark:text-stone-100 font-serif-title tracking-tight">
            Other Orchard & Natural Products
          </h2>
          <p class="text-xs sm:text-sm text-stone-600 dark:text-stone-300 mt-1 max-w-xl leading-relaxed">
            Pure raw wild honey, traditional oak-fermented apple cider vinegar, sun-dried mountain snacks & thin-shell walnuts directly from our Kinnaur estate.
          </p>
        </div>

        <!-- Category Filter & Search -->
        <div class="flex flex-col sm:flex-row gap-3">
          <!-- Search -->
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search honey, vinegar, walnuts..."
              class="w-full sm:w-60 pl-9 pr-4 py-2.5 rounded-2xl bg-white dark:bg-stone-900 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 dark:focus:ring-red-950 transition-all shadow-2xs"
            />
            <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>

          <!-- Category Selector -->
          <select
            v-model="selectedCategory"
            class="px-4 py-2.5 rounded-2xl bg-white dark:bg-stone-900 border border-stone-300 dark:border-stone-700 text-xs font-bold text-stone-700 dark:text-stone-200 outline-none focus:border-red-500 shadow-2xs cursor-pointer"
          >
            <option value="all">All Categories ({{ products.length }})</option>
            <option v-for="cat in uniqueCategories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>
      </div>

      <!-- Category Filter Pills (Desktop Horizontal Quick Filter) -->
      <div v-if="uniqueCategories.length > 1" class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 text-xs font-bold scrollbar-none">
        <button
          @click="selectedCategory = 'all'"
          :class="[
            'px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap',
            selectedCategory === 'all'
              ? 'bg-stone-900 dark:bg-stone-100 text-white dark:text-stone-900 shadow-xs'
              : 'bg-white dark:bg-stone-900 text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 border border-stone-200 dark:border-stone-800'
          ]"
        >
          All Items ({{ products.length }})
        </button>

        <button
          v-for="cat in uniqueCategories"
          :key="cat"
          @click="selectedCategory = cat"
          :class="[
            'px-4 py-2 rounded-xl transition-all cursor-pointer whitespace-nowrap',
            selectedCategory === cat
              ? 'bg-amber-600 text-white shadow-xs'
              : 'bg-white dark:bg-stone-900 text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 border border-stone-200 dark:border-stone-800'
          ]"
        >
          {{ cat }}
        </button>
      </div>

      <!-- Products Grid -->
      <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <ProductCard
          v-for="product in filteredProducts"
          :key="product.id"
          :product="product"
          :farmer-phone="settings.whatsapp"
          @view-detail="$emit('view-detail', product)"
          @add-to-cart="$emit('add-to-cart', $event)"
        />
      </div>

      <!-- Empty state if search doesn't match -->
      <div v-else class="text-center py-16 bg-white dark:bg-stone-900 rounded-3xl border border-stone-200 dark:border-stone-800 p-8 shadow-2xs">
        <div class="text-4xl mb-3">🍯</div>
        <h3 class="font-bold text-stone-800 dark:text-stone-100 text-lg">No products found in this category</h3>
        <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Try resetting the search keyword or filter.</p>
        <button @click="searchQuery = ''; selectedCategory = 'all'" class="mt-4 px-4 py-2 rounded-xl bg-stone-900 dark:bg-stone-800 text-white text-xs font-bold cursor-pointer">
          Reset Filter
        </button>
      </div>

    </div>
  </section>
</template>

<script>
import ProductCard from './ProductCard.vue';

export default {
  components: {
    ProductCard
  },
  props: {
    products: {
      type: Array,
      default: () => []
    },
    settings: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      searchQuery: '',
      selectedCategory: 'all'
    };
  },
  computed: {
    uniqueCategories() {
      const cats = new Set(this.products.map(p => p.category).filter(Boolean));
      return Array.from(cats);
    },
    filteredProducts() {
      return this.products.filter(p => {
        const matchesCat = this.selectedCategory === 'all' || p.category === this.selectedCategory;
        const q = this.searchQuery.toLowerCase().trim();
        const matchesSearch = !q ||
          p.name.toLowerCase().includes(q) ||
          (p.tagline && p.tagline.toLowerCase().includes(q)) ||
          (p.category && p.category.toLowerCase().includes(q)) ||
          (p.description && p.description.toLowerCase().includes(q));
        return matchesCat && matchesSearch;
      });
    }
  }
};
</script>
