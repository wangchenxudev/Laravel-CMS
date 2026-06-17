@php
  $article = $article ?? null;
@endphp

<div class="space-y-6">
  <div>
    <x-ui.label for="title">Title</x-ui.label>
    <x-ui.input id="title" name="title" value="{{ old('title', $article?->title) }}" required autofocus placeholder="Enter article title..." :invalid="$errors->has('title')" />
    @error('title')
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <x-ui.label for="summary">Summary</x-ui.label>
    <x-ui.input type="textarea" id="summary" name="summary" rows="3" placeholder="Enter a brief description for listing..." :invalid="$errors->has('summary')">{{ old('summary', $article?->summary) }}</x-ui.input>
    @error('summary')
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <x-ui.label for="content">Content</x-ui.label>
    <x-ui.input type="textarea" id="content" name="content" rows="12" required placeholder="Write your article content here..." class="font-mono text-sm" :invalid="$errors->has('content')">{{ old('content', $article?->content) }}</x-ui.input>
    @error('content')
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
  </div>
</div>
