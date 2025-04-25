<?php
require_once 'config.php';

// Skontrolujeme, či sú dáta poslané
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načítame dáta
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $ulice = $_POST['ulice'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];

    // Pripravíme SQL dotaz
    $stmt = $conn->prepare("INSERT INTO zakaznici (jmeno, prijmeni, ulice, mesto, psc, email, telefon) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $jmeno, $prijmeni, $ulice, $mesto, $psc, $email, $telefon);

    // Spustíme dotaz a ošetríme výsledok
    if ($stmt->execute()) {
        echo "Zákazník byl úspěšně přidán.";
    } else {
        echo "Chyba při přidávání zákazníka: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Neplatný požadavek.";
}
