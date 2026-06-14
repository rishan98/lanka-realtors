@php($pfx = $portalPrefix ?? 'agent')
@extends($pfx === 'owner' ? 'layouts.owner' : 'layouts.agent')

@section('title', 'New listing — '.config('app.name'))

@section('agent_main')
<header class="agent-page-head">
    <h1>Create ad</h1>
    <p>Fill in each section below. Required fields change based on the category you choose.</p>
</header>

@include('agent.listings._form', [
    'method' => 'POST',
    'action' => route($pfx.'.listings.store'),
    'portalPrefix' => $pfx,
    'districts' => $districts,
    'districtOptions' => $districtOptions,
    'kinds' => $kinds,
    'listing' => $listing,
])
@endsection
