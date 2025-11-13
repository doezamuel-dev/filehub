<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FILEHUB') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @php
            $manifestPath = public_path('build/manifest.json');
            $hotPath = public_path('hot');
            $hasViteAssets = file_exists($manifestPath) || file_exists($hotPath);
        @endphp
        @if ($hasViteAssets)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Tailwind CSS CDN Fallback -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {}
                    }
                }
            </script>
            <!-- Inline fallback styles for critical UI elements -->
            <style>
                * { box-sizing: border-box; }
                body { 
                    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                    margin: 0;
                    padding: 0;
                    color: #1f2937;
                }
                .min-h-screen { min-height: 100vh; }
                .flex { display: flex; }
                .flex-col { flex-direction: column; }
                .items-center { align-items: center; }
                .justify-center { justify-content: center; }
                .justify-between { justify-content: space-between; }
                .bg-gray-100 { background-color: #f3f4f6; }
                .bg-white { background-color: #ffffff; }
                .w-full { width: 100%; }
                .mt-4 { margin-top: 1rem; }
                .mt-6 { margin-top: 1.5rem; }
                .mr-3 { margin-right: 0.75rem; }
                .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
                .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
                .pt-6 { padding-top: 1.5rem; }
                .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
                .rounded-lg { border-radius: 0.5rem; }
                .overflow-hidden { overflow: hidden; }
                .block { display: block; }
                input[type="email"], input[type="password"], input[type="text"] {
                    width: 100%;
                    padding: 0.5rem 0.75rem;
                    border: 1px solid #d1d5db;
                    border-radius: 0.375rem;
                    font-size: 1rem;
                    margin-top: 0.25rem;
                }
                input[type="email"]:focus, input[type="password"]:focus, input[type="text"]:focus {
                    outline: none;
                    border-color: #4f46e5;
                    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
                }
                button[type="submit"], .primary-button {
                    background-color: #4f46e5;
                    color: white;
                    padding: 0.5rem 1.5rem;
                    border: none;
                    border-radius: 0.375rem;
                    font-weight: 500;
                    cursor: pointer;
                }
                button[type="submit"]:hover, .primary-button:hover {
                    background-color: #4338ca;
                }
                .underline { text-decoration: underline; }
                .text-sm { font-size: 0.875rem; }
                .text-gray-600 { color: #4b5563; }
                .text-gray-900 { color: #111827; }
                a:hover.text-gray-600 { color: #111827; }
                @media (min-width: 640px) {
                    [class*="sm:max-w-md"] { max-width: 28rem; }
                    [class*="sm:pt-0"] { padding-top: 0; }
                    [class*="sm:justify-center"] { justify-content: center; }
                }
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
