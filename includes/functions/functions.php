<?php

function sessionStatus() {
    
    if (isset($_SESSION['success'])) {
        echo "<p class= 'success'>" . $_SESSION['success'] . "</p>";

        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p class='error' style = '    display: box;
    color: red;
    text-align : center;
    font-weight: bold;
    font-size: 17px; 
    margin-bottom: 7px;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }

}


function getGeminiResponse($prompt, $apiKey) {
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
          "temperature" => 0.0,
          "topP" => 1,
          "topK" => 1,
          "candidateCount" => 1,
          "maxOutputTokens" => 2048,
          "stopSequences" => []
        ],
        'safetySettings' => [
            [
                'category' => 'HARM_CATEGORY_HARASSMENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ]
        ]
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
            ],
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    return $response;
}


function decodeGeminiResponse($jsonString) {
    $decoded = json_decode($jsonString, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Error al decodificar JSON: ' . json_last_error_msg()];
    }
    return $decoded;
}

?>