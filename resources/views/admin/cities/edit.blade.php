@extends('layouts.admin')

@section('title', 'Edit '.$city->name.' — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Edit city</h1>
            <p>Updating the name will sync linked property ads.</p>
        </div>
        <a class="pill" href="{{ route('admin.cities.index') }}">← All cities</a>
    </div>
</header>

@include('admin.cities._form', [
    'action' => route('admin.cities.update', $city),
    'method' => 'PUT',
])
@endsection
