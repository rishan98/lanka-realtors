<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request): View
    {
        $districts = City::query()->districts()->ordered()->get();

        $cities = City::query()
            ->with('parent')
            ->withCount([
                'listings',
                'children',
                'listings as published_listings_count' => function ($query) {
                    $query->where('status', 'published');
                },
            ])
            ->when($request->filled('district'), function ($query) use ($request) {
                $districtId = (int) $request->input('district');
                $query->where(function ($inner) use ($districtId) {
                    $inner->where('parent_id', $districtId)
                        ->orWhere('id', $districtId);
                });
            })
            ->ordered()
            ->get();

        return view('admin.cities.index', compact('cities', 'districts'));
    }

    public function create(): View
    {
        return view('admin.cities.create', [
            'city' => new City,
            'districts' => City::query()->districts()->ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        City::create($data);

        return redirect()->route('admin.cities.index')->with('status', 'Location added.');
    }

    public function edit(City $city): View
    {
        return view('admin.cities.edit', [
            'city' => $city->loadCount('children'),
            'districts' => City::query()->districts()->ordered()->get(),
        ]);
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $data = $this->validated($request, $city);
        $city->update($data);

        return redirect()->route('admin.cities.index')->with('status', 'Location updated.');
    }

    public function destroy(City $city): RedirectResponse
    {
        if ($city->listings()->exists()) {
            return back()->with('status', 'Cannot delete a location that has listings. Deactivate it instead.');
        }

        if ($city->isDistrict() && $city->children()->exists()) {
            return back()->with('status', 'Cannot delete a district that still has areas. Remove or move the areas first.');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')->with('status', 'Location removed.');
    }

    protected function validated(Request $request, ?City $city = null): array
    {
        $cityId = $city?->id;
        $parentId = $request->input('parent_id') ?: null;

        $data = $request->validate([
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('cities', 'id')->where(fn ($query) => $query->whereNull('parent_id')),
                Rule::notIn(array_filter([$cityId])),
            ],
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('cities', 'name')
                    ->where(fn ($query) => $query->where('parent_id', $parentId))
                    ->ignore($cityId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('cities', 'slug')->ignore($cityId),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['parent_id'] = $parentId;
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($request->input('sort_order') ?? 0);

        if ($city && $city->isDistrict() && $city->children()->exists()) {
            $data['parent_id'] = null;
        }

        return $data;
    }
}
