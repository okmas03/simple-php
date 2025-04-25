<?php
require_once 'config.php';

$zprava = "";
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