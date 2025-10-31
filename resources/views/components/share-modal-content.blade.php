<!-- Share Modal Content -->
<div class="bg-white rounded-lg shadow-xl p-6 w-96" @click.away="closeModal">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">
        Share <span class="text-blue-600" x-text="fileName"></span>
    </h2>

    <form @submit.prevent="submitShare">
        <!-- Recipient Email -->
        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Email</label>
        <input 
            type="email" 
            x-model="email"
            placeholder="Enter email to share with"
            class="w-full border border-gray-300 rounded p-2 mb-3 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            required
        >

        <!-- Permissions -->
        <label class="block text-sm font-medium text-gray-700 mb-1">Permission</label>
        <select 
            x-model="permission"
            class="w-full border border-gray-300 rounded p-2 mb-4 focus:ring-2 focus:ring-blue-400 focus:outline-none"
        >
            <option value="view">View Only</option>
            <option value="download">Allow Download</option>
            <option value="edit">Can Edit</option>
        </select>

        <!-- Expiry (optional) -->
        <label class="block text-sm font-medium text-gray-700 mb-1">Link Expiry (optional)</label>
        <input 
            type="date" 
            x-model="expiry"
            class="w-full border border-gray-300 rounded p-2 mb-4 focus:ring-2 focus:ring-blue-400 focus:outline-none"
        >

        <!-- Buttons -->
        <div class="flex justify-end space-x-3">
            <button 
                type="button" 
                @click="closeModal"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition"
            >
                Cancel
            </button>

            <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
                Share
            </button>
        </div>
    </form>
</div>

