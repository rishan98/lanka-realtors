@extends('layouts.portal')

@push('head')
    <link href="{{ asset('css/agent.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <main class="admin-shell__main">
        @if(session('status'))
            <div class="flash admin-flash">{{ session('status') }}</div>
        @endif

        @yield('admin_main')
    </main>
</div>
@endsection
