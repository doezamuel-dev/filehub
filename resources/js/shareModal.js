export default function shareModal() {
    return {
        show: false,
        fileId: null,
        fileName: '',
        email: '',
        sharedUsers: [],
        loading: false,
        error: '',
        success: '',

        open(id, name) {
            console.log("openShareModal called with:", id, name);
            this.fileId = id;
            this.fileName = name;
            this.show = true;
            this.fetchSharedUsers();
        },

        closeModal() {
            this.show = false;
            this.email = '';
            this.sharedUsers = [];
            this.error = '';
            this.success = '';
        },

        async fetchSharedUsers() {
            this.loading = true;
            this.error = '';
            try {
                const res = await fetch(`/files/${this.fileId}/shared-users`);
                if (!res.ok) throw new Error('Failed to load shared users');
                this.sharedUsers = await res.json();
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        async addEmail() {
            if (!this.email.trim()) return;
            this.loading = true;
            this.error = '';
            this.success = '';
            try {
                const res = await fetch(`/files/share/${this.fileId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                    },
                    body: JSON.stringify({ email: this.email }),
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to share file');

                this.success = data.message;
                this.email = '';
                this.fetchSharedUsers();
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        async removeAccess(userId) {
            this.loading = true;
            this.error = '';
            this.success = '';
            try {
                const res = await fetch(`/files/unshare/${this.fileId}/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                    },
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to unshare file');

                this.success = data.message;
                this.fetchSharedUsers();
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },
    };
}
