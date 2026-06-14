@extends('layouts.admin')

@section('title', 'Pending registrations — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Pending registrations</h1>
            <p>Approve or reject new agent accounts before they can log in.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

@if($pending->isEmpty())
    <section class="admin-panel">
        <p class="admin-empty mb-0">No accounts are waiting for approval.</p>
    </section>
@else
    <section class="admin-panel" style="overflow:auto">
        <table class="table-form data-table" style="min-width:720px;width:100%">
            <thead>
                <tr>
                    <th style="width:72px">Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Agency</th>
                    <th>Registered</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $user)
                    <tr>
                        <td>
                            <img
                                class="admin-user-avatar"
                                src="{{ $user->avatarUrl() }}"
                                alt="{{ $user->name }}"
                                width="48"
                                height="48"
                                loading="lazy"
                                decoding="async"
                            >
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="pill">{{ $user->roleLabel() }}</span></td>
                        <td>{{ $user->phone ?: '—' }}</td>
                        <td>{{ $user->agency_name ?: '—' }}</td>
                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                        <td style="text-align:right">
                            <div class="row-flex" style="gap:8px;justify-content:flex-end">
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn-gold" style="padding:8px 14px;font-size:0.85rem">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('Reject this registration?');">
                                    @csrf
                                    <button type="submit" class="btn-reject" style="padding:8px 14px;font-size:0.85rem">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endif
@endsection
