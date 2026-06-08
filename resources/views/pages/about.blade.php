@extends('layouts.portal')

@section('title', 'About us — '.config('app.name'))
@section('meta_description', 'Lanka Realtors Web LK is Sri Lanka\'s dedicated property advertising portal for real estate agents, agencies, and property marketers — established February 2025.')
@section('canonical', route('about'))

@section('content')
@php($about = $portal['about'] ?? [])

<section class="about-page">
    <div class="about-page__hero">
        <div class="about-page__hero-bg" aria-hidden="true"></div>
        <div class="container about-page__hero-inner">
            <p class="about-page__eyebrow">
                <span class="about-page__eyebrow-dot" aria-hidden="true"></span>
                {{ $about['eyebrow'] ?? 'About us' }}
            </p>
            <h1 class="about-page__title">{{ $about['title'] ?? 'About Lanka Realtors' }}</h1>
            <div class="about-page__hero-copy">
                @foreach($about['intro'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
            <ul class="about-page__highlights">
                <li>
                    <strong>Est. {{ $about['established'] ?? '2025' }}</strong>
                    <span>Built for Sri Lankan realtors</span>
                </li>
                <li>
                    <strong>SEO-focused</strong>
                    <span>Professional online property exposure</span>
                </li>
                <li>
                    <strong>Nationwide reach</strong>
                    <span>Connect with buyers and investors</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="container about-page__body">
        <div class="about-page__panels">
            <article class="about-page__panel glass glass--pad">
                <h2 class="about-page__panel-title">{{ $about['audiences_title'] ?? 'Who we support' }}</h2>
                <ul class="about-page__checklist">
                    @foreach($about['audiences'] ?? [] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </article>

            <article class="about-page__panel glass glass--pad">
                <h2 class="about-page__panel-title">{{ $about['benefits_title'] ?? 'How we help' }}</h2>
                <ul class="about-page__checklist">
                    @foreach($about['benefits'] ?? [] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </article>
        </div>

        <div class="about-page__story">
            @if(!empty($about['belief']))
                <article class="about-page__story-block">
                    <h2 class="about-page__story-title">Built for real estate professionals</h2>
                    <p>{{ $about['belief'] }}</p>
                </article>
            @endif

            @if(!empty($about['reach']))
                <article class="about-page__story-block">
                    <h2 class="about-page__story-title">Every property type, one trusted platform</h2>
                    <p>{{ $about['reach'] }}</p>
                </article>
            @endif

            @if(!empty($about['goal']))
                <article class="about-page__story-block about-page__story-block--accent glass glass--pad">
                    <h2 class="about-page__story-title">Our goal</h2>
                    <p class="mb-0">{{ $about['goal'] }}</p>
                </article>
            @endif
        </div>

        <div class="about-page__cta">
            <div class="about-page__cta-copy">
                <h2>{{ $about['cta_title'] ?? 'Join Lanka Realtors' }}</h2>
                <p>{{ $about['cta_text'] ?? '' }}</p>
            </div>
            <div class="about-page__cta-actions">
                <a class="btn-gold" href="{{ route('register') }}">Join as an agent</a>
                <a class="pill" href="{{ route('listings.index') }}">Browse properties</a>
                <a class="pill" href="{{ route('contact') }}">Contact us</a>
            </div>
        </div>
    </div>
</section>
@endsection
