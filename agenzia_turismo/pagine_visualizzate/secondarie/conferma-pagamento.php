<?php
// Get payment status from URL parameters
$success = isset($_GET['success']) && $_GET['success'] === 'true';
$pending = isset($_GET['pending']) && $_GET['pending'] === 'true';

session_start();

// Recupera i dati dalla sessione con i controlli di esistenza
$evento_id = isset($_SESSION['evento_id']) ? intval($_SESSION['evento_id']) : 0;
$nome_evento = isset($_SESSION['nome_evento']) ? htmlspecialchars($_SESSION['nome_evento']) : 'Evento';
$num_biglietti = isset($_SESSION['num_biglietti']) ? intval($_SESSION['num_biglietti']) : 1;
$totale = isset($_SESSION['totale']) ? floatval($_SESSION['totale']) : 0;
$data_evento = isset($_SESSION['data_evento']) ? htmlspecialchars($_SESSION['data_evento']) : 'Data non specificata';
$luogo = isset($_SESSION['luogo']) ? htmlspecialchars($_SESSION['luogo']) : 'Luogo non specificato';

// Generate order ID if not provided
$order_id = isset($_POST['order_id']) ? htmlspecialchars($_POST['order_id']) : 'ORD-' . rand(10000, 99999);

// Determine payment status message and style
if ($success) {
    $statusTitle = "Pagamento Completato";
    $statusMessage = "Il tuo pagamento è stato elaborato con successo. Riceverai a breve una email di conferma con tutti i dettagli.";
    $statusClass = "success";
    $statusIcon = "check-circle";
} elseif ($pending) {
    $statusTitle = "Pagamento in Attesa";
    $statusMessage = "Abbiamo registrato la tua richiesta di pagamento tramite bonifico bancario. Ti preghiamo di completare il pagamento entro 3 giorni lavorativi utilizzando i dati bancari inviati alla tua email.";
    $statusClass = "warning";
    $statusIcon = "clock";
} else {
    $statusTitle = "Pagamento non Riuscito";
    $statusMessage = "Si è verificato un problema durante l'elaborazione del tuo pagamento. Ti preghiamo di riprovare o contattare il nostro servizio clienti.";
    $statusClass = "error";
    $statusIcon = "exclamation-triangle";
}
require_once '../../altri_file/componenti/connection.php';
require_once '../../altri_file/componenti/header2.php';
?>
    <!-- Contenuto principale -->
    <div class="content-container">
        <div class="profile-header">
            <h1>Conferma Pagamento</h1>
            <p class="welcome-message">
                Grazie per il tuo ordine!
            </p>
        </div>

        <!-- Alert di stato -->
        <div class="alert <?php echo $statusClass; ?>">
<i class="fas fa-<?php echo $statusIcon; ?> status-icon"></i>
<div class="alert-content">
    <div class="alert-title"><?php echo $statusTitle; ?></div>
    <p><?php echo $statusMessage; ?></p>
</div>
</div>

<!-- Dettagli evento -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-ticket-alt"></i> Dettagli dell'evento</h2>
    </div>
    <div class="card-body">
        <div class="event-info">
            <h3><?php echo $nome_evento; ?></h3>
            <div class="event-detail">
                <i class="far fa-calendar-alt"></i>
                <span><?php echo $data_evento; ?></span>
            </div>
            <div class="event-detail">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo $luogo; ?></span>
            </div>
            <div class="event-detail">
                <i class="fas fa-ticket-alt"></i>
                <span>Biglietti: <?php echo $num_biglietti; ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Dettagli ordine -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-receipt"></i> Dettagli dell'ordine</h2>
    </div>
    <div class="card-body">
        <div class="info-group">
            <label>Numero ordine:</label>
            <span><?php echo $order_id; ?></span>
        </div>
        <div class="info-group">
            <label>Data:</label>
            <span><?php echo date("d/m/Y H:i"); ?></span>
        </div>
        <div class="info-group">
            <label>Importo totale:</label>
            <span>€<?php echo number_format($totale, 2, ',', '.'); ?></span>
        </div>
        <div class="info-group">
            <label>Stato pagamento:</label>
            <span>
<?php if ($success): ?>
    <span style="color: var(--secondary-color); font-weight: 600;">
<i class="fas fa-check-circle"></i> Completato
</span>
<?php elseif ($pending): ?>
    <span style="color: var(--warning-color); font-weight: 600;">
<i class="fas fa-clock"></i> In attesa
</span>
<?php else: ?>
    <span style="color: var(--danger-color); font-weight: 600;">
<i class="fas fa-times-circle"></i> Non riuscito
</span>
<?php endif; ?>
</span>
        </div>

        <?php if ($pending): ?>
            <div class="info-group">
                <label>Istruzioni:</label>
                <span>
Per completare il pagamento, effettua un bonifico bancario utilizzando i seguenti dati:<br>
<strong>Intestatario:</strong> Eventi Culturali Srl<br>
<strong>IBAN:</strong> IT60X0542811101000000123456<br>
<strong>Causale:</strong> <?php echo $order_id; ?> - <?php echo $nome_evento; ?><br>
<strong>Importo:</strong> €<?php echo number_format($totale, 2, ',', '.'); ?>
</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Azioni -->
<div class="actions">
    <?php if ($success): ?>
        <a href="ordini.php" class="btn">
            <i class="fas fa-clipboard-list"></i> Visualizza i tuoi ordini
        </a>
        <a href="../principali/dashboard.php" class="btn btn-secondary">
            <i class="fas fa-home"></i> Torna alla home
        </a>
    <?php elseif ($pending): ?>
        <a href="ordini.php" class="btn">
            <i class="fas fa-clipboard-list"></i> Visualizza i tuoi ordini
        </a>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-home"></i> Torna alla home
        </a>
    <?php else: ?>
        <a href="checkout.php?evento_id=<?php echo $evento_id; ?>&nome_evento=<?php echo urlencode($nome_evento); ?>&num_biglietti=<?php echo $num_biglietti; ?>&totale=<?php echo $totale; ?>&data_evento=<?php echo urlencode($data_evento); ?>&luogo=<?php echo urlencode($luogo); ?>" class="btn">
            <i class="fas fa-redo"></i> Riprova il pagamento
        </a>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-home"></i> Torna alla home
        </a>
        <a href="contatti.php" class="btn btn-warning">
            <i class="fas fa-headset"></i> Contatta assistenza
        </a>
    <?php endif; ?>
</div>
</div>
<?php require_once '../../altri_file/componenti/footer.php'; ?>
