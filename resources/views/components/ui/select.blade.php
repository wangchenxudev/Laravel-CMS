@props([
    'invalid' => false,
])

@php
    $baseStyles = 'block w-full rounded border px-3 py-2 text-sm text-slate-900 transition-all bg-white focus:outline-none';
    
    $stateStyles = $invalid
        ? 'border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20'
        : 'border-slate-300 hover:border-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20';
        
    $classes = $baseStyles . ' ' . $stateStyles;
@endphp

<select {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</select>
