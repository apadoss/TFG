@php
    $component = $notification->component;
    $componentType = class_basename($component);
    $typeMap = [
        'Procesador' => 'procesadores',
        'TarjetaGrafica' => 'tarjetas-graficas',
        'PlacasBase' => 'placas-base',
        'Almacenamiento' => 'almacenamiento',
        'MemoriaRam' => 'ram',
        'FuenteAlimentacion' => 'fuentes-alimentacion',
        'Portatil' => 'portatiles',
    ];
    $routeType = $typeMap[$componentType] ?? 'componentes';
    $componentUrl = route('componentes.view', ['type' => $routeType, 'id' => $component->id]);
@endphp

<div class="card notification-card h-100 shadow-sm hover-shadow">
    <div class="card-body d-flex flex-column">
        <!-- Imagen del componente -->
        @if($component->image)
            <div class="text-center mb-3">
                <img 
                    src="{{ $component->image }}" 
                    alt="{{ $component->name }}"
                    class="img-fluid rounded"
                    style="max-height: 150px; object-fit: contain;"
                    onerror="this.src='{{ asset('images/no-image.png') }}'">
            </div>
        @endif

        <!-- Nombre del componente -->
        <h5 class="card-title mb-2">
            <a href="{{ $componentUrl }}" class="text-decoration-none text-dark">
                {{ Str::limit($component->name, 60) }}
            </a>
        </h5>

        <!-- Precio actual -->
        <div class="mb-3">
            <span class="badge bg-success fs-6">
                <i class="fas fa-tag"></i> 
                {{ number_format($component->price, 2) }}€
            </span>
            
            @if($notification->target_price)
                <div class="text-muted small mt-2">
                    <i class="fas fa-bullseye"></i> 
                    Precio objetivo: <strong>{{ number_format($notification->target_price, 2) }}€</strong>
                </div>
            @endif
        </div>

        <!-- Tipo de notificación -->
        <div class="mb-3">
            @if($notification->notify_any_drop)
                <span class="badge bg-info">
                    <i class="fas fa-arrow-down"></i> Cualquier bajada
                </span>
            @else
                <span class="badge bg-warning">
                    <i class="fas fa-crosshairs"></i> Solo precio objetivo
                </span>
            @endif
        </div>

        <!-- Última notificación -->
        @if($notification->last_notified_at)
            <div class="text-muted small mb-3">
                <i class="fas fa-clock"></i> 
                Última notificación: {{ $notification->last_notified_at->diffForHumans() }}
            </div>
        @endif

        <!-- Botones -->
        <div class="mt-auto">
            <div class="d-grid gap-2">
                <a href="{{ $componentUrl }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> Ver componente
                </a>
                <button 
                    type="button" 
                    class="btn btn-sm btn-outline-secondary edit-notification"
                    data-component-type="{{ get_class($component) }}"
                    data-component-id="{{ $component->id }}"
                    data-target-price="{{ $notification->target_price }}"
                    data-notify-any-drop="{{ $notification->notify_any_drop ? 'true' : 'false' }}">
                    <i class="fas fa-edit"></i> Editar configuración
                </button>
                <button 
                    type="button" 
                    class="btn btn-sm btn-outline-danger deactivate-notification"
                    data-component-type="{{ get_class($component) }}"
                    data-component-id="{{ $component->id }}">
                    <i class="fas fa-bell-slash"></i> Desactivar notificación
                </button>
            </div>
        </div>

        <!-- Badge de fecha de creación -->
        <div class="text-muted small mt-2 text-center">
            <i class="fas fa-calendar-plus"></i> 
            Activada {{ $notification->created_at->diffForHumans() }}
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: box-shadow 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>