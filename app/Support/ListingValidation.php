<?php

namespace App\Support;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingValidation
{
    public static function rules(Request $request, ?Listing $listing = null): array
    {
        $kind = $listing ? $listing->listing_kind : $request->input('listing_kind');
        $subtype = $listing ? $listing->property_subtype : $request->input('property_subtype');
        $hiddenFields = self::hiddenFieldsFor($kind, $subtype);
        $fieldHidden = fn (string $name): bool => in_array($name, $hiddenFields, true);
        $subtypes = array_keys(config('listing.kinds.'.$kind.'.subtypes', []));
        $kindFields = config('listing.kind_fields.'.$kind, []);
        $requiredFields = config('listing.required_fields.'.$kind, []);
        $maxImages = self::maxImagesForKind($kind);

        $rules = [
            'listing_kind' => $listing
                ? ['required', 'string', Rule::in([$listing->listing_kind])]
                : ['required', 'string', Rule::in(array_keys(config('listing.kinds', [])))],
            'property_subtype' => $listing
                ? ['required', 'string', Rule::in([$listing->property_subtype])]
                : ['required', 'string', Rule::in($subtypes)],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'currency' => 'nullable|string|max:8',
        ];

        if (in_array('title', $kindFields, true)) {
            $rules['title'] = in_array('title', $requiredFields, true)
                ? 'required|string|max:255'
                : 'nullable|string|max:255';
        }

        if (in_array('description', $kindFields, true)) {
            $rules['description'] = in_array('description', $requiredFields, true)
                ? 'required|string'
                : 'nullable|string';
        }

        if (in_array('city_id', $kindFields, true)) {
            $rules['city_id'] = in_array('city_id', $requiredFields, true)
                ? ['required', 'integer', Rule::exists('cities', 'id')->where(fn ($query) => $query->where('is_active', true)->whereNotNull('parent_id'))]
                : ['nullable', 'integer', Rule::exists('cities', 'id')->where(fn ($query) => $query->where('is_active', true)->whereNotNull('parent_id'))];
        }

        if (in_array('contact_number', $kindFields, true)) {
            $rules['contact_number'] = in_array('contact_number', $requiredFields, true)
                ? 'required|string|max:32'
                : 'nullable|string|max:32';
        }

        if (in_array('latitude', $kindFields, true)) {
            $rules['latitude'] = 'nullable|numeric|between:-90,90';
        }

        if (in_array('longitude', $kindFields, true)) {
            $rules['longitude'] = 'nullable|numeric|between:-180,180';
        }

        if (in_array('bedrooms', $kindFields, true) && ! $fieldHidden('bedrooms')) {
            $rules['bedrooms'] = in_array('bedrooms', $requiredFields, true)
                ? 'required|integer|min:0|max:50'
                : 'nullable|integer|min:0|max:50';
        }

        if (in_array('bathrooms', $kindFields, true) && ! $fieldHidden('bathrooms')) {
            $rules['bathrooms'] = 'nullable|integer|min:0|max:50';
        }

        if (in_array('land_size', $kindFields, true) && ! $fieldHidden('land_size')) {
            $rules['land_size'] = 'nullable|string|max:120';
            $rules['land_size_unit'] = ['nullable', 'string', Rule::in(array_keys(config('listing.land_size_units', [])))];
        }

        if (in_array('built_area_sqft', $kindFields, true) && ! $fieldHidden('built_area_sqft')) {
            $rules['built_area_sqft'] = 'nullable|integer|min:0|max:10000000';
        }

        if (in_array('price', $kindFields, true)) {
            $rules['price'] = 'nullable|numeric|min:0';
        }

        if (in_array('floors', $kindFields, true) && ! $fieldHidden('floors')) {
            $rules['floors'] = 'nullable|integer|min:0|max:200';
        }

        if (in_array('property_status', $kindFields, true)) {
            $rules['property_status'] = in_array('property_status', $requiredFields, true)
                ? ['required', 'string', Rule::in(array_keys(config('listing.property_status_options', [])))]
                : ['nullable', 'string', Rule::in(array_keys(config('listing.property_status_options', [])))];
        }

        if (in_array('furnishing_status', $kindFields, true) && ! $fieldHidden('furnishing_status')) {
            $rules['furnishing_status'] = ['nullable', 'string', Rule::in(array_keys(config('listing.furnishing_options', [])))];
        }

        if (in_array('parking_available', $kindFields, true) && ! $fieldHidden('parking_available')) {
            $rules['parking_available'] = 'nullable|boolean';
        }

        if (in_array('advance_payment_months', $kindFields, true)) {
            $rules['advance_payment_months'] = 'nullable|integer|min:0|max:24';
        }

        if (in_array('deposit_months', $kindFields, true)) {
            $rules['deposit_months'] = 'nullable|integer|min:0|max:24';
        }

        if (in_array('short_term_available', $kindFields, true)) {
            $rules['short_term_available'] = 'nullable|boolean';
        }

        if (in_array('bills_included', $kindFields, true)) {
            $rules['bills_included'] = 'nullable|boolean';
        }

        if (in_array('images', $kindFields, true)) {
            $remainingExisting = 0;
            if ($listing) {
                $existing = $listing->resolvedImagePaths();
                $removed = array_intersect((array) $request->input('removed_images', []), $existing);
                $remainingExisting = count($existing) - count($removed);
            }

            $maxNewImages = max(0, $maxImages - $remainingExisting);
            $hasExistingImages = $remainingExisting > 0;

            $imageRules = ['array', 'max:'.$maxNewImages];
            if (in_array('images', $requiredFields, true) && ! $hasExistingImages) {
                $imageRules[] = 'required';
            } else {
                $imageRules[] = 'nullable';
            }
            $rules['images'] = $imageRules;
            $rules['images.*'] = 'image|max:4096';

            if ($listing) {
                $rules['removed_images'] = 'nullable|array';
                $rules['removed_images.*'] = ['string', Rule::in($listing->resolvedImagePaths())];
                $rules['image_order'] = 'nullable|array';
                $rules['image_order.*'] = 'string|max:255';
            }
        }

        return $rules;
    }

    public static function maxImagesForKind(?string $kind): int
    {
        if ($kind) {
            $byKind = config('listing.max_images_by_kind', []);
            if (isset($byKind[$kind])) {
                return (int) $byKind[$kind];
            }
        }

        return (int) config('listing.max_images', 10);
    }

    public static function landHiddenFields(): array
    {
        return config('listing.land_hidden_fields', []);
    }

    /**
     * @return array<int, string>
     */
    public static function hiddenFieldsFor(?string $kind, ?string $subtype): array
    {
        $hidden = [];

        if ($subtype === 'land') {
            $hidden = array_merge($hidden, self::landHiddenFields());
        }

        if ($subtype === 'apartment' || $kind === 'projects') {
            $hidden = array_merge($hidden, config('listing.compact_property_hidden_fields', []));
        }

        return array_values(array_unique($hidden));
    }

    public static function allowedFields(string $kind): array
    {
        return array_merge(
            ['listing_kind', 'property_subtype', 'status', 'currency'],
            config('listing.kind_fields.'.$kind, [])
        );
    }
}
