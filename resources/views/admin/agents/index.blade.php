@extends('layouts.admin')

@section('title', 'Agent ratings — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Agent ratings</h1>
            <p>Set a rating from 0 to 5 for each agent. The top 3 rated agents appear in the homepage hero section.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

@if($agents->isEmpty())
    <section class="admin-panel">
        <p class="admin-empty mb-0">No approved agents yet.</p>
    </section>
@else
    <section class="admin-panel" style="overflow:auto">
        <table class="table-form data-table admin-agents-table">
            <thead>
                <tr>
                    <th style="width:72px">Photo</th>
                    <th>Agent</th>
                    <th>Listings</th>
                    <th>Rating &amp; hero placement</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agents as $agent)
                    <tr>
                        <td>
                            <img
                                class="admin-user-avatar"
                                src="{{ $agent->avatarUrl() }}"
                                alt="{{ $agent->name }}"
                                width="48"
                                height="48"
                                loading="lazy"
                                decoding="async"
                            >
                        </td>
                        <td>
                            <strong>{{ $agent->name }}</strong>
                            <div class="muted" style="font-size:0.85rem;margin-top:2px">{{ $agent->email }}</div>
                            @if($agent->agency_name)
                                <div class="muted" style="font-size:0.82rem">{{ $agent->agency_name }}</div>
                            @endif
                        </td>
                        <td>{{ (int) $agent->published_sale_count + (int) $agent->published_rent_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.agents.rating', $agent) }}" class="admin-agent-rating-form">
                                @csrf
                                @method('PATCH')
                                <label class="sr-only" for="rating-{{ $agent->id }}">Rating for {{ $agent->name }}</label>
                                <input
                                    id="rating-{{ $agent->id }}"
                                    type="number"
                                    name="rating"
                                    class="input admin-agent-rating-form__input"
                                    min="0"
                                    max="5"
                                    step="0.1"
                                    value="{{ old('rating', $agent->formattedRating()) }}"
                                    placeholder="0.0"
                                >
                                @if($heroAgentIds->contains($agent->id))
                                    <span class="pill admin-agent-hero-badge">Top 3</span>
                                @endif
                                <button type="submit" class="btn-gold admin-agent-rating-form__btn">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endif
@endsection
