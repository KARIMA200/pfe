<?php
// Vérifier si la requête POST contient les données nécessaires
if(isset($_POST['comment_id']) && isset($_POST['click_count'])) {
    // Récupérer les données de la requête POST
    $commentId = $_POST['comment_id'];
    $clickCount = $_POST['click_count'];
    var_dump($_POST);

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommerce";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Requête SQL pour mettre à jour le nombre de clics du commentaire spécifié
    $sql = "UPDATE commentaires SET nombre_clics = $clickCount WHERE id = $commentId";

    if ($conn->query($sql) === TRUE) {
        echo "Le nombre de clics a été mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du nombre de clics: " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
} else {
    echo "Erreur: Données manquantes pour mettre à jour le nombre de clics.";
}
?>
