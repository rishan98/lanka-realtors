<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Support\ListingValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'furnishingOptions' => config('listing.furnishing_options'),
            'landSizeUnits' => config('listing.land_size_units'),
            'maxImages' => config('listing.max_images', 10),
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
            'furnishingOptions' => config('listing.furnishing_options'),
            'landSizeUnits' => config('listing.land_size_units'),
            'maxImages' => config('listing.max_images', 10),
            'portalPrefix' => $this->portalPrefix(),
        ]);
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorizeListing($listing);

        $data = $request->validate(ListingValidation::rules($request, $listing));
        $kind = $data['listing_kind'];

        $payload = $this->buildPayload($data, $kind);

        if ($listing->title !== $payload['title']) {
            $payload['slug'] = Listing::uniqueSlug($payload['title']);
        }

        $imagePaths = $this->storeUploadedImages($request);
        if ($imagePaths) {
            $this->deleteImages($listing->allImagePaths());
            $payload['images'] = $imagePaths;
            $payload['featured_image'] = $imagePaths[0];
        } elseif (! in_array('images', ListingValidation::allowedFields($kind), true)) {
            $this->deleteImages($listing->allImagePaths());
            $payload['images'] = null;
            $payload['featured_image'] = null;
        }

        $payload = array_merge($this->nullIrrelevantFields($kind), $payload);

        $listing->update($payload);

        return redirect()->route($this->portalPrefix().'.listings.index')->with('status', 'Listing updated.');
    }

    public function destroy(Listing $listing)
    {
        $this->authorizeListing($listing);

        $this->deleteImages($listing->allImagePaths());
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

        if (($payload['property_subtype'] ?? null) === 'land') {
            foreach (ListingValidation::landHiddenFields() as $field) {
                $payload[$field] = null;
            }
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

    protected function nullIrrelevantFields(string $kind): array
    {
        $all = [
            'latitude', 'longitude', 'bedrooms', 'bathrooms', 'floors', 'furnishing_status',
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

    protected function deleteImages(array $paths): void
    {
        foreach (array_unique(array_filter($paths)) as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}
