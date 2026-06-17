@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded border border-slate-200 bg-white shadow-sm']) }}>
    @if ($title || $subtitle)
        <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4">
            @if ($title)
                <h3 class="text-base font-semibold leading-6 text-slate-900 tracking-tight">
                    {{ $title }}
                </h3>
            @endif
            @if ($subtitle)
                <p class="mt-1 text-sm text-slate-500">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    @endif
    <div class="px-6 py-5">
        {{ $slot }}
    </div>
</div>
