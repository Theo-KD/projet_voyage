<?php
$conn = new mysqli("localhost", "root", "", "voyage");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$confirmation = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $conn->real_escape_string($_POST["nom"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $message = $conn->real_escape_string($_POST["message"]);

    $sql = "INSERT INTO messages (nom, email, message, date_envoi) VALUES ('$nom', '$email', '$message', NOW())";
    if ($conn->query($sql) === TRUE) {
        // Envoi email optionnel (à activer si serveur mail configuré)
        // mail($email, "Confirmation", "Merci $nom pour votre message.", "From: contact@travelnow.com");
        $confirmation = "✅ Merci $nom, votre message a bien été envoyé !";
    } else {
        $confirmation = "❌ Une erreur est survenue : " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <link rel="stylesheet" href="contact.css">
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
        <h1>Contactez-nous</h1>

        <?php if ($confirmation): ?>
            <div class="confirmation"><?= $confirmation ?></div>
        <?php endif; ?>

        <form method="post" class="form-contact">
            <input type="text" name="nom" placeholder="Votre nom" required>
            <input type="email" name="email" placeholder="Votre email" required>
            <textarea name="message" placeholder="Votre message" rows="6" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </main>
</body>
</html>