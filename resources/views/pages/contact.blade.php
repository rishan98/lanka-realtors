@extends('layouts.portal')

@section('title', 'Contact us — '.config('app.name'))

@section('content')
<section class="section section--tight site-page">
    <div class="container">
        <h1 class="section-title">Contact us</h1>
        <p class="section-lead">Send us a message or use the details below—we typically respond within one business day.</p>

        @if(session('status'))
            <div class="flash mt-2">{{ session('status') }}</div>
        @endif

        <div class="site-page__contact-grid">
            <div class="site-page__contact-cards">
                @if(!empty($company['phone']))
                    <article class="site-page__card">
                        <h2>Phone</h2>
                        <p class="mb-0"><a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}">{{ $company['phone'] }}</a></p>
                    </article>
                @endif

                @if(!empty($company['email']))
                    <article class="site-page__card">
                        <h2>Email</h2>
                        <p class="mb-0"><a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a></p>
                    </article>
                @endif

                @if(!empty($company['address_line']) || !empty($company['city']))
                    <article class="site-page__card">
                        <h2>Office</h2>
                        <p class="muted mb-0">
                            @if(!empty($company['address_line'])){{ $company['address_line'] }}<br>@endif
                            {{ $company['city'] ?? '' }}
                        </p>
                    </article>
                @endif

                @if(!empty($company['hours']))
                    <article class="site-page__card">
                        <h2>Hours</h2>
                        <p class="muted mb-0">{{ $company['hours'] }}</p>
                    </article>
                @endif
            </div>

            <div class="glass glass--pad site-page__form-panel">
                <h2 class="site-page__subhead">Send a message</h2>
                <p class="muted" style="margin:0 0 1.25rem;font-size:0.95rem">Fill in the form and our team will follow up by phone or email.</p>

                <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">
                    @csrf

                    <div class="field">
                        <label for="contact-name">Full name</label>
                        <input id="contact-name" type="text" class="input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                        @error('name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="field mt-2">
                        <label for="contact-phone">Phone</label>
                        <input id="contact-phone" type="tel" class="input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                        @error('phone')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="field mt-2">
                        <label for="contact-email">Email</label>
                        <input id="contact-email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="field mt-2">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" class="input contact-form__textarea @error('message') is-invalid @enderror" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn-gold">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
