<?php
require_once '../../altri_file/componenti/connection.php';
require_once '../../altri_file/componenti/header2.php'
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Sidebar -->

<!-- Main Content -->
<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div class="header-actions">
            <div class="user-welcome">
                Benvenuto,
                <?php
                if (isset($userInfo) && $userInfo) {
                    echo htmlspecialchars($userInfo['nome']);
                } else {
                    echo "Utente";
                }
                ?>!
            </div>
            <button>
                <i class="fas fa-bell"></i>
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="overview-cards">
        <div class="card">
            <i class="fas fa-calendar-check card-icon"></i>
            <div class="card-title">Attività Completate</div>
            <div class="card-value">24</div>
            <div class="card-info success">
                <i class="fas fa-arrow-up"></i> 12% questa settimana
            </div>
        </div>
        <div class="card">
            <i class="fas fa-tasks card-icon"></i>
            <div class="card-title">Attività in Corso</div>
            <div class="card-value">7</div>
            <div class="card-info info">
                <i class="fas fa-arrow-right"></i> 3 con scadenza oggi
            </div>
        </div>
        <div class="card">
            <i class="fas fa-clock card-icon"></i>
            <div class="card-title">Ore di Attività</div>
            <div class="card-value">32.5</div>
            <div class="card-info warning">
                <i class="fas fa-arrow-down"></i> -2% rispetto alla media
            </div>
        </div>
        <div class="card">
            <i class="fas fa-star card-icon"></i>
            <div class="card-title">Punteggio</div>
            <div class="card-value">89</div>
            <div class="card-info success">
                <i class="fas fa-medal"></i> Ottimo rendimento!
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="two-columns">
        <!-- Main Column -->
        <div class="main-column">
            <!-- Recent Activities -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Attività Recenti</h2>
                    <span class="section-action">Vedi tutte</span>
                </div>
                <div class="activities-container">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Attività completata: Aggiornamento profilo</div>
                            <div class="activity-time">Oggi, 14:30</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Documento caricato: Report Mensile</div>
                            <div class="activity-time">Ieri, 11:45</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Promemoria: Scadenza progetto imminente</div>
                            <div class="activity-time">Ieri, 09:15</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-danger">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Attività non completata: Revisione documenti</div>
                            <div class="activity-time">22 Apr, 16:20</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Attività completata: Meeting con team</div>
                            <div class="activity-time">21 Apr, 10:00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Statistiche Rapide</h2>
                </div>
                <div class="stats-grid">
                    <div class="stats-card">
                        <div class="stats-header">
                            <div class="stats-title">Produttività Settimanale</div>
                            <div class="stats-icon bg-success">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <!-- Progress items -->
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-title">Lunedì</span>
                                <span class="progress-value">85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill primary" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-title">Martedì</span>
                                <span class="progress-value">70%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill success" style="width: 70%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-title">Mercoledì</span>
                                <span class="progress-value">90%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill primary" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-header">
                            <div class="stats-title">Progetti Attivi</div>
                            <div class="stats-icon bg-warning">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                        </div>
                        <!-- Progress items -->
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-title">Progetto Alpha</span>
                                <span class="progress-value">65%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill warning" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-title">Progetto Beta</span>
                                <span class="progress-value">40%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill danger" style="width: 40%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-title">Progetto Gamma</span>
                                <span class="progress-value">78%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill success" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="sidebar-column">
            <div class="card">
                <div class="section-header">
                    <h2 class="section-title">Nuovi Messaggi</h2>
                    <span class="section-action">Vedi tutti</span>
                </div>
                <div class="message-preview">
                    <div class="message-avatar">L</div>
                    <div class="message-content">
                        <div class="message-header">
                            <span class="message-sender">Laura Bianchi</span>
                            <span class="message-time">10:30</span>
                        </div>
                        <div class="message-text">
                            Buongiorno, volevo confermare l'appuntamento di domani...
                        </div>
                    </div>
                </div>
                <div class="message-preview">
                    <div class="message-avatar">M</div>
                    <div class="message-content">
                        <div class="message-header">
                            <span class="message-sender">Marco Rossi</span>
                            <span class="message-time">Ieri</span>
                        </div>
                        <div class="message-text">
                            Ho caricato i nuovi documenti nel sistema, puoi controllarli?
                        </div>
                    </div>
                </div>
                <div class="message-preview">
                    <div class="message-avatar">S</div>
                    <div class="message-content">
                        <div class="message-header">
                            <span class="message-sender">Sofia Verdi</span>
                            <span class="message-time">20 Apr</span>
                        </div>
                        <div class="message-text">
                            Ti ringrazio per il supporto, mi è stato molto utile...
                        </div>
                    </div>
                </div>
            </div>

            <div class="card progress-card">
                <div class="section-header">
                    <h2 class="section-title">Obiettivi</h2>
                </div>
                <div class="progress-item">
                    <div class="progress-header">
                        <span class="progress-title">Completamento corsi</span>
                        <span class="progress-value">75%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill primary" style="width: 75%"></div>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-header">
                        <span class="progress-title">Produttività</span>
                        <span class="progress-value">92%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill success" style="width: 92%"></div>
                    </div>
                </div>
                <div class="progress-item">
                    <div class="progress-header">
                        <span class="progress-title">Budget utilizzato</span>
                        <span class="progress-value">45%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill warning" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle sidebar for responsive design
    document.querySelector('.sidebar-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('expanded');
        document.querySelector('.main-content').classList.toggle('shifted');
    });
</script>
</body>
</html>