<?php
require_once '../../altri_file/componenti/connection.php';
require_once '../../altri_file/componenti/header2.php';

// Aggiungiamo il collegamento al CSS
echo '<link rel="stylesheet" href="dettagli-evento.css">';
// Fontawesome per le icone (se non è già presente nell'header)


// Verifica se è stato passato un ID valido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $eventoId = $_GET['id'];

    // Query per ottenere i dettagli dell'evento e della visita collegata, inclusa la descrizione
    $sql = "
        SELECT 
            eventi.id AS id_evento,
            visite.titolo AS titolo_visita,
            visite.luogo,
            visite.durata_media,
            eventi.prezzo,
            eventi.minimo_partecipanti,
            eventi.massimo_partecipanti,
            eventi.ora_inizio,
            descrizione_visita.descrizione
        FROM eventi
        JOIN visite ON eventi.id_visita = visite.id
        LEFT JOIN descrizione_visita ON visite.id = descrizione_visita.id_visita
        WHERE eventi.id = :evento_id
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':evento_id', $eventoId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $evento = $stmt->fetch(PDO::FETCH_ASSOC);

            // Crea il percorso dell'immagine basato sul titolo della visita
            $img_filename = strtolower(str_replace(' ', '-', $evento['titolo_visita'])) . '.jfif';
            $img_path = 'img/visite/' . $img_filename;

            // Controlla i posti disponibili (possiamo simularlo)
            $posti_disponibili = $evento['massimo_partecipanti'] - rand(0, $evento['massimo_partecipanti'] - 1);
            $stato_badge = $posti_disponibili <= 5 ?
                '<span class="evento-badge badge-pochi-posti"><i class="fas fa-exclamation-circle"></i> Ultimi posti</span>' :
                '<span class="evento-badge badge-disponibile"><i class="fas fa-check-circle"></i> Disponibile</span>';

            ?>
            <div class="evento-dettagli-container">
                <div class="evento-header">
                    <h2>Dettagli Evento <?php echo $stato_badge; ?></h2>
                    <a href="catalogo.php" class="evento-torna-link"><i class="fas fa-arrow-left"></i> Torna agli eventi</a>
                </div>

                <div class="evento-card">
                    <div class="evento-img-container">
                        <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($evento['titolo_visita']); ?>" class="evento-img">
                    </div>

                    <div class="evento-content">
                        <h1 class="evento-titolo"><?php echo htmlspecialchars($evento['titolo_visita']); ?></h1>

                        <div class="evento-info-grid">
                            <div class="evento-info-item">
                                <span class="evento-info-label"><i class="fas fa-map-marker-alt"></i> Luogo</span>
                                <span class="evento-info-value"><?php echo htmlspecialchars($evento['luogo']); ?></span>
                            </div>

                            <div class="evento-info-item">
                                <span class="evento-info-label"><i class="fas fa-clock"></i> Durata</span>
                                <span class="evento-info-value"><?php echo $evento['durata_media']; ?></span>
                            </div>

                            <div class="evento-info-item evento-prezzo">
                                <span class="evento-info-label"><i class="fas fa-tag"></i> Prezzo</span>
                                <span class="evento-info-value">€<?php echo number_format($evento['prezzo'], 2, ',', '.'); ?></span>
                            </div>

                            <div class="evento-info-item">
                                <span class="evento-info-label"><i class="fas fa-users"></i> Partecipanti</span>
                                <span class="evento-info-value">Da <?php echo $evento['minimo_partecipanti']; ?> a <?php echo $evento['massimo_partecipanti']; ?> persone</span>
                            </div>

                            <div class="evento-info-item">
                                <span class="evento-info-label"><i class="fas fa-calendar-alt"></i> Data e ora</span>
                                <span class="evento-info-value"><?php echo date("d/m/Y H:i", strtotime($evento['ora_inizio'])); ?></span>
                            </div>

                            <div class="evento-info-item">
                                <span class="evento-info-label"><i class="fas fa-chair"></i> Posti disponibili</span>
                                <span class="evento-info-value"><?php echo $posti_disponibili; ?> posti</span>
                            </div>
                        </div>

                        <div class="evento-descrizione-container">
                            <h3 class="evento-descrizione-titolo"><i class="fas fa-info-circle"></i> Descrizione dell'evento</h3>
                            <div class="evento-descrizione-testo">
                                <?php echo !empty($evento['descrizione']) ? nl2br(htmlspecialchars($evento['descrizione'])) : 'Nessuna descrizione disponibile per questo evento.'; ?>
                            </div>
                        </div>

                        <a href="carrello.php?action=add&id=<?php echo $eventoId; ?>&luogo=<?= urldecode($evento['luogo']); ?>" class="btn-carrello">
                            <i class="fas fa-shopping-cart"></i> Aggiungi al carrello
                        </a>

                        <div class="evento-azioni">
                            <button class="btn-azione">
                                <i class="fas fa-share-alt"></i> Condividi
                            </button>
                            <button class="btn-azione">
                                <i class="far fa-heart"></i> Salva nei preferiti
                            </button>
                            <button class="btn-azione">
                                <i class="fas fa-question-circle"></i> Contatta per info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="evento-dettagli-container">
                    <div class="evento-card">
                        <div class="evento-content">
                            <p>Evento non trovato.</p>
                            <a href="catalogo.php" class="evento-torna-link"><i class="fas fa-arrow-left"></i> Torna agli eventi</a>
                        </div>
                    </div>
                  </div>';
        }
    } catch (PDOException $e) {
        echo '<div class="evento-dettagli-container">
                <div class="evento-card">
                    <div class="evento-content">
                        <p>Errore nella query: ' . $e->getMessage() . '</p>
                        <a href="catalogo.php" class="evento-torna-link"><i class="fas fa-arrow-left"></i> Torna agli eventi</a>
                    </div>
                </div>
              </div>';
    }
} else {
    echo '<div class="evento-dettagli-container">
            <div class="evento-card">
                <div class="evento-content">
                    <p>ID evento non valido.</p>
                    <a href="catalogo.php" class="evento-torna-link"><i class="fas fa-arrow-left"></i> Torna agli eventi</a>
                </div>
            </div>
          </div>';
}

require_once '../../altri_file/componenti/footer.php';
?>