<x-app-layout title="View Article">
  @php
    $status = $article->status->value;
  @endphp

  <div class="space-y-6 max-w-4xl">
    <!-- Back Link -->
    <div>
      <a href="{{ route('articles.index') }}" class="inline-flex items-center text-sm font-semibold text-[#1890FF] hover:text-[#40a9ff] transition-colors">
        ← Back to My Articles
      </a>
    </div>

    <!-- Article Detail Card -->
    <x-ui.card>
      <!-- Header -->
      <div class="border-b border-slate-200 pb-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <h1 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
            {{ $article->title }}
          </h1>
          
          <div class="shrink-0">
            @php
              $statusColors = [
                'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                'pending_review' => 'bg-amber-50 text-amber-800 border-amber-200',
                'published' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'rejected' => 'bg-rose-50 text-rose-800 border-rose-200',
              ];
              $statusLabels = [
                'draft' => 'Draft',
                'pending_review' => 'Pending',
                'published' => 'Published',
                'rejected' => 'Rejected',
              ];
            @endphp
            <span class="inline-flex items-center rounded border px-3 py-1 text-xs font-semibold {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-700' }}">
              {{ $statusLabels[$status] ?? $status }}
            </span>
          </div>
        </div>

        <p class="mt-2 text-xs text-slate-400 font-medium">
          Created: {{ $article->created_at->format('Y-m-d H:i') }} | Updated: {{ $article->updated_at->format('Y-m-d H:i') }}
        </p>

        @if ($article->summary)
          <div class="mt-4 rounded bg-slate-50 p-4 border border-slate-200">
            <span class="text-xs font-bold text-slate-500 block mb-1">Summary:</span>
            <p class="text-sm text-slate-600 leading-relaxed italic">{{ $article->summary }}</p>
          </div>
        @endif
      </div>

      <!-- Errors or Info Boxes -->
      @error('article')
        <div class="mt-6 rounded border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-sm">
          <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div class="flex-1 font-medium">{{ $message }}</div>
        </div>
      @enderror

      @if ($article->reject_reason)
        <div class="mt-6 rounded border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 flex flex-col gap-2 shadow-sm">
          <div class="flex items-start gap-3">
            <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 font-bold">Rejection Reason:</div>
          </div>
          <div class="pl-8 text-rose-700 whitespace-pre-line leading-relaxed text-sm">
            {{ $article->reject_reason }}
          </div>
        </div>
      @endif

      <!-- Article Content Body -->
      <div class="mt-8 py-4 border-b border-slate-100">
        <span class="text-xs font-bold text-slate-400 block mb-3 uppercase tracking-wider">Content</span>
        <article class="prose max-w-none text-slate-800 leading-relaxed whitespace-pre-line text-base font-normal tracking-wide">
          {{ $article->content }}
        </article>
      </div>

      <!-- Action Footer -->
      <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <!-- Status Specific Action Help Text -->
        <span class="text-xs text-slate-400">
           @if ($status === 'draft')
            While in draft, you can edit. Submit for review when ready.
          @elseif ($status === 'pending_review')
            The article is pending review. You cannot edit it until returned.
          @elseif ($status === 'published')
            The article is published and publicly visible. It cannot be directly modified.
          @elseif ($status === 'rejected')
            The article was returned. Edit and save to resubmit.
          @endif
        </span>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
          @can('update', $article)
            <a href="{{ route('articles.edit', $article) }}" 
              class="inline-flex items-center justify-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-sm active:scale-95 transition-all">
              Edit Content
            </a>
          @endcan

          @can('submit', $article)
            <form method="POST" action="{{ route('articles.submit', $article) }}">
              @csrf
              <x-ui.button type="submit" variant="primary">
                Submit for Review
              </x-ui.button>
            </form>
          @endcan

          @can('withdraw', $article)
            <form method="POST" action="{{ route('articles.withdraw', $article) }}">
              @csrf
              <x-ui.button type="submit" variant="secondary" class="text-rose-600 hover:text-rose-700 border-rose-200">
                Withdraw Submission
              </x-ui.button>
            </form>
          @endcan
        </div>
      </div>
    </x-ui.card>
  </div>
</x-app-layout>
