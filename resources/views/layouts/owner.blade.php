@extends('layouts.portal')

@push('head')
    <link href="{{ asset('css/agent.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="agent-shell">
    @include('owner.partials.sidebar')

    <main class="agent-shell__main">
        @if(session('status'))
            <div class="flash agent-flash">{{ session('status') }}</div>
        @endif

        @yield('agent_main')
    </main>
</div>
@endsection
