@php($portal = config('portal', []))
<div class="footer-mega">
    <div class="container footer-mega__grid">
        @foreach($portal['footer_columns'] ?? [] as $col)
            <div class="footer-mega__col">
                <div class="footer-mega__title">{{ $col['title'] }}</div>
                <ul class="footer-mega__list">
                    @foreach($col['links'] ?? [] as $link)
                        <li>
                            @if(! empty($link['route']))
                                <a href="{{ \App\Support\ListingBrowseUrl::fromFooterLink($link) }}">{{ $link['label'] }}</a>
                            @elseif(! empty($link['href']) && $link['href'] !== '#')
                                <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
                            @else
                                <span>{{ $link['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
