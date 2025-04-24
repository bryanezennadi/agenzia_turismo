<?php
require_once '../../altri_file/componenti/header.php';
?>
<form action="../../altri_file/componenti/operazioni/loginOP.php" method="post">
<label for="email">Inserisci email:</label>
<input type="email" name="email" placeholder="aaa@aaa.com" id="email" required><br>

<label for="password">Inserisci password:</label>
<input type="password" name="password" id="password" required><br>
<input type="submit">
</form>
<?php require_once '../../altri_file/componenti/footer.php'?>
