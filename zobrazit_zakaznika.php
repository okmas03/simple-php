<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    die("ID zákazníka nebylo zadáno.");
}

$id = (int) $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE id = ?");
    $stmt->execute([$id]);
    $zakaznik = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$zakaznik) {
        die("Zákazník nenalezen.");
    }
} catch (PDOException $e) {
    die("Chyba při načítání zákazníka: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Zákazník detail</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body class="w3-container w3-padding">

    <nav class="nav w3-margin-bottom">
        <a href="uvod.php">Úvod</a>
        <a href="index.php">Zákazníci</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
    </nav>

    <h1 class="w3-xlarge">Detail zákazníka</h1>

    <div class="w3-card w3-padding w3-light-grey w3-round-large">
        <p><strong>ID:</strong> <?= htmlspecialchars($zakaznik['id']) ?></p>
        <p><strong>Meno:</strong> <?= htmlspecialchars($zakaznik['jmeno']) ?></p>
        <p><strong>Priezvisko:</strong> <?= htmlspecialchars($zakaznik['prijmeni']) ?></p>
        <p><strong>Mesto:</strong> <?= htmlspecialchars($zakaznik['mesto']) ?></p>
        <p><strong>PSČ:</strong> <?= htmlspecialchars($zakaznik['psc']) ?></p>
        <p><strong>Ulica:</strong> <?= htmlspecialchars($zakaznik['ulice']) ?></p>
        <p><strong>Číslo popisné:</strong> <?= htmlspecialchars($zakaznik['cp']) ?></p>
    </div>

    <br>

    <a class="w3-button w3-blue w3-round" href="zakaznici.php">← Späť na zoznam zákazníkov</a>

</body>

</html>
