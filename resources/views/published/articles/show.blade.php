<x-app-layout title="{{ $article->title }}">
  <div class="mx-auto max-w-3xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-6">
      <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">{{ $article->title }}</h1>
      <p class="mt-2 text-sm text-zinc-600">By {{ $article->author->name }}</p>
      @if ($article->summary)
        <p class="mt-3 text-sm leading-6 text-zinc-700">{{ $article->summary }}</p>
      @endif
    </div>

    <article class="mt-8 whitespace-pre-line text-sm leading-7 text-zinc-800">{{ $article->content }}</article>
  </div>
</x-app-layout>
