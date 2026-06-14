<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentContactLead extends Model
{
    public const TYPE_PHONE = 'phone';

    public const TYPE_EMAIL = 'email';

    protected $fillable = [
        'agent_id',
        'type',
        'ip_address',
        'user_agent',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
