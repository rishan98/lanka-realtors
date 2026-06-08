@extends('layouts.admin')

@section('title', 'Add city — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Add city</h1>
            <p>New cities appear in ad forms and public search filters when marked active.</p>
        </div>
        <a class="pill" href="{{ route('admin.cities.index') }}">← All cities</a>
    </div>
</header>

@include('admin.cities._form', [
    'action' => route('admin.cities.store'),
    'method' => 'POST',
])
@endsection
