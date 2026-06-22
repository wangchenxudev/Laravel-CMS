<x-app-layout title="New Tag">
  <div class="max-w-xl">
    <x-ui.card title="Create Tag" subtitle="Add a new tag to the shared vocabulary authors can choose from.">
      <form method="POST" action="{{ route('admin.tags.store') }}" class="space-y-6">
        @csrf

        <div>
          <x-ui.label for="name">Name</x-ui.label>
          <x-ui.input id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Laravel" :invalid="$errors->has('name')" />
          @error('name')
            <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
          @enderror
          <p class="mt-2 text-xs text-slate-400">A URL-friendly slug is generated automatically from the name.</p>
        </div>

        <div class="border-t border-slate-100 pt-5 flex items-center justify-end gap-3">
          <a href="{{ route('admin.tags.index') }}"
            class="inline-flex items-center justify-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-sm active:scale-95 transition-all">
            Cancel
          </a>
          <x-ui.button type="submit" variant="primary">
            Create Tag
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>
</x-app-layout>
