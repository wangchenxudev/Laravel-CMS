@props([
    'href' => '#',
    'active' => false,
    'variant' => 'sidebar',
])

@php
    if ($variant === 'top') {
        $base = 'text-sm font-medium transition-colors';
        $state = $active ? 'text-brand-500' : 'text-slate-600 hover:text-slate-900';
    } else {
        $base = 'flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors';
        $state = $active ? 'bg-brand-500 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $base . ' ' . $state]) }} @if ($active) aria-current="page" @endif>
  {{ $slot }}
</a>
