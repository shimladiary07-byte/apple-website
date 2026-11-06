<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaymentAndShippingTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation_with_cod_when_enabled(): void
    {
        Setting::set('enable_cod', '1');
        Setting::set('default_shipping_fee', '120');
        Setting::set('enable_free_shipping', '0');

        $payload = [
            'customer_name' => 'Amit Sharma',
            'customer_phone' => '9876543210',
            'customer_email' => 'amit@example.com',
            'shipping_address' => 'Flat 402, Green Valley Apartments',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'pincode' => '171001',
            'payment_method' => 'cod',
            'shipping_method' => 'Standard Mountain Express',
            'items' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Kinnaur Royal Delicious',
                    'box_size' => '5 Kg Box',
                    'price' => 950,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'order' => [
                        'customer_name' => 'Amit Sharma',
                        'payment_method' => 'cod',
                        'shipping_method' => 'Standard Mountain Express',
                        'shipping_fee' => 120,
                        'subtotal' => 950,
                        'total_amount' => 1070,
                    ],
                ],
            ]);
    }

    public function test_order_creation_with_cod_fails_when_disabled(): void
    {
        Setting::set('enable_cod', '0');

        $payload = [
            'customer_name' => 'Amit Sharma',
            'customer_phone' => '9876543210',
            'shipping_address' => 'Flat 402, Green Valley',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'pincode' => '110001',
            'payment_method' => 'cod',
            'items' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Kinnaur Royal Delicious',
                    'box_size' => '5 Kg Box',
                    'price' => 950,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_order_creation_with_upi_and_custom_shipping_method(): void
    {
        Setting::set('enable_upi', '1');
        Setting::set('shipping_options', json_encode([
            [
                'id' => 'express',
                'name' => 'Priority Air Cargo',
                'fee' => 260,
                'is_enabled' => true,
            ],
        ]));

        $payload = [
            'customer_name' => 'Priya Singh',
            'customer_phone' => '9812345678',
            'shipping_address' => 'House 12, Sector 15',
            'city' => 'Chandigarh',
            'state' => 'Punjab',
            'pincode' => '160015',
            'payment_method' => 'upi_whatsapp',
            'shipping_method' => 'Priority Air Cargo',
            'items' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Kinnaur Royal Delicious',
                    'box_size' => '5 Kg Box',
                    'price' => 950,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'order' => [
                        'customer_name' => 'Priya Singh',
                        'payment_method' => 'upi_whatsapp',
                        'shipping_method' => 'Priority Air Cargo',
                        'shipping_fee' => 260,
                        'total_amount' => 1210,
                    ],
                ],
            ]);
    }

    public function test_admin_settings_update_cod_and_shipping(): void
    {
        $payload = [
            'enable_cod' => false,
            'default_shipping_fee' => 150,
            'free_shipping_min_order' => 2000,
            'shipping_options' => [
                [
                    'id' => 'standard',
                    'name' => 'Surface Express',
                    'fee' => 150,
                    'is_enabled' => true,
                ],
            ],
        ];

        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->postJson('/api/admin/settings', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals('0', Setting::get('enable_cod'));
        $this->assertEquals('150', Setting::get('default_shipping_fee'));
        $this->assertEquals('2000', Setting::get('free_shipping_min_order'));
    }

    public function test_non_admin_cannot_access_admin_endpoints(): void
    {
        $customer = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($customer)->getJson('/api/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_user_can_register_successfully(): void
    {
        $payload = [
            'name' => 'New Apple Farmer',
            'email' => 'newfarmer@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'user' => [
                    'name' => 'New Apple Farmer',
                    'email' => 'newfarmer@example.com',
                ],
            ]);

        $this->assertAuthenticated();
    }

    public function test_logged_in_user_can_view_their_orders(): void
    {
        $user = User::factory()->create([
            'email' => 'customer_orders@example.com',
            'is_admin' => false,
        ]);

        Order::create([
            'order_number' => 'APL-2026-TEST1',
            'customer_name' => $user->name,
            'customer_phone' => '9876543210',
            'customer_email' => $user->email,
            'shipping_address' => 'Mall Road',
            'city' => 'Shimla',
            'state' => 'Himachal Pradesh',
            'pincode' => '171001',
            'items' => [['product_id' => 1, 'product_name' => 'Royal Delicious', 'box_size' => '5 Kg', 'price' => 1000, 'quantity' => 1]],
            'subtotal' => 1000,
            'shipping_fee' => 0,
            'total_amount' => 1000,
            'payment_method' => 'cod',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)->getJson('/api/orders/my-orders');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.order_number', 'APL-2026-TEST1')
            ->assertJsonPath('data.0.status', 'confirmed');
    }
}
