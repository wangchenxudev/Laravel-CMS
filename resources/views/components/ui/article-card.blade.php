@props(['article'])

@php
    $palettes = [
        ['#0078d4', '#004578'],
        ['#2b88d8', '#106ebe'],
        ['#005a9e', '#003a6b'],
        ['#106ebe', '#2b88d8'],
        ['#0078d4', '#5c2d91'],
        ['#0078d4', '#008272'],
    ];

    $seed = (int) ($article->id ?? crc32((string) $article->title));
    $pair = $palettes[$seed % count($palettes)];
    $titleInitial = strtoupper(mb_substr($article->title, 0, 1));
    $authorInitial = strtoupper(mb_substr($article->author->name, 0, 1));
    $publishedAt = $article->approved_at ?? $article->created_at;
    $url = route('published.articles.show', $article->publicRouteParameters());
@endphp

<article class="group flex flex-col">
  <a href="{{ $url }}" class="block">
    <div class="relative aspect-video w-full overflow-hidden rounded-lg shadow-sm ring-1 ring-slate-900/5 transition group-hover:shadow-md"
      style="background-image: linear-gradient(135deg, {{ $pair[0] }}, {{ $pair[1] }});">
      @if ($article->coverImage)
        <img src="{{ $article->coverImage->url }}" alt="{{ $article->title }}" class="absolute inset-0 h-full w-full object-cover" />
      @else
        <span class="absolute inset-0 flex items-center justify-center text-6xl font-bold text-white/90 select-none">{{ $titleInitial }}</span>
      @endif
      <span class="absolute left-3 top-3 inline-flex items-center rounded bg-black/20 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-white backdrop-blur-sm">
        Article
      </span>
    </div>
  </a>

  <div class="mt-3 flex gap-3">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700">
      {{ $authorInitial }}
    </span>
    <div class="min-w-0">
      <a href="{{ $url }}" class="line-clamp-2 text-sm font-semibold leading-snug text-slate-900 transition group-hover:text-brand-600">
        {{ $article->title }}
      </a>
      <p class="mt-1 truncate text-xs font-medium text-slate-500">{{ $article->author->name }}</p>
      <p class="text-xs text-slate-400">{{ $publishedAt?->diffForHumans() }}</p>
      @if ($article->summary)
        <p class="mt-1.5 line-clamp-2 text-xs leading-5 text-slate-500">{{ $article->summary }}</p>
      @endif
      @if ($article->relationLoaded('tags') && $article->tags->isNotEmpty())
        <div class="mt-2 flex flex-wrap gap-1.5">
          @foreach ($article->tags as $tag)
            <x-ui.tag-badge :href="route('published.articles.index', ['tag' => $tag->slug])">{{ $tag->name }}</x-ui.tag-badge>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</article>
