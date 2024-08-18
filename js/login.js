const icon = document.querySelector('.eye-icon');

icon.addEventListener('click', function (e) {
    const passwordInput = document.getElementById('password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.style.filter = 'grayscale(0)';
    } else {
        passwordInput.type = 'password';
        icon.style.filter = 'grayscale(1)';
    }
})