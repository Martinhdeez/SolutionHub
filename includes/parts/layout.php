<aside class="sidebar">
    <!-- Sidebar header -->
    <header class="sidebar-header">
      <a href="../views/index.php" class="header-logo">
        <img src="../assets/img/logo.png" alt="CodingNepal">
      </a>
      <button class="toggler sidebar-toggler">
        <span class="material-symbols-rounded">chevron_left</span>
      </button>
      <button class="toggler menu-toggler">
        <span class="material-symbols-rounded">menu</span>
      </button>
    </header>

    <nav class="sidebar-nav">
      <!-- Primary top nav -->
      <ul class="nav-list primary-nav">
        <li class="nav-item">
          <a href="../views/index.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">dashboard</span>
            <span class="nav-label">Dashboard</span>
          </a>
          <span class="nav-tooltip">Dashboard</span>
        </li>
        <li class="nav-item">
          <a href="../views/searchIA.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">smart_toy</span>
            <span class="nav-label">IA Search</span>
          </a>
          <span class="nav-tooltip">IA searchTerm</span>
        </li>
        <li class="nav-item">
          <a href="../views/mysolutions.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">arrow_upward</span>
            <span class="nav-label">My solutions</span>
          </a>
          <span class="nav-tooltip">My solutions</span>
        </li>
        <li class="nav-item">
          <a href="../views/myskills.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">school</span>
            <span class="nav-label">My skills</span>
          </a>
          <span class="nav-tooltip">My skills</span>
        </li>
        <li class="nav-item">
            <a href="../views/socials.php" class="nav-link">
                <span class="nav-icon material-symbols-rounded">hub</span>
                <span class="nav-label">GitHub</span>
                <span id="global-unread-count" style="display: none;"></span> 
            </a>
            <span class="nav-tooltip">GitHub</span>
        </li>
        <li class="nav-item">
              <a href="../views/mychats.php" class="nav-link">
                <span class="nav-icon material-symbols-rounded">chat</span>
                <span class="nav-label">My Chats</span>
                <span id="chat-unread-count" class="notification-dot" style="display: none;"></span> 
              </a>
            <span class="nav-tooltip">My Chats</span>
        </li>

      </ul>

      <!-- Secondary bottom nav -->
      <ul class="nav-list secondary-nav">
        <li class="nav-item">
          <a href="../views/profile.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">account_circle</span>
            <span class="nav-label">Profile</span>
          </a>
          <span class="nav-tooltip">Profile</span>
        </li>
        <li class="nav-item">
          <a href="../controllers/logoutController.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">logout</span>
            <span class="nav-label">Logout</span>
          </a>
          <span class="nav-tooltip">Logout</span>
        </li>
      </ul>
    </nav>
</aside>

<!-- Script para actualizar los mensajes no leídos -->
<script>
    function updateUnreadMessages() {
        fetch("../api/unreadMessages.php")
            .then(response => response.json())
            .then(data => {
                console.log(data); // Ver qué devuelve la API

                const unreadDot = document.getElementById("chat-unread-count");
                
                if (data.unread_count && parseInt(data.unread_count) > 0) {
                    unreadDot.style.display = "inline-block";
                } else {
                    unreadDot.style.display = "none";
                }
            })
            .catch(error => console.error("Error obteniendo mensajes no leídos:", error));
    }

    // Ejecutar la función al cargar la página y luego cada 1 segundo
    updateUnreadMessages();
    setInterval(updateUnreadMessages, 1000);
</script>

<!-- Script del layout -->
<script src="../assets/js/layout.js"></script>
