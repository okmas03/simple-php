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

$naStranu = 10; 
$strana = isset($_GET['strana']) && is_numeric($_GET['strana']) ? (int) $_GET['strana'] : 1;
if ($strana < 1) $strana = 1;
$zacatek = ($strana - 1) * $naStranu;

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM zakaznici");
    $celkemZakazniku = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT * FROM zakaznici ORDER BY $sort ASC LIMIT :zacatek, :naStranu");
    $stmt->bindValue(':zacatek', $zacatek, PDO::PARAM_INT);
    $stmt->bindValue(':naStranu', $naStranu, PDO::PARAM_INT);
    $stmt->execute();
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
                        <th><a href="?sort=id<?= $strana ? '&strana=' . $strana : '' ?>">ID</a></th>
                        <th><a href="?sort=jmeno<?= $strana ? '&strana=' . $strana : '' ?>">Meno</a></th>
                        <th><a href="?sort=prijmeni<?= $strana ? '&strana=' . $strana : '' ?>">Priezvisko</a></th>
                        <th><a href="?sort=ulice<?= $strana ? '&strana=' . $strana : '' ?>">Ulica</a></th>
                        <th><a href="?sort=cp<?= $strana ? '&strana=' . $strana : '' ?>">Číslo popisné</a></th>
                        <th><a href="?sort=mesto<?= $strana ? '&strana=' . $strana : '' ?>">Mesto</a></th>
                        <th><a href="?sort=psc<?= $strana ? '&strana=' . $strana : '' ?>">PSČ</a></th>
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

    <div class="w3-center w3-margin-top">
        <div class="w3-bar">
            <?php
            $celkemStran = ceil($celkemZakazniku / $naStranu);

            if ($strana > 1):
            ?>
                <a href="?sort=<?= htmlspecialchars($sort) ?>&strana=<?= $strana - 1 ?>" class="w3-button w3-light-grey">« Predchádzajúca</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $celkemStran; $i++): ?>
                <a href="?sort=<?= htmlspecialchars($sort) ?>&strana=<?= $i ?>" class="w3-button <?= $i == $strana ? 'w3-blue' : 'w3-light-grey' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($strana < $celkemStran): ?>
                <a href="?sort=<?= htmlspecialchars($sort) ?>&strana=<?= $strana + 1 ?>" class="w3-button w3-light-grey">Ďalšia »</a>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
