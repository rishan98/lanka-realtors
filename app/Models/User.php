<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_AGENT = 'agent';

    public const ROLE_OWNER = 'owner';

    public const APPROVAL_PENDING = 'pending';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'approval_status',
        'phone',
        'agency_name',
        'bio',
        'avatar_path',
        'cover_path',
        'company_logo_path',
        'operating_since_year',
        'buyers_served_estimate',
        'rating',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_preferred' => 'boolean',
        'rating' => 'float',
    ];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function contactLeads()
    {
        return $this->hasMany(AgentContactLead::class, 'agent_id');
    }

    public function reviews()
    {
        return $this->hasMany(AgentReview::class, 'agent_id');
    }

    public function averageReviewRating(): ?float
    {
        $average = $this->reviews()->approved()->avg('rating');

        return $average !== null ? round((float) $average, 1) : null;
    }

    public function approvedReviewCount(): int
    {
        return $this->reviews()->approved()->count();
    }

    public function pendingReviewCount(): int
    {
        return $this->reviews()->pending()->count();
    }

    public function scopeAgents(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_AGENT);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_APPROVED);
    }

    public function scopePendingApproval(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_PENDING);
    }

    public function scopeWithPublishedListingCounts(Builder $query): Builder
    {
        return $query->withCount([
            'listings as published_sale_count' => function ($q) {
                $q->where('status', 'published')->where('listing_kind', 'sale');
            },
            'listings as published_rent_count' => function ($q) {
                $q->where('status', 'published')->where('listing_kind', 'rental');
            },
        ]);
    }

    public function scopeOrderedByRating(Builder $query): Builder
    {
        return $query
            ->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->orderByDesc('rating')
            ->orderBy('name');
    }

    public function hasRating(): bool
    {
        return $this->rating !== null && (float) $this->rating > 0;
    }

    public function formattedRating(): ?string
    {
        return $this->hasRating()
            ? number_format((float) $this->rating, 1)
            : null;
    }

    public function requiresApproval(): bool
    {
        return $this->isAgent();
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function isPendingApproval(): bool
    {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->approval_status === self::APPROVAL_REJECTED;
    }

    public function approvalStatusLabel(): string
    {
        return config('users.approval_statuses.'.$this->approval_status, ucfirst($this->approval_status));
    }

    public function loginBlockedMessage(): string
    {
        if ($this->isRejected()) {
            return 'Your account registration was not approved. Please contact support if you believe this is an error.';
        }

        return 'Your account is pending admin approval. You will be able to log in once an administrator approves your registration.';
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAgent(): bool
    {
        return $this->role === self::ROLE_AGENT;
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function roleLabel(): string
    {
        return config('users.roles.'.$this->role, ucfirst($this->role));
    }

    public function dashboardRoute(): string
    {
        switch ($this->role) {
            case self::ROLE_ADMIN:
                return route('admin.dashboard');
            case self::ROLE_OWNER:
                return route('owner.dashboard');
            default:
                return route('agent.dashboard');
        }
    }

    public function postListingRoute(): string
    {
        if ($this->isOwner()) {
            return route('owner.listings.create');
        }

        return route('agent.listings.create');
    }

    public function avatarUrl(): string
    {
        if ($this->avatar_path) {
            return asset('storage/'.$this->avatar_path);
        }

        $name = urlencode($this->name ?: 'User');

        return 'https://ui-avatars.com/api/?name='.$name.'&background=0b1b33&color=c9a227&size=128&bold=true';
    }

    public function hasCover(): bool
    {
        return $this->coverDisplayUrl() !== null;
    }

    public function coverUrl(): ?string
    {
        if ($this->cover_path) {
            return asset('storage/'.$this->cover_path);
        }

        return null;
    }

    public function coverDisplayUrl(): ?string
    {
        if ($this->cover_path) {
            return asset('storage/'.$this->cover_path);
        }

        if ($this->avatar_path) {
            return asset('storage/'.$this->avatar_path);
        }

        return null;
    }

    public function usesAvatarAsCover(): bool
    {
        return empty($this->cover_path) && ! empty($this->avatar_path);
    }

    public function companyLogoUrl(): ?string
    {
        if ($this->company_logo_path) {
            return asset('storage/'.$this->company_logo_path);
        }

        return null;
    }

    public function companyDisplayName(): string
    {
        return $this->agency_name ?: 'Independent';
    }
}
