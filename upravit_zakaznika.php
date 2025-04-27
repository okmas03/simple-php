<?php
require_once 'config.php';

$zprava = "";
$zakaznik = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($id <= 0) {
        $zprava = "Neplatné ID zákazníka.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $zakaznik = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$zakaznik) {
            $zprava = "Zákazník s ID $id nebol nájdený.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit'])) {
    $id = (int)$_POST['id'];
    $jmeno = trim($_POST['jmeno']);
    $prijmeni = trim($_POST['prijmeni']);
    $ulice = trim($_POST['ulice']);
    $mesto = trim($_POST['mesto']);
    $psc = trim($_POST['psc']);
    $cp = trim($_POST['cp']);

    // Validácie
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
    }

    if (!$zprava) {
        $stmt = $conn->prepare("UPDATE zakaznici SET 
            jmeno = :jmeno, 
            prijmeni = :prijmeni,
            ulice = :ulice,
            mesto = :mesto,
            psc = :psc,
            cp = :cp
            WHERE id = :id");

        $stmt->execute([
            ':jmeno' => $jmeno,
            ':prijmeni' => $prijmeni,
            ':ulice' => $ulice,
            ':mesto' => $mesto,
            ':psc' => $psc,
            ':cp' => $cp,
            ':id' => $id
        ]);

        $zprava = "Zákazník bol úspešne upravený.";

        $zakaznik = [
            'id' => $id,
            'jmeno' => $jmeno,
            'prijmeni' => $prijmeni,
            'ulice' => $ulice,
            'mesto' => $mesto,
            'psc' => $psc,
            'cp' => $cp,
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Upraviť zákazníka</title>
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
        <h1>Upraviť zákazníka</h1>

        <?php if ($zprava): ?>
            <div class="w3-panel w3-green w3-round">
                <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
            </div>
        <?php endif; ?>

        <?php if (!$zakaznik): ?>
            <form method="get" class="w3-container w3-card-4 w3-light-grey w3-padding">
                <p>
                    <label>Zadajte ID zákazníka:</label>
                    <input class="w3-input w3-border" type="number" name="id" min="1" required>
                </p>
                <p>
                    <button class="w3-button w3-blue w3-round" type="submit">Vyhľadať</button>
                </p>
            </form>
        <?php endif; ?>

        <?php if ($zakaznik): ?>
            <form method="post" class="w3-container w3-card-4 w3-light-grey w3-padding">
                <input type="hidden" name="id" value="<?= htmlspecialchars($zakaznik['id']) ?>">

                <p>
                    <label>Meno:</label>
                    <input class="w3-input w3-border" type="text" name="jmeno" value="<?= htmlspecialchars($zakaznik['jmeno']) ?>" required pattern="[a-zA-Zá-žÁ-Ž\s]+" title="Meno môže obsahovať iba písmená a medzery.">
                </p>

                <p>
                    <label>Priezvisko:</label>
                    <input class="w3-input w3-border" type="text" name="prijmeni" value="<?= htmlspecialchars($zakaznik['prijmeni']) ?>" required pattern="[a-zA-Zá-žÁ-Ž\s]+" title="Priezvisko môže obsahovať iba písmená a medzery.">
                </p>

                <p>
                    <label>Ulica:</label>
                    <input class="w3-input w3-border" type="text" name="ulice" value="<?= htmlspecialchars($zakaznik['ulice']) ?>" required>
                </p>

                <p>
                    <label>Mesto:</label>
                    <input class="w3-input w3-border" type="text" name="mesto" value="<?= htmlspecialchars($zakaznik['mesto']) ?>" required pattern="[a-zA-Zá-žÁ-Ž\s]+" title="Mesto môže obsahovať iba písmená a medzery.">
                </p>

                <p>
                    <label>PSČ:</label>
                    <input class="w3-input w3-border" type="text" name="psc" value="<?= htmlspecialchars($zakaznik['psc']) ?>" required pattern="\d{5}|\d{3}\s\d{2}" title="PSČ musí byť 12345 alebo 123 45.">
                </p>

                <p>
                    <label>Číslo popisné:</label>
                    <input class="w3-input w3-border" type="text" name="cp" value="<?= htmlspecialchars($zakaznik['cp']) ?>" required pattern="\d+" title="Číslo popisné musí byť číslo.">
                </p>

                <p>
                    <a class="w3-button w3-blue w3-round" href="zakaznici.php">← Späť na zoznam zákazníkov</a>
                    <button class="w3-button w3-green w3-round" type="submit" name="ulozit">Uložiť zmeny</button>
                </p>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>