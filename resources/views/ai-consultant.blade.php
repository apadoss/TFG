@extends('layouts/app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4 h1">Asesor IA</h1>

            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 70vh">
                <h2 class="text-center mb-4 h2">Bienvenido, cuéntame tus necesidades y generaré una configuración por ti.</h2>
                <div class="input-group">
                    <textarea id="user-message" class="form-control" placeholder="Escribe tu mensaje aquí..." rows="3"></textarea>
                    <button id="send-button" class="btn btn-primary" type="button">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset("css/ai-consultant.css")}}">
<script src="{{asset("js/ai-consultant.js")}}"></script>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
@endsection
