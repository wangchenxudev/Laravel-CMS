@props([
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $baseStyles = 'inline-flex items-center justify-center rounded px-4 py-2 text-sm font-semibold tracking-tight shadow-sm transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 active:scale-[0.98] cursor-pointer';
    
    $variants = [
        'primary' => 'bg-[#1890FF] text-white hover:bg-[#40a9ff] focus-visible:outline-[#1890FF]',
        'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-sm focus-visible:outline-slate-500',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-500 focus-visible:outline-rose-600',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-500 focus-visible:outline-emerald-600',
    ];

    $classes = $baseStyles . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
