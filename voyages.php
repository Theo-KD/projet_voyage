<?php
$conn = new mysqli("localhost", "root", "", "voyage");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$destination = $_GET['destination'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';
$date_depart = $_GET['date_depart'] ?? '';

$sql = "SELECT date_depart, date_retour, prix, destination.nom, destination.description 
        FROM voyage
        JOIN destination ON destination.id_destination=voyage.id_destination";

$conditions = [];
$params = [];

if (!empty($destination)) {
    $destination = $conn->real_escape_string($destination);
    $conditions[] = "destination.nom LIKE '%$destination%'";
}

if (!empty($prix_max)) {
    $prix_max = (float)$prix_max;
    $conditions[] = "prix <= $prix_max";
}

if (!empty($date_depart)) {
    $date_depart = $conn->real_escape_string($date_depart);
    $conditions[] = "date_depart >= '$date_depart'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " LIMIT 10";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voyages</title>
    <link rel="stylesheet" href="voyage.css">
</head>
<body>
    <header class="site-header">
        <div class="logo">🌍 TravelNow</div>
        <nav class="nav-menu">
            <a href="accueil.php">Accueil</a>
            <a href="voyages.php">Voyages</a>
            <a href="contact.php">Contact</a>
            <a href="reserver.php">Réserver</a>
            <a href="activite.php">Activités</a>
        </nav>
    </header>

    <main>
        <h1>Liste des Voyages</h1>

        <form method="get" class="filtre-form">
            <input type="text" name="destination" placeholder="Destination" value="<?= htmlspecialchars($destination) ?>">
            <input type="number" name="prix_max" placeholder="Prix max (€)" value="<?= htmlspecialchars($prix_max) ?>">
            <input type="date" name="date_depart" value="<?= htmlspecialchars($date_depart) ?>">
            <button type="submit">Filtrer</button>
        </form>
        <div class="voyage-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($voyage = $result->fetch_assoc()): ?>
                    <div class="voyage-card">
                        <h2><?= htmlspecialchars($voyage['nom']) ?> – <?= $voyage['prix'] ?>€</h2>
                        <p><strong>Départ :</strong> <?= htmlspecialchars($voyage['date_depart']) ?></p>
                        <p><?= nl2br(htmlspecialchars($voyage['description'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun voyage trouvé.</p>
            <?php endif; ?>
        </div>

        <?php $conn->close(); ?>
    </main>
</body>
</html>
