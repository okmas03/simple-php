<?php
require_once 'config.php';

$kriteria = ['jmeno', 'prijmeni', 'ulice', 'mesto', 'psc', 'email', 'telefon'];
$sloupec = $_GET['sloupec'] ?? '';
$hledat = $_GET['hledat'] ?? '';
$vysledky = [];

if ($sloupec && $hledat && in_array($sloupec, $kriteria)) {
    $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE $sloupec LIKE :hledat");
    $stmt->execute([':hledat' => "%$hledat%"]);
    $vysledky = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Vyhledání zákazníka</title>
</head>
<body>

<nav>
    <a href="index.php">Zoznam zákazníkov</a>
    <a href="pridat_zakaznika.php">Přidat zákazníka</a>
    <a href="vyhladat_zakaznika.php">Vyhledat zákazníka</a>
</nav>

<h2>Vyhledání zákazníka</h2>

<form method="get" action="vyhladat_zakaznika.php">
    <label for="sloupec">Vyber kritérium:</label>
    <select name="sloupec" id="sloupec" required>
        <option value="">-- Vyber --</option>
        <?php foreach ($kriteria as $k): ?>
            <option value="<?= $k ?>" <?= $sloupec === $k ? 'selected' : '' ?>>
                <?= ucfirst($k) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="hledat">Hledaný výraz:</label>
    <input type="text" name="hledat" id="hledat" value="<?= htmlspecialchars($hledat) ?>" required>

    <button type="submit">Hledat</button>
</form>

<?php if ($sloupec && $hledat): ?>
    <h3>Výsledky hledání:</h3>

    <?php if (count($vysledky) > 0): ?>
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
            <?php foreach ($vysledky as $zak): ?>
                <tr>
                    <td><?= htmlspecialchars($zak['id']) ?></td>
                    <td><?= htmlspecialchars($zak['jmeno']) ?></td>
                    <td><?= htmlspecialchars($zak['prijmeni']) ?></td>
                    <td><?= htmlspecialchars($zak['ulice']) ?></td>
                    <td><?= htmlspecialchars($zak['mesto']) ?></td>
                    <td><?= htmlspecialchars($zak['psc']) ?></td>
                    <td><?= htmlspecialchars($zak['email']) ?></td>
                    <td><?= htmlspecialchars($zak['telefon']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Žádný zákazník nebyl nalezen.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
