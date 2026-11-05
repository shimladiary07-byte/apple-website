<template>
  <footer class="bg-stone-900 text-stone-300 pt-16 pb-12 border-t border-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-stone-800">
        
        <!-- Col 1 & 2: Brand & Farm bio -->
        <div class="lg:col-span-2 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-red-500 to-rose-700 flex items-center justify-center text-white text-xl shadow-md">
              🍎
            </div>
            <div>
              <span class="text-xl font-bold tracking-tight text-white font-serif-title">{{ settings.farm_name || 'Kinnaur Orchards' }}</span>
              <p class="text-xs text-amber-400 font-semibold">High Altitude Apple Farm • Kinnaur, H.P.</p>
            </div>
          </div>

          <p class="text-xs text-stone-400 leading-relaxed pr-6">
            {{ settings.quality_promise || 'No Artificial Wax, No Cold-Storage Stale Stock, 100% Tree-Ripened Natural Sweetness Guaranteed.' }}
          </p>

          <div class="flex items-center gap-2 pt-2">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-stone-800 text-stone-300 border border-stone-700">
              🏔️ 8,500 ft Elevation
            </span>
            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-stone-800 text-emerald-400 border border-stone-700">
              🌱 Natural Orcharding
            </span>
          </div>
        </div>

        <!-- Col 3: Quick Navigation -->
        <div class="space-y-3">
          <h4 class="text-xs font-bold uppercase tracking-wider text-white">Apple Store</h4>
          <ul class="space-y-2 text-xs">
            <li><button @click="$emit('scroll-to', 'catalog')" class="hover:text-red-400 transition-colors cursor-pointer">Royal Delicious</button></li>
            <li><button @click="$emit('scroll-to', 'catalog')" class="hover:text-red-400 transition-colors cursor-pointer">Golden Delicious</button></li>
            <li><button @click="$emit('scroll-to', 'catalog')" class="hover:text-red-400 transition-colors cursor-pointer">Mountain Red</button></li>
            <li><button @click="$emit('scroll-to', 'catalog')" class="hover:text-red-400 transition-colors cursor-pointer">Royal Gala</button></li>
            <li><button @click="$emit('scroll-to', 'catalog')" class="hover:text-red-400 transition-colors cursor-pointer">Granny Smith Green</button></li>
          </ul>
        </div>

        <!-- Col 4: Quick Links -->
        <div class="space-y-3">
          <h4 class="text-xs font-bold uppercase tracking-wider text-white">Information</h4>
          <ul class="space-y-2 text-xs">
            <li><button @click="$emit('scroll-to', 'story')" class="hover:text-red-400 transition-colors cursor-pointer">{{ settings.farmer_name ? settings.farmer_name + "'s Story" : "Farmer's Story" }}</button></li>
            <li><button @click="$emit('scroll-to', 'packaging')" class="hover:text-red-400 transition-colors cursor-pointer">Foam Box Packaging</button></li>
            <li><button @click="$emit('open-track')" class="hover:text-red-400 transition-colors cursor-pointer">Track My Order</button></li>
            <li><button @click="$emit('open-bulk')" class="hover:text-red-400 transition-colors cursor-pointer">Wholesale / Bulk Orders</button></li>
            <li>
              <button @click="$emit('open-admin-login')" class="text-stone-400 hover:text-amber-400 transition-colors flex items-center gap-1 cursor-pointer">
                <span>Login / Register Portal</span>
                <span class="text-[10px] text-stone-600">🔐</span>
              </button>
            </li>
          </ul>
        </div>

        <!-- Col 5: Orchard Address & WhatsApp -->
        <div class="space-y-3">
          <h4 class="text-xs font-bold uppercase tracking-wider text-white">Orchard Contact</h4>
          <div class="space-y-2 text-xs text-stone-400">
            <p class="leading-relaxed">
              📍 {{ settings.location || 'Kalpa Valley, Kinnaur, Himachal Pradesh - 172108' }}
            </p>
            <p>
              📞 <a :href="'tel:' + settings.phone" class="hover:text-white">{{ settings.phone }}</a>
            </p>
            <p>
              💬 <a :href="'https://wa.me/' + cleanPhone(settings.whatsapp)" target="_blank" class="text-emerald-400 hover:underline">WhatsApp Farmer</a>
            </p>
            <p>
              ✉️ <a :href="'mailto:' + settings.email" class="hover:text-white">{{ settings.email }}</a>
            </p>
          </div>
        </div>

      </div>

      <!-- Developer Promotion & Credit Card -->
      <div class="mt-10 p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-stone-950 via-stone-900 to-stone-950 border border-stone-800 shadow-xl flex flex-col lg:flex-row items-center justify-between gap-5">
        <div class="flex items-center gap-4 text-left w-full lg:w-auto">
          <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr from-amber-500 via-rose-600 to-red-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-red-950/50 shrink-0">
            👨‍💻
          </div>
          <div>
            <div class="flex flex-wrap items-center gap-2">
              <span class="text-base font-extrabold text-white tracking-wide">Shubham Thakur</span>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                Web Developer
              </span>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                Available for Projects
              </span>
            </div>
            <p class="text-xs text-stone-400 mt-1">
              Custom High-Converting Websites, E-Commerce Stores & Web Applications.
            </p>
            <div class="flex flex-wrap items-center gap-3 text-xs text-stone-300 mt-1.5 font-medium">
              <a href="mailto:shubhamthakurst07@gmail.com" class="text-amber-400 hover:text-amber-300 hover:underline flex items-center gap-1">
                <span>✉️ shubhamthakurst07@gmail.com</span>
              </a>
              <span class="text-stone-600">•</span>
              <a href="tel:8219795479" class="text-stone-300 hover:text-white flex items-center gap-1">
                <span>📞 +91 8219795479</span>
              </a>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-start lg:justify-end">
          <a
            href="tel:8219795479"
            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-200 hover:text-white text-xs font-bold border border-stone-700 transition-all shadow-sm whitespace-nowrap"
          >
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span>Call: 8219795479</span>
          </a>
          <a
            href="mailto:shubhamthakurst07@gmail.com?subject=Website%20Development%20Inquiry&body=Hi%20Shubham,%20I%20saw%20your%20work%20on%20Apple%20Farm%20Shop%20and%20want%20to%20discuss%20a%20project."
            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all shadow-md shadow-blue-950/40 whitespace-nowrap"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Email</span>
          </a>
          <a
            href="https://wa.me/918219795479?text=Namaste%20Shubham%20ji!%20Maine%20aapka%20Apple%20Farm%20Shop%20project%20dekha,%20mujhe%20website%20development%20ke%20baare%20me%20baat%20karni%20hai."
            target="_blank"
            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-md shadow-emerald-950/40 whitespace-nowrap"
          >
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>WhatsApp</span>
          </a>
        </div>
      </div>

      <!-- Copyright & Theme Toggle -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
        <div>
          © {{ new Date().getFullYear() }} {{ settings.farm_name || 'Kinnaur Orchards' }}. Developed by <strong class="text-stone-300">Shubham Thakur</strong> (8219795479 • shubhamthakurst07@gmail.com).
        </div>
        <div class="flex items-center gap-4">
          <button 
            @click="$emit('toggle-dark-mode')" 
            class="px-3 py-1.5 rounded-lg bg-stone-800 hover:bg-stone-700 text-stone-300 text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer"
          >
            <span>{{ isDarkMode ? '☀️ Light Mode' : '🌙 Dark Mode' }}</span>
          </button>
          <span>•</span>
          <span class="text-emerald-500 font-semibold">100% Tree-Ripened Guarantee</span>
        </div>
      </div>

    </div>
  </footer>
</template>

<script>
export default {
  props: {
    settings: {
      type: Object,
      default: () => ({})
    },
    isDarkMode: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    cleanPhone(val) {
      return (val || '').replace(/[^0-9]/g, '');
    }
  }
}
</script>

