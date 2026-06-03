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

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'agency_name',
        'bio',
        'avatar_path',
        'company_logo_path',
        'operating_since_year',
        'buyers_served_estimate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_preferred' => 'boolean',
    ];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function scopeAgents(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_AGENT);
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
