    <?php
    require_once "../connection.php";

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepariamo la query per cercare l'utente con l'email inserita
    $query = "SELECT id, password_hash, ruolo FROM login WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Password corretta: login riuscito
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['ruolo'] = $user['ruolo'];

        header("location: ../../../pagine_visualizzate/principali/dashboard.php");
    } else {
        // Credenziali errate
        echo "Email o password non corretti.";
    }
    ?>
