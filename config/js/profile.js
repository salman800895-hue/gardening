// Profile page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('edit-profile-btn');
    const editForm = document.getElementById('edit-profile-form');
    const cancelBtn = document.getElementById('cancel-edit');
    
    if (editBtn && editForm) {
        editBtn.addEventListener('click', function() {
            editForm.style.display = 'block';
            editBtn.style.display = 'none';
        });
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            editForm.style.display = 'none';
            if (editBtn) editBtn.style.display = 'inline-flex';
        });
    }
    
    // Image preview for profile upload
    const imageInput = document.querySelector('input[type="file"]');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.querySelector('.profile-avatar img');
                    if (preview) {
                        preview.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});