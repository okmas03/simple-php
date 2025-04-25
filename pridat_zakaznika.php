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

$vysledek = $conn->query("SELECT * FROM zakaznici ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Správa zákazníků</title>
</head>

<body>
    <nav>
        <a href="index.php">Zoznam zákazníkov</a>
        <a href="pridat_zakaznika.php">Přidat zákazníka</a>
    </nav>
    <h1>Přidat zákazníka</h1>

    <?php if ($zprava): ?>
        <p><strong><?= htmlspecialchars($zprava) ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <label>Jméno: <input type="text" name="jmeno" required></label><br>
        <label>Příjmení: <input type="text" name="prijmeni" required></label><br>
        <label>Ulice: <input type="text" name="ulice" required></label><br>
        <label>Město: <input type="text" name="mesto" required></label><br>
        <label>PSČ: <input type="text" name="psc" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Telefon: <input type="text" name="telefon" required></label><br>
        <button type="submit">Uložit</button>
    </form>

</body>

</html>