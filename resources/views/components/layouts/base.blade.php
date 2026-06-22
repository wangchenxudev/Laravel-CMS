@props(['title' => null, 'bodyClass' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name', 'CMS') }}</title>

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body {{ $attributes->merge(['class' => 'min-h-full font-sans text-slate-900 antialiased ' . $bodyClass]) }}>
  {{ $slot }}
</body>

</html>
