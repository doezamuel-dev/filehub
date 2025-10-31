<div x-data="{
    email: '',
    sharedUsers: [],
    loading: false,
    error: '',
    success: '',

    init() {
        // Load shared users when modal opens
        this.loadSharedUsers();
    },

    closeModal() {
        this.$parent.showShareModal = false;
        this.email = '';
        this.error = '';
        this.success = '';
    },

    async loadSharedUsers() {
        try {
            const response = await fetch(`/files/${this.$parent.currentFileId}/shared-users`, {
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
            const response = await fetch(`/files/${this.$parent.currentFileId}/share`, {
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
            const response = await fetch(`/files/${this.$parent.currentFileId}/unshare`, {
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
}" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         x-show="$parent.showShareModal" 
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
             x-show="$parent.showShareModal"
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
                                Share "<span class="font-medium" x-text="$parent.currentFileName"></span>" with other users
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

