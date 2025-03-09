<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIConsultantController extends Controller
{
    public function index() {
        return view('ai-consultant');
    }

    public function sendMessage(Request $request) {
        $userMessage = $request->input('message');
        $apiKey = env('DEEPSEEK_API_KEY');

        if (empty($apiKey)) {
            return response()->json(['error' => 'No se ha configurado la clave de API de Deepseek.'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ])
            ->timeout(120)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'deepseek/deepseek-r1:free',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $userMessage,
                    ],
                ],
            ]);

            $responseData = $response->json();

            if (isset($responseData['choices'][0]['message']['content'])) {
                return response()->json(['message' => $responseData['choices'][0]['message']['content']]);
            } else {
                return response()->json(['error' => 'No se ha podido obtener una respuesta.', 'response' => $responseData], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ha ocurrido un error al procesar tu solicitud.'], 500);
        }
    }
}
