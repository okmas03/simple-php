<?php
require_once 'config.php';

$zprava = "";

// Spracovanie formulára
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

// Získanie všetkých zákazníkov
$vysledek = $conn->query("SELECT * FROM zakaznici ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Správa zákazníků</title>
</head>
<body>
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

    <h2>Seznam zákazníků</h2>

    <?php if ($vysledek->rowCount() > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Jméno</th>
                <th>Příjmení</th>
                <th>Ulice</th>
                <th>Město</th>
                <th>PSČ</th>
                <th>Email</th>
                <th>Telefon</th>
            </tr>
            <?php while ($radek = $vysledek->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($radek['id']) ?></td>
                    <td><?= htmlspecialchars($radek['jmeno']) ?></td>
                    <td><?= htmlspecialchars($radek['prijmeni']) ?></td>
                    <td><?= htmlspecialchars($radek['ulice']) ?></td>
                    <td><?= htmlspecialchars($radek['mesto']) ?></td>
                    <td><?= htmlspecialchars($radek['psc']) ?></td>
                    <td><?= htmlspecialchars($radek['email']) ?></td>
                    <td><?= htmlspecialchars($radek['telefon']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Žádní zákazníci zatím nejsou.</p>
    <?php endif; ?>

</body>
</html>
