@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="h1">Crear nueva configuración</h1>
    {{-- <form action="{{ route('configuraciones.store') }}" method="POST"> --}}
        {{-- @csrf --}}
        <p class="text-danger">
            <b>Nota:</b> Seleccione los valores para la creación de la configuración deseada. Mientras más 
            parámetros especifique y valores concretos indique más se ajustará la propuesta dada por el 
            sistema a sus necesidades.
        </p>
        
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="h4">Potencia de cómputo</h2>
                <div class="form-check">
                    <input class="form-check-input option-any" type="checkbox" name="computing-power[]" id="computing-power-any" value="0" checked>
                    <label class="form-check-label" for="computing-power-any">Cualquiera</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input computing-power-option" type="checkbox" name="computing-power[]" id="computing-power-low" value="1" disabled>
                    <label class="form-check-label" for="computing-power-low">Baja</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input computing-power-option" type="checkbox" name="computing-power[]" id="computing-power-medium" value="2" disabled>
                    <label class="form-check-label" for="computing-power-medium">Media</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input computing-power-option" type="checkbox" name="computing-power[]" id="computing-power-high" value="3" disabled>
                    <label class="form-check-label" for="computing-power-high">Alta</label>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="h4">Propósito principal</h2>
                <div class="form-check">
                    <input class="form-check-input option-any" type="checkbox" name="purpose[]" id="purpose-any" value="0" checked>
                    <label class="form-check-label" for="purpose-any">Cualquiera</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input purpose-option" type="checkbox" name="purpose[]" id="purpose-office" value="1" disabled>
                    <label class="form-check-label" for="purpose-office">Ofimática</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input purpose-option" type="checkbox" name="purpose[]" id="purpose-video" value="2" disabled>
                    <label class="form-check-label" for="purpose-video">Edición de video</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input purpose-option" type="checkbox" name="purpose[]" id="purpose-design" value="3" disabled>
                    <label class="form-check-label" for="purpose-design">Diseño gráfico</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input purpose-option" type="checkbox" name="purpose[]" id="purpose-gaming" value="4" disabled>
                    <label class="form-check-label" for="purpose-gaming">Gaming</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input purpose-option" type="checkbox" name="purpose[]" id="purpose-other" value="5" disabled>
                    <label class="form-check-label" for="purpose-any">Otro (especifique): </label>
                    <input type="text" class="form-control mt-1" id="purpose-other-text" name="purpose-other-text">
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="h4">Presupuesto: </h2>
                <div class="input-group">
                    <input type="text" class="form-control" id="budget-min">
                    <span class="input-group-text" id="budget-help"> - </span>
                    <input type="text" class="form-control" id="budget-max">
                    <span class="input-group-text" id="budget-help">€</span>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="h4">Portabilidad</h2>
                <select class="form-control" name="portability" id="portability">
                    <option value="1" selected>Me es indiferente</option>
                    <option value="2">Portátil</option>
                    <option value="3">Sobremesa</option>
                </select>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="h4">Almacenamiento: </h2>
                <div class="input-group">
                    <input type="text" class="form-control" id="storage-min">
                    <span class="input-group-text" id="storage-help"> - </span>
                    <input type="text" class="form-control" id="storage-max">
                    <span class="input-group-text" id="storage-help">TB</span>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="h4">Memoria RAM: </h2>
                <div class="input-group">
                    <input type="text" class="form-control" id="ram-min">
                    <span class="input-group-text" id="ram-help"> - </span>
                    <input type="text" class="form-control" id="ram-max">
                    <span class="input-group-text" id="ram-help">GB</span>
                </div>
            </div>
        </div>

        <h2 class="h2">Preferencias de marca</h2>
        <div class="row mb-3">
            <div class="col-md-6">
                <h2 class="h4">Procesador</h2>
                <div class="form-check">
                    <input class="form-check-input option-any" type="checkbox" name="cpu-brand[]" id="cpu-brand-any" value="0" checked>
                    <label class="form-check-label" for="cpu-brand-any">Cualquiera</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input cpu-brand-option" type="checkbox" name="cpu-brand[]" id="cpu-brand-intel" value="1" disabled>
                    <label class="form-check-label" for="cpu-brand-intel">Intel</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input cpu-brand-option" type="checkbox" name="cpu-brand[]" id="cpu-brand-amd" value="2" disabled>
                    <label class="form-check-label" for="cpu-brand-amd">AMD</label>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="h4">Tarjeta gráfica</h2>
                <div class="form-check">
                    <input class="form-check-input option-any" type="checkbox" name="gpu-brand[]" id="gpu-brand-any" value="0" checked>
                    <label class="form-check-label" for="gpu-brand-any">Cualquiera</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input gpu-brand-option" type="checkbox" name="gpu-brand[]" id="gpu-brand-intel" value="1" disabled>
                    <label class="form-check-label" for="gpu-brand-intel">Intel</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input gpu-brand-option" type="checkbox" name="gpu-brand[]" id="gpu-brand-amd" value="2" disabled>
                    <label class="form-check-label" for="gpu-brand-amd">AMD</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input gpu-brand-option" type="checkbox" name="gpu-brand[]" id="gpu-brand-nvidia" value="3" disabled>
                    <label class="form-check-label" for="gpu-brand-nvidia">Nvidia</label>
                </div>
            </div>
        </div>

        <div class="floating-button">
            <button type="submit" id="send-button" class="btn btn-primary">Generar</button>
        </div>
    {{-- </form> --}}
</div>

<link rel="stylesheet" href="{{ asset('css/configuracion-basica.css') }}">

<script src="{{ asset('js/configuracion-basica.js') }}"></script>
<script src="{{ asset('js/ai-consultant.js') }}"></script>
@endsection
