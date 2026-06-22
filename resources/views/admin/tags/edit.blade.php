<x-app-layout title="Edit Tag">
  <div class="max-w-xl space-y-6">
    <x-ui.card title="Edit Tag" subtitle="Rename this tag. Changes apply everywhere the tag is used.">
      <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
          <x-ui.label for="name">Name</x-ui.label>
          <x-ui.input id="name" name="name" value="{{ old('name', $tag->name) }}" required autofocus :invalid="$errors->has('name')" />
          @error('name')
            <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
          @enderror
          <p class="mt-2 text-xs text-slate-400">Current slug: <span class="font-mono">{{ $tag->slug }}</span></p>
        </div>

        <div class="border-t border-slate-100 pt-5 flex items-center justify-end gap-3">
          <a href="{{ route('admin.tags.index') }}"
            class="inline-flex items-center justify-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-sm active:scale-95 transition-all">
            Cancel
          </a>
          <x-ui.button type="submit" variant="primary">
            Save Changes
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>

    <x-ui.card title="Danger Zone" subtitle="Deleting a tag removes it from every article it is attached to.">
      <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}"
        onsubmit="return confirm('Delete the tag &quot;{{ $tag->name }}&quot;? It will be removed from all articles.');">
        @csrf
        @method('DELETE')
        <x-ui.button type="submit" variant="danger">
          Delete Tag
        </x-ui.button>
      </form>
    </x-ui.card>
  </div>
</x-app-layout>
