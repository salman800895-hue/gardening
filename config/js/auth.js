// Authentication related JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.querySelector('input[name="password"]');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updateStrengthIndicator(strength);
        });
    }
    
    // Confirm password validation
    const confirmInput = document.querySelector('input[name="confirm_password"]');
    if (confirmInput && passwordInput) {
        confirmInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    return strength;
}

function updateStrengthIndicator(strength) {
    let indicator = document.querySelector('.strength-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'strength-indicator';
        document.querySelector('input[name="password"]').parentNode.appendChild(indicator);
    }
    
    const messages = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const colors = ['#ff4444', '#ff8844', '#ffcc44', '#88cc44', '#44ff44'];
    
    indicator.textContent = messages[strength] || 'Very Weak';
    indicator.style.color = colors[strength] || '#ff4444';
}