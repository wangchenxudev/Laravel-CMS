@php
  $allTags = $tags ?? collect();
  $maxTags = (int) config('articles.max_tags');
  $selectedTagIds = collect(old('tags', ($article?->tags ?? collect())->pluck('id')->all()))
      ->map(fn ($id) => (string) $id)
      ->values()
      ->all();
@endphp

<div>
  <x-ui.label for="tags">Tags</x-ui.label>

  @if ($allTags->isEmpty())
    <p class="mt-2 text-sm text-slate-500">No tags are available yet. An administrator must create tags before they can be selected.</p>
  @else
    <div
      x-data="{
        selected: @js($selectedTagIds),
        max: {{ $maxTags }},
        has(id) { return this.selected.includes(String(id)); },
        atLimit(id) { return this.selected.length >= this.max && ! this.has(id); },
      }"
      class="mt-2"
    >
      <div class="flex flex-wrap gap-2">
        @foreach ($allTags as $tag)
          <label
            :class="{
              'border-brand-500 bg-brand-50 text-brand-700': has({{ $tag->id }}),
              'border-slate-200 bg-white text-slate-600 hover:bg-slate-50': ! has({{ $tag->id }}),
              'opacity-40 cursor-not-allowed': atLimit({{ $tag->id }}),
            }"
            class="inline-flex cursor-pointer items-center gap-2 rounded-full border px-3 py-1.5 text-sm font-medium transition-colors"
          >
            <input
              type="checkbox"
              name="tags[]"
              value="{{ $tag->id }}"
              x-model="selected"
              :disabled="atLimit({{ $tag->id }})"
              class="sr-only"
            />
            <span>{{ $tag->name }}</span>
          </label>
        @endforeach
      </div>

      <p class="mt-2 text-xs text-slate-400">
        Choose up to {{ $maxTags }} tags (<span x-text="selected.length"></span>/{{ $maxTags }} selected).
      </p>
    </div>
  @endif

  @error('tags')
    <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
  @enderror
  @foreach ($errors->get('tags.*') as $messages)
    @foreach ($messages as $message)
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @endforeach
  @endforeach
</div>
