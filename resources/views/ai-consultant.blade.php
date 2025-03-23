@extends('layouts/app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Asesor IA</h1>

            <div id="chat-container" class="mb-3" style="height: 500px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
                <div id="initial-message" class="alert alert-info">
                    Bienvenido, pregunta lo que quieras!
                </div>
            </div>

            <div class="input-group">
                <textarea id="user-message" class="form-control" placeholder="Escribe tu mensaje aquí..." rows="3"></textarea>
                <button id="send-button" class="btn btn-primary" type="button">Enviar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset("css/ai-consultant.css")}}">
<script src="{{asset("js/ai-consultant.js")}}"></script>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
@endsection
