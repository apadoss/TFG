@extends('layouts.app')

@section('content')
    <div>
        <h1 class="h1">Crear nueva configuración</h1>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form id="configuracion-form" action="{{ route('configuraciones.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Procesador --}}
                            <div class="col-md-4 mb-3">
                                <div class="card text-left h-100">
                                    <div class="component-image-container">
                                        <img
                                            class="component-image"
                                            id="procesador-image"
                                            src="{{ asset('images/placeholder-component.png') }}"
                                            style="display: none;"
                                        >
                                    </div>
                                    <div class="card-body">
                                        <h2 class="card-title">Procesador</h2>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary" id="procesador">
                                                Seleccionar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger ms-2 btn-remove"
                                                style="display: none;"
                                                data-component="procesador"
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tarjeta gráfica --}}
                            <div class="col-md-4 mb-3">
                                <div class="card text-left h-100">
                                    <div class="component-image-container">
                                        <img
                                            class="component-image"
                                            id="tarjeta_grafica-image"
                                            src="{{ asset('images/placeholder-component.png') }}"
                                            style="display: none;"
                                        >
                                    </div>
                                    <div class="card-body">
                                        <h2 class="card-title">Tarjeta gráfica</h2>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary">
                                                Seleccionar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger ms-2 btn-remove"
                                                style="display: none;"
                                                data-component="tarjeta_grafica"
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Placa Base --}}
                            <div class="col-md-4 mb-3">
                                <div class="card text-left h-100">
                                    <div class="component-image-container">
                                        <img
                                            class="component-image"
                                            id="placa_base-image"
                                            src="{{ asset('images/placeholder-component.png') }}"
                                            style="display: none;"
                                        >
                                    </div>
                                    <div class="card-body">
                                        <h2 class="card-title">Placa Base</h2>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary">
                                                Seleccionar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger ms-2 btn-remove"
                                                style="display: none;"
                                                data-component="placa_base"
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Memoria RAM --}}
                            <div class="col-md-4 mb-3">
                                <div class="card text-left h-100">
                                    <div class="component-image-container">
                                        <img
                                            class="component-image"
                                            id="memoria_ram-image"
                                            src="{{ asset('images/placeholder-component.png') }}"
                                            style="display: none;"
                                        >
                                    </div>
                                    <div class="card-body">
                                        <h2 class="card-title">Memoria RAM</h2>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary">
                                                Seleccionar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger ms-2 btn-remove"
                                                style="display: none;"
                                                data-component="memoria_ram"
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Almacenamiento --}}
                            <div class="col-md-4 mb-3">
                                <div class="card text-left h-100">
                                    <div class="component-image-container">
                                        <img
                                            class="component-image"
                                            id="almacenamiento-image"
                                            src="{{ asset('images/placeholder-component.png') }}"
                                            style="display: none;"
                                        >
                                    </div>
                                    <div class="card-body">
                                        <h2 class="card-title">Almacenamiento</h2>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary">
                                                Seleccionar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger ms-2 btn-remove"
                                                style="display: none;"
                                                data-component="almacenamiento"
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Fuente de Alimentación --}}
                            <div class="col-md-4 mb-3">
                                <div class="card text-left h-100">
                                    <div class="component-image-container">
                                        <img
                                            class="component-image"
                                            id="fuente_de_alimentacion-image"
                                            src="{{ asset('images/placeholder-component.png') }}"
                                            style="display: none;"
                                        >
                                    </div>
                                    <div class="card-body">
                                        <h2 class="card-title">Fuente de Alimentación</h2>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary">
                                                Seleccionar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger ms-2 btn-remove"
                                                style="display: none;"
                                                data-component="fuente_de_alimentacion"
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="{{ asset('js/configuraciones.js') }}"></script> --}}
    
    <link href="{{ asset('css/configuraciones.css') }}" rel="stylesheet">
    
    @push('scripts')
        <script type="module" src="{{ asset('js/configuraciones/configuraciones.js') }}"></script>
    @endpush
@endsection
