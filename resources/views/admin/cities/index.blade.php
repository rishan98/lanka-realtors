@extends('layouts.admin')

@section('title', 'Cities — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Cities &amp; areas</h1>
            <p>Districts (e.g. Colombo, Gampaha) and their areas (e.g. Colombo 7, Ganemulla, Ja-Ela) used in property ads.</p>
        </div>
        <div class="row-flex" style="gap:8px">
            <a class="btn-gold" href="{{ route('admin.cities.create') }}">Add location</a>
            <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
        </div>
    </div>
</header>

<form method="get" action="{{ route('admin.cities.index') }}" class="admin-panel glass glass--pad mb-2" style="max-width:420px">
    <div class="field">
        <label for="district">Filter by district</label>
        <select class="input" id="district" name="district" onchange="this.form.submit()">
            <option value="">All districts and areas</option>
            @foreach($districts as $district)
                <option value="{{ $district->id }}" {{ (string) request('district') === (string) $district->id ? 'selected' : '' }}>
                    {{ $district->name }}
                </option>
            @endforeach
        </select>
    </div>
</form>

@if($cities->isEmpty())
    <section class="admin-panel">
        <p class="admin-empty mb-0">No locations found. <a href="{{ route('admin.cities.create') }}">Add one</a> or run the city seeder.</p>
    </section>
@else
    <section class="admin-panel" style="overflow:auto">
        <table class="table-form data-table admin-cities-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>District</th>
                    <th>Coordinates</th>
                    <th>Published ads</th>
                    <th>Status</th>
                    <th style="width:180px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cities as $city)
                    <tr>
                        <td>
                            <strong @if($city->isArea()) style="padding-left:12px" @endif>{{ $city->name }}</strong>
                            <div class="muted" style="font-size:0.82rem;margin-top:2px">{{ $city->slug }}</div>
                        </td>
                        <td>{{ $city->isDistrict() ? 'District' : 'Area' }}</td>
                        <td>{{ $city->parent?->name ?? '—' }}</td>
                        <td class="muted" style="font-size:0.85rem;white-space:nowrap">
                            @if($city->latitude !== null && $city->longitude !== null)
                                {{ number_format($city->latitude, 4) }}, {{ number_format($city->longitude, 4) }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ (int) $city->published_listings_count }}</td>
                        <td>
                            @if($city->is_active)
                                <span class="pill admin-city-status admin-city-status--active">Active</span>
                            @else
                                <span class="pill admin-city-status admin-city-status--inactive">Hidden</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-flex" style="gap:8px;flex-wrap:wrap">
                                <a class="pill" href="{{ route('admin.cities.edit', $city) }}">Edit</a>
                                @if($city->listings_count === 0 && $city->children_count === 0)
                                    <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Delete this location?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pill admin-city-delete">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endif
@endsection
