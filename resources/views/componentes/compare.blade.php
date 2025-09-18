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
                        <tr>
                            <td>Velocidad de reloj</td>
                            <td>{{ $product1->clock_speed }} GHz</td>
                            <td>{{ $product2->clock_speed }} GHz</td>
                            <td>
                                @php
                                    $diff = $product2->clock_speed - $product1->clock_speed;
                                    $percent = ($product1->clock_speed > 0) ? round(($diff / $product1->clock_speed) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} GHz más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} GHz menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nº de núcleos</td>
                            <td>{{ $product1->n_cores }}</td>
                            <td>{{ $product2->n_cores }}</td>
                            <td>
                                @php
                                    $diff = $product2->n_cores - $product1->n_cores;
                                    $percent = ($product1->n_cores > 0) ? round(($diff / $product1->n_cores) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nº de hilos</td>
                            <td>{{ $product1->n_threads }}</td>
                            <td>{{ $product2->n_threads }}</td>
                            <td>
                                @php
                                    $diff = $product2->n_threads - $product1->n_threads;
                                    $percent = ($product1->n_threads > 0) ? round(($diff / $product1->n_threads) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Socket</td>
                            <td>{{ $product1->socket }}</td>
                            <td>{{ $product2->socket }}</td>
                            <td>
                                @if($product1->socket == $product2->socket)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>TDP</td>
                            <td>{{ $product1->tdp }} W</td>
                            <td>{{ $product2->tdp }} W</td>
                            <td>
                                @php
                                    $diff = $product2->tdp - $product1->tdp;
                                    $percent = ($product1->tdp > 0) ? round(($diff / $product1->tdp) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-warning">+{{ $percent }}% ({{ $diff }}W más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-info">{{ $percent }}% ({{ abs($diff) }}W menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                    @elseif($type == 'tarjetas-graficas')
                        <tr>
                            <td>Memoria</td>
                            <td>{{ $product1->vram }} GB</td>
                            <td>{{ $product2->vram }} GB</td>
                            <td>
                                @php
                                    $diff = $product2->vram - $product1->vram;
                                    $percent = ($product1->vram > 0) ? round(($diff / $product1->vram) * 100, 1) : 0;
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
                            <td>Tipo de memoria</td>
                            <td>{{ $product1->mem_type }}</td>
                            <td>{{ $product2->mem_type }}</td>
                            <td>
                                @if($product1->mem_type == $product2->mem_type)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Añadir más propiedades específicas de tarjetas gráficas -->
                    @elseif($type == 'placas-base')
                        <tr>
                            <td>Socket</td>
                            <td>{{ $product1->socket }}</td>
                            <td>{{ $product2->socket }}</td>
                            <td>
                                @if($product1->socket == $product2->socket)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Formato</td>
                            <td>{{ $product1->format }}</td>
                            <td>{{ $product2->format }}</td>
                            <td>
                                @if($product1->format == $product2->format)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Añadir más propiedades específicas de placas base -->
                    @elseif($type == 'almacenamiento')
                        <tr>
                            <td>Capacidad</td>
                            <td>{{ $product1->capacity }} GB</td>
                            <td>{{ $product2->capacity }} GB</td>
                            <td>
                                @php
                                    $diff = $product2->capacity - $product1->capacity;
                                    $percent = ($product1->capacity > 0) ? round(($diff / $product1->capacity) * 100, 1) : 0;
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
                            <td>Tipo</td>
                            <td>{{ $product1->type }}</td>
                            <td>{{ $product2->type }}</td>
                            <td>
                                @if($product1->type == $product2->type)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Añadir más propiedades específicas de almacenamiento -->
                    @elseif($type == 'ram')
                        <tr>
                            <td>Capacidad</td>
                            <td>{{ $product1->capacity }} GB</td>
                            <td>{{ $product2->capacity }} GB</td>
                            <td>
                                @php
                                    $diff = $product2->capacity - $product1->capacity;
                                    $percent = ($product1->capacity > 0) ? round(($diff / $product1->capacity) * 100, 1) : 0;
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
                            <td>Tipo</td>
                            <td>{{ $product1->type }}</td>
                            <td>{{ $product2->type }}</td>
                            <td>
                                @if($product1->type == $product2->type)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Velocidad</td>
                            <td>{{ $product1->speed }} MHz</td>
                            <td>{{ $product2->speed }} MHz</td>
                            <td>
                                @php
                                    $diff = $product2->speed - $product1->speed;
                                    $percent = ($product1->speed > 0) ? round(($diff / $product1->speed) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} MHz más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} MHz menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Añadir más propiedades específicas de RAM -->
                    @elseif($type == 'fuentes-alimentacion')
                        <tr>
                            <td>Potencia</td>
                            <td>{{ $product1->power }} W</td>
                            <td>{{ $product2->power }} W</td>
                            <td>
                                @php
                                    $diff = $product2->power - $product1->power;
                                    $percent = ($product1->power > 0) ? round(($diff / $product1->power) * 100, 1) : 0;
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="badge bg-success">+{{ $percent }}% ({{ $diff }}W más)</span>
                                @elseif($diff < 0)
                                    <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }}W menos)</span>
                                @else
                                    <span class="badge bg-secondary">Igual</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Certificación</td>
                            <td>{{ $product1->certification }}</td>
                            <td>{{ $product2->certification }}</td>
                            <td>
                                @if($product1->certification == $product2->certification)
                                    <span class="badge bg-secondary">Igual</span>
                                @else
                                    <span class="badge bg-info">Diferente</span>
                                @endif
                            </td>
                        </tr>
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