<?php

namespace App\Http\Controllers;

use App\Models\AgentContactLead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentContactLeadController extends Controller
{
    public function store(Request $request, User $agent): JsonResponse
    {
        if (! $agent->isAgent() || ! $agent->isApproved()) {
            abort(404);
        }

        $data = $request->validate([
            'type' => 'required|in:phone,email',
        ]);

        $type = $data['type'];

        if ($type === AgentContactLead::TYPE_PHONE && ! $agent->phone) {
            abort(404);
        }

        if ($type === AgentContactLead::TYPE_EMAIL && ! $agent->email) {
            abort(404);
        }

        AgentContactLead::create([
            'agent_id' => $agent->id,
            'type' => $type,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        if ($type === AgentContactLead::TYPE_PHONE) {
            return response()->json([
                'type' => $type,
                'value' => $agent->phone,
                'href' => 'tel:'.preg_replace('/\s+/', '', $agent->phone),
                'action_label' => 'Call now',
            ]);
        }

        return response()->json([
            'type' => $type,
            'value' => $agent->email,
            'href' => 'mailto:'.$agent->email,
            'action_label' => 'Send email',
        ]);
    }
}
