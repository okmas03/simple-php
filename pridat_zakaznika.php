<?php
require_once 'config.php';

$zprava = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $ulice = $_POST['ulice'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];

    try {
        $stmt = $conn->prepare("INSERT INTO zakaznici (jmeno, prijmeni, ulice, mesto, psc, email, telefon)
                                VALUES (:jmeno, :prijmeni, :ulice, :mesto, :psc, :email, :telefon)");
        $stmt->execute([
            ':jmeno' => $jmeno,
            ':prijmeni' => $prijmeni,
            ':ulice' => $ulice,
            ':mesto' => $mesto,
            ':psc' => $psc,
            ':email' => $email,
            ':telefon' => $telefon
        ]);
        $zprava = "Zákazník byl úspěšně přidán.";
    } catch (PDOException $e) {
        $zprava = "Chyba při přidávání: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Přidat zákazníka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body class="w3-container w3-padding">

    <nav class="nav">
        <a href="index.php">Zoznam zákazníkov</a>
        <a href="pridat_zakaznika.php">Pridat zákazníka</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
        <a href="upravit_zakaznika.php">Upraviť zákazníka</a>
    </nav>

    <h1 class="w3-xlarge">Pridať zákazníka</h1>

    <?php if ($zprava): ?>
        <div class="w3-panel w3-green w3-padding">
            <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
        </div>
    <?php endif; ?>

    <div>
        <form method="post" class="w3-container w3-card-4 w3-padding w3-white w3-round-large" style="max-width:600px">
            <p><label class="w3-text-black">Meno</label>
                <input class="w3-input w3-border w3-round" type="text" name="jmeno" required>
            </p>

            <p><label class="w3-text-black">Priezvisko</label>
                <input class="w3-input w3-border w3-round" type="text" name="prijmeni" required>
            </p>

            <p><label class="w3-text-black">Ulica</label>
                <input class="w3-input w3-border w3-round" type="text" name="ulice" required>
            </p>

            <p><label class="w3-text-black">Metso</label>
                <input class="w3-input w3-border w3-round" type="text" name="mesto" required>
            </p>

            <p><label class="w3-text-black">PSČ</label>
                <input class="w3-input w3-border w3-round" type="text" name="psc" required>
            </p>

            <p><label class="w3-text-black">Email</label>
                <input class="w3-input w3-border w3-round" type="email" name="email" required>
            </p>

            <p><label class="w3-text-black">Telefon</label>
                <input class="w3-input w3-border w3-round" type="text" name="telefon" required>
            </p>

            <p><button class="w3-button w3-blue w3-round" type="submit">Uložit</button></p>
        </form>

</body>

</html>