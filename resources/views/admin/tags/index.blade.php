<x-app-layout title="Manage Tags">
  <div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div class="flex flex-col gap-2">
        <p class="text-sm font-medium text-slate-500">Taxonomy & Classification</p>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manage Tags</h1>
        <p class="text-sm text-slate-500">Tags created here are the only ones authors can choose when tagging articles.</p>
      </div>
      <x-ui.button type="button" onclick="window.location='{{ route('admin.tags.create') }}'">
        New Tag
      </x-ui.button>
    </div>

    <!-- Data Table Card -->
    <div class="overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
            <tr>
              <th scope="col" class="px-6 py-3">Name</th>
              <th scope="col" class="px-6 py-3">Slug</th>
              <th scope="col" class="px-6 py-3">Articles</th>
              <th scope="col" class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 bg-white">
            @forelse ($tags as $tag)
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 font-semibold text-slate-900">
                  <span class="inline-flex items-center rounded border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs font-semibold text-slate-600">
                    {{ $tag->name }}
                  </span>
                </td>
                <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $tag->slug }}</td>
                <td class="px-6 py-4 text-slate-600 font-medium">{{ $tag->articles_count }}</td>
                <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-medium">
                  <div class="inline-flex items-center gap-2">
                    <a href="{{ route('admin.tags.edit', $tag) }}"
                      class="rounded border border-slate-200 bg-white px-3 py-1.5 text-slate-700 shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                      Edit
                    </a>
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}"
                      onsubmit="return confirm('Delete the tag &quot;{{ $tag->name }}&quot;? It will be removed from all articles.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="rounded bg-rose-600 px-3 py-1.5 text-white shadow-sm hover:bg-rose-500 active:scale-95 transition-all cursor-pointer">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                  <p class="text-sm">No tags yet. Create your first tag to let authors classify articles.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-4">
        {{ $tags->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
