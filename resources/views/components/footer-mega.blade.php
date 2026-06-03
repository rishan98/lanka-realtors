@php($portal = config('portal', []))
<div class="footer-mega">
    <div class="container footer-mega__grid">
        @foreach($portal['footer_columns'] ?? [] as $col)
            <div class="footer-mega__col">
                <div class="footer-mega__title">{{ $col['title'] }}</div>
                <ul class="footer-mega__list">
                    @foreach($col['links'] ?? [] as $link)
                        <li>
                            @if(!empty($link['route']))
                                <a href="{{ route($link['route'], $link['params'] ?? []) }}">{{ $link['label'] }}</a>
                            @else
                                <a href="{{ $link['href'] ?? '#' }}">{{ $link['label'] }}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
