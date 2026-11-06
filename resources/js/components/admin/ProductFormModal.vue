<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden my-8 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Close Button -->
      <button 
        @click="$emit('close')" 
        class="absolute top-4 right-4 p-2 text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-xl transition-colors cursor-pointer"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <!-- Modal Header -->
      <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-2xl bg-red-100 dark:bg-red-950/70 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-lg">
          {{ form.product_type === 'other' ? '🍯' : '🍎' }}
        </div>
        <div>
          <h3 class="text-xl font-bold text-stone-900 dark:text-stone-100 font-serif-title">
            {{ isEditing ? 'Edit Product' : 'Add New Product' }}
          </h3>
          <p class="text-xs text-stone-500 dark:text-stone-400">Products and custom categories added here will appear directly on the storefront</p>
        </div>
      </div>

      <div v-if="errorMessage" class="mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-950/60 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-xs">
        {{ errorMessage }}
      </div>

      <form @submit.prevent="saveProduct" class="space-y-4 max-h-[72vh] overflow-y-auto pr-1">
        
        <!-- 1. Product Type Selector (Apple vs Other Product) -->
        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1.5">Product Type *</label>
          <div class="grid grid-cols-2 gap-3">
            <div 
              @click="setProductType('apple')" 
              :class="[
                'p-3 rounded-2xl border text-xs cursor-pointer transition-all flex items-center gap-2.5',
                form.product_type === 'apple' 
                  ? 'border-red-600 bg-red-50/70 dark:bg-red-950/50 font-bold text-red-950 dark:text-red-300 ring-2 ring-red-500/20 shadow-xs' 
                  : 'border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800'
              ]"
            >
              <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                :class="form.product_type === 'apple' ? 'border-red-600 bg-red-600' : 'border-stone-400 dark:border-stone-500'">
                <span v-if="form.product_type === 'apple'" class="w-1.5 h-1.5 rounded-full bg-white"></span>
              </div>
              <div>
                <div>🍎 Fresh Apple Variety</div>
                <div class="text-[10px] text-stone-500 dark:text-stone-400 font-normal">Displayed in Apple Varieties Catalog</div>
              </div>
            </div>

            <div 
              @click="setProductType('other')" 
              :class="[
                'p-3 rounded-2xl border text-xs cursor-pointer transition-all flex items-center gap-2.5',
                form.product_type === 'other' 
                  ? 'border-amber-600 bg-amber-50/70 dark:bg-amber-950/50 font-bold text-amber-950 dark:text-amber-300 ring-2 ring-amber-500/20 shadow-xs' 
                  : 'border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800'
              ]"
            >
              <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                :class="form.product_type === 'other' ? 'border-amber-600 bg-amber-600' : 'border-stone-400 dark:border-stone-500'">
                <span v-if="form.product_type === 'other'" class="w-1.5 h-1.5 rounded-full bg-white"></span>
              </div>
              <div>
                <div>🍯 Other Farm Product</div>
                <div class="text-[10px] text-stone-500 dark:text-stone-400 font-normal">Honey, Vinegar, Walnuts, Jams, Snacks</div>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. Name & Category -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">
              {{ form.product_type === 'other' ? 'Product Name *' : 'Apple Variety Name *' }}
            </label>
            <input 
              v-model="form.name" 
              type="text" 
              required 
              :placeholder="form.product_type === 'other' ? 'e.g. Pure Wild Forest Honey' : 'e.g. Kinnaur Royal Delicious'" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">
              Category (Choose or Type Custom) *
            </label>
            <input 
              v-model="form.category" 
              type="text" 
              required 
              :placeholder="form.product_type === 'other' ? 'e.g. Honey & Preserves' : 'e.g. Premium Dessert Apple'" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs outline-none font-medium"
            />
            
            <!-- Quick Category Pill Suggestions -->
            <div class="flex flex-wrap gap-1 mt-1.5">
              <button 
                v-for="cat in suggestedCategories" 
                :key="cat"
                type="button"
                @click="form.category = cat"
                class="px-2 py-0.5 rounded-md bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 text-[10px] font-semibold transition-colors cursor-pointer"
                :class="{ 'bg-red-100 dark:bg-red-950/80 text-red-800 dark:text-red-300 font-bold': form.category === cat }"
              >
                {{ cat }}
              </button>
            </div>
          </div>
        </div>

        <!-- Tagline -->
        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Short Tagline / Hook</label>
          <input 
            v-model="form.tagline" 
            type="text" 
            :placeholder="form.product_type === 'other' ? 'e.g. Raw, Unprocessed Multi-Flora Wild Forest Honey' : 'e.g. High Elevation Crunch & Natural Mountain Sweetness'" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs outline-none"
          />
        </div>

        <!-- Description -->
        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Full Description</label>
          <textarea 
            v-model="form.description" 
            rows="3" 
            :placeholder="form.product_type === 'other' ? 'Describe the preparation method, organic purity, packaging, benefits...' : 'Describe the aroma, peel characteristics, mountain altitude...'" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs outline-none"
          ></textarea>
        </div>

        <!-- Origin, Harvest Season, Grade -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Origin / Farm Location</label>
            <input 
              v-model="form.origin" 
              type="text" 
              placeholder="e.g. Kalpa, Kinnaur (8,500 ft)" 
              class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Harvest / Batch</label>
            <input 
              v-model="form.harvest_season" 
              type="text" 
              placeholder="e.g. Autumn Harvest" 
              class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Quality Grade</label>
            <input 
              v-model="form.grade" 
              type="text" 
              placeholder="e.g. 100% Raw Pure / Export Grade" 
              class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none"
            />
          </div>
        </div>

        <!-- Price per unit / kg & metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">
              {{ form.product_type === 'other' ? 'Base Price (₹)' : 'Price / Kg (₹) *' }}
            </label>
            <input 
              v-model.number="form.price_per_kg" 
              type="number" 
              step="1" 
              required 
              placeholder="190" 
              class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none font-bold"
            />
          </div>

          <div v-if="form.product_type === 'apple'">
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Crispness Level</label>
            <input 
              v-model="form.crispness" 
              type="text" 
              placeholder="Extremely Crunchy (5/5)" 
              class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none"
            />
          </div>

          <div v-if="form.product_type === 'apple'">
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Sweetness Level</label>
            <input 
              v-model="form.sweetness" 
              type="text" 
              placeholder="Sweet & Juicy (4.8/5)" 
              class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 text-xs outline-none"
            />
          </div>
        </div>

        <!-- 3. PHOTO UPLOAD & URL COMBO (Selected Photo or URL) -->
        <div class="p-4 rounded-2xl bg-stone-50 dark:bg-stone-850 border border-stone-200 dark:border-stone-800 space-y-3">
          <label class="block text-xs font-bold text-stone-800 dark:text-stone-200">
            Product Photo (Choose File from Computer OR Enter URL)
          </label>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
            <!-- Choose File from Computer -->
            <div>
              <span class="block text-[11px] font-bold text-stone-600 dark:text-stone-400 mb-1">📁 Upload Image File:</span>
              <input 
                type="file" 
                @change="handleImageFileChange" 
                accept="image/*" 
                class="w-full text-xs text-stone-600 dark:text-stone-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stone-900 dark:file:bg-stone-700 file:text-white hover:file:bg-stone-800 cursor-pointer"
              />
            </div>

            <!-- OR Enter URL -->
            <div>
              <span class="block text-[11px] font-bold text-stone-600 dark:text-stone-400 mb-1">🔗 OR Enter Image URL:</span>
              <input 
                v-model="form.image_url" 
                type="url" 
                placeholder="https://images.unsplash.com/..." 
                class="w-full px-3 py-1.5 rounded-xl border border-stone-300 dark:border-stone-700 text-xs outline-none bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500"
              />
            </div>
          </div>

          <!-- Live Image Preview -->
          <div v-if="localImagePreview || form.image_url" class="flex items-center gap-3 pt-2 border-t border-stone-200 dark:border-stone-800">
            <div class="w-16 h-16 rounded-xl border border-stone-300 dark:border-stone-700 bg-white dark:bg-stone-800 overflow-hidden shrink-0 shadow-2xs">
              <img :src="localImagePreview || form.image_url" alt="Preview" class="w-full h-full object-cover" />
            </div>
            <div class="text-xs text-stone-500 dark:text-stone-400">
              <div class="font-bold text-stone-800 dark:text-stone-200">Photo Preview</div>
              <div class="text-[10px] text-stone-400 dark:text-stone-500 truncate max-w-xs">
                {{ localImagePreview ? 'Selected from local computer' : form.image_url }}
              </div>
            </div>
          </div>
        </div>

        <!-- 4. Packaging / Size Options Configuration -->
        <div class="p-4 rounded-2xl bg-stone-50 dark:bg-stone-850 border border-stone-200 dark:border-stone-800 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-stone-800 dark:text-stone-200">
              {{ form.product_type === 'other' ? 'Pack Sizes & Pricing Options' : 'Box Sizes & Packaging Pricing' }}
            </span>
            <button 
              type="button" 
              @click="addBoxOption" 
              class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 flex items-center gap-1 cursor-pointer"
            >
              + Add Size Option
            </button>
          </div>

          <div v-for="(box, idx) in form.box_options" :key="idx" class="flex items-center gap-2">
            <input 
              v-model="box.size" 
              type="text" 
              :placeholder="form.product_type === 'other' ? 'e.g. 500g Glass Jar' : 'e.g. 5 Kg Box (~22 Apples)'" 
              class="flex-1 px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-xs bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500"
            />
            <div class="flex items-center gap-1">
              <span class="text-xs text-stone-500 dark:text-stone-400">₹</span>
              <input 
                v-model.number="box.price" 
                type="number" 
                placeholder="Price" 
                class="w-24 px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-xs bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 font-bold"
              />
            </div>
            <button 
              type="button" 
              @click="removeBoxOption(idx)" 
              class="p-2 text-stone-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg cursor-pointer"
            >
              ✕
            </button>
          </div>
        </div>

        <!-- Stock Status & Featured Switch -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
          <div>
            <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Stock Availability</label>
            <select v-model="form.stock_status" class="w-full px-3 py-2 rounded-xl border border-stone-300 dark:border-stone-700 text-xs outline-none bg-white dark:bg-stone-800 text-stone-900 dark:text-stone-100 cursor-pointer font-medium">
              <option value="in_stock">In Stock / Available</option>
              <option value="pre_order">Pre-Order</option>
              <option value="out_of_stock">Out of Stock</option>
            </select>
          </div>

          <div class="flex items-center gap-2 pt-5">
            <input id="is_featured" type="checkbox" v-model="form.is_featured" class="w-4 h-4 rounded text-red-600 cursor-pointer"/>
            <label for="is_featured" class="text-xs font-bold text-stone-700 dark:text-stone-300 cursor-pointer">Featured on Storefront</label>
          </div>

          <div class="flex items-center gap-2 pt-5">
            <input id="is_active" type="checkbox" v-model="form.is_active" class="w-4 h-4 rounded text-red-600 cursor-pointer"/>
            <label for="is_active" class="text-xs font-bold text-stone-700 dark:text-stone-300 cursor-pointer">Active (Visible)</label>
          </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200 dark:border-stone-800">
          <button 
            type="button" 
            @click="$emit('close')" 
            class="px-4 py-2.5 rounded-xl border border-stone-300 dark:border-stone-700 text-xs font-bold text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-stone-800 cursor-pointer"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            :disabled="isSubmitting"
            class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md cursor-pointer disabled:bg-stone-300"
          >
            <span v-if="!isSubmitting">{{ isEditing ? 'Update Product' : 'Save Product' }}</span>
            <span v-else>Saving...</span>
          </button>
        </div>

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
    productToEdit: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      isSubmitting: false,
      errorMessage: '',
      imageFile: null,
      localImagePreview: null,
      form: {
        name: '',
        tagline: '',
        description: '',
        product_type: 'apple',
        category: 'Premium Dessert Apple',
        origin: 'Kalpa, Kinnaur (8,500 ft elevation)',
        harvest_season: 'August to November',
        grade: 'AAA Super Export Grade',
        crispness: 'Extremely Crunchy (5/5)',
        sweetness: 'Naturally Sweet & Aromatic (4.8/5)',
        price_per_kg: 190,
        box_options: [
          { size: '5 Kg Box (~22-26 Apples)', price: 950, in_stock: true },
          { size: '10 Kg Family Box (~45 Apples)', price: 1800, in_stock: true },
        ],
        image_url: 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?q=80&w=800&auto=format&fit=crop',
        stock_status: 'in_stock',
        is_featured: true,
        is_active: true
      }
    };
  },
  computed: {
    isEditing() {
      return !!this.productToEdit && !!this.productToEdit.id;
    },
    suggestedCategories() {
      if (this.form.product_type === 'other') {
        return [
          'Honey & Preserves',
          'Juices & Vinegars',
          'Dry Fruits & Snacks',
          'Nuts & Dry Fruits',
          'Homemade Jams',
          'Herbal & Organic'
        ];
      }
      return [
        'Premium Dessert Apple',
        'Sweet & Mild',
        'Traditional Heirloom',
        'High Altitude Crunch',
        'Crisp & Tangy'
      ];
    }
  },
  watch: {
    productToEdit: {
      immediate: true,
      handler(val) {
        this.imageFile = null;
        this.localImagePreview = null;
        if (val && val.id) {
          this.form = {
            ...val,
            product_type: val.product_type || 'apple',
            box_options: Array.isArray(val.box_options) ? [...val.box_options] : [
              { size: '5 Kg Box', price: val.price_per_kg * 5, in_stock: true }
            ]
          };
        } else {
          this.resetForm();
        }
      }
    }
  },
  methods: {
    setProductType(type) {
      this.form.product_type = type;
      if (type === 'other' && (!this.form.category || this.form.category.includes('Apple'))) {
        this.form.category = 'Honey & Preserves';
        if (!this.isEditing) {
          this.form.box_options = [
            { size: '500g Jar', price: 450, in_stock: true },
            { size: '1 Kg Jar', price: 850, in_stock: true }
          ];
        }
      } else if (type === 'apple' && (!this.form.category || this.form.category.includes('Honey') || this.form.category.includes('Vinegar'))) {
        this.form.category = 'Premium Dessert Apple';
        if (!this.isEditing) {
          this.form.box_options = [
            { size: '5 Kg Box (~22-26 Apples)', price: 950, in_stock: true },
            { size: '10 Kg Family Box (~45 Apples)', price: 1800, in_stock: true }
          ];
        }
      }
    },
    handleImageFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.imageFile = file;
        const reader = new FileReader();
        reader.onload = (evt) => {
          this.localImagePreview = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    resetForm() {
      this.imageFile = null;
      this.localImagePreview = null;
      this.form = {
        name: '',
        tagline: '',
        description: '',
        product_type: 'apple',
        category: 'Premium Dessert Apple',
        origin: 'Kalpa, Kinnaur (8,500 ft elevation)',
        harvest_season: 'August to November',
        grade: 'AAA Super Export Grade',
        crispness: 'Extremely Crunchy (5/5)',
        sweetness: 'Naturally Sweet & Aromatic (4.8/5)',
        price_per_kg: 190,
        box_options: [
          { size: '5 Kg Box (~22-26 Apples)', price: 950, in_stock: true },
          { size: '10 Kg Family Box (~45 Apples)', price: 1800, in_stock: true },
        ],
        image_url: 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?q=80&w=800&auto=format&fit=crop',
        stock_status: 'in_stock',
        is_featured: true,
        is_active: true
      };
    },
    addBoxOption() {
      const defaultName = this.form.product_type === 'other' ? 'New Pack / Size' : '15 Kg Master Crate';
      const defaultPrice = this.form.product_type === 'other' ? 500 : 2700;
      this.form.box_options.push({
        size: defaultName,
        price: defaultPrice,
        in_stock: true
      });
    },
    removeBoxOption(idx) {
      if (this.form.box_options.length <= 1) {
        alert('You must have at least one pack size option.');
        return;
      }
      this.form.box_options.splice(idx, 1);
    },
    async saveProduct() {
      this.isSubmitting = true;
      this.errorMessage = '';

      try {
        const formData = new FormData();
        Object.keys(this.form).forEach(key => {
          let val = this.form[key];
          if (typeof val === 'boolean') {
            formData.append(key, val ? '1' : '0');
          } else if (typeof val === 'object' && val !== null) {
            formData.append(key, JSON.stringify(val));
          } else {
            formData.append(key, val ?? '');
          }
        });

        if (this.imageFile) {
          formData.append('image', this.imageFile);
        }

        const url = this.isEditing 
          ? `/api/admin/products/${this.productToEdit.id}` 
          : '/api/admin/products';

        const res = await axios.post(url, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        if (res.data.success) {
          this.$emit('saved', res.data.data);
          this.$emit('close');
        }
      } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
          this.errorMessage = err.response.data.message;
        } else {
          this.errorMessage = 'Failed to save product. Please verify all fields.';
        }
      } finally {
        this.isSubmitting = false;
      }
    }
  }
};
</script>
