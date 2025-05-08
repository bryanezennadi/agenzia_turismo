<?php
// Funzione per determinare il percorso del CSS
$path_Style = function () {
    // Ottieni il nome del file corrente
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Verifica se la pagina è inserimento.php
    if ($currentPage === "dashboard.php") {
        return '../../altri_file/dashboard.css'; // Percorso CSS per la home
    }
    else if($currentPage === "login.php"){
        return'../../altri_file/login.css';
    }
    else if($currentPage === "catalogo.php"){
        return '../../altri_file/Catalogo.css';
    }
    else if($currentPage === "dettagli.php"){
        return '../../altri_file/Dettagli.css';
    }
    else if($currentPage === "carrello.php"){
        return '../../altri_file/carrello.css';
    }
    else if($currentPage === "pagamento.php"){
        return '../../altri_file/pagamento.css';
    }
    else {
        return '../../altri_file/Catalogo.css'; // Percorso CSS per altre pagine
    }
};

// Funzione per determinare il titolo della pagina
$titolo = function () {
    $currentPage = basename($_SERVER['PHP_SELF']);
    switch ($currentPage) {
        case "dashboard.php":
            return 'DASHBOARD';
        case "catalogo.php":
            return 'Catalogo';
        case "carrello.php": return 'CARRELLO';
        case "pagamento.php": return 'PAGAMENTO';
        default:
            return 'pagina non trovata';
    }
};

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Link per il CSS dinamico -->
    <link rel="stylesheet" href="<?= $path_Style() ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Titolo dinamico -->
    <title><?= $titolo() ?></title>
</head>
<body>
<nav class="navbar">

    <button class="navbar-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="navbar-collapse">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'nav-link active' : 'nav-link'; ?>">
                <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-circle"></i>
                    <span>Profilo</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="catalogo.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'catalogo.php') ? 'nav-link active' : 'nav-link'; ?>">

                <i class="fas fa-clipboard-list"></i>
                    <span>Catalogo</span>
                </a>
            </li>
            <li class="nav-item item-carrello">
                <a href="carrello.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'carrello.php') ? 'nav-link active' : 'nav-link'; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Carrello</span>
                </a>
            </li>
            <?php
            if(isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'admin'): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users-cog"></i>
                        <span>Gestione Utenti</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="user-dropdown">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <h3 class="user-name">
                        <?php
                        if(isset($conn) && isset($_SESSION['user_id'])) {
                            // Query per ottenere il nome utente
                            $userId = $_SESSION['user_id'];
                            $stmt = $conn->prepare("
                                    SELECT visitatori.nome 
                                    FROM login
                                    JOIN visitatori ON login.id = visitatori.id_credenziali
                                    WHERE login.id = :id
                                ");

                            $stmt->bindParam(':id', $userId);
                            $stmt->execute();
                            $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($userInfo) {
                                echo htmlspecialchars($userInfo['nome']);
                            } else {
                                echo "Utente";
                            }
                        } else {
                            echo "Utente";
                        }
                        ?>
                    </h3>
                    <p class="user-role">
                        <?php
                        if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 'admin') {
                            echo "Amministratore";
                        } else {
                            echo "visitatore";
                        }
                        ?>
                    </p>
                </div>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>

            <div class="dropdown-menu">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Il mio profilo</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    <span>Impostazioni</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="../../altri_file/componenti/operazioni/logoutOP.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Contenuto della pagina -->
<div class="content-container">
    <!-- Il contenuto specifico della pagina andrebbe qui -->
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggle = document.querySelector('.navbar-toggle');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const userDropdown = document.querySelector('.user-info');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        // Toggle per la navbar mobile
        navbarToggle.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });

        // Toggle per il dropdown utente
        userDropdown.addEventListener('click', function() {
            dropdownMenu.classList.toggle('show');
        });

        // Chiude la navbar quando si clicca fuori
        document.addEventListener('click', function(event) {
            const isClickInsideNavbar = navbarCollapse.contains(event.target) || navbarToggle.contains(event.target);
            if (!isClickInsideNavbar && navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
            }

            const isClickInsideDropdown = userDropdown.contains(event.target);
            if (!isClickInsideDropdown && dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>