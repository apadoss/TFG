<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIConsultantController extends Controller
{
    public function index() {
        return view('ai-consultant');
    }

    public function sendMessage(Request $request) {
    $userMessage = $request->input('message');
    $apiKey = env('DEEPSEEK_API_KEY');

    if (empty($apiKey)) {
        Log::error('API Key no configurada');
        return response()->json(['error' => 'No se ha configurado la clave de API de Deepseek.'], 500);
    }

    try {
        $systemPrompt = "Proporciona configuraciones de PC en formato de tabla con las columnas: 
            Componente, Modelo, Precio (USD). Genera 3 tablas por nivel: Básico, Intermedio, Avanzado. 
            NO pongas el nivel como columna de la tabla. Responde solo con la tabla sin texto adicional. 
            Las filas han de ser las siguientes: \"Procesador\", \"Tarjeta Gráfica\", \"RAM\", \"Almacenamiento\", 
            \"Placa Base\", \"Fuente de Alimentación\", \"Caja\", \"Batería\" (si corresponde). Si recibes 
            un texto que no se corresponde con una configuración válida, responde con el texto: 
            \"Configuración no válida\", sin texto adicional.";

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])
        ->timeout(120)
        ->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'deepseek/deepseek-chat-v3',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
        ]);

        // Log para debugging
        Log::info('OpenRouter Response Status: ' . $response->status());
        Log::info('OpenRouter Response Body: ' . $response->body());

        if (!$response->successful()) {
            Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json([
                'error' => 'Error al comunicarse con la API.',
                'details' => $response->json()
            ], 500);
        }

        $responseData = $response->json();

        if (isset($responseData['choices'][0]['message']['content'])) {
            return response()->json([
                'message' => $responseData['choices'][0]['message']['content']
            ]);
        } else {
            Log::error('Respuesta inesperada de OpenRouter', ['response' => $responseData]);
            return response()->json([
                'error' => 'No se ha podido obtener una respuesta.',
                'response' => $responseData
            ], 500);
        }

    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        Log::error('Error de conexión con OpenRouter: ' . $e->getMessage());
        return response()->json([
            'error' => 'Error de conexión con el servicio de IA.',
            'message' => $e->getMessage()
        ], 500);

    } catch (\Exception $e) {
        Log::error('Error general: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'error' => 'Ha ocurrido un error al procesar tu solicitud.',
            'message' => $e->getMessage()
        ], 500);
    }
}
}
