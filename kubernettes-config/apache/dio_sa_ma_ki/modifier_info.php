<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    // Vous n'êtes pas connecté.
    $error_message = urlencode("Vous n'êtes pas connecté.");
    header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
    exit();
}

// Récupérer l'email de la session
$email = $_SESSION['email'];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    // Connection failed: [error message]
    $error_message = urlencode("Échec de la connexion à la base de données");
    header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
    exit();
}

// Rechercher l'utilisateur dans la table vendeurs
$sql_vendeur = "SELECT * FROM vendeurs WHERE email = ?";
$stmt_vendeur = $conn->prepare($sql_vendeur);
$stmt_vendeur->bind_param("s", $email);
$stmt_vendeur->execute();
$result_vendeur = $stmt_vendeur->get_result();

// Rechercher l'utilisateur dans la table clients si pas trouvé dans vendeurs
$sql_client = "SELECT * FROM clients WHERE email = ?";
$stmt_client = $conn->prepare($sql_client);
$stmt_client->bind_param("s", $email);
$stmt_client->execute();
$result_client = $stmt_client->get_result();

if ($result_vendeur->num_rows > 0) {
    $row = $result_vendeur->fetch_assoc();
    $table = 'vendeurs'; // Définir la table à mettre à jour
    $success_page = 'uuv.php'; // Définir la page de succès
} elseif ($result_client->num_rows > 0) {
    $row = $result_client->fetch_assoc();
    $table = 'clients'; // Définir la table à mettre à jour
    $success_page = 'uu.php'; // Définir la page de succès
} else {
    // Utilisateur non trouvé
    $error_message = urlencode("Utilisateur non trouvé.");
    header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
    exit();
}

// Mettre à jour les informations dans la table appropriée
if (!empty($table)) {
    // Récupérer les valeurs des champs à modifier depuis $_POST
    $nom_prenom = $_POST['nom_prenom'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];
    $telephone = $_POST['telephone'];

    // Séparer la chaîne nom_prenom en nom et prénom
    list($nom, $prenom) = explode(" ", $nom_prenom);

    // Préparer la requête d'update en fonction de la table
    $sql_update = "UPDATE $table SET nom=?, prenom=?, pays=?, ville=?, telephone=? WHERE email=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssss", $nom, $prenom, $pays, $ville, $telephone, $email);

    // Exécuter la requête d'update
    if ($stmt_update->execute()) {
        $error_message = urlencode("Informations mises à jour avec succès. " . $stmt_update->error);
        // Informations mises à jour avec succès.
        header('Location:succes.php?page=' . $success_page . '&message=' . $error_message);
        exit();
    } else {
        // Erreur lors de la mise à jour des informations: [error message]
        $error_message = urlencode("Erreur lors de la mise à jour des informations: " . $stmt_update->error);
        header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
        exit();
    }
} else {
    // Type d'utilisateur non pris en charge.
    $error_message = urlencode("Type d'utilisateur non pris en charge.");
    header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>
