@props(['title' => null, 'layout' => null])

@php
    $isGuestRoute = in_array(request()->route()?->getName(), [
        'home',
        'login',
        'register',
        'published.articles.index',
        'published.articles.show'
    ]) || !auth()->check();

    $layoutType = $layout ?? ($isGuestRoute ? 'guest' : 'app');
@endphp

@if($layoutType === 'guest')
    <x-layouts.guest :title="$title">
        {{ $slot }}
    </x-layouts.guest>
@else
    @include('layouts.app', ['title' => $title])
@endif
