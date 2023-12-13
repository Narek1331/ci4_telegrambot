<?php

namespace App\Controllers;

use App\Models\MessageModel;
use CodeIgniter\Controller;

class TelegramController extends Controller
{
    public function webhook()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $message = $data['message']['text'];
        $chatId = $data['message']['chat']['id'];
        $userName = $data['message']['from']['username'];
        $mediaType = isset($data['message']['photo']) ? 'photo' : (isset($data['message']['video']) ? 'video' : 'text');

        $response = $this->processMessage($message, $userName, $mediaType);

        $this->sendTelegramMessage($chatId, $response);

        $this->saveMessage($userName, $message, $response, $mediaType);

        return $this->response->setStatusCode(200);
    }

    private function processMessage($message, $userName, $mediaType)
    {
        $response = '';
        if (stripos($message, 'привет') !== false || stripos($message, 'здравствуй') !== false) {
            $response = "Здравствуйте, $userName\n\n" .
                "1. Вопрос - при клике на нее бот должен ответить: 'Что подсказать?'\n" .
                "2. Сайт - [ссылка на гугл]\n\n" .
                "Кнопки располагаются 2 в строку.";
        } else {
            $response = 'Спасибо за сообщение';
        }

        if ($mediaType === 'photo') {
            $photoUrl = $this->saveMedia($message['photo'][0]['file_id'], 'uploads/photos/');
            $response .= "\n\nСпасибо за фото, вот [ссылка на фото]($photoUrl).";
        } elseif ($mediaType === 'video') {
            $videoUrl = $this->saveMedia($message['video']['file_id'], 'uploads/videos/');
            $response .= "\n\nСпасибо за видео, вот [ссылка на видео]($videoUrl).";
        }

        return $response;
    }

    private function saveMedia($fileId, $uploadPath)
    {
        $fileInfo = $this->getTelegramFileInfo($fileId);
    
        if ($fileInfo) {
            $fileName = uniqid() . '_' . $fileId . '.' . pathinfo($fileInfo['file_path'], PATHINFO_EXTENSION);
    
            $fileContent = file_get_contents('https://api.telegram.org/file/bot' . getenv('TELEGRAM_BOT_TOKEN') . '/' . $fileInfo['file_path']);
            if ($fileContent !== false) {
                write_file(WRITEPATH . $uploadPath . $fileName, $fileContent);
    
                return $uploadPath . $fileName;
            }
        }
    
        return null;
    }

    private function getTelegramFileInfo($fileId)
    {
        $botToken = getenv('TELEGRAM_BOT_TOKEN');

        $url = "https://api.telegram.org/bot$botToken/getFile?file_id=$fileId";
        $response = json_decode(file_get_contents($url), true);

        return $response['ok'] ? $response['result'] : null;
    }

    private function sendTelegramMessage($chatId, $message)
    {
        $botToken = getenv('TELEGRAM_BOT_TOKEN');

        $url = "https://api.telegram.org/bot$botToken/sendMessage";

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown', 
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        file_get_contents($url, false, $context);
    }


    private function saveMessage($userName, $incomingMessage, $outgoingMessage, $mediaType)
    {
        $messageModel = new MessageModel();
        $messageModel->insert([
            'username' => $userName,
            'incoming_message' => $incomingMessage,
            'outgoing_message' => $outgoingMessage,
            'media_type' => $mediaType,
        ]);
    }

    public function showHistory()
    {
        $messageModel = new MessageModel();
        $data['messages'] = $messageModel->findAll();

        return view('message_history', $data);
    }
}
