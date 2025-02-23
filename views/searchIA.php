<?php
// Se obtiene el término de búsqueda desde la URL
$query = isset($_GET['query']) ? $_GET['query'] : '';
// Ajusta la URL según la ubicación real del controlador
$busquedaUrl = 'http://localhost/solutionhub/controllers/busquedaController.php?query=$query';
$arrayResult = null;

// Si se introduce un término, se llama al controlador para obtener los resultados
if (!empty($query)) {
    $jsonResponse = file_get_contents($busquedaUrl . '?query=' . urlencode($query));
    $arrayResult = json_decode($jsonResponse, true);
}
require_once '../includes/parts/header.php';
?>
<link rel="stylesheet" href="../assets/css/searchIA.css"/>
</header>
<?php require_once "../includes/parts/layout.php" ?>
  <div class="container" id="contenedor">
      <h1>Búsqueda Avanzada con Gemini</h1>
      <!-- El formulario usa GET para recargar la misma vista -->
      <form method="GET" action="searchIA.php">
          <input type="text" name="query" placeholder="Introduce tu búsqueda" value="<?= htmlspecialchars($query); ?>">
          <button type="submit">Buscar</button>
      </form>
      <div id="results">
          <?php
          if ($query == "") {
              echo '<p class="loading">Introduce un término de búsqueda para ver los resultados.</p>';
          } else {
              // Si la respuesta contiene un error, lo mostramos
              if (isset($arrayResult['error'])) {
                  echo '<p class="error">' . htmlspecialchars($arrayResult['error']) . '</p>';
              } elseif (!is_array($arrayResult)) {
                  echo '<p class="error">Error al procesar la respuesta.</p>';
              } elseif (empty($arrayResult)) {
                  echo '<p class="error">No se encontraron resultados para la búsqueda.</p>';
              } else {
                  echo '<ul>';
                  // Se asume que tienes una clase Skill y un método getSkillById para obtener la información de cada resultado
                  require_once "../config/Db.php";
                  require_once "../models/Skill.php";
                  $db = new Db();
                  $s = new Skill($db);
                  foreach ($arrayResult as $id) {
                      $skill = $s->getSkillById($id);
                      if ($skill) {
                          echo '<li>';
                          echo '<a href="../views/userprofile.php?id='.$skill['user_id'].'" class="skill-link">';
                          echo '<h3>' . htmlspecialchars($skill['title']) . '</h3>';
                          echo '<p>' . htmlspecialchars($skill['description']) . '</p>';
                          echo '</a>';
                          echo '</li>';
                      }
                  }
                  echo '</ul>';
              }
          }
          ?>
      </div>
  </div>
</body>
</html>
 