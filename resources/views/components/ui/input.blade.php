@props([
    'type' => 'text',
    'invalid' => false,
])

@php
    $baseStyles = 'block w-full rounded border px-3 py-2 text-sm text-slate-900 transition-all placeholder:text-slate-400 focus:outline-none';
    
    $stateStyles = $invalid
        ? 'border-rose-300 text-rose-900 placeholder:text-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20'
        : 'border-slate-300 bg-white hover:border-slate-400 focus:border-[#1890FF] focus:ring-2 focus:ring-[#1890FF]/20';
        
    $classes = $baseStyles . ' ' . $stateStyles;
@endphp

@if ($type === 'textarea')
    <textarea {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</textarea>
@else
    <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
@endif
