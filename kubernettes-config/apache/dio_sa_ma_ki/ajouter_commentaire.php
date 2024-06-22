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
if (isset($_POST['produit_id']) && isset($_POST['commentaire'])) {
    // Récupérer les données POST
    $produit_id = $_POST['produit_id'];
    $commentaire = $_POST['commentaire'];

    // Répertoire de destination pour sauvegarder l'image
    $target_dir = "image/";

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Créer un nom de fichier unique en utilisant l'email et la date actuelle
        $currentDate = date("YmdHis");
        $image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = $email . "_" . $currentDate . "." . $image_extension;
        $target_file = $target_dir . $image_name;

        // Déplacez le fichier téléchargé dans le répertoire de destination
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Ajouter le chemin de l'image au commentaire
            $commentaire .= " image/" . $image_name;
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            exit();
        }
    }

    // Récupérer les informations de l'utilisateur
    $user_info = getUserInfo($email, $conn);
    $nom = $user_info['nom'];
    $prenom = $user_info['prenom'];
    $image = $user_info['user_image'];

    // Récupérer l'e-mail du vendeur
    $stmt = $conn->prepare("SELECT vendeur_id FROM produits_vendeurs WHERE produit_id = ?");
    $stmt->bind_param("i", $produit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $vendeur_id = $row['vendeur_id'];

    $stmt = $conn->prepare("SELECT email FROM vendeurs WHERE id = ?");
    $stmt->bind_param("i", $vendeur_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $vendeur_email = $row['email'];

    // Utiliser des requêtes préparées pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO commentaires (produit_id, email, commentaire, date_commentaire, nom, prenom, image) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
    $stmt->bind_param("isssss", $produit_id, $email, $commentaire, $nom, $prenom, $image);

    if ($stmt->execute()) {
        // Insérer une notification pour le vendeur
        $notification = "$prenom $nom a ajouté un commentaire sur votre produit.";
        $stmt = $conn->prepare("INSERT INTO notifications (user_1, user_2, notification) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $vendeur_email, $notification);
        if ($stmt->execute()) {
            header("Location: commenter.php?produit_id=$produit_id");
            exit();
        } else {
            echo "Erreur lors de l'ajout de la notification: " . $stmt->error;
        }
    } else {
        echo "Erreur lors de l'ajout du commentaire: " . $stmt->error;
    }

    // Fermer la requête
    $stmt->close();
} else {
    echo "Erreur: Données manquantes pour ajouter le commentaire.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
