<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Guard: If database already has products, skip seeder so user-added data is never overwritten on redeploys!
        if (Product::exists()) {
            return;
        }

        // 1. Create Farmer Admin User
        User::updateOrCreate(
            ['email' => 'admin@applefarm.com'],
            [
                'name' => 'Sunil Chauhan (Farmer & Owner)',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // 2. Seed Farm Settings
        $settings = [
            'farm_name' => 'NAAG VALLEY APPLES',
            'tagline' => '100% Tree-Ripened, Wax-Free Himalayan Apples Directly from Orchard to Your Door',
            'farmer_name' => 'Sunil Chauhan',
            'farmer_title' => '3rd Generation Apple Grower',
            'farmer_story' => 'Nestled at 8,500 feet altitude in the pristine Sangla & Kalpa valleys of Kinnaur (Himachal Pradesh), our orchards are nourished by pure glacial snow-melt waters and fresh Himalayan mountain breezes. For over 45 years, our family has practiced natural, pesticide-minimal orcharding with zero synthetic waxes or chemical ripening agents. Every single apple is handpicked at full tree-ripeness, graded for size & crunch, and packaged directly inside padded shock-proof boxes for nationwide fresh shipping.',
            'phone' => '+91 9816038671',
            'whatsapp' => '+8219950247',
            'email' => 'contact@kinnaurapples.com',
            'location' => 'Maraog, Chaupal, Himachal Pradesh - 172108',
            'upi_id' => 'kinnaurfarm@okhdfcbank',
            'upi_name' => 'Kinnaur Mountain Orchards',
            'upi_qr_image' => '',
            'enable_cod' => '1',
            'enable_upi' => '1',
            'enable_free_shipping' => '1',
            'free_shipping_min_order' => '1500',
            'default_shipping_fee' => '120',
            'shipping_options' => json_encode([
                [
                    'id' => 'standard',
                    'name' => 'Standard Mountain Express (Surface/Post)',
                    'estimated_days' => '4-6 Days',
                    'fee' => 120,
                    'is_enabled' => true,
                ],
                [
                    'id' => 'express',
                    'name' => 'Priority Air Cargo (Fastest Flight Dispatch)',
                    'estimated_days' => '1-2 Days',
                    'fee' => 260,
                    'is_enabled' => true,
                ],
                [
                    'id' => 'pickup',
                    'name' => 'Direct Orchard Pickup (Kalpa Farmhouse)',
                    'estimated_days' => 'Same Day',
                    'fee' => 0,
                    'is_enabled' => false,
                ],
            ]),
            'banner_badge' => '🍎 2026 Season Plucking Open: Fresh Himalayan harvest dispatched within 24 hours of plucking!',
            'banner_image' => 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?q=80&w=1600&auto=format&fit=crop',
            'farmer_photo' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop',
            'instagram' => 'https://instagram.com/kinnaur_apples',
            'orchard_elevation' => '8,500 Feet Altitude (High Snow-line Zone)',
            'quality_promise' => 'No Artificial Wax, No Cold-Storage Stale Stock, 100% Tree-Ripened Natural Sweetness Guaranteed.',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Seed Apple Varieties
        $apples = [
            [
                'name' => 'Kinnaur Royal Delicious',
                'slug' => 'kinnaur-royal-delicious',
                'tagline' => 'The King of Himalayan Apples - High Elevation Crunch & Natural Honey Flavor',
                'description' => 'Our flagship variety grown at high snow altitudes. Characterized by deep ruby-red streaks, firm crispy bite, and unmatched sweet-aromatic aroma. Zero wax coating, pure mountain goodness.',
                'category' => 'Premium Dessert Apple',
                'origin' => 'Kalpa, Kinnaur (8,500 ft elevation)',
                'grade' => 'AAA Super Export Grade (Hand-Selected)',
                'harvest_season' => 'August to November (Active Plucking)',
                'crispness' => 'Extremely Crunchy (5/5)',
                'sweetness' => 'Naturally Sweet & Aromatic (4.8/5)',
                'price_per_kg' => 190.00,
                'box_options' => [
                    ['size' => '5 Kg Box (~22-26 Premium Apples)', 'price' => 950, 'in_stock' => true],
                    ['size' => '10 Kg Family Box (~45-50 Apples)', 'price' => 1800, 'in_stock' => true],
                    ['size' => '20 Kg Master Orchard Crate', 'price' => 3400, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Himalayan Golden Delicious',
                'slug' => 'himalayan-golden-delicious',
                'tagline' => 'Silky Pale Yellow Skin with Delicate Floral Sweetness',
                'description' => 'Sun-kissed golden gems with a thin, silky skin and rich, juicy flesh. Melt-in-the-mouth texture with mild honey-like sweetness, loved by elders and kids alike.',
                'category' => 'Sweet & Mild',
                'origin' => 'Sangla Valley, Kinnaur (8,200 ft elevation)',
                'grade' => 'AAA Grade Orchard Select',
                'harvest_season' => 'September to November',
                'crispness' => 'Tender & Very Juicy (4.2/5)',
                'sweetness' => 'Mellow Honey Sweetness (5/5)',
                'price_per_kg' => 210.00,
                'box_options' => [
                    ['size' => '5 Kg Box (~22-24 Apples)', 'price' => 1050, 'in_stock' => true],
                    ['size' => '10 Kg Family Box (~45 Apples)', 'price' => 1950, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mountain Red Delicious',
                'slug' => 'mountain-red-delicious',
                'tagline' => 'Classic Deep Crimson, Firm Flesh & Heart Shape',
                'description' => 'The quintessential mountain apple with bold crimson red blush, prominent five crowns, and dense crisp bite. Rich in natural antioxidants and anthocyanins.',
                'category' => 'Classic Mountain Apple',
                'origin' => 'Shimla Upper Belt (7,400 ft elevation)',
                'grade' => 'A+ Grade Premium Table Fruit',
                'harvest_season' => 'August to October',
                'crispness' => 'Crisp & Solid Crunch (4.6/5)',
                'sweetness' => 'Balanced Sweet & Refreshing (4.3/5)',
                'price_per_kg' => 175.00,
                'box_options' => [
                    ['size' => '5 Kg Box (~22-26 Apples)', 'price' => 875, 'in_stock' => true],
                    ['size' => '10 Kg Family Box (~48 Apples)', 'price' => 1650, 'in_stock' => true],
                    ['size' => '20 Kg Bulk Crate', 'price' => 3100, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1579613832125-5d34a13ffe0a?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Royal Gala Orchard Select',
                'slug' => 'royal-gala-orchard-select',
                'tagline' => 'Early Harvest Special - Thin Skin, Sweet & Refreshingly Light',
                'description' => 'Dappled pinkish-orange stripes with fine-grained ivory flesh. Exceptionally crisp and fragrant, popular for fresh snacking, school fruit boxes, and breakfast salads.',
                'category' => 'Snacking & Kids Special',
                'origin' => 'Kotgarh Valley, Himachal (6,900 ft elevation)',
                'grade' => 'AAA Grade Hand-Picked',
                'harvest_season' => 'July to September',
                'crispness' => 'Brisk & Crisp (4.7/5)',
                'sweetness' => 'Delicately Sweet & Floral (4.5/5)',
                'price_per_kg' => 220.00,
                'box_options' => [
                    ['size' => '5 Kg Box (~24 Apples)', 'price' => 1100, 'in_stock' => true],
                    ['size' => '10 Kg Box (~48 Apples)', 'price' => 2050, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Zesty Granny Smith Green Apple',
                'slug' => 'zesty-granny-smith-green-apple',
                'tagline' => 'Sharp Tart Zing & Super Crunchy Bite - Ideal for Salads & Diabetics',
                'description' => 'Bright emerald green apple with sharp, mouth-watering tartness and firm crunch. Lower glycemic index, excellent for juices, weight management, and apple pies.',
                'category' => 'Tart & Baking Special',
                'origin' => 'Manali High Altitude Orchards (7,200 ft)',
                'grade' => 'AAA Grade Export Firm',
                'harvest_season' => 'September to November',
                'crispness' => 'Super Firm & Snappy (5/5)',
                'sweetness' => 'Pleasantly Tangy & Tart (3/5)',
                'price_per_kg' => 230.00,
                'box_options' => [
                    ['size' => '5 Kg Box (~20-22 Apples)', 'price' => 1150, 'in_stock' => true],
                    ['size' => '10 Kg Box (~42 Apples)', 'price' => 2150, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1590005354167-6da97870c757?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Kinnaur Super Red (Dark Spark)',
                'slug' => 'kinnaur-super-red-dark-spark',
                'tagline' => 'Deep Mahogany Red - Ultra High Elevation Rarity',
                'description' => 'Special high-chill variety grown near Tibetan border ridges above 9,000 ft. Extremely dense flesh with rich natural sugars developed by steep day-to-night temperature variations.',
                'category' => 'Limited Reserve / Gourmet',
                'origin' => 'Pooh & Moorang, Upper Kinnaur (9,100 ft)',
                'grade' => 'Reserve Collector Grade',
                'harvest_season' => 'October to November (Limited Stock)',
                'crispness' => 'Dense & Highly Crunchy (5/5)',
                'sweetness' => 'Intense Sugary Mountain Richness (5/5)',
                'price_per_kg' => 260.00,
                'box_options' => [
                    ['size' => '5 Kg Luxury Gift Box (~20 Apples)', 'price' => 1350, 'in_stock' => true],
                    ['size' => '10 Kg Family Box (~40 Apples)', 'price' => 2500, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($apples as $apple) {
            $apple['product_type'] = 'apple';
            Product::updateOrCreate(['slug' => $apple['slug']], $apple);
        }

        // 3.5 Seed Other Orchard Products
        $otherProducts = [
            [
                'name' => 'Pure Kinnaur Wild Forest Honey',
                'slug' => 'pure-kinnaur-wild-forest-honey',
                'tagline' => 'Raw, Unpasteurized Multi-Flora Honey from High Altitude Forest Blooms',
                'description' => 'Collected naturally by mountain bees from wild apple blossoms, acacia, and alpine wildflowers. 100% pure, unprocessed and rich in natural pollen.',
                'category' => 'Honey & Preserves',
                'product_type' => 'other',
                'origin' => 'Sangla Valley, Kinnaur (8,200 ft)',
                'grade' => 'A-Grade 100% Pure Raw Honey',
                'harvest_season' => 'Autumn Harvest',
                'price_per_kg' => 900.00,
                'box_options' => [
                    ['size' => '500g Pure Glass Jar', 'price' => 480, 'in_stock' => true],
                    ['size' => '1 Kg Farmhouse Glass Jar', 'price' => 890, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Raw Himalayan Apple Cider Vinegar',
                'slug' => 'raw-himalayan-apple-cider-vinegar',
                'tagline' => 'Naturally Fermented with Active Mother from 100% Tree-Ripened Apples',
                'description' => 'Traditional oak-barrel slow fermented vinegar made strictly from pristine mountain apples. Unfiltered, unpasteurized, and free from synthetic preservatives.',
                'category' => 'Juices & Vinegars',
                'product_type' => 'other',
                'origin' => 'Kalpa Valley, Kinnaur',
                'grade' => 'Raw & Unfiltered with Live Mother',
                'harvest_season' => 'Year-Round Fermentation',
                'price_per_kg' => 600.00,
                'box_options' => [
                    ['size' => '500ml Glass Bottle', 'price' => 340, 'in_stock' => true],
                    ['size' => '1 Litre Duo Pack (2 x 500ml)', 'price' => 620, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Sun-Dried Natural Apple Crisps',
                'slug' => 'sun-dried-natural-apple-crisps',
                'tagline' => '100% Natural Dried Apple Rings • No Added Sugar or Sulfur',
                'description' => 'Sliced Royal Delicious apples dried gently under high Himalayan mountain sunshine. Chewy, naturally sweet, packed with natural dietary fiber and antioxidants.',
                'category' => 'Dry Fruits & Snacks',
                'product_type' => 'other',
                'origin' => 'Kalpa, Kinnaur',
                'grade' => 'Sun-Dried Grade A (Sulfur-Free)',
                'harvest_season' => 'Fresh Sun-Dried Harvest',
                'price_per_kg' => 1100.00,
                'box_options' => [
                    ['size' => '250g Zip Pouch', 'price' => 280, 'in_stock' => true],
                    ['size' => '500g Eco Pantry Pack', 'price' => 520, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1596591606975-97ee5cef3a1e?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'Kinnaur Snow-Line Walnuts (Kagzi)',
                'slug' => 'kinnaur-snow-line-walnuts-kagzi',
                'tagline' => 'Thin Paper-Shell Mountain Walnuts with High Natural Oil Content',
                'description' => 'Naturally cracked with hand pressure. Grown alongside apple terraces at 8,500 ft altitude. Crisp, aromatic, rich in Omega-3 with zero chemical bleaching.',
                'category' => 'Nuts & Dry Fruits',
                'product_type' => 'other',
                'origin' => 'Upper Kinnaur Terraces',
                'grade' => 'Export Kagzi (Soft Paper Shell)',
                'harvest_season' => 'October Harvest',
                'price_per_kg' => 750.00,
                'box_options' => [
                    ['size' => '1 Kg Whole In-Shell Bag', 'price' => 750, 'in_stock' => true],
                    ['size' => '2 Kg Orchard Box', 'price' => 1400, 'in_stock' => true],
                ],
                'image_url' => 'https://images.unsplash.com/photo-1508736793122-f516e3ba5569?q=80&w=800&auto=format&fit=crop',
                'stock_status' => 'in_stock',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 13,
            ],
        ];

        foreach ($otherProducts as $item) {
            Product::updateOrCreate(['slug' => $item['slug']], $item);
        }

        // 4. Seed Initial Orders so Farmer can see sample workflow
        if (Order::count() === 0) {
            Order::create([
                'order_number' => 'APL-2026-9041',
                'customer_name' => 'Vikram Malhotra',
                'customer_phone' => '9820198201',
                'customer_email' => 'vikram@gmail.com',
                'shipping_address' => 'Flat 402, Oakwood Heights, Powai',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400076',
                'order_notes' => 'Please deliver on weekend if possible.',
                'items' => [
                    [
                        'product_id' => 1,
                        'product_name' => 'Kinnaur Royal Delicious',
                        'box_size' => '10 Kg Family Box (~45-50 Apples)',
                        'price' => 1800,
                        'quantity' => 1,
                        'total' => 1800,
                    ],
                ],
                'subtotal' => 1800.00,
                'shipping_fee' => 0.00,
                'total_amount' => 1800.00,
                'payment_method' => 'upi_whatsapp',
                'payment_status' => 'paid',
                'status' => 'dispatched',
                'tracking_courier' => 'Delhivery Express',
                'tracking_number' => 'DEL-HP-84910294',
                'admin_notes' => 'Dispatched on 4th Nov via refrigerated mountain express van.',
            ]);

            Order::create([
                'order_number' => 'APL-2026-9042',
                'customer_name' => 'Ananya Sharma',
                'customer_phone' => '9711097110',
                'customer_email' => 'ananya.s@outlook.com',
                'shipping_address' => 'B-12, Sector 14',
                'city' => 'Chandigarh',
                'state' => 'Punjab',
                'pincode' => '160014',
                'order_notes' => 'Call before coming.',
                'items' => [
                    [
                        'product_id' => 2,
                        'product_name' => 'Himalayan Golden Delicious',
                        'box_size' => '5 Kg Box (~22-24 Apples)',
                        'price' => 1050,
                        'quantity' => 2,
                        'total' => 2100,
                    ],
                ],
                'subtotal' => 2100.00,
                'shipping_fee' => 0.00,
                'total_amount' => 2100.00,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'packed',
                'tracking_courier' => null,
                'tracking_number' => null,
                'admin_notes' => 'Packed in 5-ply shock absorbant mesh box.',
            ]);
        }

        // 5. Seed Inquiry
        if (Inquiry::count() === 0) {
            Inquiry::create([
                'name' => 'Oberoi Organic Stores (Rajesh Sethi)',
                'phone' => '9810012345',
                'email' => 'rajesh@oberoiorganic.in',
                'inquiry_type' => 'bulk_order',
                'message' => 'Hello Sunil ji, we run 4 organic grocery outlets in Delhi NCR. We want to procure 500 kg weekly of Kinnaur Royal Delicious and Super Red. Please share wholesale crates pricing and dispatch frequency.',
                'is_read' => false,
            ]);
        }
    }
}
