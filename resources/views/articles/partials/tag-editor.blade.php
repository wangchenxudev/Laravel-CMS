@php
  $canEditTags = auth()->user()?->can('updateTags', $article) ?? false;
  $hasTagErrors = $errors->has('tags')
      || collect($errors->keys())->contains(fn (string $key): bool => str_starts_with($key, 'tags.'));
@endphp

<div x-data="{ editing: @js($hasTagErrors) }">
  <div class="flex items-center justify-between gap-3">
    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tags</span>
    @if ($canEditTags)
      <button type="button" @click="editing = !editing"
        class="text-xs font-semibold text-brand-500 hover:text-brand-600 transition-colors cursor-pointer">
        <span x-show="!editing">Edit tags</span>
        <span x-show="editing" x-cloak>Cancel</span>
      </button>
    @endif
  </div>

  <div x-show="!editing" class="mt-3 flex flex-wrap gap-2">
    @forelse ($article->tags as $tag)
      <x-ui.tag-badge>{{ $tag->name }}</x-ui.tag-badge>
    @empty
      <span class="text-sm text-slate-400">No tags assigned.</span>
    @endforelse
  </div>

  @if ($canEditTags)
    <form x-show="editing" x-cloak method="POST" action="{{ route('articles.tags.update', $article) }}" class="mt-3 space-y-4">
      @csrf
      @method('PATCH')
      @include('articles.partials.tag-picker', ['article' => $article, 'tags' => $tags ?? collect()])
      <div class="flex justify-end">
        <x-ui.button type="submit" variant="primary">Save Tags</x-ui.button>
      </div>
    </form>
  @endif
</div>
