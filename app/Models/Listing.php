<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'contact_number',
        'price',
        'currency',
        'listing_kind',
        'property_subtype',
        'city_id',
        'city',
        'area',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'floors',
        'furnishing_status',
        'parking_available',
        'land_size',
        'land_size_unit',
        'built_area_sqft',
        'advance_payment_months',
        'deposit_months',
        'short_term_available',
        'bills_included',
        'featured_image',
        'images',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
        'built_area_sqft' => 'integer',
        'floors' => 'integer',
        'parking_available' => 'boolean',
        'advance_payment_months' => 'integer',
        'deposit_months' => 'integer',
        'short_term_available' => 'boolean',
        'bills_included' => 'boolean',
        'images' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function (Listing $listing) {
            if (empty($listing->slug)) {
                $listing->slug = static::uniqueSlug($listing->title);
            }
        });

        static::saving(function (Listing $listing) {
            if ($listing->city_id) {
                $city = City::with('parent')->find($listing->city_id);
                if ($city) {
                    $listing->city = $city->listingLabel();

                    if ($listing->latitude === null && $city->latitude !== null) {
                        $listing->latitude = $city->latitude;
                    }

                    if ($listing->longitude === null && $city->longitude !== null) {
                        $listing->longitude = $city->longitude;
                    }
                }
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public static function similarTo(self $listing, int $limit = 4)
    {
        $results = collect();
        $excludeIds = [$listing->id];

        $tiers = [
            function ($query) use ($listing) {
                $query->where('listing_kind', $listing->listing_kind);

                if ($listing->city_id) {
                    $query->where('city_id', $listing->city_id);
                } elseif ($listing->city) {
                    $query->where('city', $listing->city);
                }

                if ($listing->property_subtype) {
                    $query->where('property_subtype', $listing->property_subtype);
                }
            },
            function ($query) use ($listing) {
                $query->where('listing_kind', $listing->listing_kind);

                if ($listing->city_id) {
                    $query->where('city_id', $listing->city_id);
                } elseif ($listing->city) {
                    $query->where('city', $listing->city);
                }
            },
            function ($query) use ($listing) {
                $query->where('listing_kind', $listing->listing_kind);
            },
        ];

        foreach ($tiers as $applyTier) {
            if ($results->count() >= $limit) {
                break;
            }

            $need = $limit - $results->count();

            $batch = static::query()
                ->published()
                ->whereNotIn('id', $excludeIds)
                ->tap($applyTier)
                ->latest()
                ->take($need)
                ->get();

            $results = $results->merge($batch);
            $excludeIds = array_merge($excludeIds, $batch->pluck('id')->all());
        }

        return $results->take($limit)->values();
    }

    public function subtypeLabel(): string
    {
        $kinds = config('listing.kinds', []);
        $kind = $this->listing_kind;
        if (! isset($kinds[$kind]['subtypes'][$this->property_subtype])) {
            return ucfirst(str_replace('_', ' ', $this->property_subtype));
        }

        return $kinds[$kind]['subtypes'][$this->property_subtype];
    }

    public function kindLabel(): string
    {
        return config('listing.kinds.'.$this->listing_kind.'.label', $this->listing_kind);
    }

    public function furnishingLabel(): ?string
    {
        if (! $this->furnishing_status) {
            return null;
        }

        return config('listing.furnishing_options.'.$this->furnishing_status, ucfirst(str_replace('_', ' ', $this->furnishing_status)));
    }

    public function landSizeLabel(): ?string
    {
        if (! $this->land_size) {
            return null;
        }

        $unit = $this->land_size_unit
            ? config('listing.land_size_units.'.$this->land_size_unit, $this->land_size_unit)
            : null;

        return trim($this->land_size.($unit ? ' '.$unit : ''));
    }

    public static function defaultImageUrl(): string
    {
        return asset('images/no-property-image.svg');
    }

    public function allImagePaths(): array
    {
        $paths = $this->images ?? [];
        if ($this->featured_image && ! in_array($this->featured_image, $paths, true)) {
            $paths[] = $this->featured_image;
        }

        return $paths;
    }

    public function resolvedImagePaths(): array
    {
        return array_values(array_filter($this->allImagePaths(), function (string $path) {
            return Storage::disk('public')->exists($path);
        }));
    }

    public function hasImages(): bool
    {
        return count($this->resolvedImagePaths()) > 0;
    }

    public function imageUrls(): array
    {
        return array_map(
            fn (string $path) => asset('storage/'.$path),
            $this->resolvedImagePaths()
        );
    }

    public function imageUrl(): string
    {
        $urls = $this->imageUrls();

        return $urls[0] ?? self::defaultImageUrl();
    }

    public function cardImageClass(): string
    {
        return $this->hasImages() ? '' : 'property-card__image--placeholder';
    }

    public function imageCount(): int
    {
        return count($this->resolvedImagePaths());
    }

    public function cardStatusLabel(): ?string
    {
        if ($furnishing = $this->furnishingLabel()) {
            return $furnishing;
        }

        return $this->kindLabel();
    }

    public function cardMetaLabel(): string
    {
        $parts = [];

        if ($this->bedrooms !== null) {
            $parts[] = ($this->bedrooms >= 5 ? '5+' : $this->bedrooms).' BHK';
        }

        $parts[] = $this->subtypeLabel();

        return implode(' ', $parts);
    }

    public function cardAreaLabel(): ?string
    {
        if ($this->built_area_sqft) {
            return number_format($this->built_area_sqft).' sq.ft.';
        }

        if ($landSize = $this->landSizeLabel()) {
            return $landSize;
        }

        return null;
    }

    public function priceShortLabel(): string
    {
        return $this->formattedPriceDisplay();
    }

    public function investPriceFootnote(): string
    {
        if ($this->property_subtype === 'land') {
            return $this->landSizeLabel() ?: 'Land investment';
        }

        if ($perSqft = $this->pricePerSqftLabel()) {
            return $perSqft;
        }

        return $this->kindLabel();
    }

    public function investLocationLabel(): string
    {
        $parts = [$this->subtypeLabel()];
        $place = trim(collect([$this->area, $this->city])->filter()->implode(', '));

        if ($place !== '') {
            return $parts[0].' — '.$place;
        }

        return $parts[0];
    }

    /**
     * @return string[]
     */
    public function investHighlightLines(): array
    {
        $lines = [];

        if ($location = $this->investLocationLabel()) {
            $lines[] = $location;
        }

        $description = trim(strip_tags((string) $this->description));
        if ($description !== ''
            && ! $this->isBoilerplateDescription($description)
            && ! $this->descriptionRepeatsTitle($description)) {
            $lines[] = Str::limit($description, 90);
        }

        if (count($lines) < 2 && ($fact = $this->investFactLine())) {
            $lines[] = $fact;
        }

        return array_slice($lines, 0, 2);
    }

    protected function investFactLine(): ?string
    {
        $parts = [];

        if ($this->built_area_sqft) {
            $parts[] = number_format($this->built_area_sqft).' sq.ft. built-up';
        }

        if ($this->bedrooms !== null) {
            $parts[] = ($this->bedrooms >= 5 ? '5+' : $this->bedrooms).' BHK';
        }

        if ($this->landSizeLabel()) {
            $parts[] = $this->landSizeLabel();
        }

        if ($parts !== []) {
            return implode(' · ', $parts);
        }

        return null;
    }

    protected function isBoilerplateDescription(string $description): bool
    {
        return str_contains($description, 'Listed as ')
            && str_contains($description, 'Lanka Realtors');
    }

    protected function descriptionRepeatsTitle(string $description): bool
    {
        $title = trim((string) $this->title);

        return $title !== '' && str_starts_with($description, $title);
    }

    /**
     * @return array{value: string, label: string}
     */
    public function investSecondaryStat(): array
    {
        if ($this->property_subtype === 'land' && $this->landSizeLabel()) {
            return [
                'value' => $this->landSizeLabel(),
                'label' => 'Land size',
            ];
        }

        if ($this->built_area_sqft) {
            return [
                'value' => number_format($this->built_area_sqft),
                'label' => 'Sq.ft. built-up',
            ];
        }

        if ($this->bedrooms !== null) {
            return [
                'value' => ($this->bedrooms >= 5 ? '5+' : (string) $this->bedrooms),
                'label' => 'Bedrooms',
            ];
        }

        return [
            'value' => $this->subtypeLabel(),
            'label' => 'Property type',
        ];
    }

    public function displayHeadline(): string
    {
        $parts = [];

        if ($this->bedrooms !== null) {
            $parts[] = ($this->bedrooms >= 5 ? '5+' : $this->bedrooms).' BHK';
        }

        if ($this->built_area_sqft) {
            $parts[] = number_format($this->built_area_sqft).' Sqft';
        }

        $parts[] = $this->subtypeLabel();
        $parts[] = $this->kindLabel();

        $location = trim(collect([$this->area, $this->city])->filter()->implode(', '));
        if ($location !== '') {
            $parts[] = 'in '.$location;
        }

        return implode(' ', $parts);
    }

    public function formattedPriceDisplay(): string
    {
        if (! $this->price) {
            return 'Price on request';
        }

        if ($this->price >= 1000000) {
            $millions = $this->price / 1000000;
            $formatted = number_format($millions, fmod($millions, 1.0) === 0.0 ? 0 : 1);

            return $this->currency.' '.$formatted.' Mn';
        }

        return $this->currency.' '.number_format($this->price, 0);
    }

    public function pricePerSqftLabel(): ?string
    {
        if (! $this->price || ! $this->built_area_sqft) {
            return null;
        }

        return $this->currency.' '.number_format($this->price / $this->built_area_sqft, 0).'/sqft';
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function overviewItems(): array
    {
        $items = [];

        if ($this->built_area_sqft) {
            $items[] = ['label' => 'Built-up Area', 'value' => number_format($this->built_area_sqft).' sqft'];
        }

        if ($perSqft = $this->pricePerSqftLabel()) {
            $items[] = ['label' => 'Price / sqft', 'value' => $perSqft];
        }

        if ($this->landSizeLabel()) {
            $items[] = ['label' => 'Land Size', 'value' => $this->landSizeLabel()];
        }

        if ($this->bedrooms !== null) {
            $items[] = ['label' => 'Bedrooms', 'value' => ($this->bedrooms >= 5 ? '5+' : (string) $this->bedrooms).' BHK'];
        }

        if ($this->bathrooms !== null) {
            $items[] = ['label' => 'Bathrooms', 'value' => (string) $this->bathrooms];
        }

        if ($this->floors !== null) {
            $items[] = ['label' => 'Floors', 'value' => (string) $this->floors];
        }

        if ($this->furnishingLabel()) {
            $items[] = ['label' => 'Furnishing', 'value' => $this->furnishingLabel()];
        }

        if ($this->parking_available !== null) {
            $items[] = ['label' => 'Parking', 'value' => $this->parking_available ? 'Available' : 'Not available'];
        }

        $items[] = ['label' => 'Listing Type', 'value' => $this->kindLabel()];
        $items[] = ['label' => 'Property Type', 'value' => $this->subtypeLabel()];

        if ($this->listing_kind !== 'wanted' && ($status = $this->cardStatusLabel())) {
            $items[] = ['label' => 'Status', 'value' => $status];
        }

        return $items;
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function detailFacts(): array
    {
        $facts = [];

        if ($this->city) {
            $facts[] = ['label' => 'City', 'value' => $this->city];
        }

        if ($this->area) {
            $facts[] = ['label' => 'Area', 'value' => $this->area];
        }

        if ($this->advance_payment_months !== null) {
            $facts[] = ['label' => 'Advance Payment', 'value' => $this->advance_payment_months.' month(s)'];
        }

        if ($this->deposit_months !== null) {
            $facts[] = ['label' => 'Security Deposit', 'value' => $this->deposit_months.' month(s)'];
        }

        if ($this->short_term_available !== null) {
            $facts[] = ['label' => 'Short Term', 'value' => $this->short_term_available ? 'Available' : 'Not available'];
        }

        if ($this->bills_included !== null) {
            $facts[] = ['label' => 'Bills Included', 'value' => $this->bills_included ? 'Yes' : 'No'];
        }

        if ($this->latitude && $this->longitude) {
            $facts[] = [
                'label' => 'Location',
                'value' => number_format($this->latitude, 5).', '.number_format($this->longitude, 5),
            ];
        }

        $facts[] = ['label' => 'Posted On', 'value' => $this->created_at->format('M d, Y')];
        $facts[] = ['label' => 'Property ID', 'value' => (string) $this->id];

        return $facts;
    }
}
