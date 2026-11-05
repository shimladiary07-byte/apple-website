<template>
  <div class="relative overflow-hidden bg-gradient-to-b from-amber-50/60 via-stone-50 to-white dark:from-stone-900 dark:via-stone-950 dark:to-stone-900 pt-8 pb-14 border-b border-stone-200 dark:border-stone-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
        
        <!-- Left Hero Content -->
        <div class="lg:col-span-7 space-y-6">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-100/80 dark:bg-red-950/60 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-xs font-semibold tracking-wide shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
            <span>DIRECT FROM ORCHARD • NO MIDDLEMEN</span>
          </div>

          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-stone-900 dark:text-stone-100 tracking-tight leading-[1.15] font-serif-title">
            Pure Mountain Apples, <br class="hidden sm:inline" />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 via-rose-600 to-amber-600">
              Tree-Ripened & Wax Free
            </span>
          </h1>

          <p class="text-base sm:text-lg text-stone-600 dark:text-stone-300 max-w-2xl leading-relaxed">
            {{ settings.tagline || 'Freshly plucked, crisp & aromatic Himalayan apples directly from our mountain orchards to your doorstep in shock-proof foam boxes.' }}
          </p>

          <!-- Key Highlights / Guarantees -->
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
            <div class="p-3 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 shadow-2xs">
              <div class="text-2xl mb-1">🏔️</div>
              <div class="text-xs font-bold text-stone-900 dark:text-stone-100">8,500 ft Altitude</div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Pure glacial snow water</div>
            </div>

            <div class="p-3 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 shadow-2xs">
              <div class="text-2xl mb-1">🚫</div>
              <div class="text-xs font-bold text-stone-900 dark:text-stone-100">Zero Wax Coating</div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Natural peel, edible raw</div>
            </div>

            <div class="p-3 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 shadow-2xs col-span-2 sm:col-span-1">
              <div class="text-2xl mb-1">📦</div>
              <div class="text-xs font-bold text-stone-900 dark:text-stone-100">Foam Mesh Boxes</div>
              <div class="text-[11px] text-stone-500 dark:text-stone-400">Zero bruising in transit</div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap items-center gap-4 pt-3">
            <button @click="$emit('scroll-to', 'catalog')" class="px-6 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm sm:text-base shadow-lg shadow-red-200 dark:shadow-none hover:shadow-red-300 transition-all cursor-pointer flex items-center gap-2">
              <span>View Fresh Varieties</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <button @click="$emit('scroll-to', 'story')" class="px-6 py-3.5 rounded-2xl bg-white dark:bg-stone-800 hover:bg-stone-50 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-200 font-semibold text-sm sm:text-base border border-stone-300 dark:border-stone-700 shadow-2xs transition-all cursor-pointer flex items-center gap-2">
              <span>{{ settings.farmer_name ? settings.farmer_name + "'s Story" : "Farmer's Story" }}</span>
              <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
          </div>

          <div class="flex items-center gap-3 text-xs text-stone-500 dark:text-stone-400 pt-1 font-medium">
            <span class="flex items-center gap-1 text-emerald-700 dark:text-emerald-400">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              Free Shipping over ₹1,500
            </span>
            <span>•</span>
            <span class="flex items-center gap-1 text-emerald-700 dark:text-emerald-400">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              COD & UPI Supported
            </span>
          </div>

        </div>

        <!-- Right Hero Image Showcase -->
        <div class="lg:col-span-5 relative">
          <div class="relative mx-auto max-w-md lg:max-w-none rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-stone-800 bg-stone-100 dark:bg-stone-800">
            <img 
              :src="settings.banner_image || 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?q=80&w=1600&auto=format&fit=crop'" 
              alt="Himachal Mountain Apple Orchard" 
              class="w-full h-80 sm:h-96 object-cover hover:scale-105 transition-transform duration-700"
            />
            
            <!-- Floating Orchard Badge -->
            <div class="absolute bottom-4 left-4 right-4 bg-white/95 dark:bg-stone-900/95 backdrop-blur-md p-4 rounded-2xl border border-stone-200/80 dark:border-stone-800 shadow-lg">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-950/70 flex items-center justify-center text-red-600 dark:text-red-400 font-bold text-xl shrink-0">
                  🍎
                </div>
                <div>
                  <div class="text-xs font-bold text-stone-900 dark:text-stone-100">Current Harvest: 2026 Batch</div>
                  <div class="text-[11px] text-stone-500 dark:text-stone-400">Plucked fresh from Sangla & Kalpa slopes</div>
                </div>
                <div class="ml-auto text-right">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 dark:bg-green-950 text-green-800 dark:text-green-300">
                    IN SEASON
                  </span>
                </div>
              </div>
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
    settings: {
      type: Object,
      default: () => ({})
    }
  }
}
</script>

