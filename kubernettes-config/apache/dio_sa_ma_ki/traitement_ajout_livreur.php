<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: page_de_connexion.php");
    exit;
}

// Récupérer l'email de l'utilisateur connecté
$email_utilisateur = $_SESSION['email'];

// Se connecter à la base de données (remplacez les paramètres avec les vôtres)
$conn = new mysqli("localhost", "root", "", "ecommerce");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Récupérer l'email du livreur à partir du formulaire
    $email_livreur = $_POST['email'];

    // Rechercher le nom et le prénom du client dans la table des clients en utilisant l'email
    $stmt_update = $conn->prepare("UPDATE clients SET livreur_vendeur_email = ? WHERE email = ?");
    $stmt_update->bind_param("ss", $email_utilisateur, $email_livreur);
    $stmt_update->execute();

    // Fermer la connexion à la base de données
    $conn->close();

    // Rediriger vers la page de succès avec l'email en tant que paramètre
    header("Location: succes.php?message=dtermination de kivreur a etait fait avec succes&page=uuv.php");

    exit;
}
?>
