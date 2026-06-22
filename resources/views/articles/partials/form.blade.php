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

  @include('articles.partials.tag-picker', ['tags' => $tags ?? collect()])

  <div>
    <x-ui.label for="content">Content</x-ui.label>
    <x-ui.input type="textarea" id="content" name="content" rows="12" required placeholder="Write your article content here..." class="font-mono text-sm" :invalid="$errors->has('content')">{{ old('content', $article?->content) }}</x-ui.input>
    @error('content')
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
  </div>

  @php
    $maxImages = config('articles.max_images');
    $maxImageKb = config('articles.max_image_kb');
    $imageMimes = config('articles.image_mimes');
    $existingImages = $article?->images ?? collect();
  @endphp

  <div>
    <x-ui.label for="images">Images</x-ui.label>

    @if ($existingImages->isNotEmpty())
      <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
        @foreach ($existingImages as $image)
          <label class="group relative block cursor-pointer overflow-hidden rounded border border-slate-200 bg-slate-50">
            <img src="{{ $image->url }}" alt="{{ $image->original_name }}" class="aspect-video w-full object-cover" />
            <span class="absolute inset-x-0 bottom-0 flex items-center gap-2 bg-black/50 px-2 py-1.5 text-xs font-medium text-white">
              <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="rounded border-white/50 text-rose-500 focus:ring-rose-500" />
              Remove
            </span>
          </label>
        @endforeach
      </div>
      <p class="mt-2 text-xs text-slate-400">Tick "Remove" to delete an image when you save.</p>
    @endif

    <input
      type="file"
      id="images"
      name="images[]"
      multiple
      accept="image/*"
      class="mt-2 block w-full text-sm text-slate-600 file:mr-3 file:rounded file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-600"
    />
    <p class="mt-2 text-xs text-slate-400">
      Up to {{ $maxImages }} images, max {{ number_format($maxImageKb / 1024, 1) }} MB each. Allowed: {{ implode(', ', $imageMimes) }}.
    </p>
    @error('images')
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
    @foreach ($errors->get('images.*') as $messages)
      @foreach ($messages as $message)
        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
      @endforeach
    @endforeach
    @error('remove_images')
      <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
  </div>
</div>
