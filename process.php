<?php
// Abilita la visualizzazione degli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includo la definizione della funzione starter
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Verifica l'azione richiesta e chiama la funzione corrispondente
    switch ($action) {
        case 'starter':
            starter();
            break;
        case 'moveImageToCorrectPath':
            moveImageToCorrectPath();
            break;
        case 'startProgram':
            startProgram();
            break;
        // Aggiungi altri casi per altre azioni, se necessario
        default:
            echo "Azione non supportata";
    }
} else {
    echo "Nessuna azione specificata";
}