<?php
require_once "../connection.php";

// Legge i dati dal form
$nome = $_POST['nome'] ?? '';
$nazionalita = $_POST['nazionalita'] ?? '';
$lingua = $_POST['lingua'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confPassword = $_POST['confPassword'] ?? '';
$numero_TEL = $_POST['numero_TEL'] ?? '';


// Crittografa la password
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $conn->beginTransaction();

    // 1. Inserisci nella tabella login
    $stmt1 = $conn->prepare("INSERT INTO login (email, ruolo, password_hash) VALUES (:email, 'visitatore', :password)");
    $stmt1->execute([
        ':email' => $email,
        ':password' => $hash
    ]);

    // 2. Recupera l'ID appena inserito
    $idLogin = $conn->lastInsertId();

    // 3. Inserisci nella tabella visitatori
    $stmt2 = $conn->prepare("INSERT INTO visitatori (id_credenziali, nome, lingua_base, recapito) 
                            VALUES (:id, :nome, :lingua, :recapito)");
    $stmt2->execute([
        ':id' => $idLogin,
        ':nome' => $nome,
        ':lingua' => $lingua,
        ':recapito' => $numero_TEL
    ]);

    $conn->commit();

    echo "Registrazione completata con successo!";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Errore durante la registrazione: " . $e->getMessage();
}
require_once 'inviaMail.php';
header("location: ../../../pagine_visualizzate/secondarie/post-registrazione.php");
?>
