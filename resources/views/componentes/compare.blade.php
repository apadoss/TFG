@extends('layouts/app')
@section('content')
<div class="container mt-4">
    <h1>Comparar {{ ucfirst(str_replace('-', ' ', $type)) }}</h1>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Componente 1</h5>
                </div>
                <div class="card-body">
                    <h4>{{ $product1->name }}</h4>
                    <img src="{{ $product1->image }}" class="img-fluid mb-3" style="max-height: 200px;" alt="{{ $product1->name }}">
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Componente 2</h5>
                </div>
                <div class="card-body">
                    @if($product2)
                        <h4>{{ $product2->name }}</h4>
                        <img src="{{ $product2->image }}" class="img-fluid mb-3" style="max-height: 200px;" alt="{{ $product2->name }}">
                    @else
                        <form action="#" method="GET" id="compareForm">
                            <div class="form-group">
                                <label for="product2">Selecciona un componente para comparar:</label>
                                <select class="form-control" id="product2" name="product2" onchange="selectProduct2(this.value)">
                                    <option value="">-- Seleccionar componente --</option>
                                    @foreach($allProducts as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($product2)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Especificación</th>
                        <th>{{ $product1->name }}</th>
                        <th>{{ $product2->name }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Marca</td>
                        <td>{{ $product1->brand ?? 'N/A' }}</td>
                        <td>{{ $product2->brand ?? 'N/A' }}</td>
                    </tr>
                    
                    @if($type == 'procesadores')
                        <tr>
                            <td>Velocidad de reloj</td>
                            <td>{{ $product1->clock_speed }} GHz</td>
                            <td>{{ $product2->clock_speed }} GHz</td>
                        </tr>
                        <tr>
                            <td>Nº de núcleos</td>
                            <td>{{ $product1->n_cores }}</td>
                            <td>{{ $product2->n_cores }}</td>
                        </tr>
                        <tr>
                            <td>Nº de hilos</td>
                            <td>{{ $product1->n_threads }}</td>
                            <td>{{ $product2->n_threads }}</td>
                        </tr>
                        <tr>
                            <td>Socket</td>
                            <td>{{ $product1->socket }}</td>
                            <td>{{ $product2->socket }}</td>
                        </tr>
                        <tr>
                            <td>TDP</td>
                            <td>{{ $product1->tdp }} W</td>
                            <td>{{ $product2->tdp }} W</td>
                        </tr>
                    @elseif($type == 'tarjetas-graficas')
                        <tr>
                            <td>Memoria</td>
                            <td>{{ $product1->memory }} GB</td>
                            <td>{{ $product2->memory }} GB</td>
                        </tr>
                        <tr>
                            <td>Tipo de memoria</td>
                            <td>{{ $product1->memory_type }}</td>
                            <td>{{ $product2->memory_type }}</td>
                        </tr>
                        <!-- Añadir más propiedades específicas de tarjetas gráficas -->
                    @elseif($type == 'placas-base')
                        <tr>
                            <td>Socket</td>
                            <td>{{ $product1->socket }}</td>
                            <td>{{ $product2->socket }}</td>
                        </tr>
                        <tr>
                            <td>Formato</td>
                            <td>{{ $product1->format }}</td>
                            <td>{{ $product2->format }}</td>
                        </tr>
                        <!-- Añadir más propiedades específicas de placas base -->
                    @elseif($type == 'almacenamiento')
                        <tr>
                            <td>Capacidad</td>
                            <td>{{ $product1->capacity }} GB</td>
                            <td>{{ $product2->capacity }} GB</td>
                        </tr>
                        <tr>
                            <td>Tipo</td>
                            <td>{{ $product1->type }}</td>
                            <td>{{ $product2->type }}</td>
                        </tr>
                        <!-- Añadir más propiedades específicas de almacenamiento -->
                    @elseif($type == 'ram')
                        <tr>
                            <td>Capacidad</td>
                            <td>{{ $product1->capacity }} GB</td>
                            <td>{{ $product2->capacity }} GB</td>
                        </tr>
                        <tr>
                            <td>Tipo</td>
                            <td>{{ $product1->type }}</td>
                            <td>{{ $product2->type }}</td>
                        </tr>
                        <tr>
                            <td>Velocidad</td>
                            <td>{{ $product1->speed }} MHz</td>
                            <td>{{ $product2->speed }} MHz</td>
                        </tr>
                        <!-- Añadir más propiedades específicas de RAM -->
                    @elseif($type == 'fuentes-alimentacion')
                        <tr>
                            <td>Potencia</td>
                            <td>{{ $product1->power }} W</td>
                            <td>{{ $product2->power }} W</td>
                        </tr>
                        <tr>
                            <td>Certificación</td>
                            <td>{{ $product1->certification }}</td>
                            <td>{{ $product2->certification }}</td>
                        </tr>
                        <!-- Añadir más propiedades específicas de fuentes de alimentación -->
                    @elseif($type == 'portatiles')
                        <tr>
                            <td>Procesador</td>
                            <td>{{ $product1->processor }}</td>
                            <td>{{ $product2->processor }}</td>
                        </tr>
                        <tr>
                            <td>RAM</td>
                            <td>{{ $product1->ram }} GB</td>
                            <td>{{ $product2->ram }} GB</td>
                        </tr>
                        <tr>
                            <td>Almacenamiento</td>
                            <td>{{ $product1->storage }} GB</td>
                            <td>{{ $product2->storage }} GB</td>
                        </tr>
                        <tr>
                            <td>Pantalla</td>
                            <td>{{ $product1->screen_size }}"</td>
                            <td>{{ $product2->screen_size }}"</td>
                        </tr>
                        <!-- Añadir más propiedades específicas de portátiles -->
                    @endif
                    
                    <tr>
                        <td>Precio</td>
                        <td>{{ $product1->price }}€</td>
                        <td>{{ $product2->price }}€</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('componentes.view', ['type' => $type, 'id' => $product1->id]) }}" class="btn btn-outline-primary" target="_blank">Ver detalles de {{ $product1->name }}</a>
            <a href="{{ route('componentes.view', ['type' => $type, 'id' => $product2->id]) }}" class="btn btn-outline-secondary" target="_blank">Ver detalles de {{ $product2->name }}</a>
            <a href="{{ route('componentes.compare', ['type' => $type, 'product1' => $product1->id]) }}" class="btn btn-warning">Comparar con otro componente</a>
        </div>
    @endif
</div>

<script>
    function selectProduct2(productId) {
        if (productId) {
            window.location.href = '{{ route('componentes.compare', ['type' => $type, 'product1' => $product1->id]) }}/' + productId;
        }
    }
</script>
@endsection