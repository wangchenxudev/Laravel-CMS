<x-app-layout title="Review Article">
  @php
    $status = $article->status->value;
  @endphp

  <div class="space-y-6 max-w-5xl">
    <!-- Back Link -->
    <div>
      <a href="{{ route('admin.articles.reviews.index') }}" class="inline-flex items-center text-sm font-semibold text-[#1890FF] hover:text-[#40a9ff] transition-colors">
        ← Back to Review Queue
      </a>
    </div>

    <!-- Main Workspace Split Layout -->
    <div class="grid gap-6 lg:grid-cols-3">
      <!-- Left: Article Content Viewer (Colspan 2) -->
      <div class="lg:col-span-2 space-y-6">
        <x-ui.card>
          <div class="border-b border-slate-200 pb-5">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
              {{ $article->title }}
            </h1>
            
            <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
              <div class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-slate-700 font-semibold">{{ $article->author->name }} (Author)</span>
              </div>
              <span>•</span>
              <span>Updated at {{ $article->updated_at->format('Y-m-d H:i') }}</span>
              <span>•</span>
              <span class="inline-flex items-center rounded border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-800">
                {{ $status === 'pending_review' ? 'Pending Review' : $status }}
              </span>
            </div>

            @if ($article->summary)
              <div class="mt-4 rounded bg-slate-50 p-4 border border-slate-200">
                <span class="text-xs font-bold text-slate-500 block mb-1">Summary:</span>
                <p class="text-sm text-slate-600 leading-relaxed italic">{{ $article->summary }}</p>
              </div>
            @endif
          </div>

          @error('article')
            <div class="mt-6 rounded border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-sm">
              <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div class="flex-1 font-medium">{{ $message }}</div>
            </div>
          @enderror

          <!-- Content Body -->
          <div class="mt-6">
            <span class="text-xs font-bold text-slate-400 block mb-3 uppercase tracking-wider">Content</span>
            <article class="prose max-w-none text-slate-800 leading-relaxed whitespace-pre-line text-base font-normal tracking-wide py-2">
              {{ $article->content }}
            </article>
          </div>
        </x-ui.card>

        <!-- Audit History -->
        <x-ui.card title="Activity & Audit Log" subtitle="Audit trail of status changes.">
          <div class="flow-root">
            <ul role="list" class="-mb-8">
              @forelse ($article->reviewActions->sortByDesc('created_at') as $action)
                <li>
                  <div class="relative pb-8">
                    <!-- Connector line -->
                    @if (!$loop->last)
                      <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                    @endif
                    <div class="relative flex space-x-3">
                      <div>
                        <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white">
                          <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                          </svg>
                        </span>
                      </div>
                      <div class="flex-1 min-w-0 pt-1.5 flex justify-between gap-4 text-sm">
                        <div>
                          <p class="font-semibold text-slate-800">
                            {{ $action->action->value === 'approve' ? 'Approved' : ($action->action->value === 'reject' ? 'Rejected' : ($action->action->value === 'submit' ? 'Submitted' : 'Taken Down')) }}
                          </p>
                          <p class="text-xs text-slate-500 mt-1">
                            By: {{ $action->admin?->name ?? 'Author / System' }} | State Change: 
                            <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 text-[10px]">{{ $action->from_status->value }}</span> 
                            → 
                            <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 text-[10px]">{{ $action->to_status->value }}</span>
                          </p>
                          @if ($action->reason)
                            <div class="mt-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded border border-slate-200 whitespace-pre-line leading-relaxed">
                              <span class="font-bold text-slate-500">Notes: </span>{{ $action->reason }}
                            </div>
                          @endif
                        </div>
                        <div class="text-right text-xs whitespace-nowrap text-slate-400">
                          {{ $action->created_at->format('m-d H:i') }}
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              @empty
                <li class="text-center py-4 text-xs text-slate-400">No activity recorded yet.</li>
              @endforelse
            </ul>
          </div>
        </x-ui.card>
      </div>

      <!-- Right: Decision Action Form Panels (Colspan 1) -->
      <div class="space-y-6">
        <!-- Review Help Box -->
        <div class="rounded border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
          <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
            <svg class="h-5 w-5 text-[#1890FF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Review Guidance
          </h3>
          <p class="mt-2.5 text-xs leading-relaxed text-slate-600">
            Verify that the content conforms to publishing guidelines. Approving publishes the article publicly. Rejecting returns it as a draft to the author.
          </p>
        </div>

        @if ($status === 'pending_review')
          <!-- Approve Action Card -->
          <x-ui.card title="Approve Submission">
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
              Confirm content accuracy. Click below to approve and publish immediately.
            </p>
            <form method="POST" action="{{ route('admin.articles.approve', $article) }}">
              @csrf
              <x-ui.button type="submit" variant="success" class="w-full">
                Approve & Publish
              </x-ui.button>
            </form>
          </x-ui.card>

          <!-- Reject Action Card -->
          <x-ui.card title="Reject Submission">
            <form method="POST" action="{{ route('admin.articles.reject', $article) }}" class="space-y-4">
              @csrf
              <div>
                <x-ui.label for="reason">Rejection Reason</x-ui.label>
                <x-ui.input type="textarea" id="reason" name="reason" rows="3" required placeholder="Provide a detailed explanation to guide the author..." :invalid="$errors->has('reason')">{{ old('reason') }}</x-ui.input>
                @error('reason')
                  <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
              </div>
              <x-ui.button type="submit" variant="danger" class="w-full">
                Reject & Return
              </x-ui.button>
            </form>
          </x-ui.card>
        @endif

        @if ($status === 'published')
          <!-- Take Down Action Card (Only when published) -->
          <x-ui.card title="Take Down Article">
            <p class="text-xs text-rose-600 mb-4 leading-relaxed font-semibold">
              Warning: This action will hide the article from the public space.
            </p>
            <form method="POST" action="{{ route('admin.articles.take-down', $article) }}" class="space-y-4">
              @csrf
              <div>
                <x-ui.label for="take_down_reason">Take-down Reason</x-ui.label>
                <x-ui.input type="textarea" id="take_down_reason" name="reason" rows="3" required placeholder="Provide an explanation for taking down this article..." :invalid="$errors->has('reason')">{{ old('reason') }}</x-ui.input>
                @error('reason')
                  <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
              </div>
              <x-ui.button type="submit" variant="danger" class="w-full">
                Take Down
              </x-ui.button>
            </form>
          </x-ui.card>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
