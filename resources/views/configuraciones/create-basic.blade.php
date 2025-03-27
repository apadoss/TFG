@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="h1">Crear nueva configuración</h1>
    {{-- <form action="{{ route('configuraciones.store') }}" method="POST"> --}}
        {{-- @csrf --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="computing-power">Potencia de cómputo: </label>
                <select class="form-control" name="computing-power" id="computing-power">
                    <option value="1">Baja</option>
                    <option value="2">Media</option>
                    <option value="3">Alta</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="purpose">Propósito principal: </label>
                <select class="form-control" name="purpose" id="purpose">
                    <option value="1">Ofimática</option>
                    <option value="2">Edición de video</option>
                    <option value="3">Diseño gráfico</option>
                    <option value="4">Gaming</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="budget">Presupuesto: </label>
                <div class="input-group">
                    <input type="text" class="form-control" id="budget-min">
                    <span class="input-group-text" id="budget-help"> - </span>
                    <input type="text" class="form-control" id="budget-max">
                    <span class="input-group-text" id="budget-help">€</span>
                </div>
            </div>
            <div class="col-md-6">
                <label for="portability">Portabilidad: </label>
                <select class="form-control" name="portability" id="portability">
                    <option value="1">Portátil</option>
                    <option value="2">Sobremesa</option>
                </select>
            </div>
        </div>

        <h2 class="h2">Preferencias de marca</h2>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="cpu-brand">Procesador: </label>
                <select class="form-control" name="cpu-brand" id="cpu-brand">
                    <option selected>Seleccione...</option>
                    <option value="1">Intel</option>
                    <option value="2">AMD</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="gpu-brand">Tarjeta gráfica: </label>
                <select class="form-control" name="gpu-brand" id="gpu-brand">
                    <option selected>Seleccione...</option>
                    <option value="1">Intel</option>
                    <option value="2">AMD</option>
                    <option value="3">Nvidia</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button type="submit" id="send-button" class="btn btn-primary">Generar</button>
            </div>
        </div>
    {{-- </form> --}}
</div>

<script src="{{ asset('js/ai-consultant.js') }}"></script>
@endsection
