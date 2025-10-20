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
                    <td>1</td>
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
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
</div>
@endsection