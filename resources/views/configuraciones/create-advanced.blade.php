@extends('layouts.app')

@section('content')
<div>
    <h1 class="h1">Crear nueva configuración</h1>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('configuraciones.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-left h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Procesador</h2>
                                    <button type="button" class="btn btn-primary" id="procesador">Seleccionar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card text-left h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Tarjeta gráfica</h2>
                                    <button type="button" class="btn btn-primary">Seleccionar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card text-left h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Placa Base</h2>
                                    <button type="button" class="btn btn-primary">Seleccionar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card text-left h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Memoria RAM</h2>
                                    <button type="button" class="btn btn-primary">Seleccionar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card text-left h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Almacenamiento</h2>
                                    <button type="button" class="btn btn-primary">Seleccionar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card text-left h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Fuente de Alimentación</h2>
                                    <button type="button" class="btn btn-primary">Seleccionar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/configuraciones.js')}}"></script>
<link href="{{asset('css/configuraciones.css')}}" rel="stylesheet">
@endsection