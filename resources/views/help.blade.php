<x-filehub-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Help Center</h1>
                    <p class="text-gray-600 mt-1">Everything you need to know about FileHub</p>
                </div>
            </div>
        </div>

        <!-- Table of Contents -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Quick Navigation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-medium text-blue-800 mb-2">Getting Started</h3>
                    <ul class="space-y-1 text-sm text-blue-700">
                        <li><a href="#what-is-filehub" class="hover:underline">What is FileHub?</a></li>
                        <li><a href="#platform-purpose" class="hover:underline">What is it used for?</a></li>
                        <li><a href="#navigation" class="hover:underline">How to navigate</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-medium text-blue-800 mb-2">Features & Actions</h3>
                    <ul class="space-y-1 text-sm text-blue-700">
                        <li><a href="#double-click" class="hover:underline">Double-click actions</a></li>
                        <li><a href="#pages-content" class="hover:underline">Page contents</a></li>
                        <li><a href="#drag-drop" class="hover:underline">Drag & drop</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- What is FileHub -->
        <section id="what-is-filehub" class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">What is FileHub?</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        FileHub is a comprehensive file management platform designed to help you organize, share, and manage your digital files efficiently. It provides a centralized location for all your files with powerful organization and collaboration features.
                    </p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Key Features:</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>Secure file storage and organization</li>
                            <li>Smart folder management system</li>
                            <li>File sharing and collaboration</li>
                            <li>Advanced search capabilities</li>
                            <li>Real-time notifications</li>
                            <li>File transfer and copying</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Purpose -->
        <section id="platform-purpose" class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">What is FileHub used for?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Personal Use</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Organize personal documents, photos, and files</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Create custom folders for different projects</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Quick access to frequently used files</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Collaboration</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Share files and folders with team members</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Transfer file copies to other users</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Real-time notifications for shared content</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Navigation Guide -->
        <section id="navigation" class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">How to Navigate FileHub</h2>
                
                <div class="space-y-6">
                    <!-- Sidebar Navigation -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Sidebar Navigation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Home</h4>
                                <p class="text-sm text-gray-700">Access your dashboard and main file view</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Folders</h4>
                                <p class="text-sm text-gray-700">Manage your custom folders and system folders</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Notifications</h4>
                                <p class="text-sm text-gray-700">View shared files and transfer notifications</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Help</h4>
                                <p class="text-sm text-gray-700">Access this help center</p>
                            </div>
                        </div>
                    </div>

                    <!-- Top Navigation -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Top Navigation</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Search Bar</h4>
                                    <p class="text-sm text-gray-700">Search for files and folders by name</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Action Buttons</h4>
                                    <p class="text-sm text-gray-700">Create folders, share files, and manage content</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Double-click Actions -->
        <section id="double-click" class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Double-click Actions</h2>
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">Files</h3>
                        <p class="text-blue-800 mb-2">Double-click any file to:</p>
                        <ul class="list-disc list-inside text-blue-700 space-y-1">
                            <li>Open the "Move File" modal</li>
                            <li>Select a destination folder</li>
                            <li>Move the file to a different location</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="font-semibold text-green-900 mb-2">Folders</h3>
                        <p class="text-green-800 mb-2">Double-click any folder to:</p>
                        <ul class="list-disc list-inside text-green-700 space-y-1">
                            <li>Open the folder contents</li>
                            <li>View all files inside the folder</li>
                            <li>Access folder management options</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page Contents -->
        <section id="pages-content" class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">What Each Page Contains</h2>
                
                <div class="space-y-6">
                    <!-- Home Page -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">🏠 Home Page</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Main Features:</h4>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• Drag & drop upload zone</li>
                                    <li>• Recent and Starred quick access</li>
                                    <li>• Search functionality</li>
                                    <li>• File management tools</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Actions Available:</h4>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• Upload files and folders</li>
                                    <li>• Create new folders</li>
                                    <li>• Share files and folders</li>
                                    <li>• Transfer file copies</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Folders Page -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">📁 Folders Page</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">System Folders:</h4>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• My Files (all files)</li>
                                    <li>• Documents</li>
                                    <li>• Pictures</li>
                                    <li>• Videos</li>
                                    <li>• Music</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Custom Folders:</h4>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• Your created folders</li>
                                    <li>• Open and Delete options</li>
                                    <li>• Folder management</li>
                                    <li>• File organization</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Page -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">🔔 Notifications Page</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Notification Types:</h4>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• File shared with you</li>
                                    <li>• Folder shared with you</li>
                                    <li>• Files transferred to you</li>
                                    <li>• Folders transferred to you</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Quick Actions:</h4>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• View shared files</li>
                                    <li>• Download files</li>
                                    <li>• Open shared folders</li>
                                    <li>• Mark as read</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Special Pages -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">📄 Special Pages</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Recent Files:</h4>
                                <p class="text-sm text-gray-700">Shows recently accessed files with quick access options</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Starred Files:</h4>
                                <p class="text-sm text-gray-700">Displays your starred/favorited files for quick access</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Shared With Me:</h4>
                                <p class="text-sm text-gray-700">Files and folders that others have shared with you</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Trash:</h4>
                                <p class="text-sm text-gray-700">Deleted files and folders with restore options</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Drag & Drop Guide -->
        <section id="drag-drop" class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Drag & Drop Guide</h2>
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-semibold text-yellow-900 mb-2">📁 Dropping Folders</h3>
                        <p class="text-yellow-800">When you drag and drop a folder into the upload zone:</p>
                        <ul class="list-disc list-inside text-yellow-700 mt-2 space-y-1">
                            <li>Folder is uploaded to "My Folders"</li>
                            <li>All files inside the folder are preserved</li>
                            <li>Folder structure is maintained</li>
                        </ul>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h3 class="font-semibold text-purple-900 mb-2">📄 Dropping Files</h3>
                        <p class="text-purple-800">When you drag and drop individual files:</p>
                        <ul class="list-disc list-inside text-purple-700 mt-2 space-y-1">
                            <li>Pictures → Pictures folder</li>
                            <li>Videos → Videos folder</li>
                            <li>Music → Music folder</li>
                            <li>Documents → Documents folder</li>
                            <li>Other files → My Files folder</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tips & Tricks -->
        <section class="mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">💡 Tips & Tricks</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Efficiency Tips</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span>Use the search bar to quickly find files</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span>Double-click files to move them between folders</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span>Star important files for quick access</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span>Use drag & drop for bulk file organization</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Collaboration Tips</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start space-x-2">
                                <span class="text-purple-500 font-bold">•</span>
                                <span>Share folders for team collaboration</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-purple-500 font-bold">•</span>
                                <span>Use "Transfer a Copy" to send files to others</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-purple-500 font-bold">•</span>
                                <span>Check notifications for shared content</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-purple-500 font-bold">•</span>
                                <span>Organize shared files in custom folders</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Support -->
        <section class="mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Need More Help?</h2>
                <p class="text-gray-700 mb-4">
                    If you have questions or need assistance, please contact our support team.
                </p>
                <div class="flex justify-center space-x-4">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Contact Support
                    </button>
                    <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Send Feedback
                    </button>
                </div>
            </div>
        </section>
    </div>
</x-filehub-layout>
