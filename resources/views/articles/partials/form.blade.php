@php($article = $article ?? null)

<div>
  <label for="title" class="block text-sm font-medium text-zinc-700">Title</label>
  <input id="title" name="title" value="{{ old('title', $article?->title) }}" required
    class="mt-1 block w-full rounded-sm border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
  @error('title')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>

<div>
  <label for="slug" class="block text-sm font-medium text-zinc-700">Slug</label>
  <input id="slug" name="slug" value="{{ old('slug', $article?->slug) }}"
    class="mt-1 block w-full rounded-sm border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
  @error('slug')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>

<div>
  <label for="summary" class="block text-sm font-medium text-zinc-700">Summary</label>
  <textarea id="summary" name="summary" rows="3"
    class="mt-1 block w-full rounded-sm border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('summary', $article?->summary) }}</textarea>
  @error('summary')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>

<div>
  <label for="content" class="block text-sm font-medium text-zinc-700">Content</label>
  <textarea id="content" name="content" rows="12" required
    class="mt-1 block w-full rounded-sm border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('content', $article?->content) }}</textarea>
  @error('content')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>
