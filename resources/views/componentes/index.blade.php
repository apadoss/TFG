@extends('layouts/app')
@section('content')
<div class="container mt-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ ucfirst(request()->segment(2)) }}</li>
        </ol>
    </nav>

    <!-- Header with filter options -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="fw-bold">{{ ucfirst(str_replace('-', ' ', request()->segment(2))) }}</h1>
            <p class="text-muted mb-0">Mostrando {{ $products->count() }} de {{ $products->total() }} productos</p>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-md-end gap-2 mt-3 mt-md-0">

                <!-- Sort dropdown -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-sort-down me-1"></i>Ordenar
                    </button>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="#">Precio: menor a mayor</a></li>
                        <li><a class="dropdown-item" href="#">Precio: mayor a menor</a></li>
                        <li><a class="dropdown-item" href="#">Nombre: A-Z</a></li>
                        <li><a class="dropdown-item" href="#">Nombre: Z-A</a></li>
                        <li><a class="dropdown-item" href="#">Más populares</a></li>
                    </ul>
                </div>

                <!-- View toggle buttons -->
                <div class="btn-group" role="group" aria-label="Opciones de vista">
                    <button id="cardViewBtn" class="btn btn-primary active" disabled>
                        <i class="bi bi-grid-fill"></i>
                    </button>
                    <button id="listViewBtn" class="btn btn-secondary">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>

    <!-- Cards view (visible by default) -->
    <div id="cardsView" class="row g-4">
        @foreach ($products as $product)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card position-relative">
                <div class="product-img-container">
                    <img class="card-img-top p-4" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold mb-2 product-title">{{ $product->name }}</h5>
                    
                    @if(isset($product->price))
                    <div class="d-flex align-items-center mb-3">
                        <span class="h5 text-success fw-bold mb-0">{{ $product->price }}€</span>
                    </div>
                    @endif
                    
                    <!-- Specs preview (customize based on product type) -->
                    <div class="specs-preview mb-3 small text-muted">
                        @if(request()->segment(2) == 'procesadores')
                        <div class="d-flex justify-content-between mb-1">
                            <span>Núcleos:</span>
                            <span class="fw-medium">{{ $product->n_cores ?? '8' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Velocidad:</span>
                            <span class="fw-medium">{{ $product->clock_speed ?? '3.5' }} GHz</span>
                        </div>
                        @elseif(request()->segment(2) == 'tarjetas-graficas')
                        <div class="d-flex justify-content-between mb-1">
                            <span>Memoria:</span>
                            <span class="fw-medium">{{ $product->vram ?? '8' }} GB</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tipo:</span>
                            <span class="fw-medium">{{ $product->mem_type ?? 'GDDR6' }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('componentes.view', [request()->segment(2), $product->id]) }}" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-eye me-1"></i>Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- List view (hidden by default) -->
    <div id="listView" class="d-none">
        @foreach ($products as $product)
        <div class="card mb-3 border-0 shadow-sm hover-card">
            <div class="row g-0">
                <div class="col-md-3 d-flex align-items-center justify-content-center p-3">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-center p-3">
                    <h5 class="fw-bold mb-2">{{ $product->name }}</h5>
                    
                    <!-- Specs in list view (customize based on product type) -->
                    <div class="row mb-2">
                        @if(request()->segment(2) == 'procesadores')
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small"><i class="bi bi-cpu me-2"></i>{{ $product->n_cores ?? '8' }} núcleos / {{ $product->n_threads ?? '16' }} hilos</p>
                            <p class="mb-1 text-muted small"><i class="bi bi-lightning me-2"></i>{{ $product->clock_speed ?? '3.5' }} GHz</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small"><i class="bi bi-plug me-2"></i>Socket {{ $product->socket ?? 'AM4' }}</p>
                            <p class="mb-1 text-muted small"><i class="bi bi-thermometer-half me-2"></i>{{ $product->tdp ?? '65' }}W</p>
                        </div>
                        @elseif(request()->segment(2) == 'tarjetas-graficas')
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small"><i class="bi bi-memory me-2"></i>{{ $product->vram ?? '8' }} GB {{ $product->mem_type ?? 'GDDR6' }}</p>
                            <p class="mb-1 text-muted small"><i class="bi bi-building me-2"></i>{{ $product->manufacturer ?? 'NVIDIA' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small"><i class="bi bi-gpu-card me-2"></i>{{ $product->brand ?? 'ASUS' }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Stock status indicator -->
                    <div>
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>En stock</span>
                    </div>
                </div>
                <div class="col-md-3 d-flex flex-column justify-content-center p-3 border-start">
                    @if(isset($product->price))
                    <div class="mb-3 text-center">
                        <span class="h4 text-success fw-bold">{{ $product->price }}€</span>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('componentes.view', [request()->segment(2), $product->id]) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-1"></i>Ver detalles
                        </a>
                        <button class="btn btn-outline-secondary compare-btn">
                            <i class="bi bi-arrow-left-right me-1"></i>Comparar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty state (when no products match filters) -->
    @if(count($products) == 0)
    <div class="card border-0 shadow-sm p-5 text-center">
        <div class="py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3>No se encontraron productos</h3>
            <p class="text-muted">Intenta cambiar los filtros o busca con otros términos</p>
            <a href="#" class="btn btn-primary mt-3">Limpiar filtros</a>
        </div>
    </div>
    @endif

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
/* Hover effect for cards */
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Product image container */
.product-img-container {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-img-container img {
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.hover-card:hover .product-img-container img {
    transform: scale(1.05);
}

/* Product title line clamp */
.product-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 48px;
}

/* Custom pagination styling */
.pagination {
    gap: 5px;
}

.page-item .page-link {
    border-radius: 6px;
    border: none;
}

.page-item.active .page-link {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardViewBtn = document.getElementById('cardViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const cardsView = document.getElementById('cardsView');
    const listView = document.getElementById('listView');

    const urlParams = new URLSearchParams(window.location.search);
    const viewParam = urlParams.get('view');

    if (viewParam === 'list') {
        activateListView();
    } else {
        activateCardView();
    }

    // Función para cambiar a vista de tarjetas
    cardViewBtn.addEventListener('click', function() {
        activateCardView();
        updateUrlParam('cards');
    });

    // Función para cambiar a vista de lista
    listViewBtn.addEventListener('click', function() {
        activateListView();
        updateUrlParam('list');
    });

    // Función para activar vista de tarjetas
    function activateCardView() {
        cardsView.classList.remove('d-none');
        listView.classList.add('d-none');
        cardViewBtn.classList.add('active');
        cardViewBtn.classList.remove('btn-secondary');
        cardViewBtn.classList.add('btn-primary');
        cardViewBtn.disabled = true;
        listViewBtn.classList.remove('active');
        listViewBtn.classList.remove('btn-primary');
        listViewBtn.classList.add('btn-secondary');
        listViewBtn.disabled = false;
    }

    // Función para activar vista de lista
    function activateListView() {
        cardsView.classList.add('d-none');
        listView.classList.remove('d-none');
        listViewBtn.classList.add('active');
        listViewBtn.classList.remove('btn-secondary');
        listViewBtn.classList.add('btn-primary');
        listViewBtn.disabled = true;
        cardViewBtn.classList.remove('active');
        cardViewBtn.classList.remove('btn-primary');
        cardViewBtn.classList.add('btn-secondary');
        cardViewBtn.disabled = false;
    }

    // Función para actualizar el parámetro view en la URL
    function updateUrlParam(viewType) {
        const url = new URL(window.location);
        url.searchParams.set('view', viewType);
        history.replaceState({}, '', url);

        updatePaginationLinks(viewType);
    }

    // Función para actualizar los enlaces de paginación
    function updatePaginationLinks(viewType) {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            if (link.href) {
                const linkUrl = new URL(link.href);
                linkUrl.searchParams.set('view', viewType);
                link.href = linkUrl.toString();
            }
        });
    }

    if (viewParam) {
        updatePaginationLinks(viewParam);
    }

    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Agregar producto a comparación
    const compareButtons = document.querySelectorAll('.compare-btn');
    compareButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Aquí puedes implementar la lógica para agregar a comparación
            alert('Producto añadido a la comparación');
        });
    });
});
</script>
@endsection