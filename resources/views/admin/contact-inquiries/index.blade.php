@extends('layouts.admin')

@section('title', 'Contact inquiries — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Contact inquiries</h1>
            <p>Messages submitted through the public contact form.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

@if($inquiries->isEmpty())
    <section class="admin-panel">
        <p class="admin-empty mb-0">No contact messages yet.</p>
    </section>
@else
    <section class="admin-panel" style="overflow:auto">
        <table class="table-form data-table" style="min-width:800px;width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Received</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inquiries as $inquiry)
                    <tr>
                        <td>{{ $inquiry->name }}</td>
                        <td>{{ $inquiry->phone }}</td>
                        <td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
                        <td class="muted" style="max-width:280px">{{ Str::limit($inquiry->message, 80) }}</td>
                        <td>{{ $inquiry->created_at->format('M j, Y g:i A') }}</td>
                        <td style="text-align:right">
                            <a class="pill" href="{{ route('admin.contact-inquiries.show', $inquiry) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $inquiries->links() }}</div>
    </section>
@endif
@endsection
