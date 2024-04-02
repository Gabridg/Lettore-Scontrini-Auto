<?php

function starter(){

    // Verifica se è stato fatto clic sul bottone
    if(isset($_POST['action'])&& $_POST['action'] == 'starter') {
        var_dump($_POST);

        $month = $_POST['month'];
        
        // Percorso delle cartelle da creare
        $percorsoCartella1 = $month;
        $percorsoCartella2 = 'eccedenze-mese-'.$month;

        // Crea le cartelle se non esistono già
        if (!file_exists($percorsoCartella1)) {
            mkdir('storage/'.$percorsoCartella1, 0777, true); // Imposta i permessi a 0777 per consentire l'accesso
        }
        if (!file_exists($percorsoCartella2)) {
            mkdir('storage/'.$percorsoCartella2, 0777, true);
        } 
        header('Location: index.html?starterAlert=true');
        exit();
    } else{
        echo 'Errore fratelo';
        var_dump($_POST);
    }
}

function moveImageToCorrectPath(){
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se sono state caricate immagini
        if (isset($_FILES["scontrini"])) {

            var_dump($_FILES['scontrini']);
            // Percorso della cartella dove salvare le immagini
            $percorso_cartella = "scontrini/";
    
            // Ciclo attraverso le immagini caricate
            foreach ($_FILES["scontrini"]["tmp_name"] as $key => $tmp_name) {
                // Genera un nome univoco per l'immagine temporanea
                $nome_immagine_temporanea = uniqid() . "_" . $_FILES["scontrini"]["name"][$key];
                $percorso_immagine_temporanea = $_FILES["scontrini"]["tmp_name"][$key];
    
                // Costruisci il percorso completo della nuova immagine
                $percorso_nuova_immagine = $percorso_cartella . $nome_immagine_temporanea;
    
                // Pre-elaborazione dell'immagine per migliorare la qualità
                preelaboraImmagine($percorso_immagine_temporanea);
    
                // Sposta l'immagine temporanea nella cartella desiderata
                if (move_uploaded_file($percorso_immagine_temporanea, $percorso_nuova_immagine)) {
                   
                } else {
                    echo "Si è verificato un errore durante il salvataggio dell'immagine '$nome_immagine_temporanea'.<br>";
                }
            }
            header('Location: index.html?moveImageAlert=true');
            exit();
        } else {
            echo "Nessuna immagine è stata caricata.<br>";
        }
    } else {
        echo "Il form non è stato inviato correttamente.<br>";
    }
}

function preelaboraImmagine($percorso_immagine) {
   // Carica l'immagine utilizzando GD
   $immagine = imagecreatefrompng($percorso_immagine);
    
   // Miglioramento del contrasto
   contrastEnhancement($immagine);

   // Riduzione del rumore
   noiseReduction($immagine);

   // Binarizzazione
   binarization($immagine);
   
   // Sovrascrive l'immagine originale con quella pre-elaborata
   imagejpeg($immagine, $percorso_immagine);
   
   // Libera la memoria utilizzata
   imagedestroy($immagine);
}

// Funzione per il miglioramento del contrasto
function contrastEnhancement($image) {
   imagefilter($image, IMG_FILTER_CONTRAST, -30);
}

// Funzione per la riduzione del rumore
function noiseReduction($image) {
   imagefilter($image, IMG_FILTER_SMOOTH, 5);
}

// Funzione per la binarizzazione
function binarization($image) {
   imagefilter($image, IMG_FILTER_CONTRAST, -100);
   imagefilter($image, IMG_FILTER_BRIGHTNESS, -50);
}

function startProgram(){
    require 'vendor/autoload.php';

    // Percorso dell'immagine da elaborare
    $percorso_immagine = 'scontrini/';

    $files = glob($percorso_immagine . '*.{jpg,png,gif}', GLOB_BRACE);

    // Ciclo attraverso ogni immagine e applica l'OCR
    foreach ($files as $file) {
        // Creare un'istanza di TesseractOCR con il percorso dell'immagine corrente
        $ocr = new thiagoalessio\TesseractOCR\TesseractOCR($file);

        // Eseguire l'OCR sull'immagine corrente
        $testo_estratto = $ocr->run();

        // Visualizzare il testo estratto
        echo $testo_estratto;
    }
}