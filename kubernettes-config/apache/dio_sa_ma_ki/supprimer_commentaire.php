<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
session_start(); // Démarrer la session

$conn = new mysqli($servername, $username, $password, $dbname);

$produit_id = $_POST['produit_id'];

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'identifiant du commentaire est présent dans la requête POST
if (isset($_POST['comment_id'])) {
    // Récupérer l'identifiant du commentaire depuis la requête POST
    $commentaire_id = $_POST['comment_id'];

    // Vérifier s'il existe des entrées dans la table notifications liées à ce commentaire
    $sql_notifications = "DELETE FROM notifications WHERE comment_id = $commentaire_id";
    if ($conn->query($sql_notifications) === FALSE) {
        echo "Erreur lors de la suppression des entrées dans la table notifications: " . $conn->error;
        // Sortir de l'exécution si une erreur se produit
        exit();
    }

    // Vérifier s'il existe des entrées dans la table clics_utilisateurs liées à ce commentaire
    $sql_clics = "DELETE FROM clics_utilisateurs WHERE comment_id = $commentaire_id";
    if ($conn->query($sql_clics) === FALSE) {
        echo "Erreur lors de la suppression des entrées dans la table clics_utilisateurs: " . $conn->error;
        // Sortir de l'exécution si une erreur se produit
        exit();
    }

    // Vérifier s'il existe des réponses dans la table reponses_commentaires liées à ce commentaire
    $sql_reponses = "DELETE FROM reponses_commentaires WHERE commentaire_id = $commentaire_id";
    if ($conn->query($sql_reponses) === FALSE) {
        echo "Erreur lors de la suppression des réponses dans la table reponses_commentaires: " . $conn->error;
        // Sortir de l'exécution si une erreur se produit
        exit();
    }

    // Requête SQL pour supprimer le commentaire spécifié
    $sql_commentaire = "DELETE FROM commentaires WHERE id = $commentaire_id";
    if ($conn->query($sql_commentaire) === TRUE) {
        header("Location: commenter.php?produit_id=".$produit_id);
        exit(); // Arrêter l'exécution pour s'assurer que le header est envoyé
    } else {
        echo "Erreur lors de la suppression du commentaire: " . $conn->error;
    }
} else {
    echo "Erreur: Aucun identifiant de commentaire fourni.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
