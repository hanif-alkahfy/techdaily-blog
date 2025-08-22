/**
 * Admin Panel JavaScript Helpers
 * This file contains reusable JavaScript functions for the admin panel
 */

class AdminHelpers {
    constructor() {
        this.init();
    }

    init() {
        this.initConfirmations();
        this.initFlashMessages();
        this.initFormHelpers();
        this.initSidebar();
        this.initTooltips();
    }

    /**
     * Initialize confirmation dialogs
     */
    initConfirmations() {
        // Delete confirmations
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-confirm-delete]') ||
                e.target.closest('[data-confirm-delete]')) {
                e.preventDefault();

                const button = e.target.matches('[data-confirm-delete]') ?
                    e.target : e.target.closest('[data-confirm-delete]');

                const form = button.closest('form');
                const message = button.getAttribute('data-confirm-delete') ||
                    'This action cannot be undone. Are you sure you want to delete this item?';
                const title = button.getAttribute('data-confirm-title') || 'Confirm Delete';

                this.showConfirmModal(message, () => {
                    if (form) {
                        form.submit();
                    }
                }, title, 'Delete', 'btn-danger');
            }
        });

        // Generic confirmations
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-confirm]') ||
                e.target.closest('[data-confirm]')) {
                e.preventDefault();

                const button = e.target.matches('[data-confirm]') ?
                    e.target : e.target.closest('[data-confirm]');

                const message = button.getAttribute('data-confirm');
                const title = button.getAttribute('data-confirm-title') || 'Confirm Action';
                const confirmText = button.getAttribute('data-confirm-text') || 'Confirm';
                const href = button.getAttribute('href');
                const form = button.closest('form');

                this.showConfirmModal(message, () => {
                    if (href) {
                        window.location.href = href;
                    } else if (form) {
                        form.submit();
                    }
                }, title, confirmText);
            }
        });
    }

    /**
     * Show confirmation modal
     */
    showConfirmModal(message, callback, title = 'Confirm Action', confirmText = 'Confirm', buttonClass = 'btn-primary') {
        // Create modal if it doesn't exist
        let modal = document.getElementById('confirmModal');
        if (!modal) {
            modal = this.createConfirmModal();
            document.body.appendChild(modal);
        }

        // Update modal content
        modal.querySelector('.modal-title').innerHTML = `
            <i class="bi bi-question-circle text-warning me-2"></i>${title}
        `;
        modal.querySelector('.modal-body').textContent = message;

        const confirmBtn = modal.querySelector('#confirmModalAction');
        confirmBtn.textContent = confirmText;
        confirmBtn.className = `btn ${buttonClass}`;

        // Show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        // Handle confirmation
        confirmBtn.onclick = () => {
            callback();
            bsModal.hide();
        };
    }

    /**
     * Create confirmation modal element
     */
    createConfirmModal() {
        const modalHTML = `
            <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmModalAction">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const temp = document.createElement('div');
        temp.innerHTML = modalHTML;
        return temp.firstElementChild;
    }

    /**
     * Initialize flash message handling
     */
    initFlashMessages() {
        // Auto-hide flash messages
        const flashMessages = document.querySelectorAll('.flash-message .alert');
        flashMessages.forEach((alert) => {
            setTimeout(() => {
                if (alert && alert.classList.contains('show')) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    }

    /**
     * Initialize form helpers
     */
    initFormHelpers() {
        // Add loading state to form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.matches('form:not([data-no-loading])')) {
                const form = e.target;
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');

                if (submitBtn && !submitBtn.disabled) {
                    this.setLoadingState(submitBtn, true);

                    // Reset after timeout (in case of validation errors)
                    setTimeout(() => {
                        this.setLoadingState(submitBtn, false);
                    }, 5000);
                }
            }
        });

        // Auto-generate slug from title
        this.initSlugGeneration();

        // Character counter for textareas
        this.initCharacterCounters();

        // Form validation helpers
        this.initFormValidation();
    }

    /**
     * Set loading state on buttons
     */
    setLoadingState(button, loading = true) {
        if (!button.dataset.originalText) {
            button.dataset.originalText = button.innerHTML;
        }

        if (loading) {
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            button.disabled = true;
        } else {
            button.innerHTML = button.dataset.originalText;
            button.disabled = false;
        }
    }

    /**
     * Initialize slug generation
     */
    initSlugGeneration() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', (e) => {
                // Only auto-generate if slug is empty or was auto-generated
                if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                    const slug = this.generateSlug(e.target.value);
                    slugInput.value = slug;
                    slugInput.dataset.autoGenerated = 'true';
                }
            });

            // Mark slug as manually edited when user types
            slugInput.addEventListener('input', () => {
                slugInput.dataset.autoGenerated = 'false';
            });
        }
    }

    /**
     * Generate URL-friendly slug
     */
    generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    /**
     * Initialize character counters
     */
    initCharacterCounters() {
        const textareas = document.querySelectorAll('textarea[data-max-length]');

        textareas.forEach(textarea => {
            const maxLength = parseInt(textarea.dataset.maxLength);
            const counter = document.createElement('div');
            counter.className = 'form-text text-muted text-end mt-1';
            counter.innerHTML = `<span class="current">0</span>/<span class="max">${maxLength}</span> characters`;

            textarea.parentNode.appendChild(counter);

            const updateCounter = () => {
                const current = textarea.value.length;
                const currentSpan = counter.querySelector('.current');
                currentSpan.textContent = current;

                if (current > maxLength * 0.9) {
                    currentSpan.classList.add('text-warning');
                } else if (current > maxLength) {
                    currentSpan.classList.add('text-danger');
                } else {
                    currentSpan.classList.remove('text-warning', 'text-danger');
                }
            };

            textarea.addEventListener('input', updateCounter);
            updateCounter(); // Initialize
        });
    }

    /**
     * Initialize form validation helpers
     */
    initFormValidation() {
        // Real-time validation feedback
        const forms = document.querySelectorAll('.needs-validation');

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Real-time validation for individual fields
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });
            });
        });
    }

    /**
     * Initialize sidebar functionality
     */
    initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (sidebar && sidebarToggle) {
            // Load saved sidebar state
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }

            // Toggle sidebar
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                const collapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed);
            });

            // Mobile responsive
            const handleResize = () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('collapsed');
                }
            };

            window.addEventListener('resize', handleResize);
            handleResize(); // Initialize
        }
    }

    /**
     * Initialize tooltips and popovers
     */
    initTooltips() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize Bootstrap popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(popoverTriggerEl => {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'info', duration = 5000) {
        const toastContainer = this.getOrCreateToastContainer();

        const toastId = 'toast_' + Date.now();
        const iconMap = {
            success: 'bi-check-circle',
            error: 'bi-exclamation-triangle',
            warning: 'bi-exclamation-triangle',
            info: 'bi-info-circle'
        };

        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${iconMap[type]} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: duration });
        toast.show();

        // Remove from DOM after hiding
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    /**
     * Get or create toast container
     */
    getOrCreateToastContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
        }
        return container;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.adminHelpers = new AdminHelpers();
});

// Expose utility functions globally
window.showToast = (message, type, duration) => {
    if (window.adminHelpers) {
        window.adminHelpers.showToast(message, type, duration);
    }
};

window.showConfirm = (message, callback, title, confirmText, buttonClass) => {
    if (window.adminHelpers) {
        window.adminHelpers.showConfirmModal(message, callback, title, confirmText, buttonClass);
    }
};
