<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden my-8 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Close Button -->
      <button 
        @click="$emit('close')" 
        class="absolute top-4 right-4 p-2 text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-xl transition-colors cursor-pointer"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300 flex items-center justify-center font-bold text-lg">
          🚛
        </div>
        <div>
          <h3 class="text-xl font-bold text-stone-900 dark:text-stone-100 font-serif-title">Bulk & Wholesale Crates Inquiry</h3>
          <p class="text-xs text-stone-500 dark:text-stone-400">For retail stores, hotels, corporate gifts & events (100kg+)</p>
        </div>
      </div>

      <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs text-center space-y-2">
        <div class="font-bold text-sm">Thank You! Your message was sent.</div>
        <p>{{ settings.farmer_name || 'Our orchard team' }} or our logistics team will get in touch with you shortly.</p>
        <button @click="$emit('close')" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs mt-2 cursor-pointer">Close</button>
      </div>

      <form v-else @submit.prevent="submitInquiry" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-stone-700 mb-1">Your Name / Business Name *</label>
          <input 
            v-model="form.name" 
            type="text" 
            required 
            placeholder="e.g. Ramesh Sethi / Green Grocers" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-red-500 text-xs outline-none"
          />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-stone-700 mb-1">Mobile / WhatsApp *</label>
            <input 
              v-model="form.phone" 
              type="tel" 
              required 
              placeholder="10-digit number" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-red-500 text-xs outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-stone-700 mb-1">Email</label>
            <input 
              v-model="form.email" 
              type="email" 
              placeholder="Business email" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-red-500 text-xs outline-none"
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-stone-700 mb-1">Inquiry Type</label>
          <select v-model="form.inquiry_type" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-red-500 text-xs outline-none bg-white">
            <option value="bulk_order">Bulk Crates Procurement (100 kg to 2000 kg)</option>
            <option value="corporate_gifting">Corporate / Diwali Festival Gift Packs</option>
            <option value="orchard_visit">Orchard Tour & Plucking Visit</option>
            <option value="general">Other Inquiry</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-stone-700 mb-1">Your Requirements / Message *</label>
          <textarea 
            v-model="form.message" 
            rows="3" 
            required 
            placeholder="Please mention destination city, preferred varieties (Royal, Golden, etc.), and expected quantities..." 
            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-red-500 text-xs outline-none"
          ></textarea>
        </div>

        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="w-full py-3.5 px-4 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer disabled:bg-stone-300"
        >
          <span v-if="!isSubmitting">Send Inquiry to Farmer</span>
          <span v-else>Sending...</span>
        </button>
      </form>

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
    settings: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      isSubmitting: false,
      successMsg: false,
      form: {
        name: '',
        phone: '',
        email: '',
        inquiry_type: 'bulk_order',
        message: ''
      }
    }
  },
  methods: {
    async submitInquiry() {
      this.isSubmitting = true;
      try {
        const res = await axios.post('/api/inquiries', this.form);
        if (res.data.success) {
          this.successMsg = true;
          this.form = { name: '', phone: '', email: '', inquiry_type: 'bulk_order', message: '' };
        }
      } catch (err) {
        alert('Failed to send inquiry. Please try again.');
      } finally {
        this.isSubmitting = false;
      }
    }
  }
}
</script>

