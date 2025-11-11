// Login Form Validation
function validateLogin(event) {
    event.preventDefault();
    
    clearErrors();
    
    let username = document.getElementById('username').value.trim();
    let password = document.getElementById('password').value;
    let isValid = true;

    if (username === '') {
        showError('usernameError', 'Username is required');
        isValid = false;
    } else if (username.length < 3) {
        showError('usernameError', 'Username must be at least 3 characters');
        isValid = false;
    } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        showError('usernameError', 'Username can only contain letters, numbers, and underscores');
        isValid = false;
    }

    if (password === '') {
        showError('passwordError', 'Password is required');
        isValid = false;
    } else if (password.length < 6) {
        showError('passwordError', 'Password must be at least 6 characters');
        isValid = false;
    }

    if (isValid) {
        document.getElementById('loginForm').submit();
        return true;
    }
    
    return false;
}

// Registration Form Validation
function validateRegistration(event) {
    event.preventDefault();
    
    clearErrors();
    
    let fullname = document.getElementById('fullname').value.trim();
    let username = document.getElementById('reg_username').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('reg_password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    let isValid = true;

    if (fullname === '') {
        showError('fullnameError', 'Full name is required');
        isValid = false;
    } else if (fullname.length < 3) {
        showError('fullnameError', 'Full name must be at least 3 characters');
        isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(fullname)) {
        showError('fullnameError', 'Full name can only contain letters and spaces');
        isValid = false;
    }

    if (username === '') {
        showError('regUsernameError', 'Username is required');
        isValid = false;
    } else if (username.length < 3) {
        showError('regUsernameError', 'Username must be at least 3 characters');
        isValid = false;
    } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        showError('regUsernameError', 'Username can only contain letters, numbers, and underscores');
        isValid = false;
    }

    if (email === '') {
        showError('emailError', 'Email is required');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('emailError', 'Please enter a valid email address');
        isValid = false;
    }

    if (password === '') {
        showError('regPasswordError', 'Password is required');
        isValid = false;
    } else if (password.length < 6) {
        showError('regPasswordError', 'Password must be at least 6 characters');
        isValid = false;
    }

    if (confirmPassword === '') {
        showError('confirmPasswordError', 'Please confirm your password');
        isValid = false;
    } else if (password !== confirmPassword) {
        showError('confirmPasswordError', 'Passwords do not match');
        isValid = false;
    }

    if (isValid) {
        document.getElementById('registrationForm').submit();
        return true;
    }
    
    return false;
}

function showError(elementId, message) {
    let errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function clearErrors() {
    let errorElements = document.querySelectorAll('.error-msg');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });
});

function validateField(field) {
    const fieldId = field.id;
    const value = field.value.trim();
    
    switch(fieldId) {
        case 'username':
        case 'reg_username':
            if (value === '') {
                showError(fieldId === 'username' ? 'usernameError' : 'regUsernameError', 'Username is required');
            } else if (value.length < 3) {
                showError(fieldId === 'username' ? 'usernameError' : 'regUsernameError', 'Username must be at least 3 characters');
            } else {
                clearFieldError(fieldId === 'username' ? 'usernameError' : 'regUsernameError');
            }
            break;
        case 'email':
            if (value === '') {
                showError('emailError', 'Email is required');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                showError('emailError', 'Please enter a valid email address');
            } else {
                clearFieldError('emailError');
            }
            break;
    }
}

function clearFieldError(errorId) {
    let errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
}