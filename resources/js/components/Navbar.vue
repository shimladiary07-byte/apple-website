<template>
  <header class="sticky top-0 z-40 bg-white/95 dark:bg-stone-900/95 backdrop-blur-md border-b border-stone-200 dark:border-stone-800 transition-all shadow-xs">
    <!-- Top Announcement Bar -->
    <div v-if="settings.banner_badge" class="bg-gradient-to-r from-red-700 via-rose-700 to-amber-700 text-white text-xs font-medium py-1.5 px-4 text-center tracking-wide flex items-center justify-center gap-2">
      <span>{{ settings.banner_badge }}</span>
      <span class="hidden md:inline text-rose-200">|</span>
      <span class="hidden md:inline text-rose-100">WhatsApp: {{ settings.phone }}</span>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-2.5 sm:py-3 min-h-[4.25rem] gap-2 sm:gap-4">

        <!-- Brand / Logo -->
        <div class="flex items-center gap-2.5 sm:gap-3 cursor-pointer shrink-0" @click="$emit('navigate', 'home')">
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-br from-red-500 to-rose-700 flex items-center justify-center text-white shadow-md shadow-red-200 dark:shadow-none text-xl sm:text-2xl transform hover:scale-105 transition-transform shrink-0">
            🍎
          </div>
          <div class="flex flex-col">
            <div class="flex items-center gap-1.5 sm:gap-2">
              <span class="text-base sm:text-lg lg:text-xl font-bold tracking-tight text-stone-900 dark:text-stone-100 font-serif-title whitespace-nowrap">{{ settings.farm_name || 'Kinnaur Orchards' }}</span>
              <span class="hidden md:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 whitespace-nowrap">
                Direct Farmer
              </span>
            </div>
            <p class="text-[11px] text-stone-500 dark:text-stone-400 hidden xl:block font-medium whitespace-nowrap">6,350 ft Altitude • 100% Tree-Ripened</p>
          </div>
        </div>

        <!-- Center Nav Links (Visible on XL screens) -->
        <nav class="hidden xl:flex items-center gap-4 2xl:gap-6 text-xs 2xl:text-sm font-semibold text-stone-600 dark:text-stone-300">
          <button @click="$emit('navigate', 'home')" class="hover:text-red-600 dark:hover:text-red-400 transition-colors cursor-pointer whitespace-nowrap">
            Fresh Apples
          </button>
          <button @click="$emit('scroll-to', 'other-products')" class="hover:text-amber-700 dark:hover:text-amber-400 transition-colors cursor-pointer flex items-center gap-1 whitespace-nowrap">
            <span>🍯 Other Products</span>
          </button>
          <button @click="$emit('scroll-to', 'story')" class="hover:text-red-600 dark:hover:text-red-400 transition-colors cursor-pointer whitespace-nowrap">
            Meet the Farmer
          </button>
          <button @click="$emit('scroll-to', 'packaging')" class="hover:text-red-600 dark:hover:text-red-400 transition-colors cursor-pointer whitespace-nowrap">
            Safe Packaging
          </button>
          <button @click="$emit('open-track')" class="hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
            <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            Track Order
          </button>
          <button @click="$emit('open-bulk')" class="text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 hover:bg-amber-100 dark:hover:bg-amber-900/60 px-2.5 py-1 rounded-full text-xs font-semibold border border-amber-200 dark:border-amber-800 transition-colors cursor-pointer whitespace-nowrap">
            Bulk / Wholesale
          </button>
        </nav>

        <!-- Right Actions: Theme Toggle + Cart + Admin Button + Mobile Toggle -->
        <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
          <!-- Dark / Light Mode Switcher -->
          <button
            @click="$emit('toggle-dark-mode')"
            type="button"
            class="p-2 sm:p-2.5 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-100/80 dark:bg-stone-800 text-stone-700 dark:text-stone-200 hover:bg-stone-200 dark:hover:bg-stone-700 transition-all flex items-center justify-center cursor-pointer shadow-2xs"
            :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
            aria-label="Toggle dark mode"
          >
            <!-- Sun Icon (when dark) -->
            <svg v-if="isDarkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <!-- Moon Icon (when light) -->
            <svg v-else class="w-4 h-4 text-stone-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
          </button>

          <!-- WhatsApp Quick Contact (Shown on 2xl) -->
          <a :href="'https://wa.me/' + cleanPhone(settings.whatsapp) + '?text=' + encodeURIComponent('Namaste ' + (settings.farmer_name ? settings.farmer_name.split(' ')[0] + ' ji' : 'ji') + '! Mujhe fresh pahadi seb ke baare me puchna hai.')" target="_blank" class="hidden 2xl:flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 transition-all shadow-2xs whitespace-nowrap">
            <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>WhatsApp</span>
          </a>

          <!-- Cart Button -->
          <button @click="$emit('open-cart')" class="relative flex items-center gap-1.5 sm:gap-2 px-3 sm:px-3.5 py-2 sm:py-2.5 rounded-xl bg-stone-900 hover:bg-stone-800 dark:bg-stone-800 dark:hover:bg-stone-700 text-white font-medium text-xs sm:text-sm transition-all shadow-sm cursor-pointer whitespace-nowrap">
            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span class="hidden sm:inline">Box Cart</span>
            <span v-if="cartCount > 0" class="w-5 h-5 rounded-full bg-red-600 text-white text-[11px] font-bold flex items-center justify-center animate-pulse">
              {{ cartCount }}
            </span>
          </button>

          <!-- Login / Register Portal Button -->
          <div v-if="currentUser && !isAdmin" class="flex items-center gap-1.5 sm:gap-2">
            <span class="text-xs font-medium text-stone-700 dark:text-stone-300 max-w-[110px] truncate hidden sm:inline" :title="currentUser.name">
              👤 {{ currentUser.name }}
            </span>
            <button
              @click="$emit('logout')"
              class="px-2.5 py-1.5 rounded-xl border border-stone-200 dark:border-stone-700 text-stone-600 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 text-xs font-semibold cursor-pointer whitespace-nowrap"
              title="Logout"
            >
              Logout
            </button>
          </div>
          <button
            v-else-if="!currentUser"
            @click="$emit('open-admin-login')"
            class="flex items-center gap-1.5 px-2.5 sm:px-3 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800 text-xs font-semibold transition-all shadow-2xs cursor-pointer whitespace-nowrap"
          >
            <svg class="w-4 h-4 text-stone-500 dark:text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="hidden md:inline">Login / Register</span>
          </button>
          <button v-else @click="$emit('navigate', 'admin')" class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold flex items-center gap-1.5 shadow-sm cursor-pointer whitespace-nowrap">
            <span>Admin</span>
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          </button>

          <!-- Mobile / Tablet Hamburger Toggle Button -->
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            type="button"
            class="xl:hidden p-2 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-100/80 dark:bg-stone-800 text-stone-700 dark:text-stone-200 hover:bg-stone-200 dark:hover:bg-stone-700 transition-all cursor-pointer"
            aria-label="Toggle Menu"
          >
            <svg v-if="!isMobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

      </div>
    </div>

    <!-- Mobile & Tablet Dropdown Drawer -->
    <div v-if="isMobileMenuOpen" class="xl:hidden border-t border-stone-200 dark:border-stone-800 bg-white/95 dark:bg-stone-900/95 backdrop-blur-md px-4 py-4 space-y-3 shadow-lg">
      <div class="grid grid-cols-2 gap-2 text-xs font-bold text-stone-700 dark:text-stone-200">
        <button @click="$emit('navigate', 'home'); isMobileMenuOpen = false" class="text-left p-2.5 rounded-xl bg-stone-50 dark:bg-stone-800 hover:bg-stone-100 dark:hover:bg-stone-700 flex items-center gap-2 cursor-pointer">
          <span>🍎 Fresh Apples</span>
        </button>
        <button @click="$emit('scroll-to', 'other-products'); isMobileMenuOpen = false" class="text-left p-2.5 rounded-xl bg-stone-50 dark:bg-stone-800 hover:bg-stone-100 dark:hover:bg-stone-700 flex items-center gap-2 cursor-pointer">
          <span>🍯 Other Products</span>
        </button>
        <button @click="$emit('scroll-to', 'story'); isMobileMenuOpen = false" class="text-left p-2.5 rounded-xl bg-stone-50 dark:bg-stone-800 hover:bg-stone-100 dark:hover:bg-stone-700 flex items-center gap-2 cursor-pointer">
          <span>👨‍🌾 Meet Farmer</span>
        </button>
        <button @click="$emit('scroll-to', 'packaging'); isMobileMenuOpen = false" class="text-left p-2.5 rounded-xl bg-stone-50 dark:bg-stone-800 hover:bg-stone-100 dark:hover:bg-stone-700 flex items-center gap-2 cursor-pointer">
          <span>📦 Foam Packaging</span>
        </button>
        <button @click="$emit('open-track'); isMobileMenuOpen = false" class="text-left p-2.5 rounded-xl bg-stone-50 dark:bg-stone-800 hover:bg-stone-100 dark:hover:bg-stone-700 flex items-center gap-2 cursor-pointer">
          <span>🔍 Track Order</span>
        </button>
        <button @click="$emit('open-bulk'); isMobileMenuOpen = false" class="text-left p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/60 flex items-center gap-2 cursor-pointer">
          <span>🚛 Bulk Crates</span>
        </button>
      </div>

      <div class="pt-2 border-t border-stone-100 dark:border-stone-800 flex items-center justify-between text-xs">
        <a :href="'https://wa.me/' + cleanPhone(settings.whatsapp)" target="_blank" class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-semibold border border-emerald-200 dark:border-emerald-800">
          <span>💬 WhatsApp Farmer</span>
        </a>
        <button @click="$emit('open-admin-login'); isMobileMenuOpen = false" class="px-3 py-2 rounded-xl border border-stone-200 dark:border-stone-700 font-semibold text-stone-700 dark:text-stone-300">
          <span>🔐 Login / Register</span>
        </button>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  props: {
    settings: {
      type: Object,
      default: () => ({})
    },
    cartCount: {
      type: Number,
      default: 0
    },
    isAdmin: {
      type: Boolean,
      default: false
    },
    currentUser: {
      type: Object,
      default: null
    },
    isDarkMode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isMobileMenuOpen: false
    }
  },
  methods: {
    cleanPhone(val) {
      return (val || '').replace(/[^0-9]/g, '');
    }
  }
}
</script>
