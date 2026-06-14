<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingContactLeadController extends Controller
{
    public function store(Request $request, Listing $listing): JsonResponse
    {
        if ($listing->status !== 'published') {
            abort(404);
        }

        $listing->loadMissing('user');

        $data = $request->validate([
            'type' => 'required|in:phone,email',
        ]);

        $type = $data['type'];
        $contactPhone = $listing->contactPhone();
        $contactEmail = $listing->user->email;

        if ($type === 'phone' && ! $contactPhone) {
            abort(404);
        }

        if ($type === 'email' && ! $contactEmail) {
            abort(404);
        }

        if (auth()->id() !== $listing->user_id) {
            $listing->increment($type === 'phone' ? 'phone_lead_count' : 'email_lead_count');
        }

        if ($type === 'phone') {
            return response()->json([
                'type' => $type,
                'value' => $contactPhone,
                'href' => 'tel:'.preg_replace('/\s+/', '', $contactPhone),
                'action_label' => 'Call now',
            ]);
        }

        return response()->json([
            'type' => $type,
            'value' => $contactEmail,
            'href' => 'mailto:'.$contactEmail,
            'action_label' => 'Send email',
        ]);
    }
}
