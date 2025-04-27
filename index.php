<?php
require_once 'config.php';

// Funkce pro vytvoření databáze a vložení vzorových dat
function vytvoritDatabazi() {
    global $conn;
    
    // Vytvoření tabulky pro zákazníky
    $sql = "
    CREATE TABLE IF NOT EXISTS zakaznici (
        id INT AUTO_INCREMENT PRIMARY KEY,
        jmeno VARCHAR(100),
        prijmeni VARCHAR(100),
        ulice VARCHAR(255),
        mesto VARCHAR(100),
        psc VARCHAR(10),
        cp VARCHAR(10)
    )";
    
    try {
        $conn->exec($sql);  // Provést vytvoření tabulky
    } catch (PDOException $e) {
        return "Chyba při vytváření tabulky: " . $e->getMessage();
    }

    // Kontrola, zda již existují nějaká data v tabulce
    $sql = "SELECT COUNT(*) FROM zakaznici";
    $stmt = $conn->query($sql);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        return "Databáze již obsahuje vzorová data.";
    }

    // Vložení vzorových dat pomocí prepared statements
    $sql = "
    INSERT INTO zakaznici (jmeno, prijmeni, ulice, mesto, psc, cp) 
    VALUES (:jmeno, :prijmeni, :ulice, :mesto, :psc, :cp)";

    $stmt = $conn->prepare($sql);

    // Vzorky dat pro vložení
    $data = [
        ['Jan', 'Novák', 'Náměstí 1', 'Praha', '11000', '123'],
        ['Petr', 'Svoboda', 'Ulice 10', 'Brno', '60200', '456'],
        ['Eva', 'Kovářová', 'Hlavní 20', 'Ostrava', '70300', '789']
    ];

    // Procházíme data a provádíme vložení
    foreach ($data as $row) {
        try {
            $stmt->execute([
                ':jmeno' => $row[0],
                ':prijmeni' => $row[1],
                ':ulice' => $row[2],
                ':mesto' => $row[3],
                ':psc' => $row[4],
                ':cp' => $row[5]
            ]);
        } catch (PDOException $e) {
            return "Chyba při vkládání vzorových dat: " . $e->getMessage();
        }
    }

    return "Databáze a vzorová data byla úspěšně vytvořena.";
}

$zprava = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vytvorit_databazi'])) {
    $zprava = vytvoritDatabazi();
}
?>



<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Úvod</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="nav w3-margin-bottom">
        <a href="index.php">Úvod</a>
        <a href="zakaznici.php">Zákazníci</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
    </nav>

    <div class="w3-container w3-padding">
        <h1>Úvod</h1>

        <p>Vítejte v aplikaci pro správu zákazníků. Tato aplikace umožňuje:</p>
        <ul>
            <li>Spravovat seznam zákazníků.</li>
            <li>Vyhledávat zákazníky podle ID.</li>
            <li>Upravit a přidat nové zákazníky.</li>
        </ul>

        <h2>Jak aplikace funguje:</h2>
        <p>1. Na hlavní stránce můžete procházet seznam zákazníků.</p>
        <p>2. Na stránce "Vyhledat zákazníka" můžete zadat ID zákazníka a upravit jeho údaje.</p>
        <p>3. Na této stránce můžete také vytvořit databázi a naplnit ji vzorovými daty.</p>

        <h2>Vytvoření databáze:</h2>
        <form method="post" class="w3-container w3-card-4 w3-light-grey w3-padding">
            <button class="w3-button w3-blue w3-round" type="submit" name="vytvorit_databazi">Vytvořit databázi a vzorová data</button>
        </form>

        <?php if ($zprava): ?>
            <div class="w3-panel w3-green w3-round">
                <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>
