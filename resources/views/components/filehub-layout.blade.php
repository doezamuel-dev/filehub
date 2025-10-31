<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FileHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js x-cloak styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Error alert animations */
        @keyframes slide-in-from-right-full {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slide-out-to-right-full {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .animate-in {
            animation: slide-in-from-right-full 0.3s ease-out;
        }
        
        .slide-out-to-right-full {
            animation: slide-out-to-right-full 0.2s ease-in;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: true, ...themeManager() }" x-init="initTheme()" @toggle-theme="toggleTheme()">
    <div class="min-h-screen flex">
        <!-- Left Sidebar -->
        <div 
            class="w-16 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col items-center py-4 space-y-6 transition-all duration-300 ease-in-out fixed left-0 top-0 h-full z-40"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Logo -->
            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
            </div>

            <!-- Navigation Icons -->
            <div class="flex flex-col space-y-4" x-data="sidebarNavigation()">
                <!-- Home -->
                <div class="relative group">
                    <button 
                        @click="handleHomeClick()"
                        class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors relative"
                        :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400': homeSubmenuOpen }"
                        title="Single click: Refresh | Double click: Open menu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <!-- Double-click hint indicator -->
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full animate-pulse opacity-70"></div>
                    </button>
                    
                    <!-- Tooltip -->
                    <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        Double-click for menu
                        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-0 h-0 border-t-4 border-b-4 border-r-4 border-transparent border-r-gray-900 dark:border-r-gray-700"></div>
                    </div>
                    
                    <!-- Home Submenu -->
                    <div 
                        x-show="homeSubmenuOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-2"
                        class="absolute left-full top-0 ml-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                        @click.away="homeSubmenuOpen = false"
                    >
                        <div class="py-2">
                            <a href="{{ route('home.show', 'recent-files') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Recent Files</a>
                            <a href="{{ route('home.show', 'shared-with-me') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Shared with Me</a>
                            <a href="{{ route('home.show', 'starred') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Starred</a>
                            <a href="{{ route('home.show', 'trash') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Trash</a>
                        </div>
                    </div>
                </div>

                <!-- Folder -->
                <div class="relative group">
                    <button 
                        @click="handleFolderClick()"
                        class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors relative"
                        :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400': folderSubmenuOpen }"
                        title="Single click: Refresh | Double click: Open menu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        <!-- Double-click hint indicator -->
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full animate-pulse opacity-70"></div>
                    </button>
                    
                    <!-- Tooltip -->
                    <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        Double-click for menu
                        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-1 w-0 h-0 border-t-4 border-b-4 border-r-4 border-transparent border-r-gray-900 dark:border-r-gray-700"></div>
                    </div>
                    
                    <!-- Folder Submenu -->
                    <div 
                        x-show="folderSubmenuOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-x-2"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-2"
                        class="absolute left-full top-0 ml-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                        @click.away="folderSubmenuOpen = false"
                    >
                        <div class="py-2">
                            <a href="{{ route('folders.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">My folders</a>
                            <a href="{{ route('folder.show', 'my-files') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">My Files</a>
                            <a href="{{ route('folder.show', 'documents') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Documents</a>
                            <a href="{{ route('folder.show', 'pictures') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Pictures</a>
                            <a href="{{ route('folder.show', 'videos') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Videos</a>
                            <a href="{{ route('folder.show', 'music') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Music</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <button onclick="openCreateFolderModal()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Create New Folder</button>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <a href="{{ route('notifications.index') }}" 
                   class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors relative"
                   x-data="notificationBadge()"
                   x-init="loadUnreadCount()"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"/>
                    </svg>
                    <!-- Notification Badge -->
                    <span x-show="unreadCount > 0" 
                          x-text="unreadCount > 99 ? '99+' : unreadCount"
                          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium"
                    ></span>
                </a>

                <!-- App Launcher -->
                <button class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>

                <!-- Desktop -->
                <button class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </button>

                <!-- Help -->
                <a href="{{ route('help.index') }}" 
                   class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>
            </div>

            <!-- User Avatar at bottom -->
            <div class="mt-auto">
                <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                    {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out" :class="sidebarOpen ? 'ml-16' : 'ml-0'">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Sidebar Toggle Button and Logo -->
                    <div class="flex items-center space-x-4">
                        <!-- Sidebar Toggle Button -->
                        <button 
                            @click="sidebarOpen = !sidebarOpen"
                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            :title="sidebarOpen ? 'Hide Sidebar' : 'Show Sidebar'"
                        >
                            <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                            </svg>
                            <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                        </button>
                        
                        <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">FileHub</h1>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-md mx-8" x-data="searchData()">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                x-model="searchQuery"
                                @input="search()"
                                @focus="showResults = true"
                                @blur="setTimeout(() => showResults = false, 200)"
                                placeholder="Search files and folders..." 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            >
                            
                            <!-- Search Results Dropdown -->
                            <div 
                                x-show="showResults && (files.length > 0 || folders.length > 0)"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                                class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto"
                                @click.away="showResults = false"
                            >
                                <!-- Files Section -->
                                <div x-show="files.length > 0" class="p-3">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Files</div>
                                    <template x-for="file in files" :key="file.id">
                                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer" @click="openFile(file)">
                                            <div class="w-8 h-8 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate" x-text="file.original_name"></div>
                                                <div class="text-xs text-gray-500" x-text="file.formatted_size"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Folders Section -->
                                <div x-show="folders.length > 0" class="p-3 border-t border-gray-100">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Folders</div>
                                    <template x-for="folder in folders" :key="folder.id">
                                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer" @click="openFolder(folder)">
                                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate" x-text="folder.name"></div>
                                                <div class="text-xs text-gray-500" x-text="'Created ' + folder.created_at"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- No Results -->
                                <div x-show="searchQuery.length > 0 && files.length === 0 && folders.length === 0" class="p-6 text-center text-gray-500">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="text-sm">No files or folders found</p>
                                    <p class="text-xs">Try a different search term</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4" x-data="profileDropdown()">
                        <!-- Invite Members -->
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-sm font-medium">Invite members</span>
                        </button>

                        <!-- Upgrade Button -->
                        <button class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors">
                            Click to upgrade
                        </button>

                        <!-- User Avatar with Dropdown -->
                        <div class="relative">
                            <button 
                                @click="toggleProfileDropdown()"
                                class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-medium hover:bg-pink-600 transition-colors focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2"
                            >
                                {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
                            </button>
                            
                            <!-- Profile Dropdown Menu -->
                            <div 
                                x-show="profileDropdownOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                                class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                @click.away="profileDropdownOpen = false"
                            >
                                <div class="py-2">
                                    <!-- Profile Option -->
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profile
                                    </a>
                                    
                                    <!-- Storage Gauge -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs font-medium text-gray-600">Storage</span>
                                            <span class="text-xs text-gray-500">{{ number_format(Auth::user()->storage_usage_percentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ min(100, Auth::user()->storage_usage_percentage) }}%"></div>
                                        </div>
                                        <div class="flex justify-between mt-1">
                                            <span class="text-xs text-gray-500">{{ Auth::user()->formatStorageSize(Auth::user()->storage_used) }}</span>
                                            <span class="text-xs text-gray-500">{{ Auth::user()->formatStorageSize(Auth::user()->storage_limit) }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Light/Dark Mode Toggle -->
                                    <button 
                                        @click="toggleTheme()"
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                    >
                                        <svg x-show="!isDarkMode" class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                        <svg x-show="isDarkMode" class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <span x-text="isDarkMode ? 'Light Mode' : 'Dark Mode'"></span>
                                    </button>
                                    
                                    <!-- Divider -->
                                    <div class="border-t border-gray-100 my-1"></div>
                                    
                                    <!-- Logout Option -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button 
                                            type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        >
                                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div id="createFolderModal" x-data="createFolderData()" x-show="show" x-cloak>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Modal header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Create New Folder
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Enter a name for your new folder
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white px-4 pb-4 sm:p-6">
                        <!-- Folder name input -->
                        <div class="mb-4">
                            <label for="folder-name" class="block text-sm font-medium text-gray-700 mb-2">
                                Folder Name
                            </label>
                            <input type="text" 
                                   id="folder-name" 
                                   x-model="folderName"
                                   @keydown.enter="createFolder()"
                                   placeholder="Enter folder name"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Error message -->
                        <div x-show="error" class="mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success message -->
                        <div x-show="success" class="mb-4">
                            <div class="rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800" x-text="success"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" 
                                @click="createFolder()"
                                :disabled="!folderName || loading"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">Create Folder</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Move File Modal -->
    <div id="moveFileModal" x-data="moveFileData()" x-show="show" x-cloak>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Modal header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Move File
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-text="'Move ' + fileName + ' to a folder'">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white px-4 pb-4 sm:p-6">
                        <!-- Folder selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Destination Folder
                            </label>
                            <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md">
                                <!-- Root directory option -->
                                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" 
                                     @click="selectFolder(null)"
                                     :class="{ 'bg-blue-50 border-blue-200': selectedFolderId === null }">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Root Directory</div>
                                            <div class="text-xs text-gray-500">Move to main dashboard</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- User folders -->
                                <template x-for="folder in folders" :key="folder.id">
                                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" 
                                         @click="selectFolder(folder.id)"
                                         :class="{ 'bg-blue-50 border-blue-200': selectedFolderId === folder.id }">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900" x-text="folder.name"></div>
                                                <div class="text-xs text-gray-500" x-text="'Created ' + folder.created_at"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- No folders message -->
                                <div x-show="folders.length === 0" class="p-6 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm">No folders available</p>
                                    <p class="text-xs">Create a folder first to move files</p>
                                </div>
                            </div>
                        </div>

                        <!-- Error message -->
                        <div x-show="error" class="mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success message -->
                        <div x-show="success" class="mb-4">
                            <div class="rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800" x-text="success"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" 
                                @click="moveFile()"
                                :disabled="loading"
                                class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">Move File</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Moving...
                            </span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div id="shareModal" x-data="shareModalData()" x-show="show" x-cloak>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Modal header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Share File
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Share "<span class="font-medium" x-text="fileName"></span>" with other users
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white px-4 pb-4 sm:p-6">
                        <!-- Email input -->
                        <div class="mb-4">
                            <label for="share-email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="flex gap-2">
                                <input type="email" 
                                       id="share-email" 
                                       x-model="email"
                                       @keydown.enter="addEmail()"
                                       placeholder="Enter email address"
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <button type="button" 
                                        @click="addEmail()"
                                        :disabled="!email || loading"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Add
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Press Enter or click Add to share with this user
                            </p>
                        </div>

                        <!-- Shared users list -->
                        <div x-show="sharedUsers.length > 0" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Shared With
                            </label>
                            <div class="space-y-2">
                                <template x-for="user in sharedUsers" :key="user.id">
                                    <div class="flex items-center justify-between bg-gray-50 rounded-md px-3 py-2">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-medium text-blue-600" x-text="user.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900" x-text="user.name"></p>
                                                <p class="text-xs text-gray-500" x-text="user.email"></p>
                                            </div>
                                        </div>
                                        <button type="button" 
                                                @click="removeUser(user.id)"
                                                class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Error message -->
                        <div x-show="error" class="mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success message -->
                        <div x-show="success" class="mb-4">
                            <div class="rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800" x-text="success"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Files Modal -->
    <div id="shareFilesModal" x-data="shareFilesData()" x-show="show" x-cloak>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Modal header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Select Files to Share
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Choose the files you want to share
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white px-4 pb-4 sm:p-6">
                        <!-- File selection -->
                        <div class="mb-4">
                            <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md">
                                <!-- Files list -->
                                <template x-for="file in files" :key="file.id">
                                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" 
                                         @click="toggleFileSelection(file.id)"
                                         :class="{ 'bg-blue-50 border-blue-200': selectedFiles.includes(file.id) }">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900" x-text="file.original_name"></div>
                                                <div class="text-xs text-gray-500" x-text="file.formatted_size"></div>
                                            </div>
                                            <div x-show="selectedFiles.includes(file.id)" class="text-blue-600">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- No files message -->
                                <div x-show="files.length === 0" class="p-6 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm">No files available</p>
                                </div>
                            </div>
                        </div>

                        <!-- Error message -->
                        <div x-show="error" class="mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" 
                                @click="proceedToEmailModal()"
                                :disabled="selectedFiles.length === 0"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            Share Selected Files (<span x-text="selectedFiles.length"></span>)
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Folders Modal -->
    <div id="shareFoldersModal" x-data="shareFoldersData()" x-show="show" x-cloak>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Modal header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Select Folders to Share
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Choose the folders you want to share
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white px-4 pb-4 sm:p-6">
                        <!-- Folder selection -->
                        <div class="mb-4">
                            <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md">
                                <!-- Folders list -->
                                <template x-for="folder in folders" :key="folder.id">
                                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" 
                                         @click="toggleFolderSelection(folder.id)"
                                         :class="{ 'bg-blue-50 border-blue-200': selectedFolders.includes(folder.id) }">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900" x-text="folder.name"></div>
                                                <div class="text-xs text-gray-500" x-text="'Created ' + folder.created_at"></div>
                                            </div>
                                            <div x-show="selectedFolders.includes(folder.id)" class="text-blue-600">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- No folders message -->
                                <div x-show="folders.length === 0" class="p-6 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm">No folders available</p>
                                </div>
                            </div>
                        </div>

                        <!-- Error message -->
                        <div x-show="error" class="mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" 
                                @click="proceedToEmailModal()"
                                :disabled="selectedFolders.length === 0"
                                class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            Share Selected Folders (<span x-text="selectedFolders.length"></span>)
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Email Modal -->
    <div id="shareEmailModal" x-data="shareEmailData()" x-show="show" x-cloak>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Modal header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    Share with Email
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-text="shareSummary">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal body -->
                    <div class="bg-white px-4 pb-4 sm:p-6">
                        <!-- Email input -->
                        <div class="mb-4">
                            <label for="share-email" class="block text-sm font-medium text-gray-700 mb-2">
                                Recipient Email Address
                            </label>
                            <input type="email" 
                                   id="share-email" 
                                   x-model="email"
                                   @keydown.enter="shareItems()"
                                   placeholder="Enter email address"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                        </div>

                        <!-- Error message -->
                        <div x-show="error" class="mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800" x-text="error"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success message -->
                        <div x-show="success" class="mb-4">
                            <div class="rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800" x-text="success"></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" 
                                @click="shareItems()"
                                :disabled="!email || loading"
                                class="inline-flex w-full justify-center rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">Share</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sharing...
                            </span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sidebarNavigation() {
            return {
                homeSubmenuOpen: false,
                folderSubmenuOpen: false,
                homeClickCount: 0,
                folderClickCount: 0,
                homeClickTimer: null,
                folderClickTimer: null,

                handleHomeClick() {
                    this.homeClickCount++;
                    
                    if (this.homeClickCount === 1) {
                        // First click - start timer
                        this.homeClickTimer = setTimeout(() => {
                            // Single click - refresh page
                            window.location.reload();
                            this.homeClickCount = 0;
                        }, 300);
                    } else if (this.homeClickCount === 2) {
                        // Double click - open submenu
                        clearTimeout(this.homeClickTimer);
                        this.homeSubmenuOpen = !this.homeSubmenuOpen;
                        this.folderSubmenuOpen = false; // Close other submenu
                        this.homeClickCount = 0;
                    }
                },

                handleFolderClick() {
                    this.folderClickCount++;
                    
                    if (this.folderClickCount === 1) {
                        // First click - start timer
                        this.folderClickTimer = setTimeout(() => {
                            // Single click - refresh page
                            window.location.reload();
                            this.folderClickCount = 0;
                        }, 300);
                    } else if (this.folderClickCount === 2) {
                        // Double click - open submenu
                        clearTimeout(this.folderClickTimer);
                        this.folderSubmenuOpen = !this.folderSubmenuOpen;
                        this.homeSubmenuOpen = false; // Close other submenu
                        this.folderClickCount = 0;
                    }
                }
            }
        }

        function profileDropdown() {
            return {
                profileDropdownOpen: false,

                toggleProfileDropdown() {
                    this.profileDropdownOpen = !this.profileDropdownOpen;
                },

                toggleTheme() {
                    // Dispatch event to body element to toggle theme
                    this.$dispatch('toggle-theme');
                }
            }
        }

        function shareModalData() {
            return {
                show: false,
                fileId: null,
                fileName: '',
                email: '',
                sharedUsers: [],
                loading: false,
                error: '',
                success: '',

                init() {
                    // Listen for the open-share-modal event on window
                    window.addEventListener('open-share-modal', (event) => {
                        console.log('Share modal received event:', event.detail);
                        this.fileId = event.detail.fileId;
                        this.fileName = event.detail.fileName;
                        this.show = true;
                        this.error = '';
                        this.success = '';
                        this.loadSharedUsers();
                    });
                },

                closeModal() {
                    this.show = false;
                    this.email = '';
                    this.error = '';
                    this.success = '';
                },

                async loadSharedUsers() {
                    try {
                        const response = await fetch(`/files/${this.fileId}/shared-users`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.sharedUsers = data.users || [];
                        }
                    } catch (error) {
                        console.error('Failed to load shared users:', error);
                    }
                },

                async addEmail() {
                    if (!this.email || this.loading) return;

                    this.loading = true;
                    this.error = '';
                    this.success = '';

                    try {
                        const response = await fetch(`/files/${this.fileId}/share`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                email: this.email
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.success = data.message;
                            this.email = '';
                            this.loadSharedUsers(); // Reload the list
                        } else {
                            this.error = data.message || 'Failed to share file';
                        }
                    } catch (error) {
                        this.error = 'Failed to share file: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async removeUser(userId) {
                    try {
                        const response = await fetch(`/files/${this.fileId}/unshare`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                user_id: userId
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.success = data.message;
                            this.loadSharedUsers(); // Reload the list
                        } else {
                            this.error = data.message || 'Failed to remove user';
                        }
                    } catch (error) {
                        this.error = 'Failed to remove user: ' + error.message;
                    }
                }
            }
        }

        function createFolderData() {
            return {
                show: false,
                folderName: '',
                loading: false,
                error: '',
                success: '',

                init() {
                    window.addEventListener('open-create-folder-modal', () => {
                        this.show = true;
                        this.error = '';
                        this.success = '';
                        this.folderName = '';
                        // focus input after frame
                        requestAnimationFrame(() => {
                            const input = document.getElementById('folder-name');
                            if (input) input.focus();
                        });
                    });
                },

                closeModal() {
                    this.show = false;
                    this.loading = false;
                    this.error = '';
                    this.success = '';
                    this.folderName = '';
                },

                async createFolder() {
                    if (!this.folderName || this.loading) return;
                    this.loading = true;
                    this.error = '';
                    this.success = '';

                    try {
                        const response = await fetch('/folders/create', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ name: this.folderName })
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.success = data.message || 'Folder created';
                            // Refresh to show the new folder/files
                            setTimeout(() => { window.location.reload(); }, 800);
                        } else {
                            this.error = data.message || 'Failed to create folder';
                        }
                    } catch (err) {
                        this.error = 'Failed to create folder: ' + err.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }

        function moveFileData() {
            return {
                show: false,
                fileId: null,
                fileName: '',
                folders: [],
                selectedFolderId: null,
                loading: false,
                error: '',
                success: '',

                init() {
                    window.addEventListener('open-move-file-modal', (event) => {
                        this.fileId = event.detail.fileId;
                        this.fileName = event.detail.fileName;
                        this.selectedFolderId = null;
                        this.error = '';
                        this.success = '';
                        this.loadFolders();
                        this.show = true;
                    });
                },

                closeModal() {
                    this.show = false;
                    this.loading = false;
                    this.error = '';
                    this.success = '';
                    this.fileId = null;
                    this.fileName = '';
                    this.selectedFolderId = null;
                    this.folders = [];
                },

                async loadFolders() {
                    try {
                        const response = await fetch('/folders', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                this.folders = data.folders;
                            }
                        }
                    } catch (error) {
                        console.error('Failed to load folders:', error);
                    }
                },

                selectFolder(folderId) {
                    this.selectedFolderId = folderId;
                },

                async moveFile() {
                    if (this.loading) return;
                    this.loading = true;
                    this.error = '';
                    this.success = '';

                    try {
                        const response = await fetch(`/files/${this.fileId}/move`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ 
                                folder_id: this.selectedFolderId 
                            })
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.success = data.message || 'File moved successfully';
                            // Refresh to show the updated file location
                            setTimeout(() => { window.location.reload(); }, 800);
                        } else {
                            this.error = data.message || 'Failed to move file';
                        }
                    } catch (err) {
                        this.error = 'Failed to move file: ' + err.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }

        function openCreateFolderModal() {
            window.dispatchEvent(new CustomEvent('open-create-folder-modal'));
        }

        // Global function to open share modal
        function openShareModal(fileId, fileName) {
            console.log('openShareModal called with:', fileId, fileName);
            // Dispatch event to open modal
            window.dispatchEvent(new CustomEvent('open-share-modal', {
                detail: { fileId, fileName }
            }));
        }

        // Global function to open move file modal
        function openMoveFileModal(fileId, fileName) {
            console.log('openMoveFileModal called with:', fileId, fileName);
            // Dispatch event to open modal
            window.dispatchEvent(new CustomEvent('open-move-file-modal', {
                detail: { fileId, fileName }
            }));
        }

        // Share menu functionality
        function shareMenu() {
            return {
                shareMenuOpen: false,

                toggleShareMenu() {
                    this.shareMenuOpen = !this.shareMenuOpen;
                },

                openShareFilesModal() {
                    this.shareMenuOpen = false;
                    window.dispatchEvent(new CustomEvent('open-share-files-modal'));
                },

                openShareFoldersModal() {
                    this.shareMenuOpen = false;
                    window.dispatchEvent(new CustomEvent('open-share-folders-modal'));
                }
            }
        }

        // Share files modal functionality
        function shareFilesData() {
            return {
                show: false,
                files: [],
                selectedFiles: [],
                error: '',

                init() {
                    window.addEventListener('open-share-files-modal', () => {
                        this.show = true;
                        this.error = '';
                        this.selectedFiles = [];
                        this.loadFiles();
                    });
                },

                closeModal() {
                    this.show = false;
                    this.error = '';
                    this.selectedFiles = [];
                    this.files = [];
                },

                async loadFiles() {
                    try {
                        const response = await fetch('/api/files', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                this.files = data.files;
                            }
                        }
                    } catch (error) {
                        console.error('Failed to load files:', error);
                        this.error = 'Failed to load files';
                    }
                },

                toggleFileSelection(fileId) {
                    const index = this.selectedFiles.indexOf(fileId);
                    if (index > -1) {
                        this.selectedFiles.splice(index, 1);
                    } else {
                        this.selectedFiles.push(fileId);
                    }
                },

                proceedToEmailModal() {
                    if (this.selectedFiles.length > 0) {
                        this.closeModal();
                        window.dispatchEvent(new CustomEvent('open-share-email-modal', {
                            detail: { 
                                type: 'files', 
                                items: this.selectedFiles,
                                files: this.files.filter(f => this.selectedFiles.includes(f.id))
                            }
                        }));
                    }
                }
            }
        }

        // Share folders modal functionality
        function shareFoldersData() {
            return {
                show: false,
                folders: [],
                selectedFolders: [],
                error: '',

                init() {
                    window.addEventListener('open-share-folders-modal', () => {
                        this.show = true;
                        this.error = '';
                        this.selectedFolders = [];
                        this.loadFolders();
                    });
                },

                closeModal() {
                    this.show = false;
                    this.error = '';
                    this.selectedFolders = [];
                    this.folders = [];
                },

                async loadFolders() {
                    try {
                        const response = await fetch('/folders', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                this.folders = data.folders;
                            }
                        }
                    } catch (error) {
                        console.error('Failed to load folders:', error);
                        this.error = 'Failed to load folders';
                    }
                },

                toggleFolderSelection(folderId) {
                    const index = this.selectedFolders.indexOf(folderId);
                    if (index > -1) {
                        this.selectedFolders.splice(index, 1);
                    } else {
                        this.selectedFolders.push(folderId);
                    }
                },

                proceedToEmailModal() {
                    if (this.selectedFolders.length > 0) {
                        this.closeModal();
                        window.dispatchEvent(new CustomEvent('open-share-email-modal', {
                            detail: { 
                                type: 'folders', 
                                items: this.selectedFolders,
                                folders: this.folders.filter(f => this.selectedFolders.includes(f.id))
                            }
                        }));
                    }
                }
            }
        }

        // Share email modal functionality
        function shareEmailData() {
            return {
                show: false,
                shareType: '',
                selectedItems: [],
                items: [],
                email: '',
                loading: false,
                error: '',
                success: '',

                get shareSummary() {
                    if (this.shareType === 'files') {
                        return `Share ${this.selectedItems.length} file(s)`;
                    } else if (this.shareType === 'folders') {
                        return `Share ${this.selectedItems.length} folder(s)`;
                    }
                    return '';
                },

                init() {
                    window.addEventListener('open-share-email-modal', (event) => {
                        this.shareType = event.detail.type;
                        this.selectedItems = event.detail.items;
                        this.items = event.detail[this.shareType];
                        this.email = '';
                        this.error = '';
                        this.success = '';
                        this.show = true;
                    });
                },

                closeModal() {
                    this.show = false;
                    this.loading = false;
                    this.error = '';
                    this.success = '';
                    this.shareType = '';
                    this.selectedItems = [];
                    this.items = [];
                    this.email = '';
                },

                async shareItems() {
                    if (!this.email || this.loading) return;
                    this.loading = true;
                    this.error = '';
                    this.success = '';

                    try {
                        if (this.shareType === 'files') {
                            await this.shareFiles();
                        } else if (this.shareType === 'folders') {
                            await this.shareFolders();
                        }
                    } catch (err) {
                        this.error = 'Failed to share: ' + err.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async shareFiles() {
                    const promises = this.selectedItems.map(fileId => 
                        fetch(`/files/${fileId}/share`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ email: this.email })
                        })
                    );

                    const responses = await Promise.all(promises);
                    const results = await Promise.all(responses.map(r => r.json()));

                    const successful = results.filter(r => r.success).length;
                    const failed = results.length - successful;

                    if (successful > 0) {
                        this.success = `Successfully shared ${successful} file(s) with ${this.email}`;
                        if (failed > 0) {
                            this.error = `${failed} file(s) failed to share`;
                        }
                    } else {
                        this.error = 'Failed to share files';
                    }
                },

                async shareFolders() {
                    const promises = this.selectedItems.map(folderId => 
                        fetch(`/folders/${folderId}/share`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ email: this.email })
                        })
                    );

                    const responses = await Promise.all(promises);
                    const results = await Promise.all(responses.map(r => r.json()));

                    const successful = results.filter(r => r.success).length;
                    const failed = results.length - successful;

                    if (successful > 0) {
                        this.success = `Successfully shared ${successful} folder(s) with ${this.email}`;
                        if (failed > 0) {
                            this.error = `${failed} folder(s) failed to share`;
                        }
                    } else {
                        this.error = 'Failed to share folders';
                    }
                }
            }
        }

        // Theme management functionality
        function themeManager() {
            return {
                isDarkMode: false,

                initTheme() {
                    // Check for saved theme preference or default to light mode
                    this.isDarkMode = localStorage.getItem('darkMode') === 'true';
                    this.applyTheme();
                },

                toggleTheme() {
                    this.isDarkMode = !this.isDarkMode;
                    localStorage.setItem('darkMode', this.isDarkMode);
                    this.applyTheme();
                },

                applyTheme() {
                    if (this.isDarkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }

        // Notification badge functionality
        function notificationBadge() {
            return {
                unreadCount: 0,

                async loadUnreadCount() {
                    try {
                        const response = await fetch('/notifications/unread-count', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.unreadCount = data.count || 0;
                        }
                    } catch (error) {
                        console.error('Failed to load unread count:', error);
                    }
                }
            }
        }

        // Search functionality
        function searchData() {
            return {
                searchQuery: '',
                showResults: false,
                files: [],
                folders: [],
                searchTimeout: null,

                async search() {
                    if (this.searchQuery.length < 2) {
                        this.files = [];
                        this.folders = [];
                        return;
                    }

                    // Clear previous timeout
                    if (this.searchTimeout) {
                        clearTimeout(this.searchTimeout);
                    }

                    // Debounce search
                    this.searchTimeout = setTimeout(async () => {
                        try {
                            const response = await fetch(`/api/search?q=${encodeURIComponent(this.searchQuery)}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                this.files = data.files || [];
                                this.folders = data.folders || [];
                            }
                        } catch (error) {
                            console.error('Search failed:', error);
                            this.files = [];
                            this.folders = [];
                        }
                    }, 300);
                },

                openFile(file) {
                    window.location.href = `/files/${file.id}/view`;
                },

                openFolder(folder) {
                    window.location.href = `/folders/${folder.id}`;
                }
            }
        }

        // Global message functions
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            successDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(successDiv);
            setTimeout(() => successDiv.remove(), 3000);
        }

        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 5000);
        }

        // Transfer Modal Function
        function transferModal() {
            return {
                selectedFiles: [],
                targetEmail: '',
                loading: false,
                error: '',
                success: '',
                availableFiles: [],
                showFileSelection: true,
                showTransferModal: false,

                init() {
                    console.log('Transfer modal initialized');
                    this.loadUserFiles();
                    // Set up global function
                    window.openTransferModal = () => {
                        console.log('Opening transfer modal');
                        this.showTransferModal = true;
                    };
                },

                closeModal() {
                    this.showTransferModal = false;
                    this.selectedFiles = [];
                    this.targetEmail = '';
                    this.error = '';
                    this.success = '';
                    this.showFileSelection = true;
                },

                async loadUserFiles() {
                    try {
                        const response = await fetch('/api/user-files', {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.availableFiles = data.files || [];
                        }
                    } catch (error) {
                        console.error('Failed to load user files:', error);
                        this.error = 'Failed to load files';
                    }
                },

                toggleFileSelection(fileId) {
                    const index = this.selectedFiles.indexOf(fileId);
                    if (index > -1) {
                        this.selectedFiles.splice(index, 1);
                    } else {
                        this.selectedFiles.push(fileId);
                    }
                },

                isFileSelected(fileId) {
                    return this.selectedFiles.includes(fileId);
                },

                selectAllFiles() {
                    this.selectedFiles = this.availableFiles.map(file => file.id);
                },

                deselectAllFiles() {
                    this.selectedFiles = [];
                },

                nextStep() {
                    if (this.selectedFiles.length === 0) {
                        this.error = 'Please select at least one file to transfer';
                        return;
                    }
                    this.showFileSelection = false;
                    this.error = '';
                },

                backToFileSelection() {
                    this.showFileSelection = true;
                    this.error = '';
                },

                async transferFiles() {
                    if (!this.targetEmail || this.selectedFiles.length === 0 || this.loading) return;

                    this.loading = true;
                    this.error = '';
                    this.success = '';

                    try {
                        const response = await fetch('/files/transfer', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                file_ids: this.selectedFiles,
                                target_email: this.targetEmail
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.success = data.message;
                            // Close modal after success
                            setTimeout(() => {
                                this.closeModal();
                            }, 2000);
                        } else {
                            this.error = data.message || 'Failed to transfer files';
                        }
                    } catch (error) {
                        this.error = 'Failed to transfer files: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>

    <!-- Transfer Modal -->
    <x-transfer-modal></x-transfer-modal>
</body>
</html>
