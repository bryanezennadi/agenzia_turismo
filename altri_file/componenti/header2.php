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
    else {
        return "../altre_pagine/styleForm.css"; // Percorso CSS per altre pagine
    }
};

// Funzione per determinare il titolo della pagina
$titolo = function () {
    $currentPage = basename($_SERVER['PHP_SELF']);
    switch ($currentPage) {
        case "dashboard.php":
            return 'DASHBOARD';
        case "modifica.php":
            return 'Pagina_Modifica';
        default:
            return 'pagina non trovata';
    }
};
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Titolo dinamico -->
    <title><?= $titolo() ?></title> <!-- Corretto: chiamato la funzione con le parentesi -->
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-chart-line"></i>
        <h2>MyDashboard</h2>
    </div>

    <button class="navbar-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="navbar-collapse">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
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
                <a href="#" class="nav-link">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Attività</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-envelope"></i>
                    <span>Messaggi</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Impostazioni</span>
                </a>
            </li>
            <?php session_start();
            if($_SESSION['ruolo'] == 'admin'): ?>
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
                            echo htmlspecialchars($userInfo['nome'] . ' ' );
                        } else {
                            echo "Utente";
                        }
                        ?>
                    </h3>
                    <p class="user-role">
                        <?php
                        if ($_SESSION['ruolo'] == 'admin') {
                            echo "Amministratore";
                        } else {
                            echo "Utente";
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
                <a href="../operazioni/logoutOP.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</nav>

