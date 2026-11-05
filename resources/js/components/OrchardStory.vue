<template>
  <section id="story" class="py-16 bg-stone-50 dark:bg-stone-950 border-t border-stone-200 dark:border-stone-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Meet The Farmer Card -->
      <div class="bg-white dark:bg-stone-900 rounded-3xl border border-stone-200 dark:border-stone-800 overflow-hidden shadow-xs p-8 sm:p-12 mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
          
          <div class="lg:col-span-5">
            <div class="relative rounded-3xl overflow-hidden shadow-lg border-4 border-amber-50 dark:border-stone-800">
              <img 
                :src="settings.farmer_photo || 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop'" 
                :alt="settings.farmer_name || 'Sunil Chauhan'" 
                class="w-full h-88 sm:h-96 object-cover"
              />
              <div class="absolute bottom-3 left-3 right-3 bg-stone-900/80 backdrop-blur-md text-white p-3.5 rounded-2xl border border-stone-800">
                <div class="font-bold text-sm">{{ settings.farmer_name || 'Sunil Chauhan' }}</div>
                <div class="text-[11px] text-amber-300 font-medium">{{ settings.farmer_title || '3rd Generation Apple Grower' }}</div>
                <div class="text-[10px] text-stone-300">Kalpa Valley, Kinnaur, Himachal Pradesh</div>
              </div>
            </div>
          </div>

          <div class="lg:col-span-7 space-y-5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/70 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs font-semibold">
              <span>TRADITIONAL HIMALAYAN ORCHARDING</span>
            </div>

            <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 dark:text-stone-100 font-serif-title leading-tight">
              "We grow apples with nature, <br/>not chemicals or synthetic wax."
            </h2>

            <p class="text-stone-600 dark:text-stone-300 text-sm sm:text-base leading-relaxed">
              {{ settings.farmer_story }}
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3">
              <div class="border-l-2 border-red-500 pl-3">
                <div class="text-lg font-bold text-stone-900 dark:text-stone-100 font-serif-title">45+ Years</div>
                <div class="text-xs text-stone-500 dark:text-stone-400">Family Orcharding</div>
              </div>
              <div class="border-l-2 border-red-500 pl-3">
                <div class="text-lg font-bold text-stone-900 dark:text-stone-100 font-serif-title">8,500 Feet</div>
                <div class="text-xs text-stone-500 dark:text-stone-400">Glacial Elevation</div>
              </div>
              <div class="border-l-2 border-red-500 pl-3 col-span-2 sm:col-span-1">
                <div class="text-lg font-bold text-stone-900 dark:text-stone-100 font-serif-title">0% Synthetic Wax</div>
                <div class="text-xs text-stone-500 dark:text-stone-400">100% Edible Natural Peel</div>
              </div>
            </div>

            <!-- Farmer Contact / WhatsApp CTA -->
            <div class="pt-4 flex flex-wrap items-center gap-3">
              <a 
                :href="'https://wa.me/' + cleanPhone(settings.whatsapp)" 
                target="_blank" 
                class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-sm transition-all"
              >
                <span>Chat with {{ settings.farmer_name ? settings.farmer_name.split(' ')[0] : 'Farmer' }} on WhatsApp</span>
              </a>

              <a 
                :href="'tel:' + settings.phone" 
                class="px-5 py-3 rounded-xl border border-stone-300 dark:border-stone-700 hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-700 dark:text-stone-200 font-semibold text-xs sm:text-sm transition-all"
              >
                Call: {{ settings.phone }}
              </a>
            </div>
          </div>

        </div>
      </div>

      <!-- Comparison: Supermarket Apples vs Kinnaur Orchard Apples -->
      <div id="packaging" class="mb-12">
        <div class="text-center max-w-2xl mx-auto mb-10">
          <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-widest">WHY BUY DIRECT FROM FARM?</span>
          <h3 class="text-2xl sm:text-3xl font-extrabold text-stone-900 dark:text-stone-100 font-serif-title mt-1">
            Supermarket Stale Cold-Storage vs. Fresh Plucked
          </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
          
          <!-- Supermarket Apples -->
          <div class="p-6 rounded-3xl bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-100 dark:border-stone-800">
              <span class="font-bold text-stone-700 dark:text-stone-200">Supermarket / Mandi Apples</span>
              <span class="text-xs font-semibold px-2 py-0.5 rounded bg-red-100 dark:bg-red-950/80 text-red-800 dark:text-red-300">Old Stock</span>
            </div>
            <ul class="space-y-2.5 text-xs text-stone-600 dark:text-stone-400">
              <li class="flex items-start gap-2">
                <span class="text-red-500 font-bold shrink-0">✕</span>
                <span>Coated with synthetic petroleum shellac wax for fake shine</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-red-500 font-bold shrink-0">✕</span>
                <span>Stored in industrial CA cold rooms for 6 to 10 months, losing moisture</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-red-500 font-bold shrink-0">✕</span>
                <span>Porous, floury (pithi) texture with bland diminished sugar aroma</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-red-500 font-bold shrink-0">✕</span>
                <span>Passed through 4-5 middlemen, inflating prices by 60%</span>
              </li>
            </ul>
          </div>

          <!-- Kinnaur Direct Orchard -->
          <div class="p-6 rounded-3xl bg-gradient-to-br from-red-50 to-amber-50/50 dark:from-red-950/30 dark:to-stone-900 border-2 border-red-200 dark:border-red-900/60 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-red-200 dark:border-red-900/60">
              <span class="font-bold text-red-950 dark:text-red-200 font-serif-title">Kinnaur Mountain Direct</span>
              <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300">Fresh Harvest 2026</span>
            </div>
            <ul class="space-y-2.5 text-xs text-stone-800 dark:text-stone-300">
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold shrink-0">✓</span>
                <span class="font-semibold">Zero artificial waxes: 100% natural mountain bloom, safe to eat with skin</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold shrink-0">✓</span>
                <span>Plucked off the branch upon order, shipped within 24 hours</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold shrink-0">✓</span>
                <span>Explosive crisp crunch and high natural juice sugar content</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold shrink-0">✓</span>
                <span>Packed in individual foam nets inside heavy 5-ply cartons</span>
              </li>
            </ul>
          </div>

        </div>
      </div>

    </div>
  </section>
</template>

<script>
export default {
  props: {
    settings: {
      type: Object,
      default: () => ({})
    }
  },
  methods: {
    cleanPhone(val) {
      return (val || '').replace(/[^0-9]/g, '');
    }
  }
}
</script>

