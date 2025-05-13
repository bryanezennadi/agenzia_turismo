<?php
// Inizializza la sessione per gestire il carrello
session_start();

// Connessione al database
require_once '../../altri_file/componenti/connection.php';

// Inizializza il carrello se non esiste
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Gestione delle azioni sul carrello
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            if (isset($_GET['id'])) {
                $evento_id = $_GET['id'];
                $luogo = isset($_GET['luogo']) ? $_GET['luogo'] : 'Luogo non specificato';

                // Debug - per verificare che l'ID venga ricevuto correttamente
                // echo "ID ricevuto: " . $evento_id; exit;

                // Verifica se l'evento è già nel carrello
                if (isset($_SESSION['cart'][$evento_id])) {
                    $_SESSION['cart'][$evento_id]['quantity']++;
                    header('Location: carrello.php');
                    exit;
                }

                // Recupera i dati dell'evento dal database
                $sql = "
                    SELECT 
                        eventi.id AS id_evento,
                        visite.titolo AS titolo_visita,
                        eventi.prezzo,
                        eventi.ora_inizio
                    FROM eventi
                    JOIN visite ON eventi.id_visita = visite.id
                    WHERE eventi.id = ?
                ";

                try {
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$evento_id]);

                    if ($stmt->rowCount() > 0) {
                        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Utilizziamo il luogo passato nell'URL
                        $luogo = isset($_GET['luogo']) ? urldecode($_GET['luogo']) : 'Luogo non specificato';

                        // Debug - per verificare i dati recuperati dal database
                        // echo "<pre>"; print_r($evento); exit;

                        // Crea il percorso dell'immagine basato sul titolo della visita
                        $img_filename = strtolower(str_replace(' ', '-', $evento['titolo_visita'])) . '.jfif';
                        $img_path = 'img/visite/' . $img_filename;

                        // Controllo se l'immagine esiste, altrimenti usa un'immagine predefinita
                        if (!file_exists($img_path)) {
                            $img_path = 'img/default-event.jpg';
                        }

                        // Aggiungi l'evento al carrello
                        $_SESSION['cart'][$evento_id] = [
                            'id' => $evento_id,
                            'name' => $evento['titolo_visita'],
                            'price' => $evento['prezzo'],
                            'image' => $img_path,
                            'quantity' => 1,
                            'ora_inizio' => $evento['ora_inizio'],
                            'luogo' => $luogo
                        ];

                        // Debug - per verificare che l'elemento sia stato aggiunto al carrello
                        // echo "<pre>"; print_r($_SESSION['cart']); exit;
                    } else {
                        // Debug - se non viene trovato alcun evento
                        // echo "Nessun evento trovato con ID: " . $evento_id; exit;
                    }
                } catch (PDOException $e) {
                    // Gestione errore
                    error_log("Errore nel carrello: " . $e->getMessage());
                    echo "Si è verificato un errore durante l'aggiunta al carrello: " . $e->getMessage();
                    exit;
                }
            }
            header('Location: carrello.php');
            exit;

        case 'remove':
            if (isset($_GET['id'])) {
                $evento_id = $_GET['id'];
                unset($_SESSION['cart'][$evento_id]);
            }
            header('Location: carrello.php');
            exit;

        case 'update':
            if (isset($_GET['id']) && isset($_GET['quantity'])) {
                $evento_id = $_GET['id'];
                $quantity = (int)$_GET['quantity'];

                if ($quantity > 0) {
                    $_SESSION['cart'][$evento_id]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$evento_id]);
                }
            }
            header('Location: carrello.php');
            exit;

        case 'clear':
            $_SESSION['cart'] = [];
            header('Location: carrello.php');
            exit;
    }
}

// Calcola il totale del carrello
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}
require_once '../../altri_file/componenti/header2.php';

?>
    <!-- Container principale -->
    <div class="container">
        <div class="cart-header">
            <h1><i class="fas fa-shopping-cart"></i> Il tuo carrello</h1>
        </div>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="cart-empty">
                <i class="fas fa-shopping-basket"></i>
                <h2>Il tuo carrello è vuoto</h2>
                <p>Aggiungi prodotti al carrello per completare l'acquisto.</p>
                <a href="catalogo.php" class="btn-primary">Continua lo shopping</a>
            </div>
        <?php else: ?>
            <!-- Tabella prodotti carrello -->
            <div class="cart-content">
                <div class="cart-table-container">
                    <table class="cart-table">
                        <thead>
                        <tr>
                            <th>Prodotto</th>
                            <th>Prezzo</th>
                            <th>Quantità</th>
                            <th>Totale</th>
                            <th>Azioni</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td class="product-info">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div>
                                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                        <p class="product-id">ID: <?php echo htmlspecialchars($item['id']); ?></p>
                                        <?php if (isset($item['ora_inizio'])): ?>
                                            <p class="product-datetime">
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php echo date("d/m/Y H:i", strtotime($item['ora_inizio'])); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (isset($item['luogo'])): ?>
                                            <p class="product-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?php echo htmlspecialchars($item['luogo']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="price">€<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                                <td class="quantity">
                                    <div class="quantity-controls">
                                        <button class="quantity-btn minus" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" value="<?php echo $item['quantity']; ?>" min="1"
                                               onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                        <button class="quantity-btn plus" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="item-total">€<?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                                <td class="actions">
                                    <a href="carrello.php?action=remove&id=<?php echo $item['id']; ?>" class="btn-remove" title="Rimuovi">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-actions">
                    <a href="index.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Continua lo shopping
                    </a>
                    <a href="carrello.php?action=clear" class="btn-danger">
                        <i class="fas fa-trash-alt"></i> Svuota carrello
                    </a>
                </div>

                <!-- Riepilogo carrello -->
                <div class="cart-summary">
                    <h2>Riepilogo ordine</h2>
                    <div class="summary-row">
                        <span>Subtotale</span>
                        <span>€<?php echo number_format(calculateTotal(), 2, ',', '.'); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Spedizione</span>
                        <span>€<?php echo number_format(calculateTotal() > 50 ? 0 : 5.99, 2, ',', '.'); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Totale</span>
                        <span>€<?php echo number_format(calculateTotal() + (calculateTotal() > 50 ? 0 : 5.99), 2, ',', '.'); ?></span>
                    </div>
                    <div class="promo-code">
                        <input type="text" placeholder="Codice promozionale">
                        <button class="btn-apply">Applica</button>
                    </div>

                    <?php
                    // Se c'è un solo elemento nel carrello, passiamo tutti i parametri direttamente
                    if (count($_SESSION['cart']) == 1) {
                        $item = reset($_SESSION['cart']); // Prende il primo (e unico) elemento
                        $evento_id = $item['id'];
                        $nome_evento = urlencode($item['name']);
                        $data_evento = isset($item['ora_inizio']) ? urlencode(date("d/m/Y", strtotime($item['ora_inizio']))) : '';
                        $luogo = isset($item['luogo']) ? urlencode($item['luogo']) : '';
                        $prezzo_biglietto = $item['price'];
                        $num_biglietti = $item['quantity'];
                        $totale = calculateTotal() + (calculateTotal() > 50 ? 0 : 5.99);
                        ?>
                        <a href="../secondarie/pagamento.php?evento_id=<?php echo $evento_id; ?>&nome_evento=<?php echo $nome_evento; ?>&data_evento=<?php echo $data_evento; ?>&luogo=<?php echo $luogo; ?>&prezzo_biglietto=<?php echo $prezzo_biglietto; ?>&num_biglietti=<?php echo $num_biglietti; ?>&totale=<?php echo $totale; ?>" class="btn-checkout">
                            <i class="fas fa-credit-card"></i> Procedi al pagamento
                        </a>
                        <?php
                        // Se ci sono più elementi nel carrello, consideriamo il totale del carrello
                    } else {
                        // Prendiamo il primo elemento come riferimento per evento_id
                        $first_item = reset($_SESSION['cart']);
                        $evento_id = $first_item['id'];
                        // Per il nome dell'evento, mettiamo "Acquisto multiplo"
                        $nome_evento = urlencode("Acquisto multiplo");
                        // Data attuale
                        $data_evento = urlencode(date("d/m/Y"));
                        // Luogo non specificato
                        $luogo = urlencode("Vari luoghi");
                        // Prezzo biglietto medio
                        $prezzo_biglietto = number_format(calculateTotal() / array_sum(array_column($_SESSION['cart'], 'quantity')), 2);
                        // Numero totale di biglietti
                        $num_biglietti = array_sum(array_column($_SESSION['cart'], 'quantity'));
                        // Totale carrello
                        $totale = calculateTotal() + (calculateTotal() > 50 ? 0 : 5.99);
                        ?>
                        <a href="../secondarie/pagamento.php?evento_id=<?php echo $evento_id; ?>&nome_evento=<?php echo $nome_evento; ?>&data_evento=<?php echo $data_evento; ?>&luogo=<?php echo $luogo; ?>&prezzo_biglietto=<?php echo $prezzo_biglietto; ?>&num_biglietti=<?php echo $num_biglietti; ?>&totale=<?php echo $totale; ?>" class="btn-checkout">
                            <i class="fas fa-credit-card"></i> Procedi al pagamento
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Shop Online. Tutti i diritti riservati.</p>
    </footer>

    <script>
        // Gestione dropdown utente
        document.getElementById('userDropdown').addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('show');
        });

        // Chiudi dropdown quando si clicca fuori
        window.addEventListener('click', function(event) {
            if (!event.target.closest('.user-dropdown')) {
                document.getElementById('userMenu').classList.remove('show');
            }
        });

        // Gestione toggle navbar mobile
        document.getElementById('navbarToggle').addEventListener('click', function() {
            document.getElementById('navbarCollapse').classList.toggle('show');
        });

        // Funzione per aggiornare quantità
        function updateQuantity(id, quantity) {
            window.location.href = `carrello.php?action=update&id=${id}&quantity=${quantity}`;
        }
    </script>
<?php require_once '../../altri_file/componenti/footer.php'; ?>