<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
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

    public function allImagePaths(): array
    {
        $paths = $this->images ?? [];
        if ($this->featured_image && ! in_array($this->featured_image, $paths, true)) {
            $paths[] = $this->featured_image;
        }

        return $paths;
    }

    public function imageUrls(): array
    {
        return array_map(fn ($path) => asset('storage/'.$path), $this->allImagePaths());
    }

    public function imageUrl(): string
    {
        $urls = $this->imageUrls();
        if (! empty($urls)) {
            return $urls[0];
        }

        return 'https://images.unsplash.com/photo-1600596542815-ffad4e153a9a?auto=format&fit=crop&w=1400&q=80';
    }

    public function priceShortLabel(): string
    {
        if (! $this->price) {
            return 'Price on request';
        }

        if ($this->price >= 1000000) {
            $millions = $this->price / 1000000;
            $formatted = number_format($millions, fmod($millions, 1.0) === 0.0 ? 0 : 1);

            return 'Rs. '.$formatted.'M onwards';
        }

        return $this->currency.' '.number_format($this->price, 0);
    }

    public function investPriceFootnote(): string
    {
        if ($this->property_subtype === 'land') {
            return 'per plot';
        }

        return 'starting price';
    }

    /**
     * @return string[]
     */
    public function investHighlightLines(): array
    {
        $lines = [];

        $location = trim($this->subtypeLabel().($this->city ? ' — '.$this->city : ''));
        if ($location !== '') {
            $lines[] = $location;
        }

        if ($this->description) {
            $snippet = Str::limit(trim(strip_tags($this->description)), 90);
            if ($snippet !== '') {
                $lines[] = $snippet;
            }
        }

        if (count($lines) < 2) {
            $lines[] = 'Published investment listing on Lanka Realtors';
        }

        return array_slice($lines, 0, 2);
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
        $parts[] = 'For '.$this->kindLabel();

        $location = trim(($this->area ? $this->area.', ' : '').($this->city ?? ''));
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

        if ($this->listing_kind !== 'wanted') {
            $items[] = ['label' => 'Status', 'value' => 'Ready to Move'];
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
