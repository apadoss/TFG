document.addEventListener('DOMContentLoaded', function() {
    const notifyBtn = document.getElementById('notifyBtn');
    const modal = document.getElementById('notificationModal');
    const componentType = document.getElementById('componentType').value;
    const componentId = document.getElementById('componentId').value;
    const form = document.getElementById('notificationForm');
    
    const notifyAnyDrop = document.getElementById('notifyAnyDrop');
    const notifyTargetPrice = document.getElementById('notifyTargetPrice');
    const targetPriceGroup = document.getElementById('targetPriceGroup');
    const targetPriceInput = document.getElementById('targetPrice');
    const targetPriceError = document.getElementById('targetPriceError');
    const notificationExample = document.getElementById('notificationExample');
    const saveBtn = document.getElementById('saveNotification');
    
    // Verificar si ya tiene notificación activa al cargar la página
    checkExistingNotification();
    
    function checkExistingNotification() {
        fetch(`/notifications/check?component_type=${encodeURIComponent(componentType)}&component_id=${componentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.has_notification) {
                    notifyBtn.classList.remove('btn-outline-warning');
                    notifyBtn.classList.add('btn-warning');
                    notifyBtn.querySelector('#notifyBtnText').textContent = 'Notificación activa';
                    
                    // Prellenar el formulario con los valores actuales
                    if (data.notification.target_price) {
                        notifyTargetPrice.checked = true;
                        targetPriceInput.value = data.notification.target_price;
                        targetPriceGroup.style.display = 'block';
                        updateExample('target');
                    } else if (data.notification.notify_any_drop) {
                        notifyAnyDrop.checked = true;
                        updateExample('any');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Mostrar/ocultar campo de precio objetivo
    notifyAnyDrop.addEventListener('change', function() {
        if (this.checked) {
            targetPriceGroup.style.display = 'none';
            targetPriceInput.value = '';
            targetPriceError.style.display = 'none';
            updateExample('any');
        }
    });
    
    notifyTargetPrice.addEventListener('change', function() {
        if (this.checked) {
            targetPriceGroup.style.display = 'block';
            targetPriceInput.focus();
            updateExample('target');
        }
    });
    
    // Validar precio objetivo en tiempo real
    targetPriceInput.addEventListener('input', function() {
        const currentPrice = parseFloat('{{ $product->price }}');
        const targetPrice = parseFloat(this.value);
        
        if (targetPrice >= currentPrice) {
            targetPriceError.textContent = 'El precio objetivo debe ser menor al precio actual';
            targetPriceError.style.display = 'block';
            saveBtn.disabled = true;
        } else if (targetPrice <= 0) {
            targetPriceError.textContent = 'El precio debe ser mayor a 0';
            targetPriceError.style.display = 'block';
            saveBtn.disabled = true;
        } else {
            targetPriceError.style.display = 'none';
            saveBtn.disabled = false;
            updateExample('target', targetPrice);
        }
    });
    
    // Actualizar ejemplo dinámicamente
    function updateExample(type, price = null) {
        if (type === 'any') {
            notificationExample.innerHTML = 'Te enviaremos un email <strong>cada vez</strong> que detectemos una bajada de precio.';
        } else if (type === 'target') {
            if (price) {
                notificationExample.innerHTML = `Te enviaremos un email cuando el precio llegue a <strong>${price.toFixed(2)}€</strong> o menos.`;
            } else {
                notificationExample.innerHTML = 'Te enviaremos un email solo cuando el precio llegue a tu objetivo.';
            }
        }
    }
    
    // Guardar notificación
    saveBtn.addEventListener('click', function() {
        const notificationType = document.querySelector('input[name="notificationType"]:checked').value;
        
        let data = {
            component_type: componentType,
            component_id: componentId,
            notify_any_drop: notificationType === 'any_drop',
            target_price: null
        };
        
        if (notificationType === 'target_price') {
            const targetPrice = parseFloat(targetPriceInput.value);
            
            if (!targetPrice || targetPrice <= 0) {
                alert('Por favor, introduce un precio objetivo válido');
                return;
            }
            
            if (targetPrice >= parseFloat('{{ $product->price }}')) {
                alert('El precio objetivo debe ser menor al precio actual');
                return;
            }
            
            data.target_price = targetPrice;
        }
        
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
        
        fetch('/notifications/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar botón principal
                notifyBtn.classList.remove('btn-outline-warning');
                notifyBtn.classList.add('btn-warning');
                notifyBtn.querySelector('#notifyBtnText').textContent = 'Notificación activa';
                
                // Cerrar modal
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
                
                // Mostrar mensaje de éxito
                showToast('success', data.message);
            } else {
                showToast('error', 'Error al configurar la notificación');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error al configurar la notificación');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-check"></i> Activar Notificación';
        });
    });
    
    // Toast de notificaciones
    function showToast(type, message) {
        // Si usas Bootstrap 5 toast
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Crear contenedor de toasts si no existe
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Eliminar el elemento después de que se oculte
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }
    
    // Resetear formulario al cerrar modal
    modal.addEventListener('hidden.bs.modal', function() {
        form.reset();
        targetPriceGroup.style.display = 'none';
        targetPriceError.style.display = 'none';
        saveBtn.disabled = false;
    });
});