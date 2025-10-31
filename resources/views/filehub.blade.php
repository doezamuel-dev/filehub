<x-filehub-layout>
    @php
        // Get all files for the main dashboard (including files in folders)
        $allFiles = \App\Models\File::where('user_id', auth()->id())
                    ->where('is_trashed', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        // Get all folders for the main dashboard
        $folders = \App\Models\Folder::where('user_id', auth()->id())
                    ->where('is_trashed', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
    @endphp


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
                    <span class="hidden sm:inline">Upload or drop</span>
                    <span class="sr-only">Upload or drop</span>
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
                <span class="hidden sm:inline">Create folder</span>
                <span class="sr-only">Create folder</span>
            </button>

            <!-- Transfer Button -->
            <button onclick="openTransferModal()" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span class="hidden sm:inline">Transfer a copy</span>
                <span class="sr-only">Transfer a copy</span>
            </button>

            <!-- Share Button with Submenu -->
            <div class="relative" x-data="shareMenu()">
                <button 
                    @click="toggleShareMenu()"
                    class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2"
                >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                </svg>
                <span class="hidden sm:inline">Share</span>
                <span class="sr-only">Share</span>
            </button>
                
                <!-- Share Submenu -->
                <div 
                    x-show="shareMenuOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                    class="absolute left-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                    @click.away="shareMenuOpen = false"
                >
                    <div class="py-2">
                        <!-- Files Option -->
                        <button 
                            @click="openShareFilesModal()"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Share Files
            </button>
                        
                        <!-- Folders Option -->
                        <button 
                            @click="openShareFoldersModal()"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            Share Folders
                        </button>
                    </div>
                </div>
            </div>

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

    <!-- Filters/Views -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
        <div class="flex items-center space-x-6">
            <!-- All Files (Active) -->
            <div class="flex items-center space-x-2">
                <span class="font-semibold text-gray-900">All files</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>

            <!-- Recents -->
            <a href="{{ route('home.show', 'recent-files') }}" 
               class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 transition-colors group">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium hidden sm:inline">Recents</span>
                <span class="sr-only">Recents</span>
            </a>

            <!-- Starred -->
            <a href="{{ route('home.show', 'starred') }}" 
               class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 transition-colors group">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="text-sm font-medium hidden sm:inline">Starred</span>
                <span class="sr-only">Starred</span>
            </a>

            <!-- Only You -->
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-medium">
                    {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
                </div>
                <span class="text-sm text-gray-600">Only you</span>
            </div>
        </div>
    </div>

    <!-- Drag and Drop Zone - Always Visible -->
    <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-12 mb-6 transition-colors duration-200" 
         x-data="dragDropZone()"
         @dragover.prevent="isDragOver = true"
         @dragleave.prevent="isDragOver = false"
         @drop.prevent="handleDrop($event)"
         :class="{ 'border-blue-400 bg-blue-50': isDragOver }">
        <div class="text-center">
            <!-- Document Icon -->
            <div class="mx-auto w-24 h-24 text-gray-300 mb-6" :class="{ 'text-blue-400': isDragOver }">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
            </div>

            <!-- Text -->
            <h3 class="text-lg font-medium text-gray-900 mb-2" x-text="isDragOver ? 'Drop files here!' : 'Drop anything here or'"></h3>
            <button class="text-blue-600 hover:text-blue-700 underline font-medium" @click="openFileSelector()">
                Create a folder
            </button>
        </div>
        
        <!-- Hidden File Inputs for Drag and Drop -->
        <input 
            type="file" 
            id="dragDropFileInput" 
            multiple 
            class="hidden" 
            @change="handleFileUpload($event)"
        >
        <input 
            type="file" 
            id="dragDropFolderInput" 
            webkitdirectory 
            directory 
            multiple 
            class="hidden" 
            @change="handleFolderUpload($event)"
        >
    </div>


    <script>
        // Test if Alpine.js is working
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js is initialized');
        });
        
        function uploadMenu() {
            return {
                uploadMenuOpen: false,

                init() {
                    console.log('Upload menu initialized');
                },

                toggleUploadMenu() {
                    console.log('Toggle upload menu clicked');
                    this.uploadMenuOpen = !this.uploadMenuOpen;
                },

                openFileSelector() {
                    console.log('Open file selector clicked');
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
                    console.log('Files selected:', files.length);
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
                    
                    // Check if this is a single large file
                    const LARGE_FILE_THRESHOLD = 100 * 1024 * 1024; // 100MB
                    if (files.length === 1 && files[0].size > LARGE_FILE_THRESHOLD) {
                        this.uploadLargeFile(files[0], type);
                        return;
                    }
                    
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
                    
                    // Add files
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                        console.log('Added file:', files[i].name, 'Size:', files[i].size);
                    }

                    // Show progress bar for multiple files
                    this.showUploadProgressBar('Multiple files', files.length);

                    // Upload files with progress tracking
                    console.log('Sending request to /upload...');
                    this.uploadWithProgress('/upload', formData, files.length, (progress) => {
                        this.updateProgressBar(progress);
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        this.hideUploadProgress();
                        if (data.success) {
                            this.showSuccessMessage(`Successfully uploaded ${files.length} ${type}`);
                            // Refresh the page to show new files
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showErrorMessage(data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        this.hideUploadProgress();
                        this.showErrorMessage('Upload failed: ' + error.message);
                    });
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

                showUploadProgress() {
                    // Create a simple loading indicator
                    const loadingDiv = document.createElement('div');
                    loadingDiv.id = 'upload-loading';
                    loadingDiv.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    loadingDiv.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    `;
                    document.body.appendChild(loadingDiv);
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

        function deleteFolder(folderId, folderName) {
            if (confirm(`Are you sure you want to move the folder "${folderName}" to trash? All files in this folder will also be moved to trash.`)) {
                fetch(`/folders/${folderId}/delete`, {
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
                        // Reload page to update folder list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Failed to delete folder');
                    }
                })
                .catch(error => {
                    showErrorMessage('Failed to delete folder: ' + error.message);
                });
            }
        }


        // Drag and Drop Zone functionality
        function dragDropZone() {
            return {
                isDragOver: false,

                init() {
                    console.log('Drag drop zone initialized');
                },

                openFileSelector() {
                    document.getElementById('dragDropFileInput').click();
                },

                handleDrop(event) {
                    this.isDragOver = false;
                    const files = event.dataTransfer.files;
                    if (files.length > 0) {
                        // Check if dropped items contain folders (webkitdirectory files)
                        const hasFolders = Array.from(files).some(file => file.webkitRelativePath && file.webkitRelativePath.includes('/'));
                        
                        if (hasFolders) {
                            // If folders are detected, upload as folders to "My folders"
                            this.uploadFiles(files, 'folders');
                        } else {
                            // If only files, upload to their respective system folders
                        this.uploadFiles(files, 'files');
                        }
                    }
                },

                handleFileUpload(event) {
                    console.log('Drag drop file upload handler triggered');
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
                    if (type === 'folders') {
                        // Upload folders to "My folders"
                        this.uploadFolders(files);
                    } else {
                        // Upload files to their respective system folders
                        this.uploadFilesToSystemFolders(files);
                    }
                },

                uploadFolders(files) {
                    const formData = new FormData();
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        alert('CSRF token not found. Please refresh the page.');
                        return;
                    }
                    
                    formData.append('_token', csrfToken.getAttribute('content'));
                    formData.append('upload_type', 'folders');
                    
                    // Add files
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }

                    // Show loading state
                    this.showUploadProgress();

                    // Upload folders
                    fetch('/upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.hideUploadProgress();
                        if (data.success) {
                            this.showSuccessMessage(`Successfully uploaded folders to "My folders"`);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showErrorMessage(data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        this.hideUploadProgress();
                        this.showErrorMessage('Upload failed: ' + error.message);
                    });
                },

                uploadFilesToSystemFolders(files) {
                    // Categorize files by type
                    const categorizedFiles = this.categorizeFiles(files);
                    
                    // Upload each category to its respective system folder
                    const uploadPromises = Object.keys(categorizedFiles).map(category => {
                        if (categorizedFiles[category].length === 0) return Promise.resolve();
                        
                        return this.uploadFilesToCategory(categorizedFiles[category], category);
                    });

                    // Show loading state
                    this.showUploadProgress();

                    Promise.all(uploadPromises)
                        .then(results => {
                            this.hideUploadProgress();
                            const successful = results.filter(r => r && r.success).length;
                            this.showSuccessMessage(`Successfully uploaded files to their respective folders`);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        })
                        .catch(error => {
                            this.hideUploadProgress();
                            this.showErrorMessage('Upload failed: ' + error.message);
                        });
                },

                categorizeFiles(files) {
                    const categories = {
                        documents: [],
                        pictures: [],
                        videos: [],
                        music: [],
                        other: []
                    };

                    Array.from(files).forEach(file => {
                        const mimeType = file.type;
                        
                        if (mimeType.startsWith('image/')) {
                            categories.pictures.push(file);
                        } else if (mimeType.startsWith('video/')) {
                            categories.videos.push(file);
                        } else if (mimeType.startsWith('audio/')) {
                            categories.music.push(file);
                        } else if (mimeType.includes('pdf') || 
                                   mimeType.includes('document') || 
                                   mimeType.includes('text') ||
                                   mimeType.includes('spreadsheet') ||
                                   mimeType.includes('presentation')) {
                            categories.documents.push(file);
                        } else {
                            categories.other.push(file);
                        }
                    });

                    return categories;
                },

                uploadFilesToCategory(files, category) {
                    const formData = new FormData();
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        throw new Error('CSRF token not found');
                    }
                    
                    formData.append('_token', csrfToken.getAttribute('content'));
                    formData.append('upload_type', 'files');
                    formData.append('target_folder', category);
                    
                    // Add files
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }

                    return fetch('/upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    });
                },

                showUploadProgress() {
                    const loadingDiv = document.createElement('div');
                    loadingDiv.id = 'drag-drop-upload-loading';
                    loadingDiv.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    loadingDiv.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    `;
                    document.body.appendChild(loadingDiv);
                },

                hideUploadProgress() {
                    const loadingDiv = document.getElementById('drag-drop-upload-loading');
                    if (loadingDiv) {
                        loadingDiv.remove();
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

    </script>

</x-filehub-layout>


