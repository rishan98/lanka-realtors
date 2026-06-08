<form method="post" action="{{ $action }}" class="admin-panel glass glass--pad" style="max-width:640px">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="field">
        <label for="parent_id">District</label>
        <select class="input @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" @if($city->exists && $city->isDistrict() && $city->children_count > 0) disabled @endif>
            <option value="">None — this is a district</option>
            @foreach(($districts ?? []) as $district)
                @if(! $city->exists || $district->id !== $city->id)
                    <option value="{{ $district->id }}" {{ (string) old('parent_id', $city->parent_id) === (string) $district->id ? 'selected' : '' }}>
                        {{ $district->name }}
                    </option>
                @endif
            @endforeach
        </select>
        @if($city->exists && $city->isDistrict() && ($city->children_count ?? $city->children()->count()) > 0)
            <input type="hidden" name="parent_id" value="">
            <p class="muted" style="font-size:0.85rem;margin-top:6px">This district has areas, so it cannot be moved under another district.</p>
        @endif
        @error('parent_id')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field mt-2">
        <label for="name">Name</label>
        <input class="input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $city->name) }}" required placeholder="e.g. Colombo 7 or Ganemulla">
        @error('name')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field mt-2">
        <label for="slug">URL slug <span class="muted">(optional)</span></label>
        <input class="input @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $city->slug) }}" placeholder="Auto-generated from name">
        @error('slug')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr">
        <div class="field">
            <label for="latitude">Latitude</label>
            <input class="input @error('latitude') is-invalid @enderror" id="latitude" name="latitude" type="number" step="any" value="{{ old('latitude', $city->latitude) }}" placeholder="e.g. 6.9271">
            @error('latitude')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="longitude">Longitude</label>
            <input class="input @error('longitude') is-invalid @enderror" id="longitude" name="longitude" type="number" step="any" value="{{ old('longitude', $city->longitude) }}" placeholder="e.g. 79.8612">
            @error('longitude')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr">
        <div class="field">
            <label for="sort_order">Sort order</label>
            <input class="input @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $city->sort_order ?? 0) }}">
            @error('sort_order')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="is_active">Status</label>
            <select class="input" id="is_active" name="is_active">
                <option value="1" {{ old('is_active', $city->is_active ?? true) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ ! old('is_active', $city->is_active ?? true) ? 'selected' : '' }}>Hidden</option>
            </select>
        </div>
    </div>

    <div class="mt-3 row-flex">
        <button class="btn-gold" type="submit">{{ $city->exists ? 'Save changes' : 'Add location' }}</button>
        <a class="pill" href="{{ route('admin.cities.index') }}">Cancel</a>
    </div>
</form>
