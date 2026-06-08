<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'is_active',
        'sort_order',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function (City $city) {
            if (empty($city->slug) && ! empty($city->name)) {
                $city->slug = static::uniqueSlug($city->name, $city->parent_id, $city->id);
            }
        });

        static::saved(function (City $city) {
            if ($city->wasChanged('name')) {
                $city->listings()->update(['city' => $city->listingLabel()]);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $parentId = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);

        if ($parentId) {
            $parentSlug = static::query()->whereKey($parentId)->value('slug');
            if ($parentSlug) {
                $base = $parentSlug.'-'.$base;
            }
        }

        $slug = $base;
        $i = 1;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function parent()
    {
        return $this->belongsTo(City::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(City::class, 'parent_id');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function isDistrict(): bool
    {
        return $this->parent_id === null;
    }

    public function isArea(): bool
    {
        return $this->parent_id !== null;
    }

    public function listingLabel(): string
    {
        if ($this->isArea()) {
            $parent = $this->relationLoaded('parent') ? $this->parent : $this->parent()->first();
            if ($parent) {
                return $this->name.', '.$parent->name;
            }
        }

        return $this->name;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeDistricts($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeAreas($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeSelectableForListings($query)
    {
        return $query->areas()->active();
    }

    public static function districtsForForms(?int $includeCityId = null)
    {
        return static::query()
            ->districts()
            ->where(function ($query) use ($includeCityId) {
                $query->where('is_active', true);

                if ($includeCityId) {
                    $query->orWhereHas('children', fn ($child) => $child->where('id', $includeCityId));
                }
            })
            ->ordered()
            ->with(['children' => function ($query) use ($includeCityId) {
                $query->where(function ($inner) use ($includeCityId) {
                    $inner->where('is_active', true);

                    if ($includeCityId) {
                        $inner->orWhere('id', $includeCityId);
                    }
                })->ordered();
            }])
            ->get()
            ->filter(fn (City $district) => $district->children->isNotEmpty());
    }

    public function publishedListingCount(): int
    {
        return $this->listings()->where('status', 'published')->count();
    }

    /**
     * @return int[]
     */
    public function filterCityIds(): array
    {
        if ($this->isDistrict()) {
            $ids = $this->children()->pluck('id')->all();
            $ids[] = $this->id;

            return array_values(array_unique($ids));
        }

        return [$this->id];
    }

    public static function resolveFilter(?string $value): ?self
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        return static::query()
            ->where(function ($query) use ($value) {
                $query->where('slug', $value)
                    ->orWhere('name', $value);
            })
            ->first();
    }
}
