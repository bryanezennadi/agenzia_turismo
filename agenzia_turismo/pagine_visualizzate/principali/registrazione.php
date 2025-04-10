<?php
require '../../altri_file/componenti/header.php';
?>
<body>
    <link rel="stylesheet" href="../../altri_file/registra.css">
    <form action="../../altri_file/componenti/operazioni/registraOP.php" method="post" id="registrationForm">
        <label for="nome">Inserisci nome:</label>
        <input type="text" name="nome" id="nome" required><br>

        <label for="lingua_base">Scegli la tua lingua madre:</label><br>
        <input type="radio" name="lingua" id="italiano" value="italiano" required>
        <label for="italiano">Italiano</label><br>
        <input type="radio" name="lingua" id="inglese" value="inglese">
        <label for="inglese">Inglese</label><br>
        <input type="radio" name="lingua" id="francese" value="francese">
        <label for="francese">Francese</label><br>
        <input type="radio" name="lingua" id="spagnolo" value="spagnolo">
        <label for="spagnolo">Spagnolo</label><br>
        <input type="radio" name="lingua" id="portoghese" value="portoghese">
        <label for="portoghese">Portoghese</label><br>
        <input type="radio" name="lingua" id="tedesco" value="tedesco">
        <label for="tedesco">Tedesco</label><br>
        <input type="radio" name="lingua" id="arabo" value="arabo">
        <label for="arabo">Arabo</label><br>
        <input type="radio" name="lingua" id="giapponese" value="giapponese">
        <label for="giapponese">Giapponese</label><br>

        <label for="email">Inserisci email:</label>
        <input type="email" name="email" placeholder="aaa@aaa.com" id="email" required><br>

        <label for="password">Inserisci password:</label>
        <input type="password" name="password" id="password" required oninput="validatePassword()"><br>

        <label for="Confpassword">Conferma password:</label>
        <input type="password" name="Confpassword" id="Confpassword" required oninput="validatePassword()"><br>
        <span id="password-error" style="color: red; display: none;">Le password non corrispondono!</span>

        <label for="numeroTel">Inserisci recapito telefonico:</label>
        <input type="text" name="numero_TEL" id="numeroTel" maxlength="10"
               pattern="[0-9]{10}" required
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"><br>
        <input type="submit" value="Registrati" id="submitBtn">
    </form>
</body>
<?php
// Incorporiamo lo script direttamente nell'output PHP
echo '<script>
function validatePassword() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("Confpassword").value;
    var errorSpan = document.getElementById("password-error");
    var submitBtn = document.getElementById("submitBtn");
    
    if (password === "" || confirmPassword === "") {
        errorSpan.style.display = "none";
        submitBtn.disabled = false;
        return;
    }
    
    if (password !== confirmPassword) {
        errorSpan.style.display = "block";
        submitBtn.disabled = true;
    } else {
        errorSpan.style.display = "none";
        submitBtn.disabled = false;
    }
}

document.getElementById("registrationForm").addEventListener("submit", function(event) {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("Confpassword").value;
    
    if (password !== confirmPassword) {
        event.preventDefault();
        document.getElementById("password-error").style.display = "block";
        document.getElementById("submitBtn").disabled = true;
    }
});
</script>';
?>

<?php
require '../../altri_file/componenti/footer.php';
?>