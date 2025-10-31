<x-filehub-layout>
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $pageName }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Page Header -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center space-x-4">
            <!-- Page Icon -->
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                @if($page === 'recent-files')
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @elseif($page === 'shared-with-me')
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                @elseif($page === 'starred')
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                @elseif($page === 'trash')
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                @endif
            </div>
            
            <!-- Page Info -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $pageName }}</h1>
                <p class="text-sm text-gray-500">0 items</p>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6" x-data="uploadMenu()">
        <div class="flex items-center space-x-4">
            <!-- Upload Button with Submenu -->
            <div class="relative">
                <button 
                    @click="toggleUploadMenu()"
                    class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-900 transition-colors flex items-center space-x-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span>Upload or drop</span>
                </button>
                
                <!-- Upload Submenu -->
                <div 
                    x-show="uploadMenuOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                    class="absolute left-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                    @click.away="uploadMenuOpen = false"
                >
                    <div class="py-2">
                        <!-- Files Option -->
                        <button 
                            @click="openFileSelector()"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Files
                        </button>
                        
                        <!-- Folders Option -->
                        <button 
                            @click="openFolderSelector()"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            Folders
                        </button>
                    </div>
                </div>
            </div>

            <!-- Create Folder Button -->
            <button onclick="openCreateFolderModal()" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Create folder</span>
            </button>

            <!-- Transfer Button -->
            <button onclick="openTransferModal()" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span>Transfer a copy</span>
            </button>

            <!-- Share Button -->
            <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                </svg>
                <span>Share</span>
            </button>

            <!-- Multi-Delete Button (only show if there are files) -->
            @if($files->count() > 0)
                @if($page === 'trash')
                    <!-- Trash: Permanent Delete All -->
                    <button onclick="bulkPermanentDeleteAll()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Delete All Forever</span>
                    </button>
                @else
                    <!-- Other pages: Move All to Trash -->
                    <button onclick="bulkMoveToTrashAll()" 
                            class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-700 transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Move All to Trash</span>
                    </button>
                @endif
            @endif

            <!-- Spacer -->
            <div class="flex-1"></div>

            <!-- View Options -->
            <button class="bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
        </div>
        
        <!-- Hidden File Inputs -->
        <input 
            type="file" 
            id="fileInput" 
            multiple 
            class="hidden" 
            @change="handleFileUpload($event)"
        >
        <input 
            type="file" 
            id="folderInput" 
            webkitdirectory 
            directory 
            multiple 
            class="hidden" 
            @change="handleFolderUpload($event)"
        >
    </div>

    <!-- Content Area -->
    @if($files->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ $files->count() }} items</h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($files as $file)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors cursor-pointer" 
                         ondblclick="openMoveFileModal({{ $file->id }}, {{ json_encode($file->original_name) }})"
                         title="Double-click to move file">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- File Icon -->
                                <div class="w-10 h-10 flex items-center justify-center">
                                    @if($file->file_type === 'image')
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @elseif($file->file_type === 'video')
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    @elseif($file->file_type === 'audio')
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                        </svg>
                                    @elseif($file->file_type === 'document')
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                
                                <!-- File Info -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $file->original_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $file->formatted_size }}</p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                @if($page === 'recent-files')
                                    <!-- Recent Files: View, Download, Share, Link, Delete -->
                                    <a href="{{ route('files.view', $file->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        View
                                    </a>
                                    
                                    <a href="{{ route('files.download', $file->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Download
                                    </a>
                                    
                                    <button onclick="toggleStar({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            :class="{ 'bg-yellow-50 border-yellow-300 text-yellow-700': {{ $file->is_starred ? 'true' : 'false' }} }">
                                        <svg class="w-3 h-3 mr-1" :class="{ 'fill-current': {{ $file->is_starred ? 'true' : 'false' }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        {{ $file->is_starred ? 'Unstar' : 'Star' }}
                                    </button>
                                    
                                    <button onclick="openShareModal({{ $file->id }}, {{ json_encode($file->original_name) }})" 
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Share
                                    </button>
                                    
                                    <button onclick="generateLink({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Link
                                    </button>
                                    
                                    <button onclick="deleteFile({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                @elseif($page === 'shared-with-me')
                                    <!-- Shared with Me: Download, Delete -->
                                    <a href="{{ route('files.download', $file->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Download
                                    </a>
                                    
                                    <button onclick="deleteFile({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                @elseif($page === 'starred')
                                    <!-- Starred: View, Delete -->
                                    <a href="{{ route('files.view', $file->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        View
                                    </a>
                                    
                                    <button onclick="deleteFile({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                @elseif($page === 'trash')
                                    <!-- Trash: View, Restore, Permanent Delete -->
                                    <a href="{{ route('files.view', $file->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        View
                                    </a>
                                    
                                    <button onclick="restoreFile({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Restore
                                    </button>
                                    
                                    <button onclick="permanentDeleteFile({{ $file->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete Forever
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-12">
            <div class="text-center">
                <!-- Page-specific Icon -->
                <div class="mx-auto w-24 h-24 text-gray-300 mb-6">
                    @if($page === 'recent-files')
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($page === 'shared-with-me')
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                        </svg>
                    @elseif($page === 'starred')
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @elseif($page === 'trash')
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>

                <!-- Page-specific Text -->
                @if($page === 'recent-files')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No recent files</h3>
                    <p class="text-gray-500 mb-4">Files you've opened recently will appear here</p>
                @elseif($page === 'shared-with-me')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No shared files</h3>
                    <p class="text-gray-500 mb-4">Files shared with you will appear here</p>
                @elseif($page === 'starred')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No starred files</h3>
                    <p class="text-gray-500 mb-4">Files you've starred will appear here</p>
                @elseif($page === 'trash')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Trash is empty</h3>
                    <p class="text-gray-500 mb-4">Deleted files will appear here</p>
                @endif
                
                <button class="text-blue-600 hover:text-blue-700 underline font-medium">
                    Upload files
                </button>
            </div>
        </div>
    @endif

    <script>
        function uploadMenu() {
            return {
                uploadMenuOpen: false,

                init() {
                    console.log('Upload menu initialized');
                    console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
                    console.log('User authenticated:', {{ auth()->check() ? 'true' : 'false' }});
                },

                toggleUploadMenu() {
                    this.uploadMenuOpen = !this.uploadMenuOpen;
                },

                openFileSelector() {
                    this.uploadMenuOpen = false;
                    document.getElementById('fileInput').click();
                },

                openFolderSelector() {
                    this.uploadMenuOpen = false;
                    document.getElementById('folderInput').click();
                },

                handleFileUpload(event) {
                    console.log('File upload handler triggered');
                    const files = event.target.files;
                    if (files.length > 0) {
                        this.uploadFiles(files, 'files');
                    }
                },

                handleFolderUpload(event) {
                    const files = event.target.files;
                    if (files.length > 0) {
                        this.uploadFiles(files, 'folders');
                    }
                },

                uploadFiles(files, type) {
                    console.log('Starting upload process...', files.length, 'files');
                    
                    // Check if we have large files that need special handling
                    const LARGE_FILE_THRESHOLD = 100 * 1024 * 1024; // 100MB
                    const hasLargeFiles = Array.from(files).some(file => file.size > LARGE_FILE_THRESHOLD);
                    
                    if (hasLargeFiles && files.length === 1) {
                        // Single large file - use special handling
                        console.log('Detected single large file, using special upload handling');
                        this.uploadLargeFile(files[0], type);
                        return;
                    }
                    
                    // For large batches, split into chunks of 50 files
                    const CHUNK_SIZE = 50;
                    const chunks = [];
                    for (let i = 0; i < files.length; i += CHUNK_SIZE) {
                        chunks.push(files.slice(i, i + CHUNK_SIZE));
                    }
                    
                    console.log(`Split into ${chunks.length} chunks of max ${CHUNK_SIZE} files each`);
                    
                    // Show loading state with progress
                    this.showUploadProgress(chunks.length);
                    
                    // Process chunks sequentially
                    this.processChunks(chunks, type, 0);
                },

                uploadLargeFile(file, type) {
                    console.log('Uploading large file:', file.name, 'Size:', (file.size / 1024 / 1024).toFixed(2) + 'MB');
                    
                    const formData = new FormData();
                    
                    // Add CSRF token with error handling
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        this.showErrorMessage('CSRF token not found. Please refresh the page.');
                        return;
                    }
                    
                    formData.append('_token', csrfToken.getAttribute('content'));
                    formData.append('upload_type', type);
                    formData.append('files[]', file);
                    
                    // Show progress bar
                    this.showUploadProgressBar(file.name, file.size);

                    // Upload large file with progress tracking
                    console.log('Sending large file to /upload...');
                    this.uploadWithProgress('/upload', formData, file.size, (progress) => {
                        this.updateProgressBar(progress);
                    })
                    .then(data => {
                        console.log('Large file upload response:', data);
                        this.hideUploadProgress();
                        if (data.success) {
                            this.showSuccessMessage(`Successfully uploaded ${file.name}`);
                            // Refresh the page to show new file
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showErrorMessage(data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        console.error('Large file upload error:', error);
                        this.hideUploadProgress();
                        this.showErrorMessage('Upload failed: ' + error.message);
                    });
                },

                processChunks(chunks, type, chunkIndex) {
                    if (chunkIndex >= chunks.length) {
                        this.hideUploadProgress();
                        this.showSuccessMessage(`Successfully uploaded all files!`);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                        return;
                    }
                    
                    const currentChunk = chunks[chunkIndex];
                    console.log(`Processing chunk ${chunkIndex + 1}/${chunks.length} with ${currentChunk.length} files`);
                    
                    const formData = new FormData();
                    
                    // Add CSRF token with error handling
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        this.showErrorMessage('CSRF token not found. Please refresh the page.');
                        return;
                    }
                    
                    formData.append('_token', csrfToken.getAttribute('content'));
                    formData.append('upload_type', type);
                    formData.append('chunk_index', chunkIndex);
                    formData.append('total_chunks', chunks.length);
                    
                    // Add files from current chunk
                    for (let i = 0; i < currentChunk.length; i++) {
                        formData.append('files[]', currentChunk[i]);
                        console.log('Added file:', currentChunk[i].name, 'Size:', currentChunk[i].size);
                    }

                    // Update progress
                    this.updateUploadProgress(chunkIndex + 1, chunks.length);

                    // Upload current chunk
                    console.log(`Sending chunk ${chunkIndex + 1}/${chunks.length} to /upload...`);
                    fetch('/upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        }
                    })
                    .then(response => {
                        console.log('Response received:', response.status, response.statusText);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server returned non-JSON response. This might be a server error.');
                        }
                        
                        return response.json();
                    })
                    .then(data => {
                        console.log('Chunk response data:', data);
                        if (data.success) {
                            // Process next chunk
                            this.processChunks(chunks, type, chunkIndex + 1);
                        } else {
                            this.hideUploadProgress();
                            this.showErrorMessage(data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        this.hideUploadProgress();
                        this.showErrorMessage('Upload failed: ' + error.message);
                    });
                },

                showUploadProgress(totalChunks = 1) {
                    // Create a progress indicator with chunk information
                    const loadingDiv = document.createElement('div');
                    loadingDiv.id = 'upload-loading';
                    loadingDiv.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    loadingDiv.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Uploading files...</div>
                                <div class="text-xs opacity-75" id="upload-progress-text">Preparing upload...</div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(loadingDiv);
                },

                updateUploadProgress(currentChunk, totalChunks) {
                    const progressText = document.getElementById('upload-progress-text');
                    if (progressText) {
                        progressText.textContent = `Processing chunk ${currentChunk}/${totalChunks}`;
                    }
                },

                // Upload with progress tracking using XMLHttpRequest
                uploadWithProgress(url, formData, totalSize, onProgress) {
                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        
                        // Track upload progress
                        xhr.upload.addEventListener('progress', (event) => {
                            if (event.lengthComputable) {
                                const progress = (event.loaded / event.total) * 100;
                                onProgress(progress);
                            }
                        });
                        
                        // Handle response
                        xhr.addEventListener('load', () => {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    resolve(response);
                                } catch (e) {
                                    reject(new Error('Invalid JSON response'));
                                }
                            } else {
                                reject(new Error(`HTTP error! status: ${xhr.status}`));
                            }
                        });
                        
                        // Handle errors
                        xhr.addEventListener('error', () => {
                            reject(new Error('Network error'));
                        });
                        
                        // Handle abort
                        xhr.addEventListener('abort', () => {
                            reject(new Error('Upload aborted'));
                        });
                        
                        // Start upload
                        xhr.open('POST', url);
                        
                        // Set headers AFTER open() but BEFORE send()
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                        
                        xhr.send(formData);
                    });
                },

                showUploadProgressBar(fileName, fileSize) {
                    // Create a progress bar for uploads
                    const progressDiv = document.createElement('div');
                    progressDiv.id = 'upload-progress';
                    progressDiv.className = 'fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg z-50 p-4 min-w-80';
                    const sizeInMB = (fileSize / 1024 / 1024).toFixed(2);
                    progressDiv.innerHTML = `
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">Uploading...</div>
                                    <div class="text-xs text-gray-500">${fileName} (${sizeInMB}MB)</div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                            </div>
                            
                            <!-- Progress Text -->
                            <div class="flex justify-between text-xs text-gray-500">
                                <span id="progress-text">0%</span>
                                <span id="progress-speed">Calculating...</span>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(progressDiv);
                },

                updateProgressBar(progress) {
                    const progressBar = document.getElementById('progress-bar');
                    const progressText = document.getElementById('progress-text');
                    const progressSpeed = document.getElementById('progress-speed');
                    
                    if (progressBar && progressText) {
                        const roundedProgress = Math.round(progress);
                        progressBar.style.width = `${roundedProgress}%`;
                        progressText.textContent = `${roundedProgress}%`;
                        
                        // Update speed indicator (simplified)
                        if (progressSpeed) {
                            if (progress < 10) {
                                progressSpeed.textContent = 'Starting...';
                            } else if (progress < 50) {
                                progressSpeed.textContent = 'Uploading...';
                            } else if (progress < 90) {
                                progressSpeed.textContent = 'Almost done...';
                            } else {
                                progressSpeed.textContent = 'Finishing...';
                            }
                        }
                    }
                },

                showLargeFileProgress(fileName, fileSize) {
                    // Create a special progress indicator for large files
                    const loadingDiv = document.createElement('div');
                    loadingDiv.id = 'upload-loading';
                    loadingDiv.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    const sizeInMB = (fileSize / 1024 / 1024).toFixed(2);
                    loadingDiv.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div>
                                <div class="text-sm font-medium">Uploading large file...</div>
                                <div class="text-xs opacity-75">${fileName} (${sizeInMB}MB)</div>
                                <div class="text-xs opacity-75 mt-1">This may take several minutes...</div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(loadingDiv);
                },

                hideUploadProgress() {
                    const loadingDiv = document.getElementById('upload-loading');
                    const progressDiv = document.getElementById('upload-progress');
                    if (loadingDiv) {
                        loadingDiv.remove();
                    }
                    if (progressDiv) {
                        progressDiv.remove();
                    }
                },

                showSuccessMessage(message) {
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
                },

                showErrorMessage(message) {
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
            }
        }

        // Global message functions for standalone JavaScript functions
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 3000);
        }

        // File operation functions
        function shareFile(fileId) {
            fetch(`/files/${fileId}/share`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                } else {
                    showErrorMessage(data.message || 'Failed to share file');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to share file: ' + error.message);
            });
        }

        function generateLink(fileId) {
            fetch(`/files/${fileId}/link`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Copy link to clipboard
                    navigator.clipboard.writeText(data.share_url).then(() => {
                        showSuccessMessage('Share link copied to clipboard!');
                    });
                } else {
                    showErrorMessage(data.message || 'Failed to generate link');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to generate link: ' + error.message);
            });
        }

        function deleteFile(fileId) {
            if (confirm('Are you sure you want to move this file to trash?')) {
                fetch(`/files/${fileId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        // Reload page to update file list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Failed to delete file');
                    }
                })
                .catch(error => {
                    showErrorMessage('Failed to delete file: ' + error.message);
                });
            }
        }

        function restoreFile(fileId) {
            fetch(`/files/${fileId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    // Reload page to update file list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorMessage(data.message || 'Failed to restore file');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to restore file: ' + error.message);
            });
        }

        function permanentDeleteFile(fileId) {
            if (confirm('Are you sure you want to permanently delete this file? This action cannot be undone.')) {
                fetch(`/files/${fileId}/permanent-delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        // Reload page to update file list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Failed to permanently delete file');
                    }
                })
                .catch(error => {
                    showErrorMessage('Failed to permanently delete file: ' + error.message);
                });
            }
        }

        // Transfer modal function
        function openTransferModal() {
            console.log('openTransferModal called');
            // This will be handled by the transfer modal component
            if (window.openTransferModal) {
                window.openTransferModal();
            } else {
                console.error('window.openTransferModal not found');
            }
        }

        // Star toggle function
        function toggleStar(fileId) {
            fetch(`/files/${fileId}/star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    // Reload page to update star status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showErrorMessage(data.message || 'Failed to toggle star');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to toggle star: ' + error.message);
            });
        }

        // Bulk delete functions
        function bulkMoveToTrashAll() {
            const fileIds = @json($files->pluck('id'));
            
            if (fileIds.length === 0) {
                showErrorMessage('No files to delete');
                return;
            }

            if (confirm(`Are you sure you want to move all ${fileIds.length} files to trash?`)) {
                fetch('/files/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        file_ids: fileIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        // Reload page to update file list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Failed to move files to trash');
                    }
                })
                .catch(error => {
                    showErrorMessage('Failed to move files to trash: ' + error.message);
                });
            }
        }

        function bulkPermanentDeleteAll() {
            const fileIds = @json($files->pluck('id'));
            
            if (fileIds.length === 0) {
                showErrorMessage('No files to delete');
                return;
            }

            if (confirm(`Are you sure you want to PERMANENTLY DELETE all ${fileIds.length} files? This action cannot be undone!`)) {
                fetch('/files/bulk-permanent-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        file_ids: fileIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message);
                        // Reload page to update file list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Failed to permanently delete files');
                    }
                })
                .catch(error => {
                    showErrorMessage('Failed to permanently delete files: ' + error.message);
                });
            }
        }

    </script>

</x-filehub-layout>

