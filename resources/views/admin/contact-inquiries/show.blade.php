@extends('layouts.admin')

@section('title', 'Contact inquiry — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>{{ $inquiry->name }}</h1>
            <p>Received {{ $inquiry->created_at->format('l, F j, Y \a\t g:i A') }}</p>
        </div>
        <a class="pill" href="{{ route('admin.contact-inquiries.index') }}">← All inquiries</a>
    </div>
</header>

<section class="admin-panel">
    <dl class="admin-inquiry-detail">
        <dt>Phone</dt>
        <dd><a href="tel:{{ preg_replace('/\s+/', '', $inquiry->phone) }}">{{ $inquiry->phone }}</a></dd>

        <dt>Email</dt>
        <dd><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd>

        <dt>Message</dt>
        <dd class="admin-inquiry-detail__message">{{ $inquiry->message }}</dd>
    </dl>

    <div class="row-flex mt-3">
        <a class="btn-gold" href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: Your enquiry — '.config('app.name')) }}">Reply by email</a>
        <a class="pill" href="tel:{{ preg_replace('/\s+/', '', $inquiry->phone) }}">Call</a>
    </div>
</section>
@endsection
