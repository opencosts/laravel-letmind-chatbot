<?php

namespace App\Http\Controllers;

use App\Services\NetMindService;
use App\Services\ChatBot;

use Illuminate\Http\Request;

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
        $messages = [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $request->message
                    ]

                ]
            ]
        ];

        $response = $this->netmind->chatCompletion($messages);

        $payload = [
            'request' => $request->all(),
            'response' => $response
        ];

        $this->chatbot->chatbotConversation($payload);

        return response()->json($response);
    }
}
