@props([
    'value' => null,
])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-slate-700 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
