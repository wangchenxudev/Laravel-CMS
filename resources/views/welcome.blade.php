<x-app-layout title="Welcome">
  <div class="mx-auto flex min-h-[75vh] max-w-7xl items-center px-4 sm:px-6 lg:px-8 py-12 md:py-24">
    <div class="w-full max-w-3xl">
      <div class="inline-flex items-center gap-2 rounded bg-[#1890FF]/10 px-3 py-1 text-xs font-semibold text-[#1890FF] uppercase tracking-wider mb-6">
        <span class="flex h-2 w-2 rounded-full bg-[#1890FF]"></span>
        Enterprise Content Management System
      </div>

      <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
        Secure, Efficient, and Professional<br>
        <span class="text-[#1890FF]">Publishing & Review Platform</span>
      </h1>
      
      <p class="mt-6 max-w-2xl text-base sm:text-lg leading-relaxed text-slate-600">
        Provides structured role-based access control, a professional editorial submission workflow, and comprehensive administrator reviews. Help your team build and distribute high-quality content securely.
      </p>

      <div class="mt-10 flex flex-wrap gap-4">
        @guest
          <a href="{{ route('register') }}"
            class="rounded bg-[#1890FF] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#40a9ff] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1890FF] active:scale-95 transition-all">
            Get Started Free
          </a>
          <a href="{{ route('login') }}"
            class="rounded border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 active:scale-95 transition-all">
            Login Console
          </a>
        @else
          <a href="{{ route('dashboard') }}"
            class="rounded bg-[#1890FF] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#40a9ff] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1890FF] active:scale-95 transition-all">
            Go to Dashboard
          </a>
        @endguest
        <a href="{{ route('published.articles.index') }}"
          class="rounded border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition-colors">
          Browse Articles →
        </a>
      </div>
    </div>
  </div>
</x-app-layout>
