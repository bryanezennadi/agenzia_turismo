<?php
require_once '../../altri_file/componenti/connection.php';
require_once '../../altri_file/componenti/header2.php';

// Recupero parametri dall'URL
$totale = isset($_GET['totale']) ? floatval($_GET['totale']) : 0;
$evento_id = isset($_GET['evento_id']) ? intval($_GET['evento_id']) : 0;
$num_biglietti = isset($_GET['num_biglietti']) ? intval($_GET['num_biglietti']) : 1;
$nome_evento = isset($_GET['nome_evento']) ? htmlspecialchars($_GET['nome_evento']) : 'Evento';
$data_evento = isset($_GET['data_evento']) ? htmlspecialchars($_GET['data_evento']) : 'Data non specificata';
$luogo = isset($_GET['luogo']) ? htmlspecialchars($_GET['luogo']) : 'Luogo non specificato';
$prezzo_biglietto = isset($_GET['prezzo_biglietto']) ? floatval($_GET['prezzo_biglietto']) : 0;

// Calcolo IVA (22%)
$subtotale = $prezzo_biglietto * $num_biglietti;
$iva = $subtotale * 0.22;
$spese_servizio = ($subtotale > 50) ? 0 : 5.99;
$totale_calcolato = $subtotale + $iva + $spese_servizio;

// Se totale non è passato, usa quello calcolato
if ($totale == 0) {
    $totale = $totale_calcolato;
}

// Salva i dati nella sessione
$_SESSION['evento_id'] = $evento_id;
$_SESSION['nome_evento'] = $nome_evento;
$_SESSION['num_biglietti'] = $num_biglietti;
$_SESSION['totale'] = $totale;
$_SESSION['data_evento'] = $data_evento;
$_SESSION['luogo'] = $luogo;
?>
    <!-- Contenitore Principale -->
    <div class="pagamento-container">
        <!-- Header Pagina -->
        <div class="pagamento-header">
            <h2>Completa il tuo acquisto</h2>
            <a href="../principali/dettagli.php?id=<?php echo $evento_id; ?>" class="pagamento-torna-link">
                <i class="fas fa-arrow-left"></i> Torna ai dettagli dell'evento
            </a>
        </div>

        <!-- Layout con due colonne -->
        <div class="pagamento-layout">
            <!-- Colonna Sinistra: Form di Pagamento -->
            <div class="pagamento-sinistra">
                <div class="pagamento-card">
                    <h3 class="pagamento-sezione-titolo">
                        <i class="fas fa-user"></i> Informazioni Personali
                    </h3>

                    <form id="pagamentoForm" method="POST" action="conferma-pagamento.php">
                        <div class="form-riga">
                            <div class="form-gruppo">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-controllo" placeholder="Inserisci il tuo nome" required>
                            </div>
                            <div class="form-gruppo">
                                <label for="cognome">Cognome</label>
                                <input type="text" id="cognome" name="cognome" class="form-controllo" placeholder="Inserisci il tuo cognome" required>
                            </div>
                        </div>

                        <div class="form-gruppo">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-controllo" placeholder="La tua email" required>
                        </div>

                        <div class="form-gruppo">
                            <label for="telefono">Telefono</label>
                            <input type="tel" id="telefono" name="telefono" class="form-controllo" placeholder="Numero di telefono" required>
                        </div>

                        <h3 class="pagamento-sezione-titolo" style="margin-top: 30px;">
                            <i class="fas fa-credit-card"></i> Metodo di Pagamento
                        </h3>

                        <div class="metodi-pagamento">
                            <div class="metodo-pagamento-opzione attivo" data-metodo="carta">
                                <i class="fas fa-credit-card"></i>
                                <div class="metodo-pagamento-nome">Carta di Credito</div>
                            </div>
                            <div class="metodo-pagamento-opzione" data-metodo="paypal">
                                <i class="fab fa-paypal"></i>
                                <div class="metodo-pagamento-nome">PayPal</div>
                            </div>
                            <div class="metodo-pagamento-opzione" data-metodo="bonifico">
                                <i class="fas fa-university"></i>
                                <div class="metodo-pagamento-nome">Bonifico</div>
                            </div>
                        </div>

                        <input type="hidden" name="metodo_pagamento" id="metodo_pagamento" value="carta">

                        <div class="carta-dettagli">
                            <div class="form-gruppo carta-numero-gruppo">
                                <label for="numero-carta">Numero Carta</label>
                                <input type="text" id="numero-carta" name="numero_carta" class="form-controllo" placeholder="1234 5678 9012 3456" required>
                                <div class="carta-icone">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                    <i class="fab fa-cc-amex"></i>
                                </div>
                            </div>

                            <div class="form-riga">
                                <div class="form-gruppo">
                                    <label for="scadenza">Data di Scadenza</label>
                                    <input type="text" id="scadenza" name="scadenza" class="form-controllo" placeholder="MM/AA" required>
                                </div>
                                <div class="form-gruppo">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" class="form-controllo" placeholder="123" required>
                                </div>
                            </div>

                            <div class="form-gruppo">
                                <label for="intestatario">Intestatario Carta</label>
                                <input type="text" id="intestatario" name="intestatario" class="form-controllo" placeholder="Nome cognome come sulla carta" required>
                            </div>
                        </div>

                        <div id="paypal-dettagli" class="metodo-dettagli" style="display: none;">
                            <div class="form-gruppo">
                                <label for="email-paypal">Email PayPal</label>
                                <input type="email" id="email-paypal" name="email_paypal" class="form-controllo" placeholder="Il tuo indirizzo email PayPal">
                            </div>
                            <div class="paypal-info">
                                <i class="fas fa-info-circle"></i>
                                <p>Verrai reindirizzato al sito di PayPal per completare il pagamento sicuro</p>
                            </div>
                        </div>

                        <div id="bonifico-dettagli" class="metodo-dettagli" style="display: none;">
                            <div class="bonifico-info">
                                <div class="form-gruppo">
                                    <label class="bonifico-label">Intestatario Conto</label>
                                    <div class="bonifico-value">Eventi Culturali Srl</div>
                                </div>
                                <div class="form-gruppo">
                                    <label class="bonifico-label">IBAN</label>
                                    <div class="bonifico-value">IT60X0542811101000000123456</div>
                                </div>
                                <div class="form-gruppo">
                                    <label class="bonifico-label">Causale</label>
                                    <div class="bonifico-value">Acquisto <?php echo $num_biglietti; ?> biglietti per <?php echo $nome_evento; ?></div>
                                </div>
                                <p class="bonifico-nota">
                                    <i class="fas fa-info-circle"></i>
                                    Importante: I biglietti saranno confermati solo dopo la verifica del pagamento
                                </p>
                            </div>
                        </div>

                        <div class="checkbox-gruppo">
                            <input type="checkbox" id="termini" name="termini" class="checkbox-input" required>
                            <label for="termini" class="checkbox-label">
                                Accetto i <a href="#">Termini e Condizioni</a> e la <a href="#">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="checkbox-gruppo">
                            <input type="checkbox" id="newsletter" name="newsletter" class="checkbox-input">
                            <label for="newsletter" class="checkbox-label">
                                Desidero ricevere aggiornamenti su eventi futuri e promozioni
                            </label>
                        </div>

                        <button type="submit" class="btn-paga">
                            <i class="fas fa-lock"></i> Completa Pagamento
                        </button>

                        <div class="sicurezza-badge">
                            <i class="fas fa-shield-alt"></i> I tuoi dati sono protetti con crittografia SSL
                        </div>
                    </form>
                </div>
            </div>

            <!-- Colonna Destra: Riepilogo Ordine -->
            <div class="pagamento-destra">
                <div class="pagamento-riepilogo">
                    <div class="riepilogo-header">
                        <i class="fas fa-shopping-cart"></i> Riepilogo Ordine
                    </div>
                    <div class="riepilogo-content">
                        <div class="riepilogo-evento">
                            <img src="https://via.placeholder.com/80" alt="<?php echo $nome_evento; ?>" class="riepilogo-img">
                            <div class="riepilogo-evento-info">
                                <h4><?php echo $nome_evento; ?></h4>
                                <p><i class="far fa-calendar-alt"></i> <?php echo $data_evento; ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> <?php echo $luogo; ?></p>
                            </div>
                        </div>

                        <div class="riepilogo-lista">
                            <div class="riepilogo-item">
                                <span>Prezzo Biglietto</span>
                                <span>€<?php echo number_format($prezzo_biglietto, 2); ?></span>
                            </div>
                            <div class="riepilogo-item">
                                <span>Numero Biglietti</span>
                                <span><?php echo $num_biglietti; ?></span>
                            </div>
                            <div class="riepilogo-item">
                                <span>Subtotale</span>
                                <span>€<?php echo number_format($subtotale, 2); ?></span>
                            </div>
                            <div class="riepilogo-item">
                                <span>IVA (22%)</span>
                                <span>€<?php echo number_format($iva, 2); ?></span>
                            </div>
                            <div class="riepilogo-item">
                                <span>Spese di servizio</span>
                                <span>€<?php echo number_format($spese_servizio, 2); ?></span>
                            </div>
                        </div>
                        <div class="riepilogo-totale">
                            <span>Totale</span>
                            <span>€<?php echo number_format($totale_calcolato, 2); ?></span>
                        </div>

                    </div>
                </div>

                <div class="pagamento-card">
                    <h3 class="pagamento-sezione-titolo">
                        <i class="fas fa-question-circle"></i> Hai bisogno di aiuto?
                    </h3>
                    <p style="color: var(--text-color); margin-bottom: 15px;">
                        Per qualsiasi domanda sul processo di pagamento o sull'evento, contattaci:
                    </p>
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-phone" style="color: var(--primary-color); margin-right: 10px;"></i>
                        <span>+39 0123 456789</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-envelope" style="color: var(--primary-color); margin-right: 10px;"></i>
                        <span>supporto@eventiculturali.it</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script>

        // Gestione selezione metodo di pagamento
        const metodiPagamento = document.querySelectorAll('.metodo-pagamento-opzione');
        const metodoPagamentoInput = document.getElementById('metodo_pagamento');
        const cartaDettagli = document.querySelector('.carta-dettagli');
        const paypalDettagli = document.getElementById('paypal-dettagli');
        const bonificoDettagli = document.getElementById('bonifico-dettagli');
        const numeroCartaInput = document.getElementById('numero-carta');
        const scadenzaInput = document.getElementById('scadenza');
        const cvvInput = document.getElementById('cvv');
        const intestatarioInput = document.getElementById('intestatario');

        // Nascondi tutti i dettagli e mostra quelli del metodo attivo
        function mostraDettagliMetodo(metodo) {
            // Nascondi tutti
            cartaDettagli.style.display = 'none';
            paypalDettagli.style.display = 'none';
            bonificoDettagli.style.display = 'none';

            // Rimuovi attributo required dai campi carta
            numeroCartaInput.removeAttribute('required');
            scadenzaInput.removeAttribute('required');
            cvvInput.removeAttribute('required');
            intestatarioInput.removeAttribute('required');

            // Mostra quello selezionato
            if (metodo === 'carta') {
                cartaDettagli.style.display = 'block';
                numeroCartaInput.setAttribute('required', '');
                scadenzaInput.setAttribute('required', '');
                cvvInput.setAttribute('required', '');
                intestatarioInput.setAttribute('required', '');
            } else if (metodo === 'paypal') {
                paypalDettagli.style.display = 'block';
            } else if (metodo === 'bonifico') {
                bonificoDettagli.style.display = 'block';
            }

            // Aggiorna input nascosto
            metodoPagamentoInput.value = metodo;
        }

        metodiPagamento.forEach(metodo => {
            metodo.addEventListener('click', function() {
                // Rimuove la classe attivo da tutti i metodi
                metodiPagamento.forEach(m => m.classList.remove('attivo'));
                // Aggiunge la classe attivo al metodo cliccato
                this.classList.add('attivo');

                // Mostra i dettagli del metodo selezionato
                const metodoSelezionato = this.getAttribute('data-metodo');
                mostraDettagliMetodo(metodoSelezionato);
            });
        });

        // Formattazione numero carta
        if (numeroCartaInput) {
            numeroCartaInput.addEventListener('input', function(e) {
                // Rimuovi spazi e caratteri non numerici
                let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');

                // Limita a 16 cifre
                if (value.length > 16) {
                    value = value.substr(0, 16);
                }

                // Formatta con spazi ogni 4 cifre
                const parts = [];
                for (let i = 0; i < value.length; i += 4) {
                    parts.push(value.substr(i, 4));
                }

                this.value = parts.join(' ');
            });
        }

        // Formattazione data di scadenza
        if (scadenzaInput) {
            scadenzaInput.addEventListener('input', function(e) {
                // Rimuovi caratteri non numerici
                let value = this.value.replace(/[^0-9]/gi, '');

                // Limita a 4 cifre
                if (value.length > 4) {
                    value = value.substr(0, 4);
                }

                // Formatta con / dopo i primi 2 caratteri
                if (value.length > 2) {
                    value = value.substr(0, 2) + '/' + value.substr(2);
                }

                this.value = value;

                // Validazione mese (01-12)
                if (value.length >= 2) {
                    const mese = parseInt(value.substr(0, 2));
                    if (mese < 1 || mese > 12) {
                        this.setCustomValidity('Il mese deve essere tra 01 e 12');
                    } else {
                        this.setCustomValidity('');
                    }
                }
            });
        }

        // Validazione CVV (3-4 cifre)
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                // Rimuovi caratteri non numerici
                let value = this.value.replace(/[^0-9]/gi, '');

                // Limita a 4 cifre
                if (value.length > 4) {
                    value = value.substr(0, 4);
                }

                this.value = value;

                // Validazione lunghezza
                if (value.length < 3) {
                    this.setCustomValidity('Il CVV deve avere almeno 3 cifre');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        // Gestione invio form
        document.getElementById('pagamentoForm').addEventListener('submit', function(e) {
            const metodo = metodoPagamentoInput.value;

            // Se PayPal, reindirizza a PayPal
            if (metodo === 'paypal') {
                alert('Stai per essere reindirizzato a PayPal per completare il pagamento');
                // In un'implementazione reale qui ci sarebbe il redirect a PayPal
            }

            // Per demo semplice mostriamo solo un alert
            // In un'implementazione reale qui andrebbe il codice per processare il pagamento
            if (metodo === 'carta') {
                e.preventDefault(); // Solo per demo
                alert('Pagamento con carta completato con successo! Riceverai una email di conferma.');
                window.location.href = 'conferma-pagamento.php?success=true';
            }

            if (metodo === 'bonifico') {
                e.preventDefault(); // Solo per demo
                alert('Istruzioni per il bonifico inviate alla tua email. Ti preghiamo di completare il pagamento entro 3 giorni.');
                window.location.href = 'conferma-pagamento.php?pending=true';
            }
        });
    </script>

<?php require '../../altri_file/componenti/footer.php';?>