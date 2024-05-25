<?php
// Placez ce code au début du fichier PHP pour initialiser la connexion à la base de données

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
session_start();
$email = $_SESSION['email'];
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si les données POST requises existent
if(isset($_POST['commentaire_id']) && isset($_POST['reponse'])) {
    // Récupérer les données POST
    $commentaire_id = $_POST['commentaire_id'];
    $reponse = $_POST['reponse'];

    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO reponses_commentaires (commentaire_id, email, reponse) VALUES (?, ?, ?)");
    // Remplacer "email_utilisateur" par le nom de la colonne correspondante dans votre table d'utilisateurs
    $stmt->bind_param("iss", $commentaire_id, $email_utilisateur, $reponse);

    // Email de l'utilisateur (vous devez définir cela selon votre système d'authentification)
    

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Réponse ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de la réponse: " . $conn->error;
    }

    // Fermer la requête
    $stmt->close();
} else {
    echo "Erreur: Données manquantes pour ajouter la réponse.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
