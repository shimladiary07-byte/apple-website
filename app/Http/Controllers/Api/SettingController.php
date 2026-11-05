<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::allKeyValues();

        // Defaults for newly added options
        if (! isset($settings['enable_cod'])) {
            $settings['enable_cod'] = '1';
        }
        if (! isset($settings['enable_upi'])) {
            $settings['enable_upi'] = '1';
        }
        if (! isset($settings['enable_free_shipping'])) {
            $settings['enable_free_shipping'] = '1';
        }
        if (! isset($settings['shipping_options'])) {
            $settings['shipping_options'] = json_encode([
                [
                    'id' => 'standard',
                    'name' => 'Standard Mountain Express (Road/Speed Post)',
                    'estimated_days' => '4-6 Days',
                    'fee' => (float) ($settings['default_shipping_fee'] ?? 120),
                    'is_enabled' => true,
                ],
                [
                    'id' => 'express',
                    'name' => 'Priority Air Cargo (Shimla/Kullu Air Dispatch)',
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
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'banner_image_file', 'farmer_photo_file', 'upi_qr_file']);

        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            } elseif (is_array($value)) {
                $value = json_encode($value);
            }
            Setting::set($key, $value);
        }

        // Handle uploaded files
        if ($request->hasFile('banner_image_file')) {
            $file = $request->file('banner_image_file');
            $filename = 'banner_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::set('banner_image', '/uploads/'.$filename);
        }

        if ($request->hasFile('farmer_photo_file')) {
            $file = $request->file('farmer_photo_file');
            $filename = 'farmer_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::set('farmer_photo', '/uploads/'.$filename);
        }

        if ($request->hasFile('upi_qr_file')) {
            $file = $request->file('upi_qr_file');
            $filename = 'upi_qr_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            Setting::set('upi_qr_image', '/uploads/'.$filename);
        }

        return response()->json([
            'success' => true,
            'message' => 'Farm settings saved successfully!',
            'data' => Setting::allKeyValues(),
        ]);
    }
}
