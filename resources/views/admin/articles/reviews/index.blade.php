<x-app-layout title="Article Reviews">
  <div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col gap-2">
      <p class="text-sm font-medium text-slate-500">Content Safety & Workflow</p>
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pending Reviews</h1>
    </div>

    <!-- Data Table Card -->
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
            <input type="text" placeholder="Filter by title..." 
              class="block w-full pl-9 pr-3 py-1.5 text-sm rounded border border-slate-300 bg-white placeholder:text-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all" />
          </div>

          <!-- Quick Stats Tag -->
          <div class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs px-2.5 py-1.5 rounded font-semibold">
            <span>Pending: {{ $articles->total() }}</span>
          </div>
        </div>

        <!-- Right: Actions Placeholders -->
        <div class="flex items-center gap-2">
          <select disabled class="block px-3 py-1.5 text-sm rounded border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed">
            <option>Bulk Actions</option>
            <option>Approve selected</option>
            <option>Reject selected</option>
          </select>
          <x-ui.button variant="secondary" class="py-1.5 text-xs bg-slate-50 text-slate-400 border-slate-200 cursor-not-allowed" disabled>Apply</x-ui.button>
        </div>
      </div>

      <!-- Main Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
            <tr>
              <th scope="col" class="w-12 px-6 py-3">
                <input type="checkbox" disabled class="rounded border-slate-300 text-brand-500 focus:ring-brand-500 cursor-not-allowed" />
              </th>
              <th scope="col" class="px-6 py-3">Title</th>
              <th scope="col" class="px-6 py-3">Author</th>
              <th scope="col" class="px-6 py-3">Submitted</th>
              <th scope="col" class="px-6 py-3">Status</th>
              <th scope="col" class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 bg-white">
            @forelse ($articles as $article)
              <tr class="hover:bg-slate-50/50 transition-colors">
                <!-- Checkbox -->
                <td class="px-6 py-4">
                  <input type="checkbox" disabled class="rounded border-slate-300 text-brand-500 focus:ring-brand-500 cursor-not-allowed" />
                </td>

                <!-- Title -->
                <td class="px-6 py-4 font-semibold text-slate-900 max-w-sm truncate">
                  <a href="{{ route('admin.articles.show', $article) }}" class="hover:text-brand-500 transition-colors">
                    {{ $article->title }}
                  </a>
                </td>

                <!-- Author -->
                <td class="px-6 py-4 text-slate-600 font-medium">
                  {{ $article->author->name }}
                </td>

                <!-- Created Time -->
                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                  {{ $article->created_at->diffForHumans() }}
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center rounded border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-800">
                    Pending
                  </span>
                </td>

                <!-- Action Button -->
                <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-medium">
                  <a href="{{ route('admin.articles.show', $article) }}" 
                    class="rounded bg-brand-500 px-3 py-1.5 text-white shadow-sm hover:bg-brand-600 active:scale-95 transition-all">
                    Review Submission
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                  <svg class="mx-auto h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <p class="mt-2 text-sm">The review queue is currently empty.</p>
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
