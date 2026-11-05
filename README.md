# 🍎 Fresh Apple Farm - Direct-to-Consumer E-Commerce Platform

A clean, modern, and completely dynamic Apple Farm E-Commerce web application built specifically for apple farmers to showcase tree-ripened apple varieties, take customer orders with shock-proof box packing, and manage everything from a dedicated Farmer Admin Panel.

---

## 🌟 Tech Stack
- **Backend**: Laravel 11 (PHP 8.4) with Eloquent ORM & SQLite out-of-the-box (zero DB configuration needed).
- **Frontend**: Vue.js 3 + Vite 6 + Tailwind CSS v4.
- **Architecture**: Single Page Application (SPA) with reactive component architecture and REST API backend.

---

## 🚀 Key Features

### 🍏 Customer Storefront
1. **Clean & Fresh UI**:
   - Announcement banner ribbon (Active harvest season notice).
   - High-elevation mountain orchard hero showcase (8,500 ft Kinnaur Valley).
   - "Direct from Farmer • No Middlemen • 100% Wax-Free" trust badges.

2. **Apple Varieties Catalog**:
   - Filter by variety category (Premium Dessert, Sweet & Mild, Tart / Baking, Gourmet Reserve) or live search.
   - Rich product cards featuring crispness meters, sweetness meters, origin altitude, and multiple box sizes (e.g., 5kg, 10kg, 20kg wooden crates).
   - Detailed modal view with taste profile, harvesting season, and tree-ripeness assurance.

3. **Smooth Box Cart & Direct Checkout**:
   - Box cart drawer with dynamic quantity selector and interactive **Free Shipping progress bar** (Free shipping on orders above ₹1,500).
   - Clean checkout form collecting customer name, phone/WhatsApp, delivery address, city, state, pin code, and order notes.
   - Payment choices: **Cash on Delivery (COD)** or **Direct UPI / WhatsApp Payment**.
   - Instant WhatsApp Order Confirmation button that opens a pre-filled WhatsApp chat with the farmer!

4. **Live Order Tracking**:
   - Customers can enter their phone number or Order ID (`APL-2026-XXXXX`) to track order progress on a visual timeline:
     - Placed ➔ Confirmed ➔ Tree-Plucked & Mesh Box Packed ➔ Dispatched (with Courier & AWB) ➔ Delivered.

5. **Wholesale & Bulk Orders**:
   - Dedicated inquiry modal for retail stores, hotels, wedding gifts, and corporate festival orders.

---

### 🔐 Farmer Admin Panel
Access the Admin Portal by clicking the key/lock icon in the top navigation or in the footer.
- **Admin Email**: `admin@applefarm.com`
- **Admin Password**: `admin123` *(Auto-fill button provided on login modal!)*

**Admin Capabilities**:
1. **📊 Dashboard Overview**:
   - Real-time revenue metrics, total orders, pending verification queue, dispatched & delivered counts, and recent orders table.
2. **🍎 Apple Varieties Manager**:
   - Add new apple varieties with photo URL, description, category, origin, harvest season, grade, crispness & sweetness indicators.
   - Configure custom box options (e.g., 5kg, 10kg, 20kg) with individual prices.
   - Quick 1-click toggle for active status and stock availability (In Stock / Pre-Order / Out of Season).
   - Edit or delete varieties anytime.
3. **📦 Orders Fulfillment Manager**:
   - View complete customer orders with delivery address, items breakdown, and phone number.
   - 1-click button to call or message the customer directly on WhatsApp.
   - Update order status: `Pending` ➔ `Confirmed` ➔ `Packed` ➔ `Dispatched` ➔ `Delivered` ➔ `Cancelled`.
   - Attach courier name (e.g. Delhivery, Bluedart, DTDC) and tracking AWB number.
4. **⚙️ Farm & Website Settings (100% Dynamic)**:
   - Edit Farm Name, Tagline, Elevation.
   - Edit Farmer Name, Title, and Farmer Story / Bio.
   - Update Phone, WhatsApp, and Contact Email.
   - Update Farmer UPI ID for direct payments.
   - Adjust Free Shipping Minimum Order threshold (₹) and standard delivery charge.
   - Change top announcement banner text and photo URLs.
5. **✉️ Bulk & Wholesale Leads**:
   - Review incoming wholesale inquiries and corporate orders, mark as read, or delete.

---

## 🛠️ How to Run the Project Locally

Navigate to the project folder:
```bash
cd /home/techab/.gemini/antigravity/scratch/apple-farm-shop
```

### 1. Run the Laravel Server:
```bash
php artisan serve
```
The application will be live at: `http://127.0.0.1:8000`

### 2. Run the Vite Dev Server (for live frontend editing):
```bash
npm run dev
```
*(Note: Production assets are already compiled in `public/build/`, so `php artisan serve` works out of the box right away!)*

---

## 📁 Project Structure
```
apple-farm-shop/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── AuthController.php        # Admin authentication
│   │   ├── DashboardController.php   # Revenue & order analytics
│   │   ├── InquiryController.php     # Bulk inquiries
│   │   ├── OrderController.php       # Orders checkout & tracking
│   │   ├── ProductController.php     # Apple varieties CRUD
│   │   └── SettingController.php     # Dynamic farm settings
│   └── Models/
│       ├── Inquiry.php
│       ├── Order.php
│       ├── Product.php
│       ├── Setting.php
│       └── User.php
├── database/
│   ├── migrations/                   # Products, Orders, Settings, Inquiries
│   ├── seeders/DatabaseSeeder.php    # Seeded authentic Kinnaur varieties & orders
│   └── database.sqlite               # Local SQLite database
├── resources/
│   ├── css/app.css                   # Tailwind CSS v4 setup
│   ├── js/
│   │   ├── App.vue                   # Root Vue SPA application
│   │   ├── app.js                    # Vue mount point
│   │   └── components/
│   │       ├── Navbar.vue
│   │       ├── HeroBanner.vue
│   │       ├── ProductCard.vue
│   │       ├── ProductDetailModal.vue
│   │       ├── CartDrawer.vue
│   │       ├── CheckoutModal.vue
│   │       ├── OrderSuccessModal.vue
│   │       ├── TrackOrderModal.vue
│   │       ├── OrchardStory.vue
│   │       ├── BulkInquiryModal.vue
│   │       ├── FooterSection.vue
│   │       └── admin/
│   │           ├── AdminDashboard.vue
│   │           ├── AdminLoginModal.vue
│   │           └── ProductFormModal.vue
│   └── views/app.blade.php           # SPA Blade container
└── routes/
    └── web.php                       # API endpoints & SPA catch-all
```
