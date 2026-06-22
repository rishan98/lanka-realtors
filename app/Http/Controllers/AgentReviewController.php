<?php

namespace App\Http\Controllers;

use App\Models\AgentReview;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentReviewController extends Controller
{
    public function store(Request $request, User $agent): JsonResponse
    {
        if (! $agent->isAgent() || ! $agent->isApproved()) {
            abort(404);
        }

        $data = $request->validate([
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if (auth()->check() && auth()->id() === $agent->id) {
            return response()->json([
                'message' => 'You cannot review your own portfolio.',
            ], 422);
        }

        $review = AgentReview::create([
            'agent_id' => $agent->id,
            'email' => $data['email'],
            'message' => $data['message'],
            'rating' => $data['rating'],
            'status' => AgentReview::STATUS_PENDING,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Thank you for your review. It will appear on this portfolio after an administrator approves it.',
            'pending' => true,
        ], 201);
    }
}
