<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Listing;
use App\Support\ListingValidation;
use App\Support\StoredFile;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function portalPrefix(): string
    {
        return request()->routeIs('owner.*') ? 'owner' : 'agent';
    }

    public function index()
    {
        $listings = auth()->user()->listings()->latest()->paginate(15);

        return view('agent.listings.index', [
            'listings' => $listings,
            'portalPrefix' => $this->portalPrefix(),
        ]);
    }

    public function create()
    {
        return view('agent.listings.create', [
            'kinds' => config('listing.kinds'),
            'listing' => new Listing,
            'districts' => City::districtsForForms(),
            'districtOptions' => City::districtsOptionsForJs(),
            'furnishingOptions' => config('listing.furnishing_options'),
            'propertyStatusOptions' => config('listing.property_status_options'),
            'landSizeUnits' => config('listing.land_size_units'),
            'maxImages' => ListingValidation::maxImagesForKind(null),
            'maxImagesByKind' => config('listing.max_images_by_kind', []),
            'defaultMaxImages' => (int) config('listing.max_images', 10),
            'portalPrefix' => $this->portalPrefix(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(ListingValidation::rules($request));
        $kind = $data['listing_kind'];

        $payload = $this->buildPayload($data, $kind);
        $payload['user_id'] = auth()->id();
        $payload['slug'] = Listing::uniqueSlug($payload['title']);

        $imagePaths = $this->storeUploadedImages($request);
        if ($imagePaths) {
            $payload['images'] = $imagePaths;
            $payload['featured_image'] = $imagePaths[0];
        }

        Listing::create($payload);

        return redirect()->route($this->portalPrefix().'.listings.index')->with('status', 'Listing created.');
    }

    public function edit(Listing $listing)
    {
        $this->authorizeListing($listing);

        return view('agent.listings.edit', [
            'listing' => $listing,
            'kinds' => config('listing.kinds'),
            'districts' => City::districtsForForms($listing->city_id),
            'districtOptions' => City::districtsOptionsForJs($listing->city_id),
            'furnishingOptions' => config('listing.furnishing_options'),
            'propertyStatusOptions' => config('listing.property_status_options'),
            'landSizeUnits' => config('listing.land_size_units'),
            'maxImages' => ListingValidation::maxImagesForKind(null),
            'maxImagesByKind' => config('listing.max_images_by_kind', []),
            'defaultMaxImages' => (int) config('listing.max_images', 10),
            'portalPrefix' => $this->portalPrefix(),
        ]);
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorizeListing($listing);

        $data = $request->validate(ListingValidation::rules($request, $listing));
        $kind = $listing->listing_kind;

        $payload = $this->buildPayload($data, $kind);
        $payload['listing_kind'] = $listing->listing_kind;
        $payload['property_subtype'] = $listing->property_subtype;

        if ($listing->title !== $payload['title']) {
            $payload['slug'] = Listing::uniqueSlug($payload['title']);
        }

        $imagePaths = $this->resolveImagesOnUpdate($request, $listing, $kind);
        if ($imagePaths !== false) {
            if ($imagePaths === null) {
                $payload['images'] = null;
                $payload['featured_image'] = null;
            } else {
                $payload['images'] = $imagePaths;
                $payload['featured_image'] = $imagePaths[0];
            }
        }

        $payload = array_merge($this->nullIrrelevantFields($kind), $payload);

        $listing->update($payload);

        return redirect()->route($this->portalPrefix().'.listings.index')->with('status', 'Listing updated.');
    }

    public function destroy(Listing $listing)
    {
        $this->authorizeListing($listing);

        $listing->delete();

        return redirect()->route($this->portalPrefix().'.listings.index')->with('status', 'Listing removed.');
    }

    protected function authorizeListing(Listing $listing): void
    {
        abort_unless($listing->user_id === auth()->id(), 403);
    }

    protected function buildPayload(array $data, string $kind): array
    {
        $allowed = ListingValidation::allowedFields($kind);
        $payload = collect($data)->only($allowed)->all();

        foreach (['parking_available', 'short_term_available', 'bills_included'] as $boolField) {
            if (array_key_exists($boolField, $payload)) {
                $payload[$boolField] = filter_var($payload[$boolField], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }
        }

        if (! in_array('images', $allowed, true)) {
            $payload['images'] = null;
            $payload['featured_image'] = null;
        }

        foreach (ListingValidation::hiddenFieldsFor($kind, $payload['property_subtype'] ?? null) as $field) {
            $payload[$field] = null;
        }

        return $payload;
    }

    protected function storeUploadedImages(Request $request): ?array
    {
        if (! $request->hasFile('images')) {
            return null;
        }

        $paths = [];
        foreach ($request->file('images') as $file) {
            if ($file) {
                $paths[] = $file->store('listings', 'public');
            }
        }

        if (empty($paths)) {
            throw ValidationException::withMessages([
                'images' => 'At least one image is required.',
            ]);
        }

        return $paths;
    }

    /**
     * @return array|null|false  Final image paths, null when cleared, false when unchanged.
     */
    protected function resolveImagesOnUpdate(Request $request, Listing $listing, string $kind)
    {
        if (! in_array('images', ListingValidation::allowedFields($kind), true)) {
            if (count($listing->allImagePaths()) > 0) {
                StoredFile::deleteMany($listing->allImagePaths());

                return null;
            }

            return false;
        }

        $existingPaths = $listing->resolvedImagePaths();
        $removed = array_values(array_intersect(
            (array) $request->input('removed_images', []),
            $existingPaths
        ));
        $remaining = array_values(array_diff($existingPaths, $removed));

        $newPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file) {
                    $newPaths[] = $file->store('listings', 'public');
                }
            }
        }

        $orderTokens = array_values(array_filter((array) $request->input('image_order', [])));
        if (! empty($orderTokens)) {
            $final = $this->buildOrderedImagePaths($orderTokens, $remaining, $newPaths);
        } else {
            $final = array_merge($remaining, $newPaths);
        }

        $imagesChanged = ! empty($removed) || ! empty($newPaths) || $final !== $remaining;
        if (! $imagesChanged) {
            return false;
        }

        $maxImages = ListingValidation::maxImagesForKind($kind);

        if (count($final) > $maxImages) {
            StoredFile::deleteMany($newPaths);
            throw ValidationException::withMessages([
                'images' => 'You can have at most '.$maxImages.' image'.($maxImages === 1 ? '' : 's').' for this category.',
            ]);
        }

        $imagesRequired = in_array('images', config('listing.required_fields.'.$kind, []), true);
        if (empty($final) && $imagesRequired) {
            StoredFile::deleteMany($newPaths);
            throw ValidationException::withMessages([
                'images' => 'At least one image is required.',
            ]);
        }

        StoredFile::deleteMany($removed);

        if (empty($final)) {
            return null;
        }

        return $final;
    }

    protected function nullIrrelevantFields(string $kind): array
    {
        $all = [
            'latitude', 'longitude', 'bedrooms', 'bathrooms', 'floors', 'property_status', 'furnishing_status',
            'parking_available', 'land_size', 'land_size_unit', 'built_area_sqft', 'price',
            'advance_payment_months', 'deposit_months', 'short_term_available', 'bills_included',
            'images', 'featured_image',
        ];
        $allowed = ListingValidation::allowedFields($kind);
        $nulls = [];
        foreach ($all as $field) {
            if (! in_array($field, $allowed, true)) {
                $nulls[$field] = null;
            }
        }

        return $nulls;
    }

    protected function buildOrderedImagePaths(array $tokens, array $remaining, array $newPaths): array
    {
        $final = [];
        $remainingLookup = array_flip($remaining);

        foreach ($tokens as $token) {
            if (is_string($token) && strncmp($token, 'new:', 4) === 0) {
                $index = (int) substr($token, 4);
                if (isset($newPaths[$index])) {
                    $final[] = $newPaths[$index];
                }

                continue;
            }

            if (is_string($token) && isset($remainingLookup[$token])) {
                $final[] = $token;
            }
        }

        foreach ($remaining as $path) {
            if (! in_array($path, $final, true)) {
                $final[] = $path;
            }
        }

        foreach ($newPaths as $path) {
            if (! in_array($path, $final, true)) {
                $final[] = $path;
            }
        }

        return array_values($final);
    }
}
