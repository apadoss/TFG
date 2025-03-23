document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos DOM
    const chatContainer = document.getElementById('chat-container');
    const userMessageInput = document.getElementById('user-message');
    const sendButton = document.getElementById('send-button');
    const initialMessage = document.getElementById('initial-message');
    
    // Verificar que los elementos existen
    if (!chatContainer || !userMessageInput || !sendButton) {
        console.error('Error: No se encontraron todos los elementos necesarios del DOM');
        return;
    }

    // Crear el modal de carga/resultados si no existe ya
    if (!document.getElementById('responseModal')) {
        const modalHTML = `
        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="responseModalLabel">Configuración PC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="loadingSpinner" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Generando configuraciones...</p>
                        </div>
                        <div id="responseCarousel" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner">
                                <!-- Las tablas de configuración se insertarán aquí -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#responseCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#responseCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer" id="responseButtons">
                        <button type="button" class="btn btn-success" id="saveConfigBtn">Guardar configuración</button>
                        <button type="button" class="btn btn-primary" id="regenerateBtn">Volver a generar</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Agregar el modal al body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    // Verificar si Bootstrap está disponible antes de inicializar el modal
    let responseModal;
    if (typeof bootstrap !== 'undefined') {
        responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
    } else {
        console.error('Bootstrap no está disponible. Asegúrate de incluir la biblioteca Bootstrap.');
    }
    
    // Obtener referencias a los elementos del modal
    const loadingSpinner = document.getElementById('loadingSpinner');
    const responseCarousel = document.getElementById('responseCarousel');
    const responseButtons = document.getElementById('responseButtons');
    const saveConfigBtn = document.getElementById('saveConfigBtn');
    const regenerateBtn = document.getElementById('regenerateBtn');
    
    // Último mensaje enviado para regenerar
    let lastUserMessage = '';

    // Función principal para enviar mensajes
    function sendMessage() {
        const userMessage = userMessageInput.value.trim();
        if (userMessage === '') {
            console.log('Mensaje vacío, no se envía');
            return;
        }
        
        console.log('Enviando mensaje:', userMessage);
        lastUserMessage = userMessage;
        
        // Ocultar mensaje inicial si existe
        if (initialMessage) {
            initialMessage.style.display = 'none';
        }

        // Agregar mensaje del usuario al chat
        appendMessage('user', userMessage);
        userMessageInput.value = '';
        
        // Si el modal y el spinner existen, mostrarlos
        if (loadingSpinner && responseModal) {
            loadingSpinner.style.display = 'block';
            if (responseCarousel) responseCarousel.style.display = 'none';
            if (responseButtons) responseButtons.style.display = 'none';
            responseModal.show();
        } else {
            console.error('No se encuentran elementos del modal');
        }

        // Verificar que existe el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('No se encontró el token CSRF. Asegúrate de incluirlo en la plantilla.');
            appendMessage('ai', 'Error: No se encontró el token CSRF.');
            if (responseModal) responseModal.hide();
            return;
        }

        // Realizar la solicitud al servidor
        fetch('/asesor-ia/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({ message: userMessage })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error de red: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta recibida:', data);
            if (data.message) {
                // Agregar la respuesta al chat
                appendMessage('ai', data.message, true);
                
                // Procesar la respuesta para el carrusel
                processTableResponse(data.message);
            } else {
                console.error('Respuesta sin mensaje:', data);
                appendMessage('ai', 'Error al obtener una respuesta.');
                if (responseModal) responseModal.hide();
            }
        })
        .catch(error => {
            console.error('Error al enviar mensaje:', error);
            appendMessage('ai', 'Error al procesar tu solicitud: ' + error.message);
            if (responseModal) responseModal.hide();
        });
    }

    function processTableResponse(markdownText) {
        console.log('Procesando respuesta para el carrusel');
        // Analizar la respuesta markdown para identificar las tablas
        const tables = extractTables(markdownText);
        
        // Ocultar el spinner
        if (loadingSpinner) loadingSpinner.style.display = 'none';
        
        // Si se encontraron tablas, mostrar el carrusel
        if (tables.length > 0 && responseCarousel) {
            const carouselInner = responseCarousel.querySelector('.carousel-inner');
            if (carouselInner) {
                carouselInner.innerHTML = '';
                
                // Crear un slide para cada tabla
                tables.forEach((table, index) => {
                    const slide = document.createElement('div');
                    slide.className = 'carousel-item' + (index === 0 ? ' active' : '');
                    
                    // Crear un contenedor para la tabla con título
                    const tableContainer = document.createElement('div');
                    tableContainer.className = 'table-responsive';
                    
                    // Determinar el nivel basado en el índice o el contenido
                    let levelTitle = 'Configuración';
                    if (table.includes('Básico')) levelTitle += ' Básica';
                    else if (table.includes('Intermedio')) levelTitle += ' Intermedia';
                    else if (table.includes('Avanzado')) levelTitle += ' Avanzada';
                    else {
                        const levels = ['Básica', 'Intermedia', 'Avanzada'];
                        levelTitle += ' ' + levels[index % 3];
                    }
                    
                    const title = document.createElement('h4');
                    title.className = 'text-center mb-3';
                    title.textContent = levelTitle;
                    
                    // Convertir la tabla markdown a HTML con la librería marked
                    try {
                        if (typeof marked !== 'undefined') {
                            const tableHtml = marked.parse(table);
                            tableContainer.innerHTML = tableHtml;
                        } else {
                            console.error('La librería marked no está disponible');
                            tableContainer.innerHTML = '<pre>' + table + '</pre>';
                        }
                    } catch (error) {
                        console.error('Error al parsear markdown:', error);
                        tableContainer.innerHTML = '<pre>' + table + '</pre>';
                    }
                    
                    slide.appendChild(title);
                    slide.appendChild(tableContainer);
                    carouselInner.appendChild(slide);
                });
                
                // Mostrar el carrusel y los botones
                responseCarousel.style.display = 'block';
                if (responseButtons) responseButtons.style.display = 'flex';
            } else {
                console.error('No se encuentra el contenedor del carrusel');
            }
        } else {
            // Si no hay tablas o no existe el carrusel, mostrar un mensaje
            if (responseCarousel) {
                responseCarousel.innerHTML = '<div class="alert alert-warning">No se encontraron configuraciones válidas.</div>';
                responseCarousel.style.display = 'block';
            } else {
                console.error('No se encuentra el elemento del carrusel');
            }
        }
    }

    function extractTables(markdownText) {
        console.log('Extrayendo tablas del texto markdown');
        // Función para extrair tablas de un texto markdown
        const tables = [];
        let currentTable = '';
        let inTable = false;
        
        // Dividir el texto en líneas
        const lines = markdownText.split('\n');
        
        // Identificar las tablas basadas en las filas que contienen "|"
        lines.forEach(line => {
            if (line.includes('|')) {
                if (!inTable) {
                    inTable = true;
                    currentTable = line + '\n';
                } else {
                    currentTable += line + '\n';
                }
            } else {
                if (inTable && currentTable.trim() !== '') {
                    tables.push(currentTable);
                    currentTable = '';
                    inTable = false;
                }
            }
        });
        
        // Agregar la última tabla si existe
        if (inTable && currentTable.trim() !== '') {
            tables.push(currentTable);
        }
        
        // Intentar separar las tablas por nivel si es posible
        const levelTables = [];
        const mainTable = tables.join('\n');
        
        if (mainTable.includes('Básico') && mainTable.includes('Intermedio') && mainTable.includes('Avanzado')) {
            // Intentar separar por las filas que contienen los niveles
            let currentPart = '';
            let currentLevel = '';
            
            mainTable.split('\n').forEach(line => {
                if (line.includes('Básico') || line.includes('Intermedio') || line.includes('Avanzado')) {
                    // Si ya tenemos una parte y estamos cambiando de nivel, guardarla
                    if (currentPart.trim() !== '' && currentLevel !== '') {
                        levelTables.push(currentPart);
                        currentPart = '';
                    }
                    
                    // Actualizar el nivel actual
                    if (line.includes('Básico')) currentLevel = 'Básico';
                    else if (line.includes('Intermedio')) currentLevel = 'Intermedio';
                    else if (line.includes('Avanzado')) currentLevel = 'Avanzado';
                }
                
                // Agregar la línea a la parte actual
                if (currentLevel !== '') {
                    currentPart += line + '\n';
                }
            });
            
            // Agregar la última parte si existe
            if (currentPart.trim() !== '') {
                levelTables.push(currentPart);
            }
        }
        
        console.log('Tablas encontradas:', levelTables.length > 0 ? levelTables.length : tables.length);
        return levelTables.length > 0 ? levelTables : tables;
    }

    function appendMessage(sender, message, isMarkdown = false) {
        console.log(`Agregando mensaje de ${sender}`);
        
        // Crear la estructura del mensaje
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message-container');
        messageDiv.classList.add(sender + '-message');
        
        const bubble = document.createElement('div');
        bubble.classList.add('message-bubble');
        
        // Preparar el contenido del mensaje
        let messageContent;
        if (sender === 'ai' && isMarkdown && message.includes('|')) {
            // Para respuestas con tablas, mostrar un texto simplificado
            messageContent = "Se han generado las configuraciones de PC. Haz clic para ver los detalles.";
        } else {
            messageContent = message;
        }
        
        // Insertar el contenido
        try {
            if (isMarkdown && typeof marked !== 'undefined') {
                bubble.innerHTML = marked.parse(messageContent);
            } else {
                bubble.textContent = messageContent;
            }
        } catch (error) {
            console.error('Error al renderizar markdown:', error);
            bubble.textContent = messageContent;
        }
        
        // Si es una respuesta con tablas, hacer que sea clickeable
        if (sender === 'ai' && isMarkdown && message.includes('|') && responseModal) {
            bubble.style.cursor = 'pointer';
            bubble.onclick = function() {
                responseModal.show();
            };
        }
        
        // Agregar el mensaje al chat
        messageDiv.appendChild(bubble);
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Agregar event listeners
    sendButton.addEventListener('click', function(event) {
        console.log('Botón de enviar clickeado');
        event.preventDefault(); // Prevenir comportamiento por defecto
        sendMessage();
    });

    userMessageInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            console.log('Enter presionado');
            event.preventDefault();
            sendMessage();
        }
    });

    // Botones del modal
    if (saveConfigBtn) {
        saveConfigBtn.addEventListener('click', function() {
            alert('Configuración guardada correctamente');
        });
    }

    if (regenerateBtn) {
        regenerateBtn.addEventListener('click', function() {
            if (responseModal) responseModal.hide();
            
            // Usar el último mensaje enviado para regenerar
            if (lastUserMessage) {
                userMessageInput.value = lastUserMessage;
                setTimeout(() => sendMessage(), 500);
            }
        });
    }

    console.log('Script de chat inicializado correctamente');
});