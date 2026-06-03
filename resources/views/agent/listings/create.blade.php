@php($pfx = $portalPrefix ?? 'agent')
@extends($pfx === 'owner' ? 'layouts.owner' : 'layouts.agent')

@section('title', 'New listing — '.config('app.name'))

@section('agent_main')
<header class="agent-page-head">
    <h1>Create listing</h1>
    <p>Choose the right segment so buyers can find you faster.</p>
</header>

@include('agent.listings._form', ['method' => 'POST', 'action' => route($pfx.'.listings.store'), 'portalPrefix' => $pfx])
@endsection
