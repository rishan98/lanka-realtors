@extends('layouts.portal')

@section('title', 'Projects — '.config('app.name'))
@section('meta_description', 'Explore new development and off-plan property projects in Sri Lanka — pre-launch units, commercial builds, and land projects with clear handover details.')
@section('canonical', route('projects'))

@section('content')
<section class="section section--tight">
    <div class="container" style="max-width:760px">
        <h1 class="section-title">Projects</h1>
        <p class="section-lead">Under-construction and pre-launch opportunities for buyers who want structured upside with transparent milestones.</p>

        <div class="glass glass--pad">
            <p class="muted">Browse curated projects and off-plan inventory posted by verified agents. Each record highlights construction stage, handover window, and payment structure where provided.</p>
            <div class="mt-3 row-flex">
                <a class="btn-gold" href="{{ route('listings.index', ['kind' => 'projects']) }}">View project listings</a>
                <a class="pill" href="{{ route('register') }}">Agent? Post a project</a>
            </div>
        </div>
    </div>
</section>
@endsection
