<x-app-layout title="Create Article">
  <div class="mx-auto max-w-3xl px-6 py-10">
    <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">Create Article</h1>

    <form method="POST" action="{{ route('articles.store') }}" class="mt-6 space-y-5">
      @csrf

      @include('articles.partials.form')

      <div class="flex items-center gap-3">
        <button type="submit" class="rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
          Save Draft
        </button>
        <a href="{{ route('articles.index') }}" class="text-sm text-zinc-600 hover:text-zinc-950">Cancel</a>
      </div>
    </form>
  </div>
</x-app-layout>
