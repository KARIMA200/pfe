<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
session_start(); // Démarrer la session

// Récupérer l'e-mail de la session
$email = $_SESSION['email'];

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour récupérer les informations de l'utilisateur
function getUserInfo($email, $conn) {
    $stmt = $conn->prepare("SELECT type FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['type'] == 'vendeur') {
        $stmt = $conn->prepare("SELECT nom, prenom, user_image FROM vendeurs WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT nom, prenom, user_image FROM clients WHERE email = ?");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Vérifier si les données POST requises existent
if(isset($_POST['produit_id']) && isset($_POST['commentaire'])) {
    // Récupérer les données POST
    $produit_id = $_POST['produit_id'];
    $commentaire = $_POST['commentaire'];

    // Récupérer les informations de l'utilisateur
    $user_info = getUserInfo($email, $conn);
    $nom = $user_info['nom'];
    $prenom = $user_info['prenom'];
    $image = $user_info['user_image'];

    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO commentaires (produit_id, email, commentaire, date_commentaire, nom, prenom, image) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
    $stmt->bind_param("isssss", $produit_id, $email, $commentaire, $nom, $prenom, $image);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Commentaire ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout du commentaire: " . $conn->error;
    }

    // Fermer la requête
    $stmt->close();
} else {
    echo "Erreur: Données manquantes pour ajouter le commentaire.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
