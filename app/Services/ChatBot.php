<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ChatBot
{
    public function chatbotConversation(array $payload)
    {
        // Format filename based on the current date
        $date = Carbon::now()->format('Y-m-d');
        $logFile = "chatbot-{$date}.log";

        // Convert payload to JSON
        $jsonPayload = json_encode($payload, JSON_PRETTY_PRINT);

        // Create a log entry
        $logEntry = "[" . now()->toDateTimeString() . "]\n" . $jsonPayload . "\n\n";

        // Append the log entry to the file
        File::append(storage_path("logs/{$logFile}"), $logEntry);
    }
}
