@extends('layouts.portal')

@section('title', 'Grab me — list your property — '.config('app.name'))
@section('meta_description', 'List your property on Lanka Realtors or connect with a professional agent to market your home, land, or rental in Sri Lanka.')
@section('canonical', route('grab-me'))

@section('content')
<section class="section section--tight">
    <div class="container" style="max-width:760px">
        <h1 class="section-title">Grab me</h1>
        <p class="section-lead">You own the asset—we connect you with a professional agent who can photograph, price, and market it without noise.</p>

        <div class="glass glass--pad">
            <ul class="muted" style="padding-left:18px">
                <li>Share your location and timeline in one conversation.</li>
                <li>Agents respond with a clear go-to-market plan.</li>
                <li>No clutter—just qualified outreach.</li>
            </ul>
            <div class="mt-3 row-flex">
                <a class="btn-gold" href="{{ route('register', ['role' => 'owner']) }}">Register as owner</a>
                <a class="pill" href="{{ route('register', ['role' => 'agent']) }}">Register as agent</a>
                <a class="pill" href="{{ route('find-realtor') }}">Browse agents</a>
            </div>
            <p class="muted mt-3 mb-0" style="font-size:0.92rem">Owners can register and post listings directly. Agents manage professional inventory.</p>
        </div>
    </div>
</section>
@endsection
