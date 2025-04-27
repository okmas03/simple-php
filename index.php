<?php
require_once 'config.php';

function vytvoritDatabazi()
{
    global $conn;

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
        $conn->exec($sql);
    } catch (PDOException $e) {
        return "Chyba při vytváření tabulky: " . $e->getMessage();
    }

    $sql = "SELECT COUNT(*) FROM zakaznici";
    $stmt = $conn->query($sql);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        return "Databáze již obsahuje vzorová data.";
    }

    $sql = "
    INSERT INTO zakaznici (jmeno, prijmeni, ulice, mesto, psc, cp) 
    VALUES (:jmeno, :prijmeni, :ulice, :mesto, :psc, :cp)";

    $stmt = $conn->prepare($sql);

    $data = [
        ['Samuel', 'Kováč', 'Antonínová 4379', 'Zlín', '760 01', '40'],
        ['Radoslav', 'Labon', 'Cigánska', 'Vranov nad Topľou', '093 01', '12'],
        ['Eva', 'Kovářová', 'Hlavní 20', 'Ostrava', '70300', '789'],
        ['Jakub', 'Novák', 'Náměstí 5', 'Brno', '602 00', '123'],
        ['Anna', 'Horváthová', 'Kvetinová 10', 'Košice', '040 01', '456'],
        ['Marek', 'Bielik', 'Slovenská 15', 'Prešov', '080 01', '789'],
        ['Jana', 'Černá', 'Zelená 8', 'Trnava', '917 01', '321'],
        ['Petr', 'Novotný', 'Modrá 3', 'Nitra', '949 01', '654'],
        ['Lucie', 'Svobodová', 'Žlutá 12', 'Martin', '036 01', '987'],
        ['Tomáš', 'Dvořák', 'Oranžová 7', 'Poprad', '058 01', '159']
    ];

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

    return "Databáza je už vytvorená a naplnená vzorovými dátami.";
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

        <p>Vitajte v aplikácii na správu zákazníkov. Táto aplikácia umožňuje:</p>
        <ul>
            <li>Spravovať zoznam zákazníkov.</li>
            <li>Vyhľadávať zákazníkov podľa ID.</li>
            <li>Upraviť a pridať nových zákazníkov.</li>
        </ul>

        <h2>Ako aplikácia funguje:</h2>
        <p>1. Na hlavnej stránke môžete prechádzať zoznam zákazníkov.</p>
        <p>2. Na stránke "Vyhľadať zákazníka" môžete zadať ID zákazníka a upravit jeho údaje.</p>
        <p>3. Na tejto stránke môžete tiež vytvoriť databázu a naplniť ju vzorovými dátami.</p>

        <h2>Vytvorenie databáze:</h2>
        <form method="post" class="w3-container w3-card-4 w3-light-grey w3-padding">
            <button class="w3-button w3-blue w3-round" type="submit" name="vytvorit_databazi">Vytvorit DB so vzorovými dátami</button>
        </form>

        <?php if ($zprava): ?>
            <div class="w3-panel w3-green w3-round">
                <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>