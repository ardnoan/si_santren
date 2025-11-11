// ================================================
// FILE: resources/js/global-scripts.js
// Add this to your layout after Bootstrap JS
// ================================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ================================================
    // DELETE CONFIRMATION
    // ================================================
    const deleteForms = document.querySelectorAll('form[method="POST"]');
    deleteForms.forEach(form => {
        // Check if form has DELETE method
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput && methodInput.value === 'DELETE') {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('⚠️ Apakah Anda yakin ingin menghapus data ini?\n\nData yang dihapus tidak dapat dikembalikan!')) {
                    form.submit();
                }
            });
        }
    });
    
    // ================================================
    // GRADUATE CONFIRMATION
    // ================================================
    const graduateForms = document.querySelectorAll('form[action*="graduate"]');
    graduateForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('🎓 Apakah Anda yakin ingin meluluskan santri ini?\n\nStatus santri akan berubah menjadi "Lulus".')) {
                form.submit();
            }
        });
    });
    
    // ================================================
    // AUTO-DISMISS ALERTS
    // ================================================
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // ================================================
    // LOADING OVERLAY FOR FORMS
    // ================================================
    const forms = document.querySelectorAll('form:not([data-no-loading])');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            // Show loading indicator
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                
                // Reset after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        });
    });
    
    // ================================================
    // FORM VALIDATION FEEDBACK
    // ================================================
    const inputsWithErrors = document.querySelectorAll('.is-invalid');
    if (inputsWithErrors.length > 0) {
        // Scroll to first error
        inputsWithErrors[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        inputsWithErrors[0].focus();
    }
    
    // ================================================
    // NUMERIC INPUT FORMATTING
    // ================================================
    const numericInputs = document.querySelectorAll('input[type="number"]');
    numericInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
    
    // ================================================
    // PREVENT DOUBLE SUBMIT
    // ================================================
    let formSubmitted = false;
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            if (formSubmitted) {
                return false;
            }
            formSubmitted = true;
            
            // Reset after 3 seconds
            setTimeout(() => {
                formSubmitted = false;
            }, 3000);
        });
    });
    
    // ================================================
    // TOOLTIPS INITIALIZATION
    // ================================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // ================================================
    // SUCCESS ANIMATION
    // ================================================
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(alert => {
        // Add slide-in animation
        alert.style.animation = 'slideInRight 0.5s ease-out';
    });
});

// ================================================
// CUSTOM CSS ANIMATIONS
// ================================================
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .alert-danger {
        animation: shake 0.5s ease-in-out;
    }
`;
document.head.appendChild(style);