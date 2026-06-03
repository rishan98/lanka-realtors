@php($pfx = $portalPrefix ?? 'agent')
@extends($pfx === 'owner' ? 'layouts.owner' : 'layouts.agent')

@section('title', 'Edit profile — '.config('app.name'))

@section('agent_main')
@php($user = $user ?? auth()->user())

<header class="agent-page-head">
    <h1>Edit profile</h1>
    <p>{{ ($isOwner ?? false) ? 'Update your contact details for listings.' : 'Update how buyers see you on listings and agent cards.' }}</p>
</header>

<form method="post" action="{{ route($pfx.'.profile.update') }}" enctype="multipart/form-data" class="agent-panel glass glass--pad">
    @csrf
    @method('PUT')

    <div class="agent-upload-row mb-2">
        <div class="agent-upload">
            <label>Profile photo</label>
            <img class="agent-upload__preview" src="{{ $user->avatarUrl() }}" alt="">
            <input class="input @error('avatar') is-invalid @enderror" type="file" name="avatar" accept="image/*">
            @error('avatar')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        @unless($isOwner ?? false)
        <div class="agent-upload">
            <label>Agency logo</label>
            @if($user->companyLogoUrl())
                <img class="agent-upload__preview agent-upload__preview--logo" src="{{ $user->companyLogoUrl() }}" alt="">
            @endif
            <input class="input @error('company_logo') is-invalid @enderror" type="file" name="company_logo" accept="image/*">
            @error('company_logo')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        @endunless
    </div>

    <div class="agent-form-grid">
        <div class="field">
            <label for="name">Full name</label>
            <input class="input @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name', $user->name) }}">
            @error('name')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email" required value="{{ old('email', $user->email) }}">
            @error('email')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="phone">Phone</label>
            <input class="input @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            @error('phone')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        @unless($isOwner ?? false)
        <div class="field">
            <label for="agency_name">Agency / company</label>
            <input class="input @error('agency_name') is-invalid @enderror" id="agency_name" name="agency_name" value="{{ old('agency_name', $user->agency_name) }}">
            @error('agency_name')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        @endunless

        <div class="field field--full">
            <label for="bio">Bio</label>
            <textarea class="input @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4" style="resize:vertical">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        @unless($isOwner ?? false)
        <div class="field">
            <label for="operating_since_year">Operating since (year)</label>
            <input class="input @error('operating_since_year') is-invalid @enderror" id="operating_since_year" name="operating_since_year" type="number" min="1950" max="{{ date('Y') + 1 }}" value="{{ old('operating_since_year', $user->operating_since_year) }}">
            @error('operating_since_year')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="buyers_served_estimate">Buyers served (estimate)</label>
            <input class="input @error('buyers_served_estimate') is-invalid @enderror" id="buyers_served_estimate" name="buyers_served_estimate" type="number" min="0" value="{{ old('buyers_served_estimate', $user->buyers_served_estimate) }}">
            @error('buyers_served_estimate')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        @endunless
    </div>

    <hr style="margin:1.5rem 0;border:none;border-top:1px solid rgba(11,27,51,.08)">

    <h2 style="font-size:1rem;margin:0 0 1rem;color:#0b1b33">Change password</h2>
    <p style="font-size:0.88rem;color:#5a6578;margin:0 0 1rem">Leave blank to keep your current password.</p>

    <div class="agent-form-grid">
        <div class="field">
            <label for="password">New password</label>
            <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password">
            @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>
    </div>

    <div class="row-flex mt-2" style="gap:12px">
        <button type="submit" class="btn-gold">Save profile</button>
        <a class="pill" href="{{ route($pfx.'.dashboard') }}">Cancel</a>
    </div>
</form>
@endsection
