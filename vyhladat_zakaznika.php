<?php
require_once 'config.php';

$kriteria = ['jmeno', 'prijmeni', 'ulice', 'mesto', 'psc', 'cp'];  // Pridaný cp
$sloupec = $_GET['sloupec'] ?? '';
$hledat = $_GET['hledat'] ?? '';
$vysledky = [];

if ($sloupec && $hledat && in_array($sloupec, $kriteria)) {

    $hledat = strip_tags(trim($hledat));

    $stmt = $conn->prepare("SELECT * FROM zakaznici WHERE $sloupec LIKE :hledat");
    $stmt->execute([':hledat' => "%$hledat%"]);
    $vysledky = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Vyhľadanie zákazníka</title>
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
        <h2>Vyhľadanie zákazníka</h2>

        <form method="get" action="vyhladat_zakaznika.php" class="w3-margin-bottom">
            <div class="w3-row-padding">
                <div class="w3-half">
                    <label for="sloupec">Vyberte kritérium:</label>
                    <select name="sloupec" id="sloupec" class="w3-select" required>
                        <option value="">-- Vyber --</option>
                        <?php foreach ($kriteria as $k): ?>
                            <option value="<?= $k ?>" <?= $sloupec === $k ? 'selected' : '' ?>>
                                <?= ucfirst($k) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="w3-half">
                    <label for="hledat">Hľadaný výraz:</label>
                    <input type="text" name="hledat" id="hledat" value="<?= htmlspecialchars($hledat) ?>" class="w3-input" required>
                </div>
            </div>

            <button type="submit" class="w3-button w3-green w3-margin-top">Hladať</button>
        </form>

        <?php if ($sloupec && $hledat): ?>
            <h3>Výsledok hľadania:</h3>

            <?php if (count($vysledky) > 0): ?>
                <div class="w3-responsive">
                    <table class="w3-table-all w3-hoverable w3-small">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>ID</th>
                                <th>Meno</th>
                                <th>Priezvisko</th>
                                <th>Ulica</th>
                                <th>Mesto</th>
                                <th>PSČ</th>
                                <th>Číslo popisné</th>  <!-- Pridaný stĺpec pre cp -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vysledky as $zak): ?>
                                <tr>
                                    <td><?= htmlspecialchars($zak['id']) ?></td>
                                    <td><?= htmlspecialchars($zak['jmeno']) ?></td>
                                    <td><?= htmlspecialchars($zak['prijmeni']) ?></td>
                                    <td><?= htmlspecialchars($zak['ulice']) ?></td>
                                    <td><?= htmlspecialchars($zak['mesto']) ?></td>
                                    <td><?= htmlspecialchars($zak['psc']) ?></td>
                                    <td><?= htmlspecialchars($zak['cp']) ?></td>  <!-- Zobrazenie čísla popisného -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Žiadne výsledky.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</body>

</html>
