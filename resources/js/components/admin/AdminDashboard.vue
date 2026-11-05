<template>
  <div class="min-h-screen bg-stone-100 dark:bg-stone-950 text-stone-900 dark:text-stone-100 pb-16 transition-colors">
    
    <!-- Top Header -->
    <header class="bg-white dark:bg-stone-900 border-b border-stone-200 dark:border-stone-800 sticky top-0 z-30 shadow-2xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
        
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-amber-600 text-white flex items-center justify-center font-bold text-xl shadow-xs">
            🍎
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-lg font-bold text-stone-900 dark:text-stone-100 font-serif-title">{{ settings.farm_name || 'Kinnaur Orchards' }}</h1>
              <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/70 text-amber-900 dark:text-amber-300 text-[10px] font-bold uppercase tracking-wider">
                Farmer Admin
              </span>
            </div>
            <p class="text-xs text-stone-500 dark:text-stone-400">Welcome, {{ user?.name || settings.farmer_name || 'Farmer Owner' }}</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <!-- Theme Toggle Button -->
          <button
            @click="$emit('toggle-dark-mode')"
            type="button"
            class="p-2 sm:px-3 sm:py-2 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-700 text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer"
            :title="isDarkMode ? 'Light Mode' : 'Dark Mode'"
          >
            <span v-if="isDarkMode">☀️</span>
            <span v-else>🌙</span>
            <span class="hidden md:inline">{{ isDarkMode ? 'Light' : 'Dark' }}</span>
          </button>

          <button 
            @click="$emit('switch-to-shop')" 
            class="px-3.5 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-50 dark:hover:bg-stone-800 text-xs font-semibold flex items-center gap-1.5 transition-colors cursor-pointer"
          >
            <svg class="w-4 h-4 text-stone-500 dark:text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            <span>View Live Website</span>
          </button>

          <button 
            @click="logout" 
            class="px-3.5 py-2 rounded-xl bg-stone-900 hover:bg-stone-800 dark:bg-stone-800 dark:hover:bg-stone-700 text-white text-xs font-semibold transition-all cursor-pointer"
          >
            Log Out
          </button>
        </div>

      </div>

      <!-- Navigation Tabs -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex space-x-6 overflow-x-auto text-xs font-bold border-t border-stone-100 dark:border-stone-800">
        <button 
          @click="activeTab = 'overview'" 
          :class="[
            'py-3 border-b-2 transition-all cursor-pointer whitespace-nowrap',
            activeTab === 'overview' ? 'border-red-600 text-red-600' : 'border-transparent text-stone-500 hover:text-stone-900'
          ]"
        >
          📊 Dashboard Overview
        </button>

        <button 
          @click="activeTab = 'products'" 
          :class="[
            'py-3 border-b-2 transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5',
            activeTab === 'products' ? 'border-red-600 text-red-600' : 'border-transparent text-stone-500 hover:text-stone-900'
          ]"
        >
          <span>🍎 Apple Varieties</span>
          <span class="px-1.5 py-0.2 rounded-full bg-stone-200 text-stone-700 text-[10px]">{{ products.length }}</span>
        </button>

        <button 
          @click="activeTab = 'orders'" 
          :class="[
            'py-3 border-b-2 transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5',
            activeTab === 'orders' ? 'border-red-600 text-red-600' : 'border-transparent text-stone-500 hover:text-stone-900'
          ]"
        >
          <span>📦 Customer Orders</span>
          <span v-if="stats.pending_orders > 0" class="px-1.5 py-0.2 rounded-full bg-red-600 text-white text-[10px]">
            {{ stats.pending_orders }} new
          </span>
        </button>

        <button 
          @click="activeTab = 'settings'" 
          :class="[
            'py-3 border-b-2 transition-all cursor-pointer whitespace-nowrap',
            activeTab === 'settings' ? 'border-red-600 text-red-600' : 'border-transparent text-stone-500 hover:text-stone-900'
          ]"
        >
          ⚙️ Farm & Website Settings
        </button>

        <button 
          @click="activeTab = 'inquiries'" 
          :class="[
            'py-3 border-b-2 transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5',
            activeTab === 'inquiries' ? 'border-red-600 text-red-600' : 'border-transparent text-stone-500 hover:text-stone-900'
          ]"
        >
          <span>✉️ Bulk / Wholesale Leads</span>
          <span v-if="stats.unread_inquiries > 0" class="px-1.5 py-0.2 rounded-full bg-amber-500 text-white text-[10px]">
            {{ stats.unread_inquiries }}
          </span>
        </button>
      </div>
    </header>

    <!-- Main Admin Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
      
      <!-- TAB 1: OVERVIEW & METRICS -->
      <div v-if="activeTab === 'overview'" class="space-y-8 animate-in fade-in duration-200">
        
        <!-- Metrics Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <div class="p-5 rounded-3xl bg-white border border-stone-200/80 shadow-2xs">
            <span class="text-xs font-bold text-stone-400 uppercase">Total Orchard Revenue</span>
            <div class="text-2xl sm:text-3xl font-extrabold text-stone-900 mt-1 font-serif-title">
              ₹{{ formatMoney(stats.total_revenue) }}
            </div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1">Direct from customers</div>
          </div>

          <div class="p-5 rounded-3xl bg-white border border-stone-200/80 shadow-2xs">
            <span class="text-xs font-bold text-stone-400 uppercase">Total Orders</span>
            <div class="text-2xl sm:text-3xl font-extrabold text-stone-900 mt-1 font-serif-title">
              {{ stats.total_orders || 0 }}
            </div>
            <div class="text-[11px] text-stone-500 font-semibold mt-1">Boxes dispatched across India</div>
          </div>

          <div class="p-5 rounded-3xl bg-white border border-stone-200/80 shadow-2xs">
            <span class="text-xs font-bold text-amber-600 uppercase">Pending Verification</span>
            <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-1 font-serif-title">
              {{ stats.pending_orders || 0 }}
            </div>
            <div class="text-[11px] text-stone-500 font-semibold mt-1">Need confirmation or packing</div>
          </div>

          <div class="p-5 rounded-3xl bg-white border border-stone-200/80 shadow-2xs">
            <span class="text-xs font-bold text-emerald-600 uppercase">Dispatched / Delivered</span>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-1 font-serif-title">
              {{ (stats.dispatched_orders || 0) + (stats.delivered_orders || 0) }}
            </div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1">En-route or delivered</div>
          </div>
        </div>

        <!-- Recent Orders Table Preview -->
        <div class="bg-white rounded-3xl border border-stone-200/80 shadow-2xs p-6 space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-base font-bold text-stone-900 font-serif-title">Recent Customer Orders</h3>
              <p class="text-xs text-stone-500">Latest fresh apple box orders</p>
            </div>
            <button @click="activeTab = 'orders'" class="text-xs font-bold text-red-600 hover:text-red-700 cursor-pointer">
              View All Orders →
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-stone-50 text-stone-500 font-bold border-y border-stone-200">
                <tr>
                  <th class="py-3 px-4">Order ID</th>
                  <th class="py-3 px-4">Customer</th>
                  <th class="py-3 px-4">Destination</th>
                  <th class="py-3 px-4">Total</th>
                  <th class="py-3 px-4">Payment</th>
                  <th class="py-3 px-4">Status</th>
                  <th class="py-3 px-4 text-right">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-100">
                <tr v-for="order in stats.recent_orders" :key="order.id" class="hover:bg-stone-50/50">
                  <td class="py-3 px-4 font-mono font-bold text-stone-900">{{ order.order_number }}</td>
                  <td class="py-3 px-4">
                    <div class="font-bold text-stone-900">{{ order.customer_name }}</div>
                    <div class="text-[11px] text-stone-500">{{ order.customer_phone }}</div>
                  </td>
                  <td class="py-3 px-4 text-stone-600">{{ order.city }}, {{ order.state }}</td>
                  <td class="py-3 px-4 font-extrabold text-stone-900">₹{{ order.total_amount }}</td>
                  <td class="py-3 px-4 uppercase text-[10px] font-bold text-stone-600">{{ order.payment_method }}</td>
                  <td class="py-3 px-4">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                      :class="{
                        'bg-amber-100 text-amber-800': order.status === 'pending',
                        'bg-blue-100 text-blue-800': order.status === 'confirmed',
                        'bg-purple-100 text-purple-800': order.status === 'packed',
                        'bg-indigo-100 text-indigo-800': order.status === 'dispatched',
                        'bg-emerald-100 text-emerald-800': order.status === 'delivered',
                      }">
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-right">
                    <a :href="'https://wa.me/' + cleanPhone(order.customer_phone)" target="_blank" class="text-emerald-600 font-bold hover:underline mr-2">
                      WhatsApp
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- TAB 2: PRODUCTS / APPLE VARIETIES & OTHER PRODUCTS MANAGER -->
      <div v-if="activeTab === 'products'" class="space-y-6 animate-in fade-in duration-200">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-stone-200/80 shadow-2xs">
          <div>
            <h2 class="text-xl font-bold text-stone-900 font-serif-title">Products & Farm Catalog</h2>
            <p class="text-xs text-stone-500">Manage fresh apple varieties, custom categories, honey, vinegars & other farm products</p>
          </div>

          <button 
            @click="openAddProductModal" 
            class="px-5 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <span>+ Add New Product</span>
          </button>
        </div>

        <!-- Filter Pills by Type (All / Apples / Other Products) -->
        <div class="flex items-center gap-2">
          <button
            @click="productFilterType = 'all'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer',
              productFilterType === 'all'
                ? 'bg-stone-900 text-white shadow-xs'
                : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'
            ]"
          >
            All Products ({{ products.length }})
          </button>
          <button
            @click="productFilterType = 'apple'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer',
              productFilterType === 'apple'
                ? 'bg-red-600 text-white shadow-xs'
                : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'
            ]"
          >
            🍎 Fresh Apples ({{ appleCount }})
          </button>
          <button
            @click="productFilterType = 'other'"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer',
              productFilterType === 'other'
                ? 'bg-amber-600 text-white shadow-xs'
                : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'
            ]"
          >
            🍯 Other Farm Products ({{ otherCount }})
          </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-3xl border border-stone-200/80 shadow-2xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-stone-50 text-stone-500 font-bold border-b border-stone-200">
                <tr>
                  <th class="py-3.5 px-4">Product & Category</th>
                  <th class="py-3.5 px-4">Origin / Farm</th>
                  <th class="py-3.5 px-4">Base Price</th>
                  <th class="py-3.5 px-4">Pack / Box Sizes</th>
                  <th class="py-3.5 px-4">Stock Status</th>
                  <th class="py-3.5 px-4">Active</th>
                  <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-100">
                <tr v-for="product in filteredAdminProducts" :key="product.id" class="hover:bg-stone-50/50">
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                      <img :src="product.image_url" :alt="product.name" class="w-12 h-12 rounded-xl object-cover bg-stone-100 shrink-0"/>
                      <div>
                        <div class="flex items-center gap-1.5">
                          <span class="font-bold text-stone-900 text-sm">{{ product.name }}</span>
                          <span 
                            class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider"
                            :class="product.product_type === 'other' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800'"
                          >
                            {{ product.product_type === 'other' ? '🍯 Other' : '🍎 Apple' }}
                          </span>
                        </div>
                        <div class="text-[11px] text-stone-500 font-medium">{{ product.category }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 text-stone-600 font-medium">{{ product.origin }}</td>
                  <td class="py-3.5 px-4 font-extrabold text-stone-900">
                    ₹{{ product.price_per_kg }}{{ product.product_type === 'other' ? '' : '/kg' }}
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="flex flex-wrap gap-1 max-w-[200px]">
                      <span 
                        v-for="(box, i) in (product.box_options || [])" 
                        :key="i" 
                        class="px-2 py-0.5 rounded bg-stone-100 text-stone-700 text-[10px] font-semibold"
                      >
                        {{ box.size.split(' ')[0] }} {{ box.size.split(' ')[1] }}: ₹{{ box.price }}
                      </span>
                    </div>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                      :class="{
                        'bg-emerald-100 text-emerald-800': product.stock_status === 'in_stock',
                        'bg-indigo-100 text-indigo-800': product.stock_status === 'pre_order',
                        'bg-stone-200 text-stone-700': product.stock_status === 'out_of_stock',
                      }">
                      {{ product.stock_status.replace('_', ' ') }}
                    </span>
                  </td>
                  <td class="py-3.5 px-4">
                    <button 
                      @click="toggleProductStatus(product)" 
                      :class="[
                        'w-8 h-4 rounded-full transition-colors relative cursor-pointer',
                        product.is_active ? 'bg-emerald-500' : 'bg-stone-300'
                      ]"
                    >
                      <span 
                        :class="[
                          'w-3 h-3 rounded-full bg-white absolute top-0.5 transition-transform',
                          product.is_active ? 'right-0.5' : 'left-0.5'
                        ]"
                      ></span>
                    </button>
                  </td>
                  <td class="py-3.5 px-4 text-right space-x-2">
                    <button @click="openEditProductModal(product)" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs cursor-pointer">
                      Edit
                    </button>
                    <button @click="deleteProduct(product.id)" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs cursor-pointer">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- TAB 3: ORDERS MANAGEMENT -->
      <div v-if="activeTab === 'orders'" class="space-y-6 animate-in fade-in duration-200">
        
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-2xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-stone-900 font-serif-title">Customer Apple Orders</h2>
              <p class="text-xs text-stone-500">Update order fulfillment status, courier tracking & customer WhatsApp communication</p>
            </div>

            <!-- Status Filter Pills -->
            <div class="flex flex-wrap gap-1.5 text-xs font-semibold">
              <button 
                v-for="st in ['all', 'pending', 'confirmed', 'packed', 'dispatched', 'delivered', 'cancelled']" 
                :key="st"
                @click="filterOrderStatus = st; loadOrders();"
                :class="[
                  'px-3 py-1.5 rounded-xl capitalize transition-all cursor-pointer',
                  filterOrderStatus === st 
                    ? 'bg-stone-900 text-white shadow-xs' 
                    : 'bg-stone-100 text-stone-600 hover:bg-stone-200'
                ]"
              >
                {{ st }}
              </button>
            </div>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-3xl border border-stone-200/80 shadow-2xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-stone-50 text-stone-500 font-bold border-b border-stone-200">
                <tr>
                  <th class="py-3.5 px-4">Order Number</th>
                  <th class="py-3.5 px-4">Customer Contact</th>
                  <th class="py-3.5 px-4">Full Shipping Address</th>
                  <th class="py-3.5 px-4">Ordered Apple Boxes</th>
                  <th class="py-3.5 px-4">Amount & Payment</th>
                  <th class="py-3.5 px-4">Courier / Tracking</th>
                  <th class="py-3.5 px-4">Fulfillment Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-100">
                <tr v-for="order in orders" :key="order.id" class="hover:bg-stone-50/50">
                  <td class="py-3.5 px-4">
                    <div class="font-mono font-bold text-stone-900">{{ order.order_number }}</div>
                    <div class="text-[10px] text-stone-400">{{ formatDate(order.created_at) }}</div>
                  </td>

                  <td class="py-3.5 px-4">
                    <div class="font-bold text-stone-900">{{ order.customer_name }}</div>
                    <div class="text-stone-500 flex items-center gap-1.5 mt-0.5">
                      <span>{{ order.customer_phone }}</span>
                      <a :href="'https://wa.me/' + cleanPhone(order.customer_phone)" target="_blank" title="WhatsApp Customer" class="text-emerald-600 font-bold">
                        💬
                      </a>
                    </div>
                  </td>

                  <td class="py-3.5 px-4 max-w-xs text-stone-600">
                    <div>{{ order.shipping_address }}</div>
                    <div class="font-semibold text-stone-800">{{ order.city }}, {{ order.state }} - {{ order.pincode }}</div>
                    <div v-if="order.order_notes" class="text-[10px] text-amber-700 italic mt-0.5">"{{ order.order_notes }}"</div>
                  </td>

                  <td class="py-3.5 px-4">
                    <ul class="space-y-1">
                      <li v-for="(it, i) in order.items" :key="i" class="text-[11px] font-medium text-stone-800">
                        • {{ it.product_name }} ({{ it.box_size }}) × {{ it.quantity }}
                      </li>
                    </ul>
                  </td>

                  <td class="py-3.5 px-4">
                    <div class="text-sm font-extrabold text-stone-900">₹{{ order.total_amount }}</div>
                    <div class="text-[10px] text-stone-500 font-medium">
                      Sub: ₹{{ order.subtotal }} + Ship: ₹{{ order.shipping_fee }}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1">
                      <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase"
                        :class="order.payment_method === 'cod' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'">
                        {{ order.payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'UPI Payment' }}
                      </span>
                    </div>
                    <div v-if="order.shipping_method" class="text-[10px] text-stone-600 mt-1 flex items-center gap-1">
                      <span>🚚</span>
                      <span class="font-medium truncate max-w-[140px]" :title="order.shipping_method">{{ order.shipping_method }}</span>
                    </div>
                  </td>

                  <td class="py-3.5 px-4">
                    <div v-if="order.tracking_courier">
                      <div class="font-semibold text-stone-900">{{ order.tracking_courier }}</div>
                      <div class="font-mono text-[10px] text-stone-500">{{ order.tracking_number }}</div>
                    </div>
                    <button 
                      v-else 
                      @click="promptTracking(order)" 
                      class="text-[11px] text-indigo-600 hover:underline font-bold cursor-pointer"
                    >
                      + Add Tracking
                    </button>
                  </td>

                  <td class="py-3.5 px-4">
                    <select 
                      v-model="order.status" 
                      @change="updateOrderStatus(order)"
                      class="px-2.5 py-1 rounded-xl border text-xs font-bold outline-none bg-white cursor-pointer"
                      :class="{
                        'border-amber-400 text-amber-900 bg-amber-50': order.status === 'pending',
                        'border-blue-400 text-blue-900 bg-blue-50': order.status === 'confirmed',
                        'border-purple-400 text-purple-900 bg-purple-50': order.status === 'packed',
                        'border-indigo-400 text-indigo-900 bg-indigo-50': order.status === 'dispatched',
                        'border-emerald-400 text-emerald-900 bg-emerald-50': order.status === 'delivered',
                        'border-red-400 text-red-900 bg-red-50': order.status === 'cancelled',
                      }"
                    >
                      <option value="pending">Pending</option>
                      <option value="confirmed">Confirmed</option>
                      <option value="packed">Packed</option>
                      <option value="dispatched">Dispatched</option>
                      <option value="delivered">Delivered</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- TAB 4: FARM & WEBSITE SETTINGS (DYNAMIC CONTROL) -->
      <div v-if="activeTab === 'settings'" class="max-w-4xl mx-auto space-y-6 animate-in fade-in duration-200">
        
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-2xs space-y-6">
          <div class="border-b border-stone-200 pb-4">
            <h2 class="text-xl font-bold text-stone-900 font-serif-title">Farm & Website Settings</h2>
            <p class="text-xs text-stone-500">Whatever you update here immediately updates on the live website.</p>
          </div>

          <div v-if="settingsSuccessMsg" class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
            Settings saved successfully!
          </div>

          <form @submit.prevent="saveSettings" class="space-y-5">
            
            <!-- Farm Name & Tagline -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">Farm / Orchard Name</label>
                <input v-model="editSettings.farm_name" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"/>
              </div>

              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">Elevation / Altitude</label>
                <input v-model="editSettings.orchard_elevation" type="text" placeholder="e.g. 8,500 Feet Altitude" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"/>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-stone-700 mb-1">Store Tagline / Subtitle</label>
              <input v-model="editSettings.tagline" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"/>
            </div>

            <!-- Farmer Profile -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">Farmer Name</label>
                <input v-model="editSettings.farmer_name" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"/>
              </div>

              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">Farmer Title / Subtitle</label>
                <input v-model="editSettings.farmer_title" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"/>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-stone-700 mb-1">Farmer Bio & Orchard Story</label>
              <textarea v-model="editSettings.farmer_story" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"></textarea>
            </div>

            <!-- Contacts -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">Phone Number</label>
                <input v-model="editSettings.phone" type="text" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-xs outline-none"/>
              </div>

              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">WhatsApp Number (with country code)</label>
                <input v-model="editSettings.whatsapp" type="text" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-xs outline-none"/>
              </div>

              <div>
                <label class="block text-xs font-bold text-stone-700 mb-1">Contact Email</label>
                <input v-model="editSettings.email" type="email" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-xs outline-none"/>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-stone-700 mb-1">Farm / Orchard Location Address</label>
              <input v-model="editSettings.location" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none"/>
            </div>

            <!-- PAYMENT METHODS CONFIGURATION -->
            <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 space-y-4">
              <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                  <span class="text-xl">💳</span>
                  <div>
                    <h3 class="text-sm font-bold text-stone-900">Payment Methods & UPI QR Code</h3>
                    <p class="text-xs text-stone-500">Configure Cash on Delivery toggle, UPI ID, and payment QR code</p>
                  </div>
                </div>
              </div>

              <!-- COD and UPI Toggles -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- COD Toggle Card -->
                <div class="p-4 rounded-xl border transition-all"
                  :class="editSettings.enable_cod ? 'bg-white border-emerald-300 shadow-2xs' : 'bg-stone-100 border-stone-200 opacity-80'">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <div class="flex items-center gap-2">
                        <span class="font-bold text-xs text-stone-900">Cash on Delivery (COD)</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                          :class="editSettings.enable_cod ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-600'">
                          {{ editSettings.enable_cod ? 'Active / Enabled' : 'Disabled' }}
                        </span>
                      </div>
                      <p class="text-[11px] text-stone-500 mt-1">
                        Allow customers to pay cash when fresh apple boxes are delivered to their doorstep.
                      </p>
                    </div>

                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                      <input type="checkbox" v-model="editSettings.enable_cod" class="sr-only peer">
                      <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                  </div>
                </div>

                <!-- UPI Toggle Card -->
                <div class="p-4 rounded-xl border transition-all"
                  :class="editSettings.enable_upi ? 'bg-white border-emerald-300 shadow-2xs' : 'bg-stone-100 border-stone-200 opacity-80'">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <div class="flex items-center gap-2">
                        <span class="font-bold text-xs text-stone-900">UPI / QR Payment</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                          :class="editSettings.enable_upi ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-600'">
                          {{ editSettings.enable_upi ? 'Active / Enabled' : 'Disabled' }}
                        </span>
                      </div>
                      <p class="text-[11px] text-stone-500 mt-1">
                        Opens instant scan-and-pay QR code (GPay, PhonePe, Paytm, BHIM) at checkout.
                      </p>
                    </div>

                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                      <input type="checkbox" v-model="editSettings.enable_upi" class="sr-only peer">
                      <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                  </div>
                </div>

              </div>

              <!-- UPI Details & QR Settings -->
              <div v-if="editSettings.enable_upi" class="bg-white p-4 rounded-xl border border-stone-200 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1">
                      Farmer UPI ID * <span class="text-stone-400 font-normal">(for GPay, PhonePe, Paytm)</span>
                    </label>
                    <input 
                      v-model="editSettings.upi_id" 
                      type="text" 
                      placeholder="e.g. kinnaurfarm@okhdfcbank"
                      class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-mono outline-none focus:border-red-500"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1">
                      Payee Display Name
                    </label>
                    <input 
                      v-model="editSettings.upi_name" 
                      type="text" 
                      placeholder="e.g. Sunil Chauhan / Kinnaur Orchards"
                      class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs outline-none focus:border-red-500"
                    />
                  </div>
                </div>

                <!-- QR Code Options & Live Preview -->
                <div class="border-t border-stone-100 pt-3">
                  <label class="block text-xs font-bold text-stone-700 mb-2">UPI QR Code Configuration & Live Preview:</label>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    
                    <!-- Dynamic QR Card -->
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50 flex items-center gap-3.5">
                      <div class="bg-white p-2 rounded-lg border border-stone-200 shadow-2xs shrink-0">
                        <img 
                          :src="dynamicUpiQrPreviewUrl" 
                          alt="Dynamic UPI QR" 
                          class="w-24 h-24 object-contain"
                        />
                      </div>
                      <div class="text-xs space-y-1">
                        <div class="font-bold text-stone-900 flex items-center gap-1.5">
                          <span>Dynamic Amount QR</span>
                          <span class="px-1.5 py-0.2 bg-emerald-100 text-emerald-800 text-[9px] rounded font-semibold">Auto-Generates</span>
                        </div>
                        <p class="text-[11px] text-stone-500">
                          Automatically generates a QR code with the customer's exact order total and UPI ID.
                        </p>
                        <div class="text-[10px] font-mono text-stone-600 bg-white px-2 py-0.5 rounded border border-stone-200 inline-block">
                          {{ editSettings.upi_id || 'kinnaurfarm@okhdfcbank' }}
                        </div>
                      </div>
                    </div>

                    <!-- Custom QR Upload Card -->
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50 space-y-2">
                      <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-stone-900">Custom Standee QR (Optional)</span>
                        <button 
                          v-if="editSettings.upi_qr_image || upiQrLocalPreview" 
                          @click="removeCustomQrImage" 
                          type="button" 
                          class="text-[10px] text-red-600 hover:underline font-bold"
                        >
                          Remove Custom QR
                        </button>
                      </div>
                      <p class="text-[11px] text-stone-500">
                        Upload your printed GPay / PhonePe QR standee photo if you prefer customers scan your physical QR.
                      </p>

                      <div class="flex items-center gap-3">
                        <input 
                          type="file" 
                          @change="handleUpiQrUpload" 
                          accept="image/*" 
                          class="text-xs text-stone-600 file:mr-2.5 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer"
                        />
                        <div v-if="upiQrLocalPreview || editSettings.upi_qr_image" class="w-12 h-12 rounded-lg bg-white border border-stone-300 p-0.5 overflow-hidden shrink-0">
                          <img :src="upiQrLocalPreview || editSettings.upi_qr_image" class="w-full h-full object-cover rounded" />
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

              </div>
            </div>

            <!-- SHIPPING & DELIVERY CHARGES CONFIGURATION -->
            <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 space-y-4">
              <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                  <span class="text-xl">🚚</span>
                  <div>
                    <h3 class="text-sm font-bold text-stone-900">Shipping Options & Delivery Charges</h3>
                    <p class="text-xs text-stone-500">Set standard delivery fees, free delivery thresholds, and configure shipping options</p>
                  </div>
                </div>
              </div>

              <!-- General Delivery Fees & Threshold -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-stone-700 mb-1">Standard Delivery Fee (₹)</label>
                  <input 
                    v-model.number="editSettings.default_shipping_fee" 
                    type="number" 
                    min="0"
                    placeholder="120"
                    class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-xs font-bold outline-none focus:border-red-500"
                  />
                  <p class="text-[10px] text-stone-400 mt-0.5">Base delivery charge for standard apple box orders.</p>
                </div>

                <div>
                  <label class="block text-xs font-bold text-stone-700 mb-1">Free Delivery Min Order (₹)</label>
                  <input 
                    v-model.number="editSettings.free_shipping_min_order" 
                    type="number" 
                    min="0"
                    placeholder="1500"
                    class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-xs font-bold outline-none focus:border-red-500"
                  />
                  <p class="text-[10px] text-stone-400 mt-0.5">Orders above this amount unlock free delivery.</p>
                </div>

                <div class="flex flex-col justify-between">
                  <label class="block text-xs font-bold text-stone-700 mb-1">Free Delivery Rule</label>
                  <div class="p-2.5 rounded-xl bg-white border border-stone-200 flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-800">
                      {{ editSettings.enable_free_shipping ? 'Offer Free Delivery' : 'No Free Delivery' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" v-model="editSettings.enable_free_shipping" class="sr-only peer">
                      <div class="w-9 h-5 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Configurable Shipping Methods List -->
              <div class="bg-white p-4 rounded-xl border border-stone-200 space-y-3">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="text-xs font-bold text-stone-900">Configured Shipping Methods</h4>
                    <p class="text-[11px] text-stone-500">Customers will be able to choose from these options at checkout</p>
                  </div>
                  <button 
                    type="button" 
                    @click="addShippingOption" 
                    class="px-2.5 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-[11px] font-bold flex items-center gap-1 cursor-pointer transition-colors"
                  >
                    + Add Shipping Option
                  </button>
                </div>

                <!-- Shipping Options Cards -->
                <div class="space-y-2.5">
                  <div 
                    v-for="(opt, idx) in editSettings.shipping_options" 
                    :key="opt.id || idx"
                    class="p-3 rounded-xl border border-stone-200 bg-stone-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                  >
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 flex-1">
                      <div>
                        <label class="block text-[10px] font-bold text-stone-500 mb-0.5">Method Name</label>
                        <input 
                          v-model="opt.name" 
                          type="text" 
                          placeholder="e.g. Standard Mountain Express"
                          class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-stone-300 text-xs font-semibold outline-none focus:border-red-400"
                        />
                      </div>
                      <div>
                        <label class="block text-[10px] font-bold text-stone-500 mb-0.5">Estimated Delivery Time</label>
                        <input 
                          v-model="opt.estimated_days" 
                          type="text" 
                          placeholder="e.g. 4-6 Days"
                          class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-stone-300 text-xs outline-none focus:border-red-400"
                        />
                      </div>
                      <div>
                        <label class="block text-[10px] font-bold text-stone-700 mb-0.5">Delivery Fee (₹)</label>
                        <input 
                          v-model.number="opt.fee" 
                          type="number" 
                          min="0"
                          class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-stone-300 text-xs font-bold outline-none focus:border-red-400"
                        />
                      </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-stone-200">
                      <!-- Enabled Toggle -->
                      <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                        <input type="checkbox" v-model="opt.is_enabled" class="rounded text-red-600 focus:ring-0">
                        <span class="text-[11px] font-semibold" :class="opt.is_enabled ? 'text-emerald-700' : 'text-stone-400'">
                          {{ opt.is_enabled ? 'Enabled' : 'Disabled' }}
                        </span>
                      </label>

                      <!-- Remove Button -->
                      <button 
                        type="button" 
                        @click="removeShippingOption(idx)" 
                        class="p-1 text-stone-400 hover:text-red-600 rounded transition-colors"
                        title="Remove shipping option"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Top Banner Badge -->
            <div>
              <label class="block text-xs font-bold text-stone-700 mb-1">Top Announcement Ribbon Text</label>
              <input v-model="editSettings.banner_badge" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-xs outline-none"/>
            </div>

            <!-- Hero Banner Photo & Farmer Photo (Upload File or Enter URL) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Banner Photo Card -->
              <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-2.5">
                <label class="block text-xs font-bold text-stone-800">
                  Hero Banner Photo (Upload File or Enter URL)
                </label>
                
                <div>
                  <span class="block text-[10px] font-bold text-stone-500 mb-1">📁 Upload Image File:</span>
                  <input 
                    type="file" 
                    @change="handleBannerFileChange" 
                    accept="image/*" 
                    class="w-full text-xs text-stone-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer"
                  />
                </div>

                <div>
                  <span class="block text-[10px] font-bold text-stone-500 mb-1">🔗 OR Image URL:</span>
                  <input 
                    v-model="editSettings.banner_image" 
                    type="url" 
                    placeholder="https://..."
                    class="w-full px-3 py-1.5 rounded-xl border border-stone-300 text-xs outline-none bg-white"
                  />
                </div>

                <!-- Preview -->
                <div v-if="bannerLocalPreview || editSettings.banner_image" class="pt-2 border-t border-stone-200 flex items-center gap-2.5">
                  <div class="w-16 h-10 rounded-lg border border-stone-300 overflow-hidden bg-white shrink-0">
                    <img :src="bannerLocalPreview || editSettings.banner_image" class="w-full h-full object-cover" />
                  </div>
                  <span class="text-[10px] text-stone-500">Banner Preview</span>
                </div>
              </div>

              <!-- Farmer Photo Card -->
              <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-2.5">
                <label class="block text-xs font-bold text-stone-800">
                  Farmer Profile Photo (Upload File or Enter URL)
                </label>

                <div>
                  <span class="block text-[10px] font-bold text-stone-500 mb-1">📁 Upload Image File:</span>
                  <input 
                    type="file" 
                    @change="handleFarmerPhotoFileChange" 
                    accept="image/*" 
                    class="w-full text-xs text-stone-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer"
                  />
                </div>

                <div>
                  <span class="block text-[10px] font-bold text-stone-500 mb-1">🔗 OR Image URL:</span>
                  <input 
                    v-model="editSettings.farmer_photo" 
                    type="url" 
                    placeholder="https://..."
                    class="w-full px-3 py-1.5 rounded-xl border border-stone-300 text-xs outline-none bg-white"
                  />
                </div>

                <!-- Preview -->
                <div v-if="farmerLocalPreview || editSettings.farmer_photo" class="pt-2 border-t border-stone-200 flex items-center gap-2.5">
                  <div class="w-12 h-12 rounded-lg border border-stone-300 overflow-hidden bg-white shrink-0">
                    <img :src="farmerLocalPreview || editSettings.farmer_photo" class="w-full h-full object-cover" />
                  </div>
                  <span class="text-[10px] text-stone-500">Farmer Photo Preview</span>
                </div>
              </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-stone-200">
              <button 
                type="submit" 
                :disabled="isSavingSettings"
                class="px-6 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer disabled:bg-stone-300"
              >
                <span v-if="!isSavingSettings">Save All Farm Settings</span>
                <span v-else>Saving...</span>
              </button>
            </div>

          </form>
        </div>

      </div>

      <!-- TAB 5: INQUIRIES & BULK LEADS -->
      <div v-if="activeTab === 'inquiries'" class="space-y-6 animate-in fade-in duration-200">
        
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-2xs">
          <h2 class="text-xl font-bold text-stone-900 font-serif-title">Wholesale & Bulk Inquiries</h2>
          <p class="text-xs text-stone-500">Retail store inquiries, corporate gift orders, or orchard tour requests</p>
        </div>

        <div class="bg-white rounded-3xl border border-stone-200/80 shadow-2xs overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-stone-50 text-stone-500 font-bold border-b border-stone-200">
                <tr>
                  <th class="py-3.5 px-4">Contact Person / Store</th>
                  <th class="py-3.5 px-4">Phone / WhatsApp</th>
                  <th class="py-3.5 px-4">Inquiry Type</th>
                  <th class="py-3.5 px-4">Message / Requirements</th>
                  <th class="py-3.5 px-4">Date</th>
                  <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-100">
                <tr v-for="inq in inquiries" :key="inq.id" class="hover:bg-stone-50/50" :class="{ 'bg-amber-50/30 font-semibold': !inq.is_read }">
                  <td class="py-3.5 px-4">
                    <div class="font-bold text-stone-900">{{ inq.name }}</div>
                    <div class="text-[11px] text-stone-400">{{ inq.email }}</div>
                  </td>
                  <td class="py-3.5 px-4">
                    <a :href="'https://wa.me/' + cleanPhone(inq.phone)" target="_blank" class="text-emerald-700 font-bold hover:underline">
                      {{ inq.phone }} 💬
                    </a>
                  </td>
                  <td class="py-3.5 px-4 uppercase text-[10px] font-bold text-stone-700">
                    {{ inq.inquiry_type.replace('_', ' ') }}
                  </td>
                  <td class="py-3.5 px-4 max-w-sm text-stone-700 leading-relaxed">
                    {{ inq.message }}
                  </td>
                  <td class="py-3.5 px-4 text-stone-400 text-[10px]">
                    {{ formatDate(inq.created_at) }}
                  </td>
                  <td class="py-3.5 px-4 text-right space-x-2">
                    <button v-if="!inq.is_read" @click="markInquiryRead(inq.id)" class="px-2.5 py-1 rounded bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs cursor-pointer">
                      Mark Read
                    </button>
                    <button @click="deleteInquiry(inq.id)" class="px-2.5 py-1 rounded bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs cursor-pointer">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

    </main>

    <!-- Developer Footer Credit in Admin Dashboard -->
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-stone-200 dark:border-stone-800 text-xs text-stone-500 dark:text-stone-400 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-2">
        <span>E-Commerce System Developed & Maintained by</span>
        <strong class="text-stone-800 dark:text-stone-200 font-bold">Shubham Thakur</strong>
        <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 font-bold text-[10px]">Web Developer</span>
      </div>
      <div class="flex items-center gap-4">
        <a href="mailto:shubhamthakurst07@gmail.com" class="hover:text-stone-900 dark:hover:text-stone-100 font-semibold flex items-center gap-1 transition-colors">
          <span>✉️ shubhamthakurst07@gmail.com</span>
        </a>
        <span>•</span>
        <a href="tel:8219795479" class="hover:text-stone-900 dark:hover:text-stone-100 font-semibold flex items-center gap-1 transition-colors">
          <span>📞 8219795479</span>
        </a>
        <span>•</span>
        <a href="https://wa.me/918219795479?text=Hi%20Shubham,%20contacting%20you%20from%20Apple%20Farm%20Admin." target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 font-semibold flex items-center gap-1 transition-colors">
          <span>💬 WhatsApp Support</span>
        </a>
      </div>
    </footer>

    <!-- Product Form Modal -->
    <ProductFormModal 
      :is-open="isProductModalOpen" 
      :product-to-edit="selectedProductToEdit" 
      @close="isProductModalOpen = false" 
      @saved="onProductSaved" 
    />

  </div>
</template>

<script>
import axios from 'axios';
import ProductFormModal from './ProductFormModal.vue';

export default {
  components: {
    ProductFormModal
  },
  props: {
    settings: {
      type: Object,
      default: () => ({})
    },
    user: {
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
      activeTab: 'overview',
      stats: {},
      products: [],
      orders: [],
      inquiries: [],
      filterOrderStatus: 'all',
      productFilterType: 'all',
      isProductModalOpen: false,
      selectedProductToEdit: null,
      editSettings: {},
      isSavingSettings: false,
      settingsSuccessMsg: false,
      upiQrFile: null,
      upiQrLocalPreview: null,
      bannerFile: null,
      bannerLocalPreview: null,
      farmerFile: null,
      farmerLocalPreview: null
    }
  },
  computed: {
    dynamicUpiQrPreviewUrl() {
      const upiId = this.editSettings.upi_id || 'kinnaurfarm@okhdfcbank';
      const name = this.editSettings.upi_name || this.editSettings.farm_name || 'Kinnaur Mountain Orchards';
      const upiUri = `upi://pay?pa=${encodeURIComponent(upiId)}&pn=${encodeURIComponent(name)}&cu=INR&tn=Apple_Harvest_Order`;
      return `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiUri)}`;
    },
    filteredAdminProducts() {
      if (this.productFilterType === 'all') return this.products;
      if (this.productFilterType === 'other') {
        return this.products.filter(p => p.product_type === 'other');
      }
      return this.products.filter(p => !p.product_type || p.product_type === 'apple');
    },
    appleCount() {
      return this.products.filter(p => !p.product_type || p.product_type === 'apple').length;
    },
    otherCount() {
      return this.products.filter(p => p.product_type === 'other').length;
    }
  },
  watch: {
    settings: {
      immediate: true,
      handler(val) {
        const copy = { ...val };
        copy.enable_cod = copy.enable_cod === undefined ? true : (copy.enable_cod === '1' || copy.enable_cod === true || copy.enable_cod === 'true');
        copy.enable_upi = copy.enable_upi === undefined ? true : (copy.enable_upi === '1' || copy.enable_upi === true || copy.enable_upi === 'true');
        copy.enable_free_shipping = copy.enable_free_shipping === undefined ? true : (copy.enable_free_shipping === '1' || copy.enable_free_shipping === true || copy.enable_free_shipping === 'true');

        let options = [];
        if (typeof copy.shipping_options === 'string') {
          try {
            options = JSON.parse(copy.shipping_options);
          } catch(e) {
            options = [];
          }
        } else if (Array.isArray(copy.shipping_options)) {
          options = copy.shipping_options;
        }

        if (!options || options.length === 0) {
          options = [
            {
              id: 'standard',
              name: 'Standard Mountain Express (Surface/Post)',
              estimated_days: '4-6 Days',
              fee: Number(copy.default_shipping_fee) || 120,
              is_enabled: true
            },
            {
              id: 'express',
              name: 'Priority Air Cargo (Fastest Flight Dispatch)',
              estimated_days: '1-2 Days',
              fee: 260,
              is_enabled: true
            },
            {
              id: 'pickup',
              name: 'Direct Orchard Pickup (Kalpa Farmhouse)',
              estimated_days: 'Same Day',
              fee: 0,
              is_enabled: false
            }
          ];
        }

        copy.shipping_options = options;
        this.editSettings = copy;
      }
    }
  },
  mounted() {
    this.loadStats();
    this.loadProducts();
    this.loadOrders();
    this.loadInquiries();
  },
  methods: {
    async loadStats() {
      try {
        const res = await axios.get('/api/admin/dashboard');
        if (res.data.success) {
          this.stats = res.data.data;
        }
      } catch (err) {
        console.error(err);
      }
    },
    async loadProducts() {
      try {
        const res = await axios.get('/api/products?all=1');
        if (res.data.success) {
          this.products = res.data.data;
        }
      } catch (err) {
        console.error(err);
      }
    },
    async loadOrders() {
      try {
        const params = {};
        if (this.filterOrderStatus !== 'all') {
          params.status = this.filterOrderStatus;
        }
        const res = await axios.get('/api/admin/orders', { params });
        if (res.data.success) {
          this.orders = res.data.data.data || res.data.data;
        }
      } catch (err) {
        console.error(err);
      }
    },
    async loadInquiries() {
      try {
        const res = await axios.get('/api/admin/inquiries');
        if (res.data.success) {
          this.inquiries = res.data.data;
        }
      } catch (err) {
        console.error(err);
      }
    },
    openAddProductModal() {
      this.selectedProductToEdit = null;
      this.isProductModalOpen = true;
    },
    openEditProductModal(prod) {
      this.selectedProductToEdit = prod;
      this.isProductModalOpen = true;
    },
    onProductSaved() {
      this.loadProducts();
      this.loadStats();
      this.$emit('refresh-products');
    },
    async toggleProductStatus(prod) {
      try {
        const res = await axios.patch(`/api/admin/products/${prod.id}/toggle-status`);
        if (res.data.success) {
          prod.is_active = res.data.data.is_active;
          this.$emit('refresh-products');
        }
      } catch (err) {
        alert('Could not update status');
      }
    },
    async deleteProduct(id) {
      if (!confirm('Are you sure you want to delete this apple variety?')) return;
      try {
        await axios.delete(`/api/admin/products/${id}`);
        this.loadProducts();
        this.loadStats();
        this.$emit('refresh-products');
      } catch (err) {
        alert('Failed to delete product');
      }
    },
    async updateOrderStatus(order) {
      try {
        await axios.patch(`/api/admin/orders/${order.id}/status`, {
          status: order.status
        });
        this.loadStats();
      } catch (err) {
        alert('Failed to update order status');
      }
    },
    promptTracking(order) {
      const courier = prompt('Enter Courier Company (e.g. Delhivery, BlueDart, DTDC):', 'Delhivery Express');
      if (!courier) return;
      const awb = prompt('Enter Tracking Number / AWB:', 'DEL-');
      if (!awb) return;

      axios.patch(`/api/admin/orders/${order.id}/status`, {
        status: 'dispatched',
        tracking_courier: courier,
        tracking_number: awb
      }).then(() => {
        order.tracking_courier = courier;
        order.tracking_number = awb;
        order.status = 'dispatched';
        this.loadStats();
      });
    },
    addShippingOption() {
      if (!this.editSettings.shipping_options) {
        this.editSettings.shipping_options = [];
      }
      this.editSettings.shipping_options.push({
        id: 'opt_' + Date.now(),
        name: 'Special Delivery Express',
        estimated_days: '2-4 Days',
        fee: 150,
        is_enabled: true
      });
    },
    removeShippingOption(idx) {
      if (this.editSettings.shipping_options.length <= 1) {
        alert('You must have at least one shipping option configured.');
        return;
      }
      this.editSettings.shipping_options.splice(idx, 1);
    },
    handleUpiQrUpload(e) {
      const file = e.target.files[0];
      if (file) {
        this.upiQrFile = file;
        const reader = new FileReader();
        reader.onload = (evt) => {
          this.upiQrLocalPreview = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    handleBannerFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.bannerFile = file;
        const reader = new FileReader();
        reader.onload = (evt) => {
          this.bannerLocalPreview = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    handleFarmerPhotoFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.farmerFile = file;
        const reader = new FileReader();
        reader.onload = (evt) => {
          this.farmerLocalPreview = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    removeCustomQrImage() {
      this.editSettings.upi_qr_image = '';
      this.upiQrFile = null;
      this.upiQrLocalPreview = null;
    },
    async saveSettings() {
      this.isSavingSettings = true;
      this.settingsSuccessMsg = false;
      try {
        const formData = new FormData();
        const settingsToSave = { ...this.editSettings };

        Object.keys(settingsToSave).forEach(key => {
          let val = settingsToSave[key];
          if (typeof val === 'boolean') {
            formData.append(key, val ? '1' : '0');
          } else if (typeof val === 'object' && val !== null) {
            formData.append(key, JSON.stringify(val));
          } else {
            formData.append(key, val ?? '');
          }
        });

        if (this.upiQrFile) {
          formData.append('upi_qr_file', this.upiQrFile);
        }
        if (this.bannerFile) {
          formData.append('banner_image_file', this.bannerFile);
        }
        if (this.farmerFile) {
          formData.append('farmer_photo_file', this.farmerFile);
        }

        const res = await axios.post('/api/admin/settings', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        if (res.data.success) {
          this.settingsSuccessMsg = true;
          this.upiQrFile = null;
          this.upiQrLocalPreview = null;
          this.bannerFile = null;
          this.bannerLocalPreview = null;
          this.farmerFile = null;
          this.farmerLocalPreview = null;
          this.$emit('refresh-settings', res.data.data);
          setTimeout(() => { this.settingsSuccessMsg = false; }, 4000);
        }
      } catch (err) {
        alert('Failed to save settings: ' + (err.response?.data?.message || err.message));
      } finally {
        this.isSavingSettings = false;
      }
    },
    async markInquiryRead(id) {
      try {
        await axios.patch(`/api/admin/inquiries/${id}/read`);
        this.loadInquiries();
        this.loadStats();
      } catch (err) {
        console.error(err);
      }
    },
    async deleteInquiry(id) {
      if (!confirm('Delete inquiry?')) return;
      try {
        await axios.delete(`/api/admin/inquiries/${id}`);
        this.loadInquiries();
        this.loadStats();
      } catch (err) {
        console.error(err);
      }
    },
    async logout() {
      try {
        await axios.post('/api/auth/logout');
      } catch (e) {}
      this.$emit('logged-out');
    },
    formatMoney(val) {
      return (Number(val) || 0).toLocaleString('en-IN');
    },
    formatDate(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr);
      return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short' });
    },
    cleanPhone(val) {
      return (val || '').replace(/[^0-9]/g, '');
    }
  }
}
</script>

