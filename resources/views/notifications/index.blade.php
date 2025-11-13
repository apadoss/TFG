@extends('layouts.app')

@section('title', 'Mis Notificaciones de Precio')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-bell text-primary"></i> 
                    Mis Notificaciones de Precio
                </h1>
                <span class="badge bg-primary fs-6">
                    {{ $notifications->total() }} activas
                </span>
            </div>

            @if($notifications->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No tienes notificaciones activas</h4>
                        <p class="text-muted mb-4">
                            Activa notificaciones en los componentes que te interesen y te avisaremos cuando bajen de precio.
                        </p>
                        <a href="{{ route('componentes.index', ['type' => 'procesadores']) }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Explorar Componentes
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <strong>¿Cómo funciona?</strong><br>
                        Te enviaremos un email cuando alguno de estos componentes baje de precio. 
                        Puedes desactivar las notificaciones en cualquier momento.
                    </div>
                </div>

                <div class="row">
                    @foreach($notifications as $notification)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            @include('partials.notification-card', ['notification' => $notification])
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editNotificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Editar Notificación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editNotificationForm">
                    <input type="hidden" id="editComponentType">
                    <input type="hidden" id="editComponentId">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">¿Cuándo quieres que te notifiquemos?</label>
                        
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="editNotificationType" 
                                id="editNotifyAnyDrop" 
                                value="any_drop">
                            <label class="form-check-label" for="editNotifyAnyDrop">
                                <strong>Cualquier bajada de precio</strong>
                            </label>
                        </div>
                        
                        <div class="form-check mt-2">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="editNotificationType" 
                                id="editNotifyTargetPrice" 
                                value="target_price">
                            <label class="form-check-label" for="editNotifyTargetPrice">
                                <strong>Solo cuando llegue a un precio específico</strong>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="editTargetPriceGroup" style="display: none;">
                        <label for="editTargetPrice" class="form-label fw-bold">Precio objetivo</label>
                        <div class="input-group">
                            <input 
                                type="number" 
                                class="form-control" 
                                id="editTargetPrice" 
                                step="0.01"
                                min="0">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEditNotification">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación de eliminación --}}
<div class="modal fade" id="deleteNotificationModal" tabindex="-1" aria-labelledby="deleteNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteNotificationModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Confirmar eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas desactivar esta notificación?</p>
                <p class="text-muted small mb-0">Podrás activarla nuevamente más tarde desde el componente.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteNotification">
                    <i class="bi bi-trash me-1"></i> Desactivar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ========== DESACTIVAR NOTIFICACIÓN ==========
    let notificationToDelete = null;
    const deleteNotificationModal = new bootstrap.Modal(document.getElementById('deleteNotificationModal'));
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.deactivate-notification')) {
            e.preventDefault();
            
            const button = e.target.closest('.deactivate-notification');
            
            // Guardar datos de la notificación a eliminar
            notificationToDelete = {
                componentType: button.dataset.componentType,
                componentId: button.dataset.componentId,
                card: button.closest('.notification-card')
            };
            
            // Mostrar modal
            deleteNotificationModal.show();
        }
    });
    
    // Cuando se confirma la eliminación en el modal
    document.getElementById('confirmDeleteNotification').addEventListener('click', function() {
        if (!notificationToDelete) return;
        
        const { componentType, componentId, card } = notificationToDelete;
        
        // Deshabilitar el botón mientras se procesa
        this.disabled = true;
        const originalHTML = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...';
    
        fetch('/notifications/deactivate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                component_type: componentType,
                component_id: componentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar modal
                deleteNotificationModal.hide();
                
                // Animar la eliminación
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    const cardCol = card.closest('.col-12');
                    if (cardCol) {
                        cardCol.remove();
                    }
                    
                    // Si no quedan notificaciones, recargar la página
                    if (document.querySelectorAll('.notification-card').length === 0) {
                        location.reload();
                    }
                }, 300);
                
                // Resetear estado
                notificationToDelete = null;
            } else {
                alert('Error al desactivar la notificación');
                this.disabled = false;
                this.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al desactivar la notificación');
            this.disabled = false;
            this.innerHTML = originalHTML;
        });
    });

    // ========== EDITAR NOTIFICACIÓN (con delegación) ==========
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-notification')) {
            const button = e.target.closest('.edit-notification');
            
            const componentType = button.dataset.componentType;
            const componentId = button.dataset.componentId;
            const targetPrice = button.dataset.targetPrice;
            const notifyAnyDrop = button.dataset.notifyAnyDrop === 'true';
            
            // Verificar que existen los elementos del modal
            const editComponentType = document.getElementById('editComponentType');
            const editComponentId = document.getElementById('editComponentId');
            const editNotifyAnyDrop = document.getElementById('editNotifyAnyDrop');
            const editNotifyTargetPrice = document.getElementById('editNotifyTargetPrice');
            const editTargetPrice = document.getElementById('editTargetPrice');
            const editTargetPriceGroup = document.getElementById('editTargetPriceGroup');
            
            if (!editComponentType || !editComponentId) {
                console.error('Elementos del modal no encontrados');
                return;
            }
            
            // Llenar el formulario
            editComponentType.value = componentType;
            editComponentId.value = componentId;
            
            if (targetPrice && targetPrice !== 'null' && targetPrice !== '' && targetPrice !== 'undefined') {
                editNotifyTargetPrice.checked = true;
                editTargetPrice.value = targetPrice;
                editTargetPriceGroup.style.display = 'block';
            } else {
                editNotifyAnyDrop.checked = true;
                editTargetPriceGroup.style.display = 'none';
            }
            
            // Mostrar modal
            const modalElement = document.getElementById('editNotificationModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }
    });
    
    // ========== CAMBIOS EN RADIO BUTTONS DEL MODAL ==========
    const editNotifyAnyDropRadio = document.getElementById('editNotifyAnyDrop');
    const editNotifyTargetPriceRadio = document.getElementById('editNotifyTargetPrice');
    const editTargetPriceGroup = document.getElementById('editTargetPriceGroup');
    
    if (editNotifyAnyDropRadio) {
        editNotifyAnyDropRadio.addEventListener('change', function() {
            if (this.checked) {
                editTargetPriceGroup.style.display = 'none';
            }
        });
    }
    
    if (editNotifyTargetPriceRadio) {
        editNotifyTargetPriceRadio.addEventListener('change', function() {
            if (this.checked) {
                editTargetPriceGroup.style.display = 'block';
            }
        });
    }
    
    // ========== GUARDAR CAMBIOS ==========
    const saveEditButton = document.getElementById('saveEditNotification');
    if (saveEditButton) {
        saveEditButton.addEventListener('click', function() {
            const editComponentType = document.getElementById('editComponentType');
            const editComponentId = document.getElementById('editComponentId');
            const notificationTypeRadio = document.querySelector('input[name="editNotificationType"]:checked');
            
            if (!editComponentType || !editComponentId) {
                alert('Error: No se encontraron los datos del componente');
                return;
            }
            
            const componentType = editComponentType.value;
            const componentId = editComponentId.value;
            
            if (!notificationTypeRadio) {
                alert('Por favor, selecciona un tipo de notificación');
                return;
            }
            
            const notificationType = notificationTypeRadio.value;
            
            let data = {
                component_type: componentType,
                component_id: componentId,
                notify_any_drop: notificationType === 'any_drop',
                target_price: null
            };
            
            if (notificationType === 'target_price') {
                const editTargetPrice = document.getElementById('editTargetPrice');
                const targetPrice = parseFloat(editTargetPrice.value);
                
                if (!targetPrice || targetPrice <= 0) {
                    alert('Por favor, introduce un precio objetivo válido');
                    return;
                }
                data.target_price = targetPrice;
            }
            
            // Deshabilitar botón mientras se guarda
            saveEditButton.disabled = true;
            const originalHTML = saveEditButton.innerHTML;
            saveEditButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
            
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
                    location.reload();
                } else {
                    alert('Error al actualizar la notificación');
                    saveEditButton.disabled = false;
                    saveEditButton.innerHTML = originalHTML;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar la notificación');
                saveEditButton.disabled = false;
                saveEditButton.innerHTML = originalHTML;
            });
        });
    }
});
</script>
@endpush
@endsection