<?php

namespace App\Http\Controllers;

use App\Services\NetMindService;
use App\Services\ChatBot;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class   NetMindController extends Controller
{
    protected $netmind;
    protected $chatbot;

    public function __construct(NetMindService $netmind, ChatBot $chatbot)
    {
        $this->netmind = $netmind;
        $this->chatbot = $chatbot;
    }



    public function chat(Request $request)
    {
        // $sessionId = $request->session()->getId();

        $sessionId = $request->header('X-Session-ID') ?? Str::uuid();

        DB::table('chat_histories')->insert([
            'session_id' => $sessionId,
            'role' => 'user',
            'content' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chatHistory = DB::table('chat_histories')
            ->where('session_id', $sessionId)
            ->orderBy('id', 'desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->map(fn($msg) => [
                'role' => $msg->role,
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $msg->content,
                    ]
                ]
            ])
            ->values()
            ->all();

        $messages = $chatHistory;

        $response = $this->netmind->chatCompletion($messages);

        $assistantMessage = $response['choices'][0]['message']['content'] ?? 'No reply.';

        DB::table('chat_histories')->insert([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'content' => $assistantMessage,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'request' => $request->all(),
            'response' => $response
        ];
        $this->chatbot->chatbotConversation($payload);

        return response()->json($response);
    }
}
