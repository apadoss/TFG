@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Comparar Configuraciones</h1>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Configuración 1</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $config1->name ?? 'Configuración #' . $config1->id }}</h4>
                    <div class="mb-2">
                        <strong>Precio total:</strong> 
                        <span class="badge bg-primary fs-6">{{ number_format($config1->total_price, 2) }}€</span>
                    </div>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-cpu"></i> <strong>CPU:</strong> {{ $config1->cpu->name ?? 'N/A' }}</li>
                        <li><i class="bi bi-gpu-card"></i> <strong>GPU:</strong> {{ $config1->graphic_card->name ?? 'N/A' }}</li>
                        <li><i class="bi bi-motherboard"></i> <strong>Placa Base:</strong> {{ $config1->motherboard->name ?? 'N/A' }}</li>
                        <li><i class="bi bi-memory"></i> <strong>RAM:</strong> {{ $config1->ram->name ?? 'N/A' }}</li>
                        <li><i class="bi bi-device-hdd"></i> <strong>Almacenamiento:</strong> {{ $config1->storage->name ?? 'N/A' }}</li>
                        <li><i class="bi bi-power"></i> <strong>Fuente:</strong> {{ $config1->power_supply->name ?? 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Configuración 2</h5>
                </div>
                <div class="card-body">
                    @if($config2)
                        <h4 class="mb-3">{{ $config2->name ?? 'Configuración #' . $config2->id }}</h4>
                        <div class="mb-2">
                            <strong>Precio total:</strong> 
                            <span class="badge bg-secondary fs-6">{{ number_format($config2->total_price, 2) }}€</span>
                        </div>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-cpu"></i> <strong>CPU:</strong> {{ $config2->cpu->name ?? 'N/A' }}</li>
                            <li><i class="bi bi-gpu-card"></i> <strong>GPU:</strong> {{ $config2->graphic_card->name ?? 'N/A' }}</li>
                            <li><i class="bi bi-motherboard"></i> <strong>Placa Base:</strong> {{ $config2->motherboard->name ?? 'N/A' }}</li>
                            <li><i class="bi bi-memory"></i> <strong>RAM:</strong> {{ $config2->ram->name ?? 'N/A' }}</li>
                            <li><i class="bi bi-device-hdd"></i> <strong>Almacenamiento:</strong> {{ $config2->storage->name ?? 'N/A' }}</li>
                            <li><i class="bi bi-power"></i> <strong>Fuente:</strong> {{ $config2->power_supply->name ?? 'N/A' }}</li>
                        </ul>
                    @else
                        <form action="#" method="GET" id="compareForm">
                            <div class="form-group">
                                <label for="config2">Selecciona una configuración para comparar:</label>
                                <select class="form-control" id="config2" name="config2" onchange="selectConfig2(this.value)">
                                    <option value="">-- Seleccionar configuración --</option>
                                    @foreach($allConfigs as $config)
                                        <option value="{{ $config->id }}">
                                            Configuración #{{ $config->id }} - {{ number_format($config->total_price, 2) }}€
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($config2)
        <!-- Resumen de diferencias -->
        <div class="alert alert-info mb-4">
            <h5>Resumen de comparación</h5>
            <div class="row">
                <div class="col-md-6">
                    <strong>Diferencia de precio:</strong>
                    @php
                        $priceDiff = $config2->total_price - $config1->total_price;
                        $pricePercent = ($config1->total_price > 0) ? round(($priceDiff / $config1->total_price) * 100, 1) : 0;
                    @endphp
                    @if($priceDiff > 0)
                        <span class="badge bg-danger">+{{ number_format(abs($priceDiff), 2) }}€ (+{{ $pricePercent }}%)</span>
                    @elseif($priceDiff < 0)
                        <span class="badge bg-success">-{{ number_format(abs($priceDiff), 2) }}€ ({{ $pricePercent }}%)</span>
                    @else
                        <span class="badge bg-secondary">Mismo precio</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <strong>Potencia requerida:</strong>
                    @if($config1->power_supply && $config2->power_supply)
                        {{ $config1->power_supply->power }}W vs {{ $config2->power_supply->power }}W
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabla de comparación detallada -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 20%">Componente</th>
                        <th style="width: 30%">Configuración 1</th>
                        <th style="width: 30%">Configuración 2</th>
                        <th style="width: 20%">Comparación</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Procesador -->
                    <tr>
                        <td><strong><i class="bi bi-cpu"></i> Procesador</strong></td>
                        <td>
                            @if($config1->cpu)
                                {{ $config1->cpu->name }}<br>
                                <small class="text-muted">
                                    {{ $config1->cpu->n_cores }} núcleos / {{ $config1->cpu->n_threads }} hilos<br>
                                    {{ $config1->cpu->clock_speed }} GHz | {{ $config1->cpu->tdp }}W TDP<br>
                                    <strong>{{ $config1->cpu->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config2->cpu)
                                {{ $config2->cpu->name }}<br>
                                <small class="text-muted">
                                    {{ $config2->cpu->n_cores }} núcleos / {{ $config2->cpu->n_threads }} hilos<br>
                                    {{ $config2->cpu->clock_speed }} GHz | {{ $config2->cpu->tdp }}W TDP<br>
                                    <strong>{{ $config2->cpu->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config1->cpu && $config2->cpu)
                                @php
                                    $coresDiff = $config2->cpu->n_cores - $config1->cpu->n_cores;
                                    $speedDiff = $config2->cpu->clock_speed - $config1->cpu->clock_speed;
                                @endphp
                                @if($coresDiff > 0)
                                    <span class="badge bg-success">+{{ $coresDiff }} núcleos</span><br>
                                @elseif($coresDiff < 0)
                                    <span class="badge bg-danger">{{ $coresDiff }} núcleos</span><br>
                                @else
                                    <span class="badge bg-secondary">Mismos núcleos</span><br>
                                @endif
                                
                                @if($speedDiff > 0)
                                    <span class="badge bg-success">+{{ number_format($speedDiff, 2) }} GHz</span>
                                @elseif($speedDiff < 0)
                                    <span class="badge bg-danger">{{ number_format($speedDiff, 2) }} GHz</span>
                                @else
                                    <span class="badge bg-secondary">Misma velocidad</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No comparable</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Tarjeta gráfica -->
                    <tr>
                        <td><strong><i class="bi bi-gpu-card"></i> Tarjeta Gráfica</strong></td>
                        <td>
                            @if($config1->graphic_card)
                                {{ $config1->graphic_card->name }}<br>
                                <small class="text-muted">
                                    {{ $config1->graphic_card->vram }} GB {{ $config1->graphic_card->mem_type }}<br>
                                    <strong>{{ $config1->graphic_card->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config2->graphic_card)
                                {{ $config2->graphic_card->name }}<br>
                                <small class="text-muted">
                                    {{ $config2->graphic_card->vram }} GB {{ $config2->graphic_card->mem_type }}<br>
                                    <strong>{{ $config2->graphic_card->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config1->graphic_card && $config2->graphic_card)
                                @php
                                    $vramDiff = $config2->graphic_card->vram - $config1->graphic_card->vram;
                                @endphp
                                @if($vramDiff > 0)
                                    <span class="badge bg-success">+{{ $vramDiff }} GB VRAM</span>
                                @elseif($vramDiff < 0)
                                    <span class="badge bg-danger">{{ $vramDiff }} GB VRAM</span>
                                @else
                                    <span class="badge bg-secondary">Misma VRAM</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No comparable</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Placa Base -->
                    <tr>
                        <td><strong><i class="bi bi-motherboard"></i> Placa Base</strong></td>
                        <td>
                            @if($config1->motherboard)
                                {{ $config1->motherboard->name }}<br>
                                <small class="text-muted">
                                    Socket: {{ $config1->motherboard->socket }} | {{ $config1->motherboard->size_format }}<br>
                                    <strong>{{ $config1->motherboard->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config2->motherboard)
                                {{ $config2->motherboard->name }}<br>
                                <small class="text-muted">
                                    Socket: {{ $config2->motherboard->socket }} | {{ $config2->motherboard->size_format }}<br>
                                    <strong>{{ $config2->motherboard->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config1->motherboard && $config2->motherboard)
                                @if($config1->motherboard->socket == $config2->motherboard->socket)
                                    <span class="badge bg-secondary">Socket compatible</span>
                                @else
                                    <span class="badge bg-secondary">Socket diferente</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No comparable</span>
                            @endif
                        </td>
                    </tr>

                    <!-- RAM -->
                    <tr>
                        <td><strong><i class="bi bi-memory"></i> Memoria RAM</strong></td>
                        <td>
                            @if($config1->ram)
                                {{ $config1->ram->name }}<br>
                                <small class="text-muted">
                                    {{ $config1->ram->n_modules * $config1->ram->module_capacity }} GB {{ $config1->ram->type }} @ {{ $config1->ram->frequency }} MHz<br>
                                    <strong>{{ $config1->ram->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config2->ram)
                                {{ $config2->ram->name }}<br>
                                <small class="text-muted">
                                    {{ $config2->ram->n_modules * $config2->ram->module_capacity }} GB {{ $config2->ram->type }} @ {{ $config2->ram->frequency }} MHz<br>
                                    <strong>{{ $config2->ram->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config1->ram && $config2->ram)
                                @php
                                    $ramDiff = ($config2->ram->n_modules * $config2->ram->module_capacity) - ($config1->ram->n_modules * $config1->ram->module_capacity);
                                    $speedDiff = $config2->ram->frequency - $config1->ram->frequency;
                                @endphp
                                @if($ramDiff > 0)
                                    <span class="badge bg-success">+{{ $ramDiff }} GB</span><br>
                                @elseif($ramDiff < 0)
                                    <span class="badge bg-danger">{{ $ramDiff }} GB</span><br>
                                @else
                                    <span class="badge bg-secondary">Misma capacidad</span><br>
                                @endif
                                
                                @if($speedDiff > 0)
                                    <span class="badge bg-success">+{{ $speedDiff }} MHz</span>
                                @elseif($speedDiff < 0)
                                    <span class="badge bg-danger">{{ $speedDiff }} MHz</span>
                                @else
                                    <span class="badge bg-secondary">Misma velocidad</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No comparable</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Almacenamiento -->
                    <tr>
                        <td><strong><i class="bi bi-device-hdd"></i> Almacenamiento</strong></td>
                        <td>
                            @if($config1->storage)
                                {{ $config1->storage->name }}<br>
                                <small class="text-muted">
                                    {{ $config1->storage->storage }} TB {{ $config1->storage->type }}<br>
                                    <strong>{{ $config1->storage->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config2->storage)
                                {{ $config2->storage->name }}<br>
                                <small class="text-muted">
                                    {{ $config2->storage->storage }} TB {{ $config2->storage->type }}<br>
                                    <strong>{{ $config2->storage->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config1->storage && $config2->storage)
                                @php
                                    $storageDiff = $config2->storage->storage - $config1->storage->storage;
                                @endphp
                                @if($storageDiff > 0)
                                    <span class="badge bg-success">+{{ $storageDiff }} GB</span>
                                @elseif($storageDiff < 0)
                                    <span class="badge bg-danger">{{ $storageDiff }} GB</span>
                                @else
                                    <span class="badge bg-secondary">Misma capacidad</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No comparable</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Fuente de alimentación -->
                    <tr>
                        <td><strong><i class="bi bi-power"></i> Fuente</strong></td>
                        <td>
                            @if($config1->power_supply)
                                {{ $config1->power_supply->name }}<br>
                                <small class="text-muted">
                                    {{ $config1->power_supply->power }}W | {{ $config1->power_supply->certification }}<br>
                                    <strong>{{ $config1->power_supply->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config2->power_supply)
                                {{ $config2->power_supply->name }}<br>
                                <small class="text-muted">
                                    {{ $config2->power_supply->power }}W | {{ $config2->power_supply->certification }}<br>
                                    <strong>{{ $config2->power_supply->price }}€</strong>
                                </small>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </td>
                        <td>
                            @if($config1->power_supply && $config2->power_supply)
                                @php
                                    $powerDiff = $config2->power_supply->power - $config1->power_supply->power;
                                @endphp
                                @if($powerDiff > 0)
                                    <span class="badge bg-danger">+{{ $powerDiff }}W (peor)</span>
                                @elseif($powerDiff < 0)
                                    <span class="badge bg-success">{{ $powerDiff }}W (mejor)</span>
                                @else
                                    <span class="badge bg-secondary">Misma potencia</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No comparable</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Precio total -->
                    <tr class="table-active">
                        <td><strong>PRECIO TOTAL</strong></td>
                        <td><h5 class="mb-0">{{ number_format($config1->total_price, 2) }}€</h5></td>
                        <td><h5 class="mb-0">{{ number_format($config2->total_price, 2) }}€</h5></td>
                        <td>
                            @php
                                $priceDiff = $config2->total_price - $config1->total_price;
                                $pricePercent = ($config1->total_price > 0) ? round(($priceDiff / $config1->total_price) * 100, 1) : 0;
                            @endphp
                            
                            @if($priceDiff > 0)
                                <span class="badge bg-danger">+{{ number_format(abs($priceDiff), 2) }}€<br>(+{{ $pricePercent }}%)</span>
                            @elseif($priceDiff < 0)
                                <span class="badge bg-success">-{{ number_format(abs($priceDiff), 2) }}€<br>({{ $pricePercent }}%)</span>
                            @else
                                <span class="badge bg-secondary">Mismo precio</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('configuraciones.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Volver a configuraciones
            </a>
        </div>
    @endif
</div>

<script>
    function selectConfig2(configId) {
        if (configId) {
            window.location.href = '{{ route('configuraciones.compare', $config1->id) }}/' + configId;
        }
    }
</script>
@endsection