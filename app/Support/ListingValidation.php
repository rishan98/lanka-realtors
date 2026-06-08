<?php

namespace App\Support;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingValidation
{
    public static function rules(Request $request, ?Listing $listing = null): array
    {
        $kind = $request->input('listing_kind');
        $subtype = $request->input('property_subtype');
        $isLand = $subtype === 'land';
        $subtypes = array_keys(config('listing.kinds.'.$kind.'.subtypes', []));
        $kindFields = config('listing.kind_fields.'.$kind, []);
        $requiredFields = config('listing.required_fields.'.$kind, []);
        $maxImages = config('listing.max_images', 10);
        $hasExistingImages = $listing && count($listing->allImagePaths()) > 0;

        $rules = [
            'listing_kind' => ['required', 'string', Rule::in(array_keys(config('listing.kinds', [])))],
            'property_subtype' => ['required', 'string', Rule::in($subtypes)],
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

        if (in_array('bedrooms', $kindFields, true)) {
            $rules['bedrooms'] = (! $isLand && in_array('bedrooms', $requiredFields, true))
                ? 'required|integer|min:0|max:50'
                : 'nullable|integer|min:0|max:50';
        }

        if (in_array('bathrooms', $kindFields, true) && ! $isLand) {
            $rules['bathrooms'] = 'nullable|integer|min:0|max:50';
        }

        if (in_array('land_size', $kindFields, true)) {
            $rules['land_size'] = 'nullable|string|max:120';
            $rules['land_size_unit'] = ['nullable', 'string', Rule::in(array_keys(config('listing.land_size_units', [])))];
        }

        if (in_array('built_area_sqft', $kindFields, true) && ! $isLand) {
            $rules['built_area_sqft'] = 'nullable|integer|min:0|max:10000000';
        }

        if (in_array('price', $kindFields, true)) {
            $rules['price'] = 'nullable|numeric|min:0';
        }

        if (in_array('floors', $kindFields, true) && ! $isLand) {
            $rules['floors'] = 'nullable|integer|min:0|max:200';
        }

        if (in_array('furnishing_status', $kindFields, true) && ! $isLand) {
            $rules['furnishing_status'] = ['nullable', 'string', Rule::in(array_keys(config('listing.furnishing_options', [])))];
        }

        if (in_array('parking_available', $kindFields, true) && ! $isLand) {
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
            $imageRules = ['array', 'max:'.$maxImages];
            if (in_array('images', $requiredFields, true) && ! $hasExistingImages) {
                $imageRules[] = 'required';
            } else {
                $imageRules[] = 'nullable';
            }
            $rules['images'] = $imageRules;
            $rules['images.*'] = 'image|max:4096';
        }

        return $rules;
    }

    public static function landHiddenFields(): array
    {
        return config('listing.land_hidden_fields', []);
    }

    public static function allowedFields(string $kind): array
    {
        return array_merge(
            ['listing_kind', 'property_subtype', 'status', 'currency'],
            config('listing.kind_fields.'.$kind, [])
        );
    }
}
