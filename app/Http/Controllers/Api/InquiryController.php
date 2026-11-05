<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $inquiries,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'inquiry_type' => 'required|string',
            'message' => 'required|string',
        ]);

        $inquiry = Inquiry::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your inquiry has been sent to the farmer.',
            'data' => $inquiry,
        ], 201);
    }

    public function markRead($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->is_read = true;
        $inquiry->save();

        return response()->json([
            'success' => true,
            'message' => 'Inquiry marked as read.',
        ]);
    }

    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inquiry deleted.',
        ]);
    }
}
