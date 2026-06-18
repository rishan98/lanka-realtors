@extends('layouts.portal')

@section('title', 'Find a realtor — '.config('app.name'))
@section('meta_description', 'Find verified real estate agents in Sri Lanka. Compare agents by active listings, ratings, and portfolio before you reach out.')
@section('canonical', route('find-realtor'))

@section('content')
<section class="section section--tight">
    <div class="container">
        @if(! empty($categoryCarousel))
            <div class="listings-banner mb-3">
                <x-hero-carousel
                    :slides="$categoryCarousel"
                    modifier="listings"
                    label="Find realtor banners"
                />
            </div>
        @endif

        <h1 class="section-title">Find the realtor</h1>
        <p class="section-lead">Agents with active inventory rise to the top—pick someone who is actively closing deals in your segment.</p>

        @if($agents->isEmpty())
            <div class="block-gray mt-4">
                <p class="muted mb-0">No agents registered yet.</p>
            </div>
        @else
            <div class="mb-agent-grid mt-3">
                @foreach($agents as $agent)
                    <x-agent-card :agent="$agent" />
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@if(! empty($categoryCarousel ?? []))
    @push('scripts')
        @include('partials.hero-carousel-script')
    @endpush
@endif
