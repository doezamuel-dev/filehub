import './bootstrap';
import Alpine from 'alpinejs';
import shareModal from './shareModal'; // ✅ Add this line
import './errorHandler'; // Add error handler

window.Alpine = Alpine;

// ✅ Register the component globally for Alpine
Alpine.data('shareModal', shareModal);

Alpine.start();

