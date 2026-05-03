<?php
require_once 'connexion.php';

$errors = [];

$destination_presel = $_GET['destination'] ?? '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['nom'])) {
        $errors[] = "Le nom est requis";
    }
    if (empty($_POST['email'])) {
        $errors[] = "L'email est requis";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }
    if (empty($_POST['voyage'])) {
        $errors[] = "Veuillez sélectionner un voyage";
    }
    if (empty($_POST['date_reservation'])) {
        $errors[] = "La date de réservation est requise";
    }

    if (empty($errors)) {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $voyageNom = $_POST['voyage'];
        $date_reservation = $_POST['date_reservation'];
        $date_inscription = date('Y-m-d');

        $stmtUser = $conn->prepare("INSERT INTO utilisateur (nom, email, date_inscription) VALUES (?, ?, ?)");
        if ($stmtUser) {
            $stmtUser->bind_param("sss", $nom, $email, $date_inscription);
            if ($stmtUser->execute()) {
                $id_utilisateur = $stmtUser->insert_id;
            } else {
                die("Erreur lors de l'insertion de l'utilisateur : " . $stmtUser->error);
            }
            $stmtUser->close();
        } else {
            die("Erreur de préparation utilisateur : " . $conn->error);
        }

        $stmtVoyage = $conn->prepare("
            SELECT voyage.id_voyage 
            FROM voyage 
            JOIN destination ON voyage.id_destination = destination.id_destination 
            WHERE destination.nom = ?
        ");
        if ($stmtVoyage) {
            $stmtVoyage->bind_param("s", $voyageNom);
            $stmtVoyage->execute();
            $resultVoyage = $stmtVoyage->get_result();
            if ($rowVoyage = $resultVoyage->fetch_assoc()) {
                $id_voyage = $rowVoyage['id_voyage'];
            } else {
                die("Erreur : Voyage introuvable.");
            }
            $stmtVoyage->close();
        } else {
            die("Erreur de préparation voyage : " . $conn->error);
        }

        $statut = 'en attente';
        $stmtReservation = $conn->prepare("
            INSERT INTO reservation (id_utilisateur, id_voyage, date_reservation, statut)
            VALUES (?, ?, ?, ?)
        ");
        if ($stmtReservation) {
            $stmtReservation->bind_param("iiss", $id_utilisateur, $id_voyage, $date_reservation, $statut);
            if ($stmtReservation->execute()) {
                $success_message = "✅ Réservation enregistrée avec succès !";
            } else {
                $errors[] = "❌ Erreur lors de l'insertion de la réservation : " . $stmtReservation->error;
            }
            $stmtReservation->close();
        } else {
            die("Erreur de préparation réservation : " . $conn->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un voyage</title>
    <link rel="stylesheet" href="reserver.css">
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

<h1>Réserver un voyage</h1>

<?php
if (!empty($errors)) {
    echo '<div class="error-message">';
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo '</div>';
}

if (isset($success_message)) {
    echo '<div class="success-message">';
    echo "<p>$success_message</p>";
    echo '</div>';
}
?>

<form method="post" class="form-reservation">
    <input type="text" name="nom" placeholder="Votre nom" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
    <input type="email" name="email" placeholder="Votre email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

    <select name="voyage" required>
        <option value="">-- Choisissez un voyage --</option>
        <?php
        $voyages = [];
        $voyageQuery = "SELECT destination.nom, prix, date_depart, date_retour FROM voyage
                        JOIN destination ON voyage.id_destination= destination.id_destination";
        $voyageResult = $conn->query($voyageQuery);
        if ($voyageResult) {
            while ($row = $voyageResult->fetch_assoc()) {
                $voyages[] = $row;
            }
        }

        foreach ($voyages as $voyage):
            $isSelected = false;
            if (isset($_POST['voyage']) && $_POST['voyage'] === $voyage['nom']) {
                $isSelected = true;
            } elseif (!$isSelected && $destination_presel && $destination_presel === $voyage['nom']) {
                $isSelected = true;
            }
        ?>
            <option value="<?= htmlspecialchars($voyage['nom']) ?>" <?= $isSelected ? 'selected' : '' ?>>
                <?= htmlspecialchars($voyage['nom']) ?> - <?= number_format($voyage['prix'], 2, ',', ' ') ?> €
            </option>
        <?php endforeach; ?>
    </select>

    <input type="date" name="date_reservation" required value="<?= isset($_POST['date_reservation']) ? htmlspecialchars($_POST['date_reservation']) : '' ?>">
    <button type="submit">Réserver</button>
</form>

</body>
</html>