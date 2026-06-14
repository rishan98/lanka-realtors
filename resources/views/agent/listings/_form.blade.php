@php($isEdit = $listing->exists)
@php($kindFields = config('listing.kind_fields'))
@php($requiredFields = config('listing.required_fields'))
<form method="post" action="{{ $action }}" enctype="multipart/form-data" class="listing-form glass glass--pad" id="listing-form">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Category</h2>
            <p class="listing-form__section-lead">Choose the listing type and property category.</p>
        </header>
        <div class="listing-form__grid listing-form__grid--2">
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
    </section>

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Ad details</h2>
            <p class="listing-form__section-lead">Write a clear heading and description buyers will see first.</p>
        </header>
        <div class="listing-form__stack">
            <div class="field" data-field="title">
                <label for="title">Ad heading</label>
                <input class="input @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $listing->title) }}" placeholder="e.g. Spacious 3BR house near schools">
                @error('title')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="field" data-field="description">
                <label for="description">Description</label>
                <textarea class="input @error('description') is-invalid @enderror" id="description" name="description" rows="6" placeholder="Highlight key features, nearby amenities, and anything buyers should know.">{{ old('description', $listing->description) }}</textarea>
                @error('description')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
    </section>

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Location &amp; contact</h2>
            <p class="listing-form__section-lead">Where is the property and how can interested buyers reach you?</p>
        </header>
        <div class="listing-form__stack">
            <div class="listing-form__grid listing-form__grid--2">
                <div class="field" data-field="city_id">
                    <label for="listing_district">District</label>
                    <select class="input" id="listing_district">
                        <option value="">Select district</option>
                        @foreach(($districts ?? []) as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" data-field="city_id">
                    <label for="city_id">City / area</label>
                    <select class="input @error('city_id') is-invalid @enderror" id="city_id" name="city_id">
                        <option value="">Select area</option>
                    </select>
                    @error('city_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="field" data-field="contact_number">
                <label for="contact_number">Contact number</label>
                <input class="input @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $listing->contact_number) }}" placeholder="e.g. 077 123 4567">
                @error('contact_number')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="listing-form__subblock" data-fields="latitude,longitude">
                <p class="listing-form__subblock-label">Map coordinates <span class="listing-form__optional">(optional)</span></p>
                <div class="listing-form__grid listing-form__grid--2">
                    <div class="field" data-field="latitude">
                        <label for="latitude">Latitude</label>
                        <input class="input @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $listing->latitude) }}" placeholder="6.9271">
                        @error('latitude')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="field" data-field="longitude">
                        <label for="longitude">Longitude</label>
                        <input class="input @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $listing->longitude) }}" placeholder="79.8612">
                        @error('longitude')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Property specifications</h2>
            <p class="listing-form__section-lead">Add size and layout details. Fields change based on property type.</p>
        </header>
        <div class="listing-form__stack">
            <div class="listing-form__grid listing-form__grid--3" data-fields="bedrooms,bathrooms,floors">
                <div class="field" data-field="bedrooms">
                    <label for="bedrooms">Bedrooms</label>
                    <input class="input @error('bedrooms') is-invalid @enderror" id="bedrooms" name="bedrooms" type="number" min="0" value="{{ old('bedrooms', $listing->bedrooms) }}" placeholder="e.g. 3">
                    @error('bedrooms')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="field" data-field="bathrooms">
                    <label for="bathrooms">Bathrooms</label>
                    <input class="input @error('bathrooms') is-invalid @enderror" id="bathrooms" name="bathrooms" type="number" min="0" value="{{ old('bathrooms', $listing->bathrooms) }}" placeholder="e.g. 2">
                    @error('bathrooms')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="field" data-field="floors">
                    <label for="floors">No. of floors</label>
                    <input class="input @error('floors') is-invalid @enderror" id="floors" name="floors" type="number" min="0" value="{{ old('floors', $listing->floors) }}" placeholder="e.g. 2">
                    @error('floors')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="listing-form__grid listing-form__grid--2" data-fields="land_size,land_size_unit">
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
            <div class="field" data-field="built_area_sqft">
                <label for="built_area_sqft">Floor area (square feet)</label>
                <input class="input @error('built_area_sqft') is-invalid @enderror" id="built_area_sqft" name="built_area_sqft" type="number" min="0" value="{{ old('built_area_sqft', $listing->built_area_sqft) }}" placeholder="e.g. 1800">
                @error('built_area_sqft')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
    </section>

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Pricing</h2>
            <p class="listing-form__section-lead">Set the asking price shown on your listing.</p>
        </header>
        <div class="listing-form__grid listing-form__grid--2" data-fields="price">
            <div class="field" data-field="price">
                <label for="price">Price</label>
                <input class="input @error('price') is-invalid @enderror" id="price" name="price" type="number" step="0.01" value="{{ old('price', $listing->price) }}" placeholder="e.g. 25000000">
                @error('price')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="currency">Currency</label>
                <input class="input" id="currency" name="currency" value="{{ old('currency', $listing->currency ?? 'LKR') }}" maxlength="8">
            </div>
        </div>
    </section>

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Features &amp; rental terms</h2>
            <p class="listing-form__section-lead">Optional details that help buyers compare properties.</p>
        </header>
        <div class="listing-form__stack">
            <div class="listing-form__grid listing-form__grid--2" data-fields="furnishing_status,parking_available">
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
            <div class="listing-form__grid listing-form__grid--4" data-fields="advance_payment_months,deposit_months,short_term_available,bills_included">
                <div class="field" data-field="advance_payment_months">
                    <label for="advance_payment_months">Advance (months)</label>
                    <input class="input @error('advance_payment_months') is-invalid @enderror" id="advance_payment_months" name="advance_payment_months" type="number" min="0" value="{{ old('advance_payment_months', $listing->advance_payment_months) }}">
                    @error('advance_payment_months')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="field" data-field="deposit_months">
                    <label for="deposit_months">Deposit (months)</label>
                    <input class="input @error('deposit_months') is-invalid @enderror" id="deposit_months" name="deposit_months" type="number" min="0" value="{{ old('deposit_months', $listing->deposit_months) }}">
                    @error('deposit_months')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="field" data-field="short_term_available">
                    <label for="short_term_available">Short term</label>
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
        </div>
    </section>

    <section class="listing-form__section">
        <header class="listing-form__section-head">
            <h2 class="listing-form__section-title">Photos</h2>
            <p class="listing-form__section-lead" id="listing-photos-lead">Upload up to {{ $maxImages }} clear photos. The first image is used as the cover.</p>
        </header>
        <div class="listing-form__stack">
            <div class="field listing-form__upload" data-field="images">
                <div class="listing-form__upload-zone">
                    <label for="images" class="listing-form__upload-label">
                        <span class="listing-form__upload-icon" aria-hidden="true">+</span>
                        <span class="listing-form__upload-text">Choose images</span>
                        <span class="listing-form__upload-hint" id="listing-upload-hint">
                            JPG, PNG or WebP · max <span id="listing-upload-max">{{ $maxImages }}</span> <span id="listing-upload-max-label">{{ $maxImages === 1 ? 'file' : 'files' }}</span>
                            @if(!$isEdit)
                                · at least 1 required
                            @endif
                        </span>
                    </label>
                    <input class="listing-form__upload-input{{ ($errors->has('images') || $errors->has('images.*')) ? ' is-invalid' : '' }}" id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" multiple>
                </div>
                <div class="listing-form__previews" id="listing-image-previews" hidden></div>
                @error('images')<div class="error-text">{{ $message }}</div>@enderror
                @error('images.*')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            @if($isEdit && count($listing->resolvedImagePaths()))
                @php($removedImages = old('removed_images', []))
                <div data-field="images" id="listing-existing-images">
                    <p class="listing-form__current-label">Current images (<span id="listing-existing-count">{{ count($listing->resolvedImagePaths()) - count($removedImages) }}</span>)</p>
                    <div class="listing-form__previews" id="listing-existing-previews">
                        @foreach($listing->resolvedImagePaths() as $index => $path)
                            <div class="listing-form__preview" data-existing-path="{{ $path }}" @if(in_array($path, $removedImages, true)) hidden @endif>
                                <img src="{{ asset('storage/'.$path) }}" alt="">
                                <span class="listing-form__preview-badge">{{ $index === 0 ? 'Cover' : 'Photo '.($index + 1) }}</span>
                                <button type="button" class="listing-form__preview-remove" aria-label="Remove photo">&times;</button>
                            </div>
                        @endforeach
                    </div>
                    <div id="listing-removed-images-inputs"></div>
                    <p class="listing-form__hint">Remove photos with × or upload additional images above.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="listing-form__section listing-form__section--actions">
        <div class="listing-form__grid listing-form__grid--2">
            <div class="field">
                <label for="status">Visibility</label>
                <select class="input" id="status" name="status" required>
                    <option value="draft" {{ old('status', $listing->status) === 'draft' ? 'selected' : '' }}>Draft — save without publishing</option>
                    <option value="published" {{ old('status', $listing->status) === 'published' ? 'selected' : '' }}>Published — visible on the site</option>
                </select>
                @error('status')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="listing-form__actions">
            <button class="btn-gold" type="submit">{{ $isEdit ? 'Save changes' : 'Publish listing' }}</button>
            <a class="pill" href="{{ route(($portalPrefix ?? 'agent').'.listings.index') }}">Cancel</a>
        </div>
    </section>
</form>

<script type="application/json" id="taxonomy-data">{!! json_encode($kinds) !!}</script>
<script type="application/json" id="kind-fields-data">{!! json_encode($kindFields) !!}</script>
<script type="application/json" id="required-fields-data">{!! json_encode($requiredFields) !!}</script>
<script type="application/json" id="land-hidden-fields-data">{!! json_encode(config('listing.land_hidden_fields')) !!}</script>
<script type="application/json" id="districts-data">@json($districtOptions ?? [])</script>
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
                    input.required = isRequired && existingRemainingCount() === 0;
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
        updateImageLimits();
    });

    subEl.addEventListener('change', updateFields);

    var districtsData = JSON.parse(document.getElementById('districts-data').textContent);
    var districtEl = document.getElementById('listing_district');
    var cityEl = document.getElementById('city_id');
    var selectedCityId = @json(old('city_id', $listing->city_id));

    function fillAreas(districtId, selectedId) {
        cityEl.innerHTML = '<option value="">Select area</option>';
        if (!districtId) {
            return;
        }

        var district = districtsData.find(function (entry) {
            return String(entry.id) === String(districtId);
        });

        if (!district) {
            return;
        }

        district.areas.forEach(function (area) {
            var opt = document.createElement('option');
            opt.value = area.id;
            opt.textContent = area.name;
            if (selectedId && String(selectedId) === String(area.id)) {
                opt.selected = true;
            }
            cityEl.appendChild(opt);
        });
    }

    if (districtEl && cityEl) {
        districtEl.addEventListener('change', function () {
            fillAreas(districtEl.value, null);
        });

        if (selectedCityId) {
            var matchDistrict = districtsData.find(function (entry) {
                return entry.areas.some(function (area) {
                    return String(area.id) === String(selectedCityId);
                });
            });

            if (matchDistrict) {
                districtEl.value = matchDistrict.id;
                fillAreas(matchDistrict.id, selectedCityId);
            }
        }
    }

    var imagesInput = document.getElementById('images');
    var uploadHint = document.getElementById('listing-upload-hint');
    var previewEl = document.getElementById('listing-image-previews');
    var maxImagesByKind = @json($maxImagesByKind ?? []);
    var defaultMaxImages = @json($defaultMaxImages ?? 10);
    var maxImages = defaultMaxImages;
    var selectedFiles = [];
    var previewUrls = [];
    var photosLeadEl = document.getElementById('listing-photos-lead');
    var uploadMaxEl = document.getElementById('listing-upload-max');
    var uploadMaxLabelEl = document.getElementById('listing-upload-max-label');
    var requireImageHint = @json(!$isEdit);
    var existingPreviewsEl = document.getElementById('listing-existing-previews');
    var removedInputsEl = document.getElementById('listing-removed-images-inputs');
    var removedPaths = new Set(@json(old('removed_images', [])));

    function existingTotalCount() {
        if (!existingPreviewsEl) {
            return 0;
        }

        return existingPreviewsEl.querySelectorAll('[data-existing-path]').length;
    }

    function existingRemainingCount() {
        return Math.max(0, existingTotalCount() - removedPaths.size);
    }

    function maxAllowedNewFiles() {
        if (!isEdit) {
            return maxImages;
        }

        return Math.max(0, maxImages - existingRemainingCount());
    }

    function syncRemovedInputs() {
        if (!removedInputsEl) {
            return;
        }

        removedInputsEl.innerHTML = '';
        removedPaths.forEach(function (path) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'removed_images[]';
            input.value = path;
            removedInputsEl.appendChild(input);
        });

        var countEl = document.getElementById('listing-existing-count');
        if (countEl) {
            countEl.textContent = String(existingRemainingCount());
        }

        updateFields();
        updateUploadHint();
    }

    function markExistingImageRemoved(path, item) {
        removedPaths.add(path);
        item.hidden = true;
        syncRemovedInputs();

        var allowed = maxAllowedNewFiles();
        if (selectedFiles.length > allowed) {
            selectedFiles = selectedFiles.slice(0, allowed);
            window.alert('You can upload a maximum of ' + maxImages + ' image' + (maxImages === 1 ? '' : 's') + ' for this category.');
            syncInputFiles();
        }
    }

    if (existingPreviewsEl) {
        existingPreviewsEl.addEventListener('click', function (event) {
            var button = event.target.closest('.listing-form__preview-remove');
            if (!button) {
                return;
            }

            var item = button.closest('[data-existing-path]');
            if (!item) {
                return;
            }

            markExistingImageRemoved(item.getAttribute('data-existing-path'), item);
        });

        syncRemovedInputs();
    }

    function maxImagesForKind(kind) {
        if (maxImagesByKind[kind] !== undefined && maxImagesByKind[kind] !== null) {
            return parseInt(maxImagesByKind[kind], 10);
        }

        return defaultMaxImages;
    }

    function effectiveMaxFiles() {
        return isEdit ? maxAllowedNewFiles() : maxImages;
    }

    function updateImageLimits() {
        if (!kindEl || !imagesInput) {
            return;
        }

        maxImages = maxImagesForKind(kindEl.value);
        imagesInput.multiple = maxImages > 1;

        if (photosLeadEl) {
            photosLeadEl.textContent = maxImages === 1
                ? 'Upload one clear photo for your wanted ad.'
                : 'Upload up to ' + maxImages + ' clear photos. The first image is used as the cover.';
        }

        if (uploadMaxEl) {
            uploadMaxEl.textContent = String(maxImages);
        }

        if (uploadMaxLabelEl) {
            uploadMaxLabelEl.textContent = maxImages === 1 ? 'file' : 'files';
        }

        if (selectedFiles.length > effectiveMaxFiles()) {
            selectedFiles = selectedFiles.slice(0, effectiveMaxFiles());
            window.alert('You can upload a maximum of ' + maxImages + ' image' + (maxImages === 1 ? '' : 's') + ' for this category.');
            syncInputFiles();
        } else {
            updateUploadHint();
        }
    }

    function defaultHintText() {
        var suffix = requireImageHint && existingRemainingCount() === 0 ? ' · at least 1 required' : '';
        var uploadLimit = effectiveMaxFiles();

        if (isEdit && uploadLimit === 0) {
            return 'Remove an existing photo before uploading a new one.';
        }

        return 'JPG, PNG or WebP · max ' + uploadLimit + ' ' + (uploadLimit === 1 ? 'file' : 'files') + suffix;
    }

    function updateUploadHint() {
        if (!uploadHint) {
            return;
        }

        if (selectedFiles.length === 0) {
            uploadHint.textContent = defaultHintText();
        } else {
            uploadHint.textContent = selectedFiles.length + ' file' + (selectedFiles.length === 1 ? '' : 's') + ' selected';
        }
    }

    function fileKey(file) {
        return file.name + '|' + file.size + '|' + file.lastModified;
    }

    function revokePreviewUrls() {
        previewUrls.forEach(function (url) {
            URL.revokeObjectURL(url);
        });
        previewUrls = [];
    }

    function syncInputFiles() {
        if (!imagesInput) {
            return;
        }

        var transfer = new DataTransfer();
        selectedFiles.forEach(function (file) {
            transfer.items.add(file);
        });
        imagesInput.files = transfer.files;

        if (uploadHint) {
            updateUploadHint();
        }

        renderPreviews();
    }

    function renderPreviews() {
        if (!previewEl) {
            return;
        }

        revokePreviewUrls();
        previewEl.innerHTML = '';

        if (selectedFiles.length === 0) {
            previewEl.hidden = true;
            return;
        }

        previewEl.hidden = false;

        selectedFiles.forEach(function (file, index) {
            var item = document.createElement('div');
            item.className = 'listing-form__preview';

            var img = document.createElement('img');
            var objectUrl = URL.createObjectURL(file);
            previewUrls.push(objectUrl);
            img.src = objectUrl;
            img.alt = file.name;

            var label = document.createElement('span');
            label.className = 'listing-form__preview-badge';
            label.textContent = index === 0 ? 'Cover' : 'Photo ' + (index + 1);

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'listing-form__preview-remove';
            removeBtn.setAttribute('aria-label', 'Remove ' + file.name);
            removeBtn.innerHTML = '&times;';
            removeBtn.addEventListener('click', function () {
                selectedFiles.splice(index, 1);
                syncInputFiles();
            });

            item.appendChild(img);
            item.appendChild(label);
            item.appendChild(removeBtn);
            previewEl.appendChild(item);
        });
    }

    if (imagesInput) {
        imagesInput.addEventListener('change', function () {
            var allowed = effectiveMaxFiles();
            var incoming = Array.prototype.slice.call(imagesInput.files || []).filter(function (file) {
                return file.type && file.type.indexOf('image/') === 0;
            });

            if (allowed === 0) {
                selectedFiles = [];
                window.alert('Remove an existing photo before uploading a new one.');
                syncInputFiles();
                return;
            }

            if (allowed === 1) {
                selectedFiles = incoming.slice(0, 1);
            } else {
                var known = {};

                selectedFiles.forEach(function (file) {
                    known[fileKey(file)] = true;
                });

                incoming.forEach(function (file) {
                    var key = fileKey(file);
                    if (!known[key]) {
                        selectedFiles.push(file);
                        known[key] = true;
                    }
                });

                if (selectedFiles.length > allowed) {
                    selectedFiles = selectedFiles.slice(0, allowed);
                    window.alert('You can upload a maximum of ' + maxImages + ' images for this category.');
                }
            }

            syncInputFiles();
        });
    }

    fillSubtypes();
    updateFields();
    updateImageLimits();
})();
</script>
