<?php
require_once 'config.php';

$zprava = "";
$zakaznik = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $zakaznik = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$zakaznik) {
        $zprava = "Zákazník s ID $id nebol nájdený.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit'])) {
    $id = $_POST['id'];
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $ulice = $_POST['ulice'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];

    $stmt = $conn->prepare("UPDATE zakaznici SET 
        jmeno = :jmeno, 
        prijmeni = :prijmeni,
        ulice = :ulice,
        mesto = :mesto,
        psc = :psc,
        email = :email,
        telefon = :telefon
        WHERE id = :id");

    $stmt->execute([
        ':jmeno' => $jmeno,
        ':prijmeni' => $prijmeni,
        ':ulice' => $ulice,
        ':mesto' => $mesto,
        ':psc' => $psc,
        ':email' => $email,
        ':telefon' => $telefon,
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
        'email' => $email,
        'telefon' => $telefon
    ];
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

    <div class="nav">
        <a href="index.php">Zoznam zákazníkov</a>
        <a href="pridat_zakaznika.php">Pridať zákazníka</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
        <a href="upravit_zakaznika.php">Upraviť zákazníka</a>
    </div>

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
                    <input class="w3-input w3-border" type="number" name="id" required>
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
                    <input class="w3-input w3-border" type="text" name="jmeno" value="<?= htmlspecialchars($zakaznik['jmeno']) ?>" required>
                </p>
                <p>
                    <label>Priezvisko:</label>
                    <input class="w3-input w3-border" type="text" name="prijmeni" value="<?= htmlspecialchars($zakaznik['prijmeni']) ?>" required>
                </p>
                <p>
                    <label>Ulica:</label>
                    <input class="w3-input w3-border" type="text" name="ulice" value="<?= htmlspecialchars($zakaznik['ulice']) ?>" required>
                </p>
                <p>
                    <label>Mesto:</label>
                    <input class="w3-input w3-border" type="text" name="mesto" value="<?= htmlspecialchars($zakaznik['mesto']) ?>" required>
                </p>
                <p>
                    <label>PSČ:</label>
                    <input class="w3-input w3-border" type="text" name="psc" value="<?= htmlspecialchars($zakaznik['psc']) ?>" required>
                </p>
                <p>
                    <label>Email:</label>
                    <input class="w3-input w3-border" type="email" name="email" value="<?= htmlspecialchars($zakaznik['email']) ?>" required>
                </p>
                <p>
                    <label>Telefon:</label>
                    <input class="w3-input w3-border" type="text" name="telefon" value="<?= htmlspecialchars($zakaznik['telefon']) ?>" required>
                </p>
                <p>
                    <button class="w3-button w3-green w3-round" type="submit" name="ulozit">Uložiť zmeny</button>
                </p>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>