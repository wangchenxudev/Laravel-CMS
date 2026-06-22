<x-app-layout title="Create Article">
  <div class="max-w-3xl">
    <x-ui.card title="Create New Article" subtitle="Create a new draft. Submit for review once finished.">
      <form method="POST" action="{{ route('articles.store') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf

        @include('articles.partials.form')

        <div class="border-t border-slate-100 pt-5 flex items-center justify-end gap-3">
          <a href="{{ route('articles.index') }}" 
            class="inline-flex items-center justify-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-sm active:scale-95 transition-all">
            Cancel
          </a>
          <x-ui.button type="submit" variant="primary">
            Save Draft
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>
</x-app-layout>
