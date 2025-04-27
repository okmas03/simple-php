<?php
require_once 'config.php';

if (isset($_GET['delete_id'])) {
    $deleteId = (int) $_GET['delete_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM zakaznici WHERE id = ?");
        $stmt->execute([$deleteId]);
        header("Location: zakaznici.php");
        exit;
    } catch (PDOException $e) {
        die("Chyba při mazání zákazníka: " . $e->getMessage());
    }
}

$sort = $_GET['sort'] ?? 'id';
$allowedSort = ['id', 'jmeno', 'prijmeni', 'ulice', 'cp', 'mesto', 'psc'];

if (!in_array($sort, $allowedSort)) {
    $sort = 'id';
}

try {
    $stmt = $conn->query("SELECT * FROM zakaznici ORDER BY $sort ASC");
    $zakaznici = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba při načítání zákazníků: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <title>Zákazníci</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body class="w3-container w3-padding">

    <nav class="nav w3-margin-bottom">
        <a href="index.php">Úvod</a>
        <a href="zakaznici.php">Zákazníci</a>
        <a href="vyhladat_zakaznika.php">Vyhľadať zákazníka</a>
    </nav>

    <h1 class="w3-xlarge">Zákazníci</h1>

    <a class="w3-button w3-green w3-round w3-margin-bottom" href="pridat_zakaznika.php">+ Pridať nového zákazníka</a>

    <div class="w3-responsive" style="display: flex; justify-content: center;">

        <div style="max-width: 1200px; width: 100%;">
            <table class="w3-table-all w3-hoverable">
                <thead>
                    <tr class="w3-light-grey">
                        <th><a href="?sort=id">ID</a></th>
                        <th><a href="?sort=jmeno">Meno</a></th>
                        <th><a href="?sort=prijmeni">Priezvisko</a></th>
                        <th><a href="?sort=ulice">Ulica</a></th>
                        <th><a href="?sort=cp">Číslo popisné</a></th>
                        <th><a href="?sort=mesto">Mesto</a></th>
                        <th><a href="?sort=psc">PSČ</a></th>
                        <th>Akcie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($zakaznici): ?>
                        <?php foreach ($zakaznici as $zakaznik): ?>
                            <tr>
                                <td><?= htmlspecialchars($zakaznik['id']) ?></td>
                                <td><?= htmlspecialchars($zakaznik['jmeno']) ?></td>
                                <td><?= htmlspecialchars($zakaznik['prijmeni']) ?></td>
                                <td><?= htmlspecialchars($zakaznik['ulice']) ?></td>
                                <td><?= htmlspecialchars($zakaznik['cp']) ?></td>
                                <td><?= htmlspecialchars($zakaznik['mesto']) ?></td>
                                <td><?= htmlspecialchars($zakaznik['psc']) ?></td>
                                <td>
                                    <a class="w3-button w3-blue w3-small w3-round" href="zobrazit_zakaznika.php?id=<?= $zakaznik['id'] ?>">Zobraziť</a>
                                    <a class="w3-button w3-yellow w3-small w3-round" href="upravit_zakaznika.php?id=<?= $zakaznik['id'] ?>">Editovať</a>
                                    <a class="w3-button w3-red w3-small w3-round" href="?delete_id=<?= $zakaznik['id'] ?>" onclick="return confirm('Naozaj chcete vymazať tohoto zákazníka?');">Vymazať</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Žiadni zákazníci v databáze.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>