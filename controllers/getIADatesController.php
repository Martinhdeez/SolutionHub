<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/Db.php";
require_once "../includes/functions/functions.php";
session_start(); // Iniciar sesión antes de acceder a $_SESSION

if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado.");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $githubUrl = $_GET["url"];

    // Validar que la URL sea de GitHub
    if (strpos($githubUrl, "github.com") !== false) {
        $htmlContent = getHTMLContent($githubUrl);

        if ($htmlContent) {
            $repositoryInfo = extractRepositoryInfoFromHTML($htmlContent);

            // Guardar datos en la base de datos
            $db = new Db();
            $conn = $db->connect();

            try {
                $conn->beginTransaction();

                // Iterar sobre los repositorios extraídos
                foreach ($repositoryInfo as $repo) {
                    if($repo['language'] == null || $repo['language'] == ""){
                       $language_id = 18;
                    }else{
                        $language = strtoupper($repo['language']);
                        // Insertar el lenguaje
                        $stmt = $conn->prepare("SELECT * FROM languages WHERE name = ?");
                        $res = $stmt->execute([$language]);
                        $l = $stmt->fetch();
                        if($l){
                            $language_id = $l['id'];
                        }else{
                            $language_id = 18;
                        }                  
                    }
                 

                    if($repo['repositorio_url']){
                         // Insertar los detalles del repositorio en la tabla userinfo
                        $stmt = $conn->prepare("INSERT INTO userinfo (user_id, repositorio_url, language_id) VALUES (?, ?, ?)");
                        $stmt->execute([$_SESSION['user_id'], $repo['repositorio_url'], $language_id]);
                    }
                   
                }

                $conn->commit();
                echo "Datos guardados correctamente";
            } catch (PDOException $e) {
                $conn->rollBack();
                die("Error al guardar los datos: " . $e->getMessage());
            }

            // Redirigir al perfil del usuario
            header("Location: ../views/index.php");
            exit();
        } else {
            echo "<p>Error: No se pudo obtener el contenido HTML desde la URL.</p>";
        }
    } else {
        echo "<p>Error: URL de GitHub no válida. Por favor, ingrese una URL que contenga 'github.com'.</p>";
    }
}

function getHTMLContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $htmlContent = curl_exec($ch);

    if (curl_errno($ch)) {
        return false;
    }

    curl_close($ch);
    return $htmlContent;
}

function extractRepositoryInfoFromHTML($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html); // Suprime warnings
    $xpath = new DOMXPath($dom);

    $repositories = [];

    $repoList = $xpath->query('//ol[@class="d-flex flex-wrap list-style-none gutter-condensed mb-4"]')->item(0);

    if ($repoList) {
        $repoItems = $xpath->query('./li', $repoList);

        foreach ($repoItems as $repoItem) {
            $repoData = [];

            $repoLinkNode = $xpath->query('.//a[@class="min-width-0 Link text-bold flex-auto wb-break-all"]', $repoItem)->item(0);
            if ($repoLinkNode) {
                $repoData['name'] = trim($repoLinkNode->textContent);
                $repoData['repositorio_url'] = $repoLinkNode->getAttribute('href'); // Changed 'link' to 'repositorio_url'
            } else {
                $repoData['name'] = null;
                $repoData['repositorio_url'] = null;
            }

            $repoDescNode = $xpath->query('.//p[@class="pinned-item-desc color-fg-muted text-small d-block mt-2 mb-3"]', $repoItem)->item(0);
            $repoData['description'] = $repoDescNode ? trim($repoDescNode->textContent) : null;

            $languageNode = $xpath->query('.//span[@itemprop="programmingLanguage"]', $repoItem)->item(0);
            $repoData['language'] = $languageNode ? trim($languageNode->textContent) : null;

            $repositories[] = $repoData;
        }
    }

    return $repositories;
}
?>
