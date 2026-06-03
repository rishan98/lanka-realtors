@extends('layouts.portal')

@section('title', 'Invest now — '.config('app.name'))

@section('content')
<section class="section section--tight">
    <div class="container" style="max-width:760px">
        <h1 class="section-title">Invest now</h1>
        <p class="section-lead">Under-construction and pre-launch opportunities for buyers who want structured upside with transparent milestones.</p>

        <div class="glass glass--pad">
            <p class="muted">Browse curated projects and off-plan inventory posted by verified agents. Each record highlights construction stage, handover window, and payment structure where provided.</p>
            <div class="mt-3 row-flex">
                <a class="btn-gold" href="{{ route('listings.index', ['kind' => 'invest']) }}">View invest listings</a>
                <a class="pill" href="{{ route('register') }}">Agent? Post a project</a>
            </div>
        </div>
    </div>
</section>
@endsection
