<?php
require_once '../../altri_file/componenti/connection.php'; // qui deve esserci una connessione PDO
require_once '../../altri_file/componenti/header2.php';

try {
    // Query JOIN
    $sql = "
        SELECT 
            eventi.id AS id_evento,
            visite.titolo AS titolo_visita,
            visite.luogo,
            visite.durata_media,
            eventi.prezzo,
            eventi.minimo_partecipanti,
            eventi.massimo_partecipanti,
            eventi.ora_inizio
        FROM eventi
        JOIN visite ON eventi.id_visita = visite.id
        ORDER BY eventi.ora_inizio
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $eventi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generazione card
    if ($eventi) {
        echo '<div class="card-container" style="display: flex; flex-wrap: wrap; gap: 20px;">';

        foreach ($eventi as $row) {
            // Converti titolo in formato filename per immagine
            $img_filename = strtolower(str_replace(' ', '-', $row['titolo_visita'])) . '.jfif';
            $img_path = 'img/visite/'.$img_filename;

            echo '<div class="card" style="width: 300px; border: 1px solid #ccc; border-radius: 10px; overflow: hidden; box-shadow: 2px 2px 10px rgba(0,0,0,0.1);">';
            echo '  <img src="'.$img_path.'" alt="' . htmlspecialchars($row['titolo_visita']) . '" style="width: 100%; height: 180px; object-fit: cover;">';
            echo '  <div class="card-body" style="padding: 15px;">';
            echo '    <h3>' . htmlspecialchars($row['titolo_visita']) . '</h3>';
            echo '    <p><strong>Luogo:</strong> ' . htmlspecialchars($row['luogo']) . '</p>';
            echo '    <p><strong>Durata:</strong> ' . $row['durata_media'] . '</p>';
            echo '    <p><strong>Prezzo:</strong> €' . number_format($row['prezzo'], 2, ',', '.') . '</p>';
            echo '    <p><strong>Partecipanti:</strong> da ' . $row['minimo_partecipanti'] . ' a ' . $row['massimo_partecipanti'] . '</p>';
            echo '    <p><strong>Inizio:</strong> ' . date("d/m/Y H:i", strtotime($row['ora_inizio'])) . '</p>';
            echo '  </div>';
            echo '<a href="dettagli.php?id=' . $row['id_evento'] . '" class="card-button">Dettagli</a>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo 'Nessun evento disponibile.';
    }

} catch (PDOException $e) {
    echo "Errore nella query: " . $e->getMessage();
}

require_once '../../altri_file/componenti/footer.php';
?>
