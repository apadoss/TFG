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

<style>
    .message-container {
        display: flex;
        margin-bottom: 10px;
    }

    .user-message {
        justify-content: flex-end;
    }

    .ai-message {
        justify-content: flex-start;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 10px;
        word-wrap: break-word;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .user-message .message-bubble {
        background-color: #007bff; /* Bootstrap primary color */
        color: white;
        border-bottom-right-radius: 0;
    }

    .ai-message .message-bubble {
        background-color: #e9ecef; /* Light gray */
        color: #212529; /* Dark text */
        border-bottom-left-radius: 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatContainer = document.getElementById('chat-container');
        const userMessageInput = document.getElementById('user-message');
        const sendButton = document.getElementById('send-button');
        const initialMessage = document.getElementById('initial-message');

        sendButton.addEventListener('click', sendMessage);

        userMessageInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        });

        function sendMessage() {
            const userMessage = userMessageInput.value.trim();
            if (userMessage === '') return;
            if(initialMessage) {
                initialMessage.style.display = 'none';
            }

            appendMessage('user', userMessage);
            userMessageInput.value = '';

            fetch('/asesor-ia/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: userMessage })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    appendMessage('ai', data.message);
                } else {
                    appendMessage('ai', 'Error al obtener una respuesta.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                appendMessage('ai', 'Error al procesar tu solicitud.');
            });
        }

        function appendMessage(sender, message) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', `${sender}-message`);
            
            if(sender === "user") {
                messageDiv.classList.add('text-end');
            } else {
                messageDiv.classList.add('text-start');
            }
            messageDiv.classList.add('mb-2');

            messageDiv.innerHTML = `<p class="bg-${sender === 'user' ? 'primary' : 'success'} text-white p-2 rounded">${message}</p>`;
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
@endsection
