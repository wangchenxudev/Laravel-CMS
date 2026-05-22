<x-app-layout title="Review Article">
  @php($status = $article->currentStatus?->status)

  <div class="mx-auto max-w-4xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-6">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">{{ $article->title }}</h1>
          <p class="mt-2 text-sm text-zinc-600">By {{ $article->author->name }}</p>
        </div>
        <span class="rounded-sm border border-zinc-200 px-2 py-1 text-xs font-medium text-zinc-700">
          {{ $status?->value }}
        </span>
      </div>
    </div>

    @error('article')
      <div class="mt-6 rounded-sm border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <article class="mt-8 whitespace-pre-line text-sm leading-7 text-zinc-800">{{ $article->content }}</article>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
      <form method="POST" action="{{ route('admin.articles.approve', $article) }}" class="rounded-sm border border-zinc-200 bg-white p-4">
        @csrf
        <button type="submit" class="rounded-sm bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
          Approve and Publish
        </button>
      </form>

      <form method="POST" action="{{ route('admin.articles.reject', $article) }}" class="rounded-sm border border-zinc-200 bg-white p-4">
        @csrf
        <label for="reason" class="block text-sm font-medium text-zinc-700">Reject reason</label>
        <textarea id="reason" name="reason" rows="3" required
          class="mt-1 block w-full rounded-sm border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('reason') }}</textarea>
        @error('reason')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <button type="submit" class="mt-3 rounded-sm border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">
          Reject
        </button>
      </form>
    </div>

    <form method="POST" action="{{ route('admin.articles.take-down', $article) }}" class="mt-4 rounded-sm border border-zinc-200 bg-white p-4">
      @csrf
      <label for="take_down_reason" class="block text-sm font-medium text-zinc-700">Take-down reason</label>
      <textarea id="take_down_reason" name="reason" rows="2"
        class="mt-1 block w-full rounded-sm border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('reason') }}</textarea>
      <button type="submit" class="mt-3 rounded-sm border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">
        Take Down
      </button>
    </form>

    <div class="mt-8">
      <h2 class="text-sm font-semibold text-zinc-950">Review actions</h2>
      <div class="mt-3 overflow-hidden rounded-sm border border-zinc-200 bg-white">
        @forelse ($article->reviewActions->sortByDesc('created_at') as $action)
          <div class="border-b border-zinc-100 px-4 py-3 text-sm last:border-b-0">
            <span class="font-medium text-zinc-950">{{ $action->action->value }}</span>
            <span class="text-zinc-600">from {{ $action->from_status->value }} to {{ $action->to_status->value }}</span>
          </div>
        @empty
          <div class="px-4 py-6 text-sm text-zinc-600">No review actions yet.</div>
        @endforelse
      </div>
    </div>
  </div>
</x-app-layout>
