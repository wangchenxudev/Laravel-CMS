@props([
    'type' => 'success',
    'dismissible' => true,
])

@php
    $styles = [
        'success' => [
            'wrap' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            'icon' => 'text-emerald-600',
            'button' => 'text-emerald-500 hover:text-emerald-700',
            'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'error' => [
            'wrap' => 'border-rose-200 bg-rose-50 text-rose-800',
            'icon' => 'text-rose-600',
            'button' => 'text-rose-500 hover:text-rose-700',
            'path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        ],
        'info' => [
            'wrap' => 'border-brand-200 bg-brand-50 text-brand-800',
            'icon' => 'text-brand-600',
            'button' => 'text-brand-500 hover:text-brand-700',
            'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];

    $style = $styles[$type] ?? $styles['success'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-md border p-4 text-sm shadow-sm ' . $style['wrap']]) }}
  x-data="{ show: true }" x-show="show">
  <svg class="h-5 w-5 shrink-0 {{ $style['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['path'] }}" />
  </svg>
  <div class="flex-1 font-medium">{{ $slot }}</div>
  @if ($dismissible)
    <button type="button" @click="show = false" class="shrink-0 cursor-pointer {{ $style['button'] }}">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  @endif
</div>
