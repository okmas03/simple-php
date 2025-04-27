<?php
require_once 'config.php';

$zprava = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = trim($_POST['jmeno']);
    $prijmeni = trim($_POST['prijmeni']);
    $ulice = trim($_POST['ulice']);
    $mesto = trim($_POST['mesto']);
    $psc = trim($_POST['psc']);
    $cp = trim($_POST['cp']);

    if (!preg_match('/^[a-zA-Zá-žÁ-Ž\s]+$/u', $jmeno)) {
        $zprava = "Meno môže obsahovať iba písmená a medzery.";
    } elseif (!preg_match('/^[a-zA-Zá-žÁ-Ž\s]+$/u', $prijmeni)) {
        $zprava = "Priezvisko môže obsahovať iba písmená a medzery.";
    } elseif (!preg_match('/^[a-zA-Zá-žÁ-Ž\s]+$/u', $mesto)) {
        $zprava = "Mesto môže obsahovať iba písmená a medzery.";
    } elseif (!preg_match('/^\d{5}$|^\d{3}\s\d{2}$/', $psc)) {
        $zprava = "PSČ musí byť vo formáte 12345 alebo 123 45.";
    } elseif (!preg_match('/^\d+$/', $cp)) {
        $zprava = "Číslo popisné musí byť číslo.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO zakaznici (jmeno, prijmeni, ulice, cp, mesto, psc)
                                    VALUES (:jmeno, :prijmeni, :ulice, :cp, :mesto, :psc)");
            $stmt->execute([
                ':jmeno' => $jmeno,
                ':prijmeni' => $prijmeni,
                ':ulice' => $ulice,
                ':cp' => $cp,
                ':mesto' => $mesto,
                ':psc' => $psc
            ]);
            $zprava = "Zákazník bol úspešne pridaný.";
        } catch (PDOException $e) {
            $zprava = "Chyba pri pridávaní: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Pridať zákazníka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body class="w3-container w3-padding">

    <nav class="nav w3-margin-bottom">
        <a href="index.php">Úvod</a>
        <a href="zakaznici.php">Zákazníci</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
    </nav>

    <h1 class="w3-xlarge">Pridať zákazníka</h1>

    <?php if ($zprava): ?>
        <div class="w3-panel <?= strpos($zprava, 'úspešne') !== false ? 'w3-green' : 'w3-red' ?> w3-padding">
            <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
        </div>
    <?php endif; ?>

    <div>
        <form method="post" class="w3-container w3-card-4 w3-padding w3-white w3-round-large" style="max-width:600px">
            <p>
                <label class="w3-text-black">Meno</label>
                <input class="w3-input w3-border w3-round" placeholder="Janko" type="text" name="jmeno" required pattern="[a-zA-Zá-žÁ-Ž\s]+" title="Meno môže obsahovať iba písmená a medzery.">
            </p>

            <p>
                <label class="w3-text-black">Priezvisko</label>
                <input class="w3-input w3-border w3-round" placeholder="Hráško" type="text" name="prijmeni" required pattern="[a-zA-Zá-žÁ-Ž\s]+" title="Priezvisko môže obsahovať iba písmená a medzery.">
            </p>

            <p>
                <label class="w3-text-black">Ulica</label>
                <input class="w3-input w3-border w3-round" placeholder="Dlhá 22" type="text" name="ulice" required>
            </p>

            <p>
                <label class="w3-text-black">Číslo popisné</label>
                <input class="w3-input w3-border w3-round" placeholder="232" type="text" name="cp" required pattern="\d+" title="Číslo popisné musí byť číslo.">
            </p>

            <p>
                <label class="w3-text-black">Mesto</label>
                <input class="w3-input w3-border w3-round" placeholder="Trenčín" type="text" name="mesto" required pattern="[a-zA-Zá-žÁ-Ž\s]+" title="Mesto môže obsahovať iba písmená a medzery.">
            </p>

            <p>
                <label class="w3-text-black">PSČ</label>
                <input class="w3-input w3-border w3-round" type="text" name="psc" required pattern="\d{5}|\d{3}\s\d{2}" placeholder="123 45" title="PSČ musí byť 12345 alebo 123 45.">
            </p>

            <p>
                <button class="w3-button w3-blue w3-round" type="submit">Uložiť</button>
            </p>
        </form>
    </div>

</body>

</html>