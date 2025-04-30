<?php
require_once '../../altri_file/componenti/connection.php';
require_once '../../altri_file/componenti/header2.php';

// Verifica che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ottieni informazioni utente combinando login e visitatori (o solo login per admin)
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT l.id, l.email, l.ruolo, 
           COALESCE(v.nome, 'Admin') AS nome,
           v.lingua_base, v.recapito
    FROM login l
    LEFT JOIN visitatori v ON l.id = v.id_credenziali
    WHERE l.id = :id
");
$stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Gestione del cambio password
$passwordMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Ottieni hash password attuale
    $passwordStmt = $conn->prepare("SELECT password_hash FROM login WHERE id = :id");
    $passwordStmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $passwordStmt->execute();
    $passwordData = $passwordStmt->fetch(PDO::FETCH_ASSOC);

    // Verifica password attuale
    if ($passwordData && password_verify($currentPassword, $passwordData['password_hash'])) {
        if ($newPassword === $confirmPassword) {
            // Aggiorna la password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE login SET password_hash = :newHash WHERE id = :id");
            $updateStmt->bindValue(':newHash', $hashedPassword);
            $updateStmt->bindValue(':id', $user_id, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                $passwordMessage = '<div class="alert success">Password aggiornata con successo!</div>';
            } else {
                $passwordMessage = '<div class="alert error">Errore durante l\'aggiornamento della password.</div>';
            }
        } else {
            $passwordMessage = '<div class="alert error">Le nuove password non corrispondono.</div>';
        }
    } else {
        $passwordMessage = '<div class="alert error">Password attuale non corretta.</div>';
    }
}

// Crea la tabella prenotazioni se non esiste
$creaTabella = "
CREATE TABLE IF NOT EXISTS prenotazioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_evento INT NOT NULL,
    id_visitatore INT NOT NULL,
    data_prenotazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_pagamento DATETIME NULL,
    stato_pagamento ENUM('in attesa', 'completato', 'annullato') DEFAULT 'in attesa',
    FOREIGN KEY (id_evento) REFERENCES eventi(id),
    FOREIGN KEY (id_visitatore) REFERENCES login(id)
)";
$conn->exec($creaTabella); // con PDO si usa exec per query senza risultato

// Ottieni eventi prenotati (solo per visitatori)
$eventiPrenotati = [];
if ($user['ruolo'] === 'visitatore') {
    $stmtEventi = $conn->prepare("
        SELECT e.*, v.titolo AS titolo_visita, v.luogo, p.data_pagamento
        FROM eventi e 
        JOIN visite v ON e.id_visita = v.id
        JOIN prenotazioni p ON e.id = p.id_evento
        WHERE p.id_visitatore = :id_visitatore AND p.stato_pagamento = 'completato'
        ORDER BY e.ora_inizio DESC
    ");
    $stmtEventi->bindValue(':id_visitatore', $user_id, PDO::PARAM_INT);
    $stmtEventi->execute();
    $eventiPrenotati = $stmtEventi->fetchAll(PDO::FETCH_ASSOC);
}

?>
<div class="container">
    <div class="profile-header">
        <h1>Il tuo profilo</h1>
        <p class="welcome-message">
            Benvenuto, <?php echo htmlspecialchars($user['nome']); ?>
            <span class="user-type">(<?php echo ucfirst($user['ruolo']); ?>)</span>
        </p>
    </div>

    <div class="profile-content">
        <!-- Informazioni utente -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user"></i> Informazioni personali</h2>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Nome:</label>
                    <span><?php echo htmlspecialchars($user['nome']); ?></span>
                </div>
                <?php if ($user['ruolo'] === 'visitatore'): ?>
                    <div class="info-group">
                        <label>Lingua base:</label>
                        <span><?php echo htmlspecialchars($user['lingua_base']); ?></span>
                    </div>
                    <div class="info-group">
                        <label>Recapito:</label>
                        <span><?php echo htmlspecialchars($user['recapito']); ?></span>
                    </div>
                <?php endif; ?>
                <div class="info-group">
                    <label>Email:</label>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-group">
                    <label>Tipo account:</label>
                    <span><?php echo ucfirst(htmlspecialchars($user['ruolo'])); ?></span>
                </div>
            </div>
        </div>

        <!-- Cambio password -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-key"></i> Cambia password</h2>
            </div>
            <div class="card-body">
                <?php echo $passwordMessage; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="current_password">Password attuale:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nuova password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Conferma nuova password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Aggiorna password</button>
                </form>
            </div>
        </div>

        <?php if ($user['ruolo'] === 'visitatore' && !empty($eventiPrenotati)): ?>
            <!-- Eventi prenotati (solo per visitatori) -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-calendar-check"></i> Eventi prenotati</h2>
                </div>
                <div class="card-body">
                    <div class="eventi-list">
                        <?php foreach ($eventiPrenotati as $evento): ?>
                            <div class="evento-item">
                                <div class="evento-info">
                                    <h3><?php echo htmlspecialchars($evento['titolo']); ?></h3>
                                    <div class="evento-details">
                                        <span class="evento-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d/m/Y', strtotime($evento['data_inizio'])); ?>
                                            <?php if ($evento['data_fine']): ?>
                                                - <?php echo date('d/m/Y', strtotime($evento['data_fine'])); ?>
                                            <?php endif; ?>
                                        </span>
                                        <span class="evento-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($evento['luogo']); ?>
                                        </span>
                                    </div>
                                    <div class="pagamento-info">
                                        <span class="label">Pagamento effettuato il:</span>
                                        <span class="value"><?php echo date('d/m/Y', strtotime($evento['data_pagamento'])); ?></span>
                                    </div>
                                    <?php if (!empty($evento['note'])): ?>
                                        <div class="evento-note">
                                            <?php echo nl2br(htmlspecialchars($evento['note'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="evento-actions">
                                    <a href="dettaglio_evento.php?id=<?php echo $evento['id']; ?>" class="btn btn-sm">
                                        <i class="fas fa-eye"></i> Dettagli
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php elseif ($user['ruolo'] === 'visitatore'): ?>
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-calendar-check"></i> Eventi prenotati</h2>
                </div>
                <div class="card-body">
                    <p class="no-items">Non hai ancora prenotato nessun evento.</p>
                    <a href="catalogo.php" class="btn btn-primary">Scopri gli eventi disponibili</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../../altri_file/componenti/footer.php'; ?>