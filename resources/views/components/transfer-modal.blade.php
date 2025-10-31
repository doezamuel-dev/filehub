<div x-data="transferModal()" x-show="showTransferModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         x-show="showTransferModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()"></div>

    <!-- Modal panel -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
             x-show="showTransferModal"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                            Transfer a Copy
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Select files to copy and share with another user
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-white px-4 pb-4 sm:p-6">
                <!-- Step 1: File Selection -->
                <div x-show="showFileSelection">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Select Files to Transfer</h4>
                            <div class="flex space-x-2">
                                <button type="button" 
                                        @click="selectAllFiles()"
                                        class="text-sm text-blue-600 hover:text-blue-800">
                                    Select All
                                </button>
                                <button type="button" 
                                        @click="deselectAllFiles()"
                                        class="text-sm text-gray-600 hover:text-gray-800">
                                    Deselect All
                                </button>
                            </div>
                        </div>
                        
                        <!-- File list -->
                        <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
                            <template x-if="availableFiles.length === 0">
                                <div class="p-8 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="mt-2">No files available to transfer</p>
                                </div>
                            </template>
                            
                            <template x-for="file in availableFiles" :key="file.id">
                                <div class="flex items-center p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                    <input type="checkbox" 
                                           :id="'file-' + file.id"
                                           :checked="isFileSelected(file.id)"
                                           @change="toggleFileSelection(file.id)"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    
                                    <label :for="'file-' + file.id" class="flex items-center flex-1 ml-3 cursor-pointer">
                                        <!-- File icon -->
                                        <div class="w-8 h-8 flex items-center justify-center mr-3">
                                            <template x-if="file.file_type === 'image'">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </template>
                                            <template x-if="file.file_type === 'video'">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </template>
                                            <template x-if="file.file_type === 'audio'">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                                </svg>
                                            </template>
                                            <template x-if="file.file_type === 'document'">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </template>
                                            <template x-if="!['image', 'video', 'audio', 'document'].includes(file.file_type)">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </template>
                                        </div>
                                        
                                        <!-- File info -->
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900" x-text="file.original_name"></p>
                                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                <span x-text="file.formatted_size"></span>
                                                <span>•</span>
                                                <span x-text="file.folder_name"></span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </template>
                        </div>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            <span x-text="selectedFiles.length"></span> file(s) selected
                        </div>
                    </div>
                </div>

                <!-- Step 2: Email Input -->
                <div x-show="!showFileSelection">
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Enter Target User Email</h4>
                        
                        <div class="mb-4">
                            <label for="target-email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" 
                                   id="target-email"
                                   x-model="targetEmail"
                                   placeholder="Enter the email address of the user to receive the files"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Selected files summary -->
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Files to be transferred:</h5>
                            <div class="max-h-32 overflow-y-auto border border-gray-200 rounded-lg p-2">
                                <template x-for="fileId in selectedFiles" :key="fileId">
                                    <div class="flex items-center text-sm text-gray-600 py-1">
                                        <span x-text="availableFiles.find(f => f.id === fileId)?.original_name"></span>
                                    </div>
                                </template>
                            </div>
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
                <template x-if="showFileSelection">
                    <div class="flex space-x-3">
                        <button type="button" 
                                @click="nextStep()"
                                :disabled="selectedFiles.length === 0"
                                class="inline-flex justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </template>
                
                <template x-if="!showFileSelection">
                    <div class="flex space-x-3">
                        <button type="button" 
                                @click="transferFiles()"
                                :disabled="!targetEmail || loading"
                                class="inline-flex justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">Transfer Files</span>
                            <span x-show="loading">Transferring...</span>
                        </button>
                        <button type="button" 
                                @click="backToFileSelection()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Back
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>