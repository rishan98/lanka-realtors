@extends('layouts.admin')

@section('title', 'Property ads — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Property ads</h1>
            <p>Activate or deactivate listings posted by agents and owners.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

<div class="admin-stats">
    <article class="admin-stat admin-stat--listings">
        <div class="admin-stat__label">Total ads</div>
        <div class="admin-stat__value">{{ $counts['total'] }}</div>
    </article>
    <article class="admin-stat admin-stat--published">
        <div class="admin-stat__label">Active</div>
        <div class="admin-stat__value">{{ $counts['published'] }}</div>
    </article>
    <article class="admin-stat admin-stat--drafts">
        <div class="admin-stat__label">Deactivated</div>
        <div class="admin-stat__value">{{ $counts['draft'] }}</div>
    </article>
</div>

<div class="admin-actions">
    <a class="pill{{ ($filters['status'] ?? null) === null ? ' is-active' : '' }}" href="{{ route('admin.listings.index', array_filter(['q' => $filters['q'] ?? null])) }}">All</a>
    <a class="pill{{ ($filters['status'] ?? null) === 'published' ? ' is-active' : '' }}" href="{{ route('admin.listings.index', array_filter(['status' => 'published', 'q' => $filters['q'] ?? null])) }}">Active</a>
    <a class="pill{{ ($filters['status'] ?? null) === 'draft' ? ' is-active' : '' }}" href="{{ route('admin.listings.index', array_filter(['status' => 'draft', 'q' => $filters['q'] ?? null])) }}">Deactivated</a>
</div>

<form method="get" action="{{ route('admin.listings.index') }}" class="admin-listings-search row-flex" style="gap:12px;align-items:flex-end;margin-bottom:20px;flex-wrap:wrap">
    @if(! empty($filters['status']))
        <input type="hidden" name="status" value="{{ $filters['status'] }}">
    @endif
    <div class="field" style="flex:1;min-width:220px;margin:0">
        <label for="admin-listings-q">Search</label>
        <input id="admin-listings-q" class="input" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Title, city, agent name, or email">
    </div>
    <button type="submit" class="btn-gold">Search</button>
</form>

@if($listings->isEmpty())
    <section class="admin-panel">
        <p class="admin-empty mb-0">No property ads found.</p>
    </section>
@else
    <section class="admin-panel" style="overflow:auto">
        <table class="table-form data-table admin-listings-table" style="min-width:960px;width:100%">
            <thead>
                <tr>
                    <th style="width:88px">Photo</th>
                    <th>Ad</th>
                    <th>Posted by</th>
                    <th>Kind</th>
                    <th>Status</th>
                    <th>Posted</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listings as $listing)
                    <tr>
                        <td>
                            <img
                                src="{{ $listing->imageUrl() }}"
                                alt=""
                                class="agent-listings-table__photo {{ $listing->cardImageClass() }}"
                                width="72"
                                height="54"
                                loading="lazy"
                                decoding="async"
                            >
                        </td>
                        <td>
                            <strong>{{ $listing->title }}</strong>
                            @if($listing->city)
                                <div class="muted" style="font-size:0.82rem;margin-top:4px">{{ $listing->city }}</div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $listing->user?->name ?? 'Unknown' }}</strong>
                            <div class="muted" style="font-size:0.82rem;margin-top:2px">{{ $listing->user?->email }}</div>
                            @if($listing->user)
                                <div class="muted" style="font-size:0.82rem">{{ $listing->user->roleLabel() }}</div>
                            @endif
                        </td>
                        <td>{{ $listing->kindLabel() }}</td>
                        <td>
                            <span class="pill">{{ $listing->adminStatusLabel() }}</span>
                        </td>
                        <td>{{ $listing->created_at->format('M j, Y') }}</td>
                        <td style="text-align:right">
                            <div class="row-flex" style="gap:8px;justify-content:flex-end;flex-wrap:wrap">
                                @if($listing->isPublished())
                                    <a class="pill" href="{{ route('listings.show', $listing) }}" target="_blank" rel="noopener">View</a>
                                    <form method="post" action="{{ route('admin.listings.deactivate', $listing) }}" onsubmit="return confirm('Deactivate this ad? It will be hidden from the public site.');">
                                        @csrf
                                        <button type="submit" class="pill">Deactivate</button>
                                    </form>
                                @else
                                    <form method="post" action="{{ route('admin.listings.activate', $listing) }}">
                                        @csrf
                                        <button type="submit" class="btn-gold" style="padding:8px 14px;font-size:0.85rem">Activate</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $listings->links() }}</div>
    </section>
@endif
@endsection
