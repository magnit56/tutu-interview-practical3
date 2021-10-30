<?php

$data = json_encode(file_get_contents('php://input'), TRUE);
file_put_contents('file.txt', '$data: ' . print_r($data, 1) . '\n', FILE_APPEND);

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

define('TOKEN', '2056422928:AAFENBxYSL7_zFUXAoBFF4p6WBdAItoOFSc');

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']), 'utf-8');

switch ($message)
{
    case 'текст':
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Вот мой ответ',
        ];
        break;
    case 'кнопки':
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Вот мои кнопки',
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Кнопка 1'],
                        ['text' => 'Кнопка 2'],
                    ],
                    [
                        ['text' => 'Кнопка 3'],
                        ['text' => 'Кнопка 4'],
                    ],
                ],
            ]
        ];
        break;
    case 'видео':
        $method = 'sendVideo';
        $send_data = [
            'video' => 'https://chastoedov.ru/video/amo.mp4',
            'caption' => 'Вот мое видео',
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' => [
                    [
                        ['text' => 'Кнопка 1'],
                        ['text' => 'Кнопка 2'],
                    ],
                    [
                        ['text' => 'Кнопка 3'],
                        ['text' => 'Кнопка 4'],
                    ],
                ],
            ],
        ];
        break;
    default:
        $send_data = [
            'method' => 'sendMessage',
            'text' => 'Не понимаю о чем вы :(',
        ];
}

$send_data['chat_id'] = $data['chat']['id'];

$res = sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = [])
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array('Content-Type: application/json'), $headers),
    ]);

    $result = curl_exec($curl);
    curl_close($curl);
    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}