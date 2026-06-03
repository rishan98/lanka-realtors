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

<section class="agent-panel" style="overflow:auto">
    <table class="table-form data-table" style="min-width:640px;width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Kind</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listings as $listing)
                <tr>
                    <td><a href="{{ route($pfx.'.listings.edit', $listing) }}">{{ $listing->title }}</a></td>
                    <td>{{ $listing->kindLabel() }}</td>
                    <td><span class="pill">{{ $listing->status }}</span></td>
                    <td style="text-align:right">
                        <a class="pill" href="{{ route('listings.show', $listing) }}">View</a>
                        <a class="pill" href="{{ route($pfx.'.listings.edit', $listing) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">No listings yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">{{ $listings->links() }}</div>
</section>
@endsection
