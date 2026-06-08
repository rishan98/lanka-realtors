@props([
    'class' => 'brand',
])

<a {{ $attributes->merge(['class' => $class, 'href' => route('portal.home')]) }}>
    <img
        src="{{ asset(config('portal.logo', 'images/logo.jpeg')) }}"
        alt="{{ config('app.name') }}"
        class="brand__logo"
        width="200"
        height="52"
        decoding="async"
    >
</a>
