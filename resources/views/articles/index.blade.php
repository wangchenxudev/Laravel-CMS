<x-app-layout title="Articles">
  <div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <p class="text-sm font-medium text-slate-500">Content & Workflow</p>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Articles</h1>
      </div>
      <a href="{{ route('articles.create') }}" 
        class="inline-flex items-center gap-2 rounded bg-[#1890FF] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#40a9ff] active:scale-95 transition-all">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        New Article
      </a>
    </div>

    <!-- Data Table Container -->
    <div class="overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
      <!-- Search, Filter & Bulk Actions Bar -->
      <div class="border-b border-slate-200 bg-slate-50/50 p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <!-- Left: Search and Filters -->
        <div class="flex flex-wrap items-center gap-3 flex-1 max-w-2xl">
          <!-- Search Input -->
          <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </span>
            <input type="text" placeholder="Search titles..." 
              class="block w-full pl-9 pr-3 py-1.5 text-sm rounded border border-slate-300 bg-white placeholder:text-slate-400 focus:border-[#1890FF] focus:ring-2 focus:ring-[#1890FF]/20 focus:outline-none transition-all" />
          </div>

          <!-- Status Filter -->
          <div class="w-full sm:w-44">
            <select class="block w-full px-3 py-1.5 text-sm rounded border border-slate-300 bg-white focus:border-[#1890FF] focus:ring-2 focus:ring-[#1890FF]/20 focus:outline-none transition-all">
              <option value="">All statuses</option>
              <option value="draft">Draft</option>
              <option value="pending_review">Pending</option>
              <option value="published">Published</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>

        <!-- Right: Bulk Actions -->
        <div class="flex items-center gap-2">
          <select disabled class="block px-3 py-1.5 text-sm rounded border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed">
            <option>Bulk Actions</option>
            <option>Delete selected</option>
            <option>Submit for review</option>
          </select>
          <x-ui.button variant="secondary" class="py-1.5 text-xs bg-slate-50 text-slate-400 border-slate-200 cursor-not-allowed" disabled>Apply</x-ui.button>
        </div>
      </div>

      <!-- Main Grid/Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
            <tr>
              <th scope="col" class="w-12 px-6 py-3">
                <input type="checkbox" disabled class="rounded border-slate-300 text-[#1890FF] focus:ring-[#1890FF] cursor-not-allowed" />
              </th>
              <th scope="col" class="px-6 py-3">Title</th>
              <th scope="col" class="px-6 py-3">Summary</th>
              <th scope="col" class="px-6 py-3">Updated</th>
              <th scope="col" class="px-6 py-3">Status</th>
              <th scope="col" class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 bg-white">
            @forelse ($articles as $article)
              <tr class="hover:bg-slate-50/50 transition-colors">
                <!-- Checkbox -->
                <td class="px-6 py-4">
                  <input type="checkbox" disabled class="rounded border-slate-300 text-[#1890FF] focus:ring-[#1890FF] cursor-not-allowed" />
                </td>
                
                <!-- Title -->
                <td class="px-6 py-4 font-semibold text-slate-900 max-w-xs truncate">
                  <a href="{{ route('articles.show', $article) }}" class="hover:text-[#1890FF] transition-colors">
                    {{ $article->title }}
                  </a>
                </td>
                
                <!-- Summary -->
                <td class="px-6 py-4 text-slate-500 max-w-sm truncate">
                  {{ $article->summary ?? 'No summary provided' }}
                </td>

                <!-- Updated At -->
                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                  {{ $article->updated_at->format('Y-m-d H:i') }}
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4 whitespace-nowrap">
                  @php
                    $status = $article->status->value;
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
                  <span class="inline-flex items-center rounded border px-2 py-0.5 text-xs font-semibold {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $statusLabels[$status] ?? $status }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-medium space-x-2">
                  <a href="{{ route('articles.show', $article) }}" class="text-[#1890FF] hover:text-[#40a9ff] transition-colors">View</a>
                  @if ($status === 'draft' || $status === 'rejected')
                    <a href="{{ route('articles.edit', $article) }}" class="text-slate-600 hover:text-slate-900 transition-colors">Edit</a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                  <svg class="mx-auto h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="mt-2 text-sm">You haven't written any articles yet.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-4">
        {{ $articles->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
