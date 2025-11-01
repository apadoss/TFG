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
                        <th>Diferencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Marca</td>
                        <td>{{ $product1->brand ?? 'N/A' }}</td>
                        <td>{{ $product2->brand ?? 'N/A' }}</td>
                        <td>
                            @if(($product1->brand ?? '') == ($product2->brand ?? ''))
                                <span class="badge bg-secondary">Igual</span>
                            @else
                                <span class="badge bg-info">Diferente</span>
                            @endif
                        </td>
                    </tr>
                    
                    @if($type == 'procesadores')
                       @include('partials.compare.procesador') 
                    @elseif($type == 'tarjetas-graficas')
                        @include('partials.compare.tarjeta-grafica')
                    @elseif($type == 'placas-base')
                        @include('partials.compare.placa-base')
                    @elseif($type == 'almacenamiento')
                       @include('partials.compare.almacenamiento') 
                    @elseif($type == 'ram')
                       @include('partials.compare.ram') 
                    @elseif($type == 'fuentes-alimentacion')
                       @include('partials.compare.fuentes-alimentacion') 
                        <!-- Añadir más propiedades específicas de fuentes de alimentación -->
                    @elseif($type == 'portatiles')
                        <tr>
                            <td>Procesador</td>
                            <td>{{ $product1->processor }}</td>
                            <td>{{ $product2->processor }}</td>
                            <td>
                                @if($product1->processor == $product2->processor)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>RAM</td>
                            <td>{{ $product1->ram }} GB</td>
                            <td>{{ $product2->ram }} GB</td>
                            <td>
                                @php
                                    $diff = $product2->ram - $product1->ram;
                                    $percent = ($product1->ram > 0) ? round(($diff / $product1->ram) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} GB más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} GB menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Almacenamiento</td>
                            <td>{{ $product1->storage }} GB</td>
                            <td>{{ $product2->storage }} GB</td>
                            <td>
                                @php
                                    $diff = $product2->storage - $product1->storage;
                                    $percent = ($product1->storage > 0) ? round(($diff / $product1->storage) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} GB más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} GB menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Pantalla</td>
                            <td>{{ $product1->screen_size }}"</td>
                            <td>{{ $product2->screen_size }}"</td>
                            <td>
                                @php
                                    $diff = $product2->screen_size - $product1->screen_size;
                                    $percent = ($product1->screen_size > 0) ? round(($diff / $product1->screen_size) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }}" más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }}" menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Añadir más propiedades específicas de portátiles -->
                    @endif
                    
                    <tr>
                        <td>Precio</td>
                        <td>{{ $product1->price }}€</td>
                        <td>{{ $product2->price }}€</td>
                        <td>
                            @php
                                $diff = $product2->price - $product1->price;
                                $percent = ($product1->price > 0) ? round(($diff / $product1->price) * 100, 1) : 0;
                            @endphp
                            
                            @if($diff > 0)
                                <span class="badge bg-danger">+{{ $percent }}% ({{ $diff }}€ más caro)</span>
                            @elseif($diff < 0)
                                <span class="badge bg-success">{{ $percent }}% ({{ abs($diff) }}€ más barato)</span>
                            @else
                                <span class="badge bg-secondary">Igual</span>
                            @endif
                        </td>
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