<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../config/Db.php';
require_once '../models/Skill.php';
require_once '../models/User.php';
require_once '../models/Solution.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// API Key de Google AI Studio (debería estar en un archivo de configuración seguro)
$apiKey = 'AIzaSyCWI3DNhkMYJtYK1bhJeIldJizdGPsAU68';

// Obtener el término de búsqueda
$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';

// Instanciar la base de datos y obtener la conexión
$db = new Db();
$conn = $db->conn;

// Instanciar los modelos usando la misma conexión PDO
$s = new Skill($db);
$skills = $s->getAllSKills();

$sol = new Solution($conn);
$solutions = $sol->getAllSolutions();

// Unificar datos en un solo array
$data_array = [
    "skills" => $skills,
    "solutions" => $solutions
];

// Asegurar que hay datos antes de hacer la solicitud a la IA
if (empty($data_array["skills"]) && empty($data_array["solutions"])) {
    echo json_encode(['error' => 'No hay datos en la base de datos']);
    exit;
}

// Convertir los resultados a string (usando json_encode) para evitar el error de conversión
$skillsStr = json_encode($skills->fetchAll(PDO::FETCH_ASSOC));

// Crear el prompt para Gemini
$prompt = <<<TEXT
Hola Gemini, por favor, podrias responderme con un string separado por comas con las ids de
las skills localizadas en este JSON {{skillsStr}} que tengan relacion con el termino de busqueda "{{searchTerm}}", porfavor evitate explicaciones y solo dame el string con las ids.
TEXT;

$prompt = str_replace('{{searchTerm}}', $searchTerm, $prompt);
$prompt = str_replace('{{skillsStr}}', $skillsStr, $prompt);

// Función para llamar a la API de Gemini
function getGeminiResponse($prompt, $apiKey)
{
   try {
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

    $data = [
        'contents' => [
            ['parts' => [['text' => $prompt]]]
        ],
        'generationConfig' => [
            "temperature" => 0.0,
            "topP" => 1,
            "topK" => 1,
            "candidateCount" => 1,
            "maxOutputTokens" => 500
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout de 30 segundos
    $response = curl_exec($ch);
    
    // En caso de error en cURL, se retorna el error en formato JSON
    if(curl_errno($ch)) {
        echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
        exit;
    }
    
    curl_close($ch);
    return $response;
   } catch (Exception $e) {
       var_dump($e);
   }

   return '';
}

$geminiResponse = getGeminiResponse($prompt, $apiKey);

if ($geminiResponse === false) {
    echo json_encode(['error' => "Error al llamar a la API de Gemini"]);
    exit;
}

$geminiResponseDecoded = json_decode($geminiResponse, true);

if (!isset($geminiResponseDecoded['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(['error' => "Error en la respuesta de Gemini"]);
    exit;
}

// Get skills ids from rensponse
$skillsIdentifiers = $geminiResponseDecoded['candidates'][0]['content']['parts'][0]['text'] ?? '';

if (empty($skillsIdentifiers)) {
    echo json_encode(['error' => "No se encontraron resultados para la búsqueda"]);
    exit;
}

echo json_encode(value: ['ids' => implode(',', array_map('trim', explode(',', $skillsIdentifiers)))]);
// Enviar resultados (siempre un JSON válido)
exit;
