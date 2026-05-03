<?php
require_once 'connexion.php';

$search_nom = isset($_GET['nom']) ? $_GET['nom'] : '';
$search_type = isset($_GET['type']) ? $_GET['type'] : '';
$prix_min = isset($_GET['prix_min']) ? $_GET['prix_min'] : '';
$prix_max = isset($_GET['prix_max']) ? $_GET['prix_max'] : '';

$sql = "SELECT * FROM activité WHERE 1=1";
$params = array();

if (!empty($search_nom)) {
    $sql .= " AND nom LIKE ?";
    $params[] = '%' . $search_nom . '%';
}
if (!empty($search_type)) {
    $sql .= " AND type = ?";
    $params[] = $search_type;
}
if (!empty($prix_min)) {
    $sql .= " AND prix >= ?";
    $params[] = $prix_min;
}
if (!empty($prix_max)) {
    $sql .= " AND prix <= ?";
    $params[] = $prix_max;
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$types_query = $conn->query("SELECT DISTINCT type FROM activité ORDER BY type");
$types = $types_query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Activités</title>
    <link rel="stylesheet" href="activite.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<h1>Nos Activités</h1>

<div class="search-container">
    <form class="search-form" method="GET" action="">
        <div class="form-row">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($search_nom); ?>">
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="">Tous</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type['type']); ?>" <?php echo ($search_type == $type['type']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type['type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group price-range">
                <label>Prix</label>
                <div class="range-inputs">
                    <input type="number" name="prix_min" placeholder="Min" value="<?php echo htmlspecialchars($prix_min); ?>">
                    <span>à</span>
                    <input type="number" name="prix_max" placeholder="Max" value="<?php echo htmlspecialchars($prix_max); ?>">
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" class="search-btn">Rechercher</button>
                <button type="button" class="reset-btn" onclick="window.location.href='activite.php'">Réinitialiser</button>
            </div>
        </div>
    </form>
</div>

<div class="activite-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="activite-card">
                <?php if (!empty($row['image'])): ?>
                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['nom']); ?>">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($row['nom']); ?></h2>
                <p class="type">Type : <?php echo htmlspecialchars($row['type']); ?></p>
                <p class="prix">Prix : <?php echo htmlspecialchars($row['prix']); ?> €</p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-results">Aucune activité ne correspond à vos critères de recherche.</p>
    <?php endif; ?>
</div>

</body>
</html>