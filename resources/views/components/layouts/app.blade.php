<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200 flex flex-col" >

{{-- The navbar with `sticky` and `full-width` --}}
<x-nav sticky full-width>

    <x-slot:brand>
        {{-- Brand --}}
        <div>
            <a href="{{ route('profile.show', $apartment->uuid) }}" wire:navigate>
                <!-- Hidden when collapsed -->
                <div>
                    <div class="flex items-center gap-2">
                        <livewire:logo />
                        <span class="font-bold text-3xl me-3 bg-gradient-to-r from-black to-gray-200 bg-clip-text text-transparent ">
                            FV 2
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </x-slot:brand>

    {{-- Right side actions --}}
    <x-slot:actions>
        <x-button label="Messages" icon="m-information-circle" link="{{route('profile.about', $apartment->uuid)}}" class="btn-ghost btn-sm" responsive />
    </x-slot:actions>
</x-nav>

    {{-- MAIN --}}
    <x-main full-width >
        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>


    {{-- FOOTER --}}
    <footer class="footer footer-center bg-gray-200 text-base-content p-4 fixed bottom-0">
        <aside>
            <p> تم تصميم و تنفيذ من قبل فريق عمل  <a  href="https://badinansoft.com" target="_blank" class="font-bold text-red-700"> بادينان سوفت </a> </p>
        </aside>
    </footer>
</body>
</html>
