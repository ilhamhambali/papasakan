<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- Penting untuk melakukan HTTP request

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $userMessage = $request->input('message');
        $apiKey = config('services.gemini.api_key'); // Kita akan setup ini nanti
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}";

        // Prompt engineering: Memberi konteks pada AI
        $prompt = "You are a friendly and helpful cooking assistant named 'Chef Bot' on a recipe website. Your expertise is in Indonesian and Southeast Asian cuisine, but you can answer general cooking questions too. Keep your answers concise and easy to understand. User's question: " . $userMessage;

        try {
            $response = Http::timeout(30)->post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text');
                return response()->json(['reply' => $reply]);
            } else {
                return response()->json(['reply' => 'Maaf, saya sedang kesulitan berpikir saat ini. Coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Maaf, terjadi masalah saat menghubungi asisten AI.'], 500);
        }
    }
}
