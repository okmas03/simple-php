<?php
require_once 'config.php';

$zprava = "";

// Určte výchozí kritérium pro seřazení (pokud není zvoleno, použije se výchozí 'id')
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';

// Ověřte, že je zvolené platné kritérium
$validSortColumns = ['id', 'jmeno', 'prijmeni', 'ulice', 'mesto', 'psc', 'email', 'telefon'];
if (!in_array($sortBy, $validSortColumns)) {
    $sortBy = 'id';  // Pokud je neplatné kritérium, použije se výchozí 'id'
}

$sql = "SELECT * FROM zakaznici ORDER BY $sortBy $sortOrder";
$vysledek = $conn->query($sql);

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

    <form method="get" action="index.php">
        <label for="sort">Seřadit podle:</label>
        <select name="sort" id="sort">
            <option value="id" <?= ($sortBy == 'id') ? 'selected' : ''; ?>>ID</option>
            <option value="jmeno" <?= ($sortBy == 'jmeno') ? 'selected' : ''; ?>>Jméno</option>
            <option value="prijmeni" <?= ($sortBy == 'prijmeni') ? 'selected' : ''; ?>>Příjmení</option>
            <option value="ulice" <?= ($sortBy == 'ulice') ? 'selected' : ''; ?>>Ulice</option>
            <option value="mesto" <?= ($sortBy == 'mesto') ? 'selected' : ''; ?>>Město</option>
            <option value="psc" <?= ($sortBy == 'psc') ? 'selected' : ''; ?>>PSČ</option>
            <option value="email" <?= ($sortBy == 'email') ? 'selected' : ''; ?>>Email</option>
            <option value="telefon" <?= ($sortBy == 'telefon') ? 'selected' : ''; ?>>Telefon</option>
        </select>

        <label for="order">Pořadí:</label>
        <select name="order" id="order">
            <option value="asc" <?= ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Vzestupně</option>
            <option value="desc" <?= ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Sestupně</option>
        </select>
        <button type="submit">Seřadit</button>
    </form>

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
