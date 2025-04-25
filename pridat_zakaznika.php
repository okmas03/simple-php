<?php
require_once 'config.php';

$sloupec = $_GET['sloupec'] ?? '';
$hledat = $_GET['hledat'] ?? '';
$vysledek = [];

if ($sloupec && $hledat) {
    // whitelist sloupců, aby uživatel neposlal nebezpečný SQL dotaz
    $povoleneSloupce = ['jmeno', 'prijmeni', 'ulice', 'mesto', 'psc', 'email', 'telefon'];
    if (in_array($sloupec, $povoleneSloupce)) {
        $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE $sloupec LIKE :hledat");
        $stmt->execute([':hledat' => "%$hledat%"]);
        $vysledek = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Neplatné kritérium vyhledávání.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Vyhledat zákazníka</title>
</head>
<body>
    <nav>
        <a href="index.php">Zoznam zákazníkov</a>
        <a href="pridat_zakaznika.php">Přidat zákazníka</a>
        <a href="vyhledat_zakaznika.php">Vyhledat zákazníka</a>
    </nav>

    <h2>Vyhledat zákazníka</h2>

    <form method="get" action="vyhledat_zakaznika.php">
        <label for="sloupec">Kritérium:</label>
        <select name="sloupec" id="sloupec">
            <option value="jmeno">Jméno</option>
            <option value="prijmeni">Příjmení</option>
            <option value="ulice">Ulice</option>
            <option value="mesto">Město</option>
            <option value="psc">PSČ</option>
            <option value="email">Email</option>
            <option value="telefon">Telefon</option>
        </select>
        <input type="text" name="hledat" required>
        <button type="submit">Hledat</button>
    </form>

    <?php if ($vysledek): ?>
        <h3>Výsledky hledání</h3>
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
            <?php foreach ($vysledek as $radek): ?>
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
            <?php endforeach; ?>
        </table>
    <?php elseif ($sloupec && $hledat): ?>
        <p>Nebyly nalezeny žádné záznamy.</p>
    <?php endif; ?>
</body>
</html>
