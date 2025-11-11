@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h1">Configuraciones</h1>

    <div class="d-flex justify-content-end">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                Nueva
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('configuraciones.create', ['type' => 'basic']) }}">Básica</a></li>
                <li><a class="dropdown-item" href="{{ route('configuraciones.create', ['type' => 'advanced']) }}">Avanzada</a></li>
            </ul>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th> </th>
                <th>Componentes</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            @foreach($configuraciones as $configuracion)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <ul>
                            <li><b>Procesador:</b> {{$configuracion->cpu ? $configuracion->cpu->name : 'No especificado'}}</li>
                            <li><b>Tarjeta gráfica:</b> {{$configuracion->graphic_card ? $configuracion->graphic_card->name : 'No especificado'}}</li>
                            <li><b>Placa Base:</b> {{$configuracion->motherboard ? $configuracion->motherboard->name : 'No especificado'}}</li>
                            <li><b>Almacenamiento:</b> {{$configuracion->storage ? $configuracion->storage->name : 'No especificado'}}</li>
                            <li><b>RAM:</b> {{$configuracion->ram ? $configuracion->ram->name : 'No especificado'}}</li>
                        </ul>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-primary">Editar</button>
                            <a href="{{ route('configuraciones.compare', $configuracion->id) }}" class="btn btn-warning">
                                <i class="bi bi-arrows-angle-expand"></i> Comparar
                        </a>
                            <form action="{{ route('configuraciones.destroy', $configuracion->id) }}" method="POST" class="w-100">
                               @csrf
                               @method('DELETE')
                               <button type="submit" class="btn btn-danger w-100 btn-delete" data-delete-config="{{ $configuracion->id }}">
                                   Eliminar
                               </button>
                           </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
</div>

{{-- Modal de confirmación --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Confirmar eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas eliminar esta configuración?</p>
                <p class="text-muted small mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash me-1"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/elimina_configuracion.js') }}"></script>
<link href="{{ asset('css/elimina_configuracion.css') }}" rel="stylesheet">

@endsection