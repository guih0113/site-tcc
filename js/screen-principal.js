document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', () => {
            const dropdownContent = item.nextElementSibling;
            dropdownContent.classList.toggle('active');
            if (dropdownContent.classList.contains('active')) {
                dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
            } else {
                dropdownContent.style.height = '0';
            }
        });
    });
});