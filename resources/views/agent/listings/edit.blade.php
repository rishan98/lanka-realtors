@php($pfx = $portalPrefix ?? 'agent')
@extends($pfx === 'owner' ? 'layouts.owner' : 'layouts.agent')

@section('title', 'Edit listing — '.config('app.name'))

@section('agent_main')
<header class="agent-page-head">
    <h1>Edit ad</h1>
    <p>Update details in each section below. Required fields depend on the listing category.</p>
</header>

@include('agent.listings._form', [
    'method' => 'PUT',
    'action' => route($pfx.'.listings.update', $listing),
    'portalPrefix' => $pfx,
    'districts' => $districts,
    'districtOptions' => $districtOptions,
    'kinds' => $kinds,
    'listing' => $listing,
])

<form method="post" action="{{ route($pfx.'.listings.destroy', $listing) }}" class="mt-3" onsubmit="return confirm('Delete this listing permanently?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="pill" style="border:1px solid #c0392b;color:#c0392b;background:transparent;cursor:pointer">Delete listing</button>
</form>
@endsection
