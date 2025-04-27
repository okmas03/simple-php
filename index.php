<?php
require_once 'config.php';

$zprava = "";

$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';

$validSortColumns = ['id', 'jmeno', 'prijmeni', 'ulice', 'mesto', 'psc', 'email', 'telefon'];
if (!in_array($sortBy, $validSortColumns)) {
    $sortBy = 'id';
}

$sql = "SELECT * FROM zakaznici ORDER BY $sortBy $sortOrder";
$vysledek = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Zoznam zákazníkov</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body class="w3-container w3-light-grey">

    <nav class="nav">
        <a href="index.php">Zoznam zákazníkov</a>
        <a href="pridat_zakaznika.php">Pridat zákazníka</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
        <a href="upravit_zakaznika.php">Upraviť zákazníka</a>
    </nav>
    <div class="w3-container w3-white w3-round-large w3-padding-large w3-card-4">

        <h2 class="w3-center">Zoznam zákazníkov</h2>

        <form method="get" action="index.php" class="w3-container w3-margin-bottom">
            <div class="w3-row-padding">
                <div class="w3-third">
                    <label for="sort">Zoradiť podľa:</label>
                    <select name="sort" id="sort" class="w3-select" required>
                        <option value="" disabled selected>Vyberte...</option>
                        <option value="id" <?= ($sortBy == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="jmeno" <?= ($sortBy == 'jmeno') ? 'selected' : ''; ?>>Meno</option>
                        <option value="prijmeni" <?= ($sortBy == 'prijmeni') ? 'selected' : ''; ?>>Priezvisko</option>
                        <option value="ulice" <?= ($sortBy == 'ulice') ? 'selected' : ''; ?>>Ulica</option>
                        <option value="mesto" <?= ($sortBy == 'mesto') ? 'selected' : ''; ?>>Mesto</option>
                        <option value="psc" <?= ($sortBy == 'psc') ? 'selected' : ''; ?>>PSČ</option>
                        <option value="email" <?= ($sortBy == 'email') ? 'selected' : ''; ?>>Email</option>
                        <option value="telefon" <?= ($sortBy == 'telefon') ? 'selected' : ''; ?>>Telefon</option>
                    </select>
                </div>

                <div class="w3-third">
                    <label for="order">Poradie:</label>
                    <select name="order" id="order" class="w3-select" required>
                        <option value="asc" <?= ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Vzostupne</option>
                        <option value="desc" <?= ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Zostupne</option>
                    </select>
                </div>

                <div class="w3-third w3-padding-top-24">
                    <button type="submit" class="w3-button w3-green w3-block w3-round">Zoradiť</button>
                </div>
            </div>
        </form>

        <?php if ($vysledek->rowCount() > 0): ?>
            <div class="w3-responsive">
                <table class="w3-table-all w3-striped w3-bordered w3-hoverable">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>ID</th>
                            <th>Meno</th>
                            <th>Priezvisko</th>
                            <th>Ulica</th>
                            <th>Mesto</th>
                            <th>PSČ</th>
                            <th>Email</th>
                            <th>Telefon</th>
                        </tr>
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="w3-panel w3-yellow w3-padding">Žiadne výsledky.</p>
        <?php endif; ?>

    </div>

</body>

</html>