@extends('layouts/app')
@section('content')
<div class="container mt-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('componentes.index', ['type' => $type]) }}" class="text-decoration-none">{{ ucfirst($type) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold mb-2 d-inline-flex align-items-center">
                {{ $product->name }}
                <i class="bi bi-info-circle text-primary ms-2 nomenclature-info"
                   data-brand="{{ strtolower($product->brand) }}"
                   data-model="{{ $product->name }}">
                </i>
            </h1>
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-primary me-2">{{ ucfirst($type) }}</span>
                <span class="badge bg-secondary me-2">{{ $product->brand }}</span>
                @if(isset($product->manufacturer))
                <span class="badge bg-info">{{ $product->manufacturer }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('componentes.compare', ['type' => $type, 'product1' => $product->id]) }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-right me-2"></i>Comparar
            </a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#priceHistoryModal"
                    data-component-type={{ get_class($product) }}
                    data-component-id={{ $product->id }}>
                <i class="bi bi-bar-chart-line"></i>
            </button>
        </div>
    </div>

    <!-- Modal de Histórico de Precios -->
    <div class="modal fade" id="priceHistoryModal" tabindex="-1" aria-labelledby="priceHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceHistoryModalLabel">Histórico de Precios - {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <canvas id="priceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Info -->
    <div class="row g-4">
        <!-- Product Image -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <img src="{{ $product->image }}" class="img-fluid rounded-3" alt="{{ $product->name }}" style="max-height: 350px; object-fit: contain;">
                </div>
            </div>
        </div>

        <!-- Pricing Table -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h3 class="fw-bold mb-0">Precios y Disponibilidad</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="table-light">
                                    <th scope="col" style="width: 30%">Tienda</th>
                                    <th scope="col" style="width: 25%">Disponibilidad</th>
                                    <th scope="col" style="width: 25%">Precio</th>
                                    <th scope="col" style="width: 20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://cdn.pccomponentes.com/img/logos/logo-pccomponentes.svg" alt="PC Componentes" class="img-fluid me-2" style="max-width: 80px; max-height: 40px;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill me-1"></i>En Stock</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-5">{{ isset($product->prices_by_vendor['pccomponentes']) ? $product->prices_by_vendor['pccomponentes'].'€' : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ isset($product->urls_by_vendor['pccomponentes']) ? $product->urls_by_vendor['pccomponentes'] : '#' }}" class="btn btn-success btn-sm w-100" target="_blank">
                                            <i class="bi bi-cart-plus me-1"></i>Comprar
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/1200px-Amazon_logo.svg.png" alt="Amazon" class="img-fluid me-2" style="max-width: 80px; max-height: 40px;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill me-1"></i>En Stock</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-5">{{ isset($product->prices_by_vendor['amazon']) ? $product->prices_by_vendor['amazon'].'€' : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ isset($product->urls_by_vendor['amazon']) ? $product->urls_by_vendor['amazon'] : '#' }}" class="btn btn-success btn-sm w-100" target="_blank">
                                            <i class="bi bi-cart-plus me-1"></i>Comprar
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://www.coolmod.com/images/logos/logo_coolmod.png" alt="Coolmod" class="img-fluid me-2" style="max-width: 80px; max-height: 40px;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill me-1"></i>En Stock</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-5">{{ isset($product->prices_by_vendor['coolmod']) ? $product->prices_by_vendor['coolmod'].'€' : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ isset($product->urls_by_vendor['coolmod']) ? $product->urls_by_vendor['coolmod'] : '#' }}" class="btn btn-success btn-sm w-100" target="_blank">
                                            <i class="bi bi-cart-plus me-1"></i>Comprar
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://www.neobyte.es/img/corporativo/neobyte_computers.png" alt="Neobyte" class="img-fluid me-2" style="max-width: 80px; max-height: 40px;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle-fill me-1"></i>En Stock</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-5">{{ isset($product->prices_by_vendor['neobyte']) ? $product->prices_by_vendor['neobyte'].'€' : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ isset($product->urls_by_vendor['neobyte']) ? $product->urls_by_vendor['neobyte'] : '#' }}" class="btn btn-success btn-sm w-100" target="_blank">
                                            <i class="bi bi-cart-plus me-1"></i>Comprar
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Specifications -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold mb-0">Especificaciones Técnicas</h3>
                    <i class="bi bi-cpu fs-4 text-primary"></i>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($type == 'procesadores')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Marca</span>
                                    <span class="fw-bold">{{ $product->brand }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Velocidad de reloj</span>
                                    <span class="fw-bold">{{ $product->clock_speed }} GHz</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Nº de núcleos</span>
                                    <span class="fw-bold">{{ $product->n_cores }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Nº de hilos</span>
                                    <span class="fw-bold">{{ $product->n_threads }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Socket</span>
                                    <span class="fw-bold">{{ $product->socket }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">TDP</span>
                                    <span class="fw-bold">{{ $product->tdp }} W</span>
                                </li>
                            </ul>
                            @elseif($type == 'tarjetas-graficas')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Marca</span>
                                    <span class="fw-bold">{{ $product->brand }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Manufacturador</span>
                                    <span class="fw-bold">{{ $product->manufacturer }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Memoria</span>
                                    <span class="fw-bold">{{ $product->vram }} GB</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span class="text-muted">Tipo de memoria</span>
                                    <span class="fw-bold">{{ $product->mem_type }}</span>
                                </li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src={{asset('js/nomenclature.js')}}></script>
<script type="module" src={{asset('js/graph.js')}}></script>
@endsection

