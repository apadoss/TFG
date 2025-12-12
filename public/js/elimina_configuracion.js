document.addEventListener('DOMContentLoaded', function() {
    let formToSubmit = null;
    
    const deleteModalElement = document.getElementById('deleteModal');
    if (!deleteModalElement || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        console.error("El modal de Bootstrap o la librería Bootstrap no están disponibles.");
        return;
    }

    const deleteModal = new bootstrap.Modal(deleteModalElement);
    
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Recoger el ID de la configuración
            const configId = this.getAttribute('data-config-id');
            
            // Buscar el formulario por su ID
            formToSubmit = document.getElementById('delete-form-' + configId);
            
            if (formToSubmit) {
                deleteModal.show();
            } else {
                console.error('Formulario de eliminación no encontrado para ID:', configId);
            }
        });
    });
    
    // Cuando se confirma la eliminación
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });
});