document.addEventListener('DOMContentLoaded', function() {
    let formToSubmit = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Cuando se hace clic en cualquier botón de eliminar
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const configId = this.getAttribute('data-config-id');
            formToSubmit = document.getElementById('delete-form-' + configId);
            deleteModal.show();
        });
    });
    
    // Cuando se confirma la eliminación
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });
});