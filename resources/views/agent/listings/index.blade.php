@php($pfx = $portalPrefix ?? 'agent')
@extends($pfx === 'owner' ? 'layouts.owner' : 'layouts.agent')

@section('title', 'My listings — '.config('app.name'))

@section('agent_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>My listings</h1>
            <p>All properties you have posted on Lanka Realtors.</p>
        </div>
        <a class="btn-gold" href="{{ route($pfx.'.listings.create') }}">New listing</a>
    </div>
</header>

<section class="agent-panel agent-listings-panel" style="overflow:auto">
    <table class="table-form data-table agent-listings-table" style="min-width:720px;width:100%">
        <thead>
            <tr>
                <th style="width:88px">Photo</th>
                <th>Title</th>
                <th>Kind</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listings as $listing)
                <tr>
                    <td>
                        <a href="{{ route($pfx.'.listings.edit', $listing) }}" class="agent-listings-table__photo-link">
                            <img
                                src="{{ $listing->imageUrl() }}"
                                alt=""
                                class="agent-listings-table__photo {{ $listing->cardImageClass() }}"
                                width="72"
                                height="54"
                                loading="lazy"
                                decoding="async"
                            >
                        </a>
                    </td>
                    <td>
                        <a href="{{ route($pfx.'.listings.edit', $listing) }}" class="agent-listings-table__title">{{ $listing->title }}</a>
                        @if($listing->city)
                            <div class="muted" style="font-size:0.82rem;margin-top:4px">{{ $listing->city }}</div>
                        @endif
                    </td>
                    <td>{{ $listing->kindLabel() }}</td>
                    <td><span class="pill">{{ $listing->status }}</span></td>
                    <td style="text-align:right">
                        <div class="agent-listings-table__actions">
                            <a class="pill" href="{{ route('listings.show', $listing) }}">View</a>
                            <a class="pill" href="{{ route($pfx.'.listings.edit', $listing) }}">Edit</a>
                            <form method="POST" action="{{ route($pfx.'.listings.destroy', $listing) }}" class="agent-listings-table__delete-form" onsubmit="return confirm('Delete this listing permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pill agent-listings-table__delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">No listings yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">{{ $listings->links() }}</div>
</section>
@endsection
