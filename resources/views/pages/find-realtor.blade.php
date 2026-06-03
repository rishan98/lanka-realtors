@extends('layouts.portal')

@section('title', 'Find a realtor — '.config('app.name'))

@section('content')
<section class="section section--tight">
    <div class="container">
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
