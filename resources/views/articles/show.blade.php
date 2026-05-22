<x-app-layout title="{{ $article->title }}">
  @php($status = $article->currentStatus?->status)

  <div class="mx-auto max-w-3xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-6">
      <div class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">{{ $article->title }}</h1>
        <span class="rounded-sm border border-zinc-200 px-2 py-1 text-xs font-medium text-zinc-700">
          {{ $status?->value }}
        </span>
      </div>
      @if ($article->summary)
        <p class="mt-3 text-sm leading-6 text-zinc-600">{{ $article->summary }}</p>
      @endif
    </div>

    @error('article')
      <div class="mt-6 rounded-sm border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    @if ($article->currentStatus?->reject_reason)
      <div class="mt-6 rounded-sm border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ $article->currentStatus->reject_reason }}
      </div>
    @endif

    <article class="mt-8 whitespace-pre-line text-sm leading-7 text-zinc-800">{{ $article->content }}</article>

    <div class="mt-8 flex flex-wrap items-center gap-3">
      @can('update', $article)
        <a href="{{ route('articles.edit', $article) }}" class="rounded-sm border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">
          Edit
        </a>
      @endcan

      @can('submit', $article)
        <form method="POST" action="{{ route('articles.submit', $article) }}">
          @csrf
          <button type="submit" class="rounded-sm bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Submit for Review
          </button>
        </form>
      @endcan

      @can('withdraw', $article)
        <form method="POST" action="{{ route('articles.withdraw', $article) }}">
          @csrf
          <button type="submit" class="rounded-sm border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100">
            Withdraw
          </button>
        </form>
      @endcan

      <a href="{{ route('articles.index') }}" class="text-sm text-zinc-600 hover:text-zinc-950">Back</a>
    </div>
  </div>
</x-app-layout>
