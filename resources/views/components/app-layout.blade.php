@props(['title' => null, 'layout' => null])

@php
    $publicLayoutRoutes = ['home', 'login', 'register', 'register.verify', 'password.request', 'password.reset'];
    $routeName = request()->route()?->getName();
    $useGuest = ! auth()->check() || in_array($routeName, $publicLayoutRoutes, true);

    $layoutType = $layout ?? ($useGuest ? 'guest' : 'app');
@endphp

@if ($layoutType === 'guest')
    <x-layouts.guest :title="$title">
        {{ $slot }}
    </x-layouts.guest>
@else
    <x-layouts.app :title="$title">
        {{ $slot }}
    </x-layouts.app>
@endif
