// Toggle password visibility
function togglePasswordVisibility(button) {
    const targetId = button.getAttribute('data-target');
    const passwordInput = document.getElementById(targetId);
    const icon = button.querySelector('i');
    
    // Toggle the type attribute
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Toggle the eye / eye-slash icon
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

// Initialize when document is ready
$(document).ready(function() {
    // Handle password toggle buttons
    $(document).on('click', '.toggle-password', function(e) {
        e.preventDefault();
        e.stopPropagation();
        togglePasswordVisibility(this);
    });
});
