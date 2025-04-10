<?php
// Funzione per determinare il percorso del CSS
$path_Style = function () {
    // Ottieni il nome del file corrente
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Verifica se la pagina è inserimento.php
    if ($currentPage === "registrazione.php") {
        return '../../altri_file/registra.css'; // Percorso CSS per la home
    } else {
        return "../altre_pagine/styleForm.css"; // Percorso CSS per altre pagine
    }
};

// Funzione per determinare il titolo della pagina
$titolo = function () {
    $currentPage = basename($_SERVER['PHP_SELF']);
    switch ($currentPage) {

        case "index.php":
            return 'HOME';
        case "registrazione.php":
            return 'REGISTRAZIONE';
        case "elimina.php":
            return 'Pagina_Cancella';
        case "visualizza.php":
            return 'Pagina_Visualizza';
        case "modifica.php":
            return 'Pagina_Modifica';
        default:
            return 'pagina non trovata';
    }
};
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Link per il CSS dinamico -->
    <link rel="stylesheet" href="<?= $path_Style() ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Titolo dinamico -->
    <title><?= $titolo() ?></title> <!-- Corretto: chiamato la funzione con le parentesi -->
</head>