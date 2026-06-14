@php
    $requireFirst = $requireFirst ?? false;
    $maxSlots = $maxSlots ?? \App\Models\HeroCarouselBanner::MAX_BANNERS;
    $singleSlot = $maxSlots === 1;
@endphp

@error('banners')
    <div class="flash admin-flash" style="background:#fde8e8;color:#8b1a1a">{{ $message }}</div>
@enderror

<div class="admin-hero-carousel-slots{{ $singleSlot ? ' admin-hero-carousel-slots--single' : '' }}">
    @for($position = 1; $position <= $maxSlots; $position++)
        @php
            $banner = $banners->get($position);
            $required = $requireFirst && $position === 1 && ! $banner;
            $imageKey = 'banners.'.$position.'.image';
            $altKey = 'banners.'.$position.'.alt';
            $urlKey = 'banners.'.$position.'.url';
            $removeKey = 'banners.'.$position.'.remove';
            $imageClass = 'input'.($errors->has($imageKey) ? ' is-invalid' : '');
            $altClass = 'input'.($errors->has($altKey) ? ' is-invalid' : '');
            $urlClass = 'input'.($errors->has($urlKey) ? ' is-invalid' : '');
        @endphp
        <section class="admin-panel glass glass--pad admin-hero-carousel-slot">
            @unless($singleSlot)
                <h2 class="admin-hero-carousel-slot__title">
                    Banner {{ $position }}
                    @if($requireFirst && $position === 1)
                        <span class="admin-hero-carousel-slot__badge">Required</span>
                    @else
                        <span class="admin-hero-carousel-slot__badge admin-hero-carousel-slot__badge--optional">Optional</span>
                    @endif
                </h2>
            @endunless

            @if($banner)
                <div class="admin-hero-carousel-slot__preview">
                    <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->alt ?? 'Listing banner' }}">
                </div>
                <label class="admin-hero-carousel-slot__remove">
                    <input type="hidden" name="banners[{{ $position }}][remove]" value="0">
                    <input type="checkbox" name="banners[{{ $position }}][remove]" value="1" {{ old($removeKey) ? 'checked' : '' }}>
                    Remove this banner
                </label>
            @endif

            <div class="field">
                <label for="banner-image-{{ $position }}">
                    {{ $banner ? 'Replace banner' : 'Upload banner' }}
                    @if($required)<span aria-hidden="true">*</span>@endif
                </label>
                <input
                    class="{{ $imageClass }}"
                    id="banner-image-{{ $position }}"
                    type="file"
                    name="banners[{{ $position }}][image]"
                    accept="image/*"
                    @if($required) required @endif
                >
                @if($errors->has($imageKey))
                    <div class="error-text">{{ $errors->first($imageKey) }}</div>
                @endif
                <p class="admin-hint">Wide landscape image recommended (e.g. 1200×400 px). Max 4 MB.</p>
            </div>

            <div class="field">
                <label for="banner-alt-{{ $position }}">Alt text</label>
                <input
                    class="{{ $altClass }}"
                    id="banner-alt-{{ $position }}"
                    type="text"
                    name="banners[{{ $position }}][alt]"
                    value="{{ old($altKey, $banner?->alt) }}"
                    placeholder="Short description for accessibility"
                >
                @if($errors->has($altKey))
                    <div class="error-text">{{ $errors->first($altKey) }}</div>
                @endif
            </div>

            <div class="field">
                <label for="banner-url-{{ $position }}">Link URL (optional)</label>
                <input
                    class="{{ $urlClass }}"
                    id="banner-url-{{ $position }}"
                    type="url"
                    name="banners[{{ $position }}][url]"
                    value="{{ old($urlKey, $banner?->url) }}"
                    placeholder="https://"
                >
                @if($errors->has($urlKey))
                    <div class="error-text">{{ $errors->first($urlKey) }}</div>
                @endif
            </div>
        </section>
    @endfor
</div>
