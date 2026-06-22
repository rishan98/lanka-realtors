@props([
    'class' => 'brand',
])

<a {{ $attributes->merge(['class' => $class, 'href' => route('portal.home')]) }}>
    <span class="brand__text">{{ config('app.name') }} home</span>
    <img
        src="{{ asset(config('portal.logo', 'images/logo.png')) }}"
        alt=""
        role="presentation"
        class="brand__logo"
        width="200"
        height="52"
        decoding="async"
    >
</a>
