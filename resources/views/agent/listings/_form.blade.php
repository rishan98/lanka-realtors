@php($isEdit = $listing->exists)
@php($kindFields = config('listing.kind_fields'))
@php($requiredFields = config('listing.required_fields'))
<form method="post" action="{{ $action }}" enctype="multipart/form-data" class="glass glass--pad" id="listing-form">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr">
        <div class="field">
            <label for="listing_kind">Category</label>
            <select class="input" id="listing_kind" name="listing_kind" required>
                @foreach($kinds as $key => $meta)
                    <option value="{{ $key }}" {{ old('listing_kind', $listing->listing_kind ?? 'sale') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
            @error('listing_kind')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="property_subtype">Property type</label>
            <select class="input" id="property_subtype" name="property_subtype" required></select>
            @error('property_subtype')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="field mt-2" data-field="title">
        <label for="title">Ad heading</label>
        <input class="input @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $listing->title) }}">
        @error('title')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field mt-2" data-field="description">
        <label for="description">Description</label>
        <textarea class="input @error('description') is-invalid @enderror" id="description" name="description" rows="6" style="resize:vertical">{{ old('description', $listing->description) }}</textarea>
        @error('description')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field mt-2" data-field="city">
        <label for="city">City / town</label>
        <input class="input @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $listing->city) }}">
        @error('city')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="field mt-2" data-field="contact_number">
        <label for="contact_number">Contact number</label>
        <input class="input @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $listing->contact_number) }}">
        @error('contact_number')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr" data-fields="latitude,longitude">
        <div class="field" data-field="latitude">
            <label for="latitude">Latitude</label>
            <input class="input @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $listing->latitude) }}">
            @error('latitude')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="longitude">
            <label for="longitude">Longitude</label>
            <input class="input @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $listing->longitude) }}">
            @error('longitude')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr 1fr" data-fields="bedrooms,bathrooms,floors">
        <div class="field" data-field="bedrooms">
            <label for="bedrooms">Bedrooms</label>
            <input class="input @error('bedrooms') is-invalid @enderror" id="bedrooms" name="bedrooms" type="number" min="0" value="{{ old('bedrooms', $listing->bedrooms) }}" placeholder="e.g. 3">
            @error('bedrooms')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="bathrooms">
            <label for="bathrooms">Bathrooms</label>
            <input class="input @error('bathrooms') is-invalid @enderror" id="bathrooms" name="bathrooms" type="number" min="0" value="{{ old('bathrooms', $listing->bathrooms) }}">
            @error('bathrooms')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="floors">
            <label for="floors">No. of floors</label>
            <input class="input @error('floors') is-invalid @enderror" id="floors" name="floors" type="number" min="0" value="{{ old('floors', $listing->floors) }}">
            @error('floors')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr" data-fields="land_size,land_size_unit">
        <div class="field" data-field="land_size">
            <label for="land_size">Size of land</label>
            <input class="input @error('land_size') is-invalid @enderror" id="land_size" name="land_size" value="{{ old('land_size', $listing->land_size) }}" placeholder="e.g. 15">
            @error('land_size')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="land_size_unit">
            <label for="land_size_unit">Land unit</label>
            <select class="input" id="land_size_unit" name="land_size_unit">
                <option value="">Select unit</option>
                @foreach($landSizeUnits as $key => $label)
                    <option value="{{ $key }}" {{ old('land_size_unit', $listing->land_size_unit) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('land_size_unit')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="field mt-2" data-field="built_area_sqft">
        <label for="built_area_sqft">Floor area (square feet)</label>
        <input class="input @error('built_area_sqft') is-invalid @enderror" id="built_area_sqft" name="built_area_sqft" type="number" min="0" value="{{ old('built_area_sqft', $listing->built_area_sqft) }}">
        @error('built_area_sqft')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr" data-fields="price">
        <div class="field" data-field="price">
            <label for="price">Price</label>
            <input class="input @error('price') is-invalid @enderror" id="price" name="price" type="number" step="0.01" value="{{ old('price', $listing->price) }}">
            @error('price')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="currency">Currency</label>
            <input class="input" id="currency" name="currency" value="{{ old('currency', $listing->currency ?? 'LKR') }}" maxlength="8">
        </div>
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr" data-fields="furnishing_status,parking_available">
        <div class="field" data-field="furnishing_status">
            <label for="furnishing_status">Furnishing status</label>
            <select class="input" id="furnishing_status" name="furnishing_status">
                <option value="">Select</option>
                @foreach($furnishingOptions as $key => $label)
                    <option value="{{ $key }}" {{ old('furnishing_status', $listing->furnishing_status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('furnishing_status')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="parking_available">
            <label for="parking_available">Parking available</label>
            <select class="input" id="parking_available" name="parking_available">
                <option value="">Select</option>
                <option value="1" {{ old('parking_available', $listing->parking_available) === true || old('parking_available') === '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('parking_available', $listing->parking_available) === false || old('parking_available') === '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('parking_available')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="search-row mt-2" style="grid-template-columns:1fr 1fr 1fr 1fr" data-fields="advance_payment_months,deposit_months,short_term_available,bills_included">
        <div class="field" data-field="advance_payment_months">
            <label for="advance_payment_months">Advance payment (months)</label>
            <input class="input @error('advance_payment_months') is-invalid @enderror" id="advance_payment_months" name="advance_payment_months" type="number" min="0" value="{{ old('advance_payment_months', $listing->advance_payment_months) }}">
            @error('advance_payment_months')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="deposit_months">
            <label for="deposit_months">Deposit (months)</label>
            <input class="input @error('deposit_months') is-invalid @enderror" id="deposit_months" name="deposit_months" type="number" min="0" value="{{ old('deposit_months', $listing->deposit_months) }}">
            @error('deposit_months')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="short_term_available">
            <label for="short_term_available">Short term available</label>
            <select class="input" id="short_term_available" name="short_term_available">
                <option value="">Select</option>
                <option value="1" {{ old('short_term_available', $listing->short_term_available) === true || old('short_term_available') === '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('short_term_available', $listing->short_term_available) === false || old('short_term_available') === '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('short_term_available')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field" data-field="bills_included">
            <label for="bills_included">Bills included</label>
            <select class="input" id="bills_included" name="bills_included">
                <option value="">Select</option>
                <option value="1" {{ old('bills_included', $listing->bills_included) === true || old('bills_included') === '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('bills_included', $listing->bills_included) === false || old('bills_included') === '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('bills_included')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="field mt-2" data-field="images">
        <label for="images">Images @if($isEdit)(upload new to replace all)@endif</label>
        <input class="input @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" type="file" accept="image/*" multiple>
        <p class="muted" style="font-size:0.85rem;margin-top:6px">Maximum {{ $maxImages }} images. @if(!$isEdit)At least one image is required.@endif</p>
        @error('images')<div class="error-text">{{ $message }}</div>@enderror
        @error('images.*')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    @if($isEdit && count($listing->imageUrls()))
        <div class="mt-2" data-field="images">
            <p class="muted" style="font-size:0.9rem">Current images ({{ count($listing->imageUrls()) }}):</p>
            <div class="row-flex" style="gap:8px;flex-wrap:wrap">
                @foreach($listing->imageUrls() as $url)
                    <img src="{{ $url }}" alt="" style="width:80px;height:60px;object-fit:cover;border-radius:6px">
                @endforeach
            </div>
        </div>
    @endif

    <div class="field mt-2">
        <label for="status">Visibility</label>
        <select class="input" id="status" name="status" required>
            <option value="draft" {{ old('status', $listing->status) === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status', $listing->status) === 'published' ? 'selected' : '' }}>Published</option>
        </select>
        @error('status')<div class="error-text">{{ $message }}</div>@enderror
    </div>

    <div class="mt-3 row-flex">
        <button class="btn-gold" type="submit">{{ $isEdit ? 'Save changes' : 'Publish listing' }}</button>
        <a class="pill" href="{{ route(($portalPrefix ?? 'agent').'.listings.index') }}">Cancel</a>
    </div>
</form>

<script type="application/json" id="taxonomy-data">{!! json_encode($kinds) !!}</script>
<script type="application/json" id="kind-fields-data">{!! json_encode($kindFields) !!}</script>
<script type="application/json" id="required-fields-data">{!! json_encode($requiredFields) !!}</script>
<script type="application/json" id="land-hidden-fields-data">{!! json_encode(config('listing.land_hidden_fields')) !!}</script>
<script>
(function () {
    var taxonomy = JSON.parse(document.getElementById('taxonomy-data').textContent);
    var kindFields = JSON.parse(document.getElementById('kind-fields-data').textContent);
    var requiredFields = JSON.parse(document.getElementById('required-fields-data').textContent);
    var landHiddenFields = JSON.parse(document.getElementById('land-hidden-fields-data').textContent);
    var kindEl = document.getElementById('listing_kind');
    var subEl = document.getElementById('property_subtype');
    var formEl = document.getElementById('listing-form');
    var oldSubtype = @json(old('property_subtype', $listing->property_subtype));
    var isEdit = @json($isEdit);

    function fillSubtypes() {
        var k = kindEl.value;
        var subs = (taxonomy[k] && taxonomy[k].subtypes) ? taxonomy[k].subtypes : {};
        subEl.innerHTML = '';
        var keys = Object.keys(subs);
        var matched = false;
        keys.forEach(function (key) {
            var opt = document.createElement('option');
            opt.value = key;
            opt.textContent = subs[key];
            if (oldSubtype && oldSubtype === key) {
                opt.selected = true;
                matched = true;
            }
            subEl.appendChild(opt);
        });
        if (!matched && keys.length) {
            subEl.options[0].selected = true;
        }
    }

    function isLandSubtype() {
        return subEl.value === 'land';
    }

    function fieldVisible(fields, name) {
        if (fields.indexOf(name) === -1) {
            return false;
        }
        if (isLandSubtype() && landHiddenFields.indexOf(name) !== -1) {
            return false;
        }
        return true;
    }

    function updateFields() {
        var kind = kindEl.value;
        var fields = kindFields[kind] || [];
        var required = requiredFields[kind] || [];

        formEl.querySelectorAll('[data-field]').forEach(function (el) {
            var fieldName = el.getAttribute('data-field');
            var show = fieldVisible(fields, fieldName);
            el.style.display = show ? '' : 'none';

            el.querySelectorAll('input, textarea, select').forEach(function (input) {
                if (!input.id || input.id === 'currency') {
                    return;
                }
                var isRequired = required.indexOf(fieldName) !== -1;
                if (input.name === 'images[]') {
                    input.required = isRequired && !isEdit;
                } else {
                    input.required = isRequired && show;
                }
            });
        });

        formEl.querySelectorAll('[data-fields]').forEach(function (row) {
            var names = row.getAttribute('data-fields').split(',');
            var showRow = names.some(function (name) {
                return fieldVisible(fields, name);
            });
            row.style.display = showRow ? '' : 'none';
        });
    }

    kindEl.addEventListener('change', function () {
        oldSubtype = null;
        fillSubtypes();
        updateFields();
    });

    subEl.addEventListener('change', updateFields);

    fillSubtypes();
    updateFields();
})();
</script>
