<?php
require_once 'config.php';

$zprava = "";
$zakaznik = null;

// Ak bol formulár na výber ID odoslaný
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $zakaznik = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$zakaznik) {
        $zprava = "Zákazník s ID $id nebyl nalezen.";
    }
}

// Ak bol formulár na úpravu údajov odoslaný
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

    $zprava = "Zákazník byl úspěšně upraven.";

    // Načítaj nové hodnoty
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
    <title>Upravit zákazníka</title>
</head>
<body>
<nav>
    <a href="index.php">Zoznam zákazníkov</a>
    <a href="pridat_zakaznika.php">Přidat zákazníka</a>
    <a href="vyhledat_zakaznika.php">Vyhledat zákazníka</a>
</nav>

<h1>Upravit zákazníka</h1>

<?php if ($zprava): ?>
    <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
<?php endif; ?>

<!-- Formulár na zadanie ID zákazníka -->
<?php if (!$zakaznik): ?>
    <form method="get">
        <label>Zadejte ID zákazníka: <input type="number" name="id" required></label>
        <button type="submit">Načíst</button>
    </form>
<?php endif; ?>

<!-- Formulár na úpravu údajov -->
<?php if ($zakaznik): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($zakaznik['id']) ?>">
        <label>Jméno: <input type="text" name="jmeno" value="<?= htmlspecialchars($zakaznik['jmeno']) ?>" required></label><br>
        <label>Příjmení: <input type="text" name="prijmeni" value="<?= htmlspecialchars($zakaznik['prijmeni']) ?>" required></label><br>
        <label>Ulice: <input type="text" name="ulice" value="<?= htmlspecialchars($zakaznik['ulice']) ?>" required></label><br>
        <label>Město: <input type="text" name="mesto" value="<?= htmlspecialchars($zakaznik['mesto']) ?>" required></label><br>
        <label>PSČ: <input type="text" name="psc" value="<?= htmlspecialchars($zakaznik['psc']) ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($zakaznik['email']) ?>" required></label><br>
        <label>Telefon: <input type="text" name="telefon" value="<?= htmlspecialchars($zakaznik['telefon']) ?>" required></label><br>
        <button type="submit" name="ulozit">Uložit změny</button>
    </form>
<?php endif; ?>

</body>
</html>
