<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    // Redirection vers erreur.php avec le message d'erreur et la page
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
    // Redirection vers erreur.php avec le message d'erreur et la page
    $error_message = urlencode("Échec de la connexion à la base de données.");
    header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
    exit();
}

// Rechercher l'utilisateur dans la table utilisateurs
$sql_user = "SELECT * FROM utilisateurs WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $type_utilisateur = $row_user['type'];

    // Déterminer la table à mettre à jour et la page de redirection
    $table = '';
    $success_page = '';
    if ($type_utilisateur == 'vendeur') {
        $table = 'vendeurs';
        $success_page = 'uuv.php';
    } elseif ($type_utilisateur == 'client') {
        $table = 'clients';
        $success_page = 'uu.php';
    } else {
        // Redirection vers erreur.php avec le message d'erreur et la page
        $error_message = urlencode("Type d'utilisateur non pris en charge.");
        header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
        exit();
    }

    // Mettre à jour le champ user_image dans la table appropriée
    if (!empty($table)) {
        // Spécifier le chemin de l'image par défaut
        $image_path = 'pardefaut.jpg';

        // Mettre à jour le champ user_image dans la base de données
        $sql_update = "UPDATE $table SET user_image = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $image_path, $email);

        if ($stmt_update->execute()) {
            // Redirection vers la page de succès avec le message de succès et la page
            $success_message = urlencode("Image mise à jour avec succès.");
            header('Location: succes.php?page=' . $success_page . '&message=' . $success_message);
            exit();
        } else {
            // Redirection vers erreur.php avec le message d'erreur et la page
            $error_message = urlencode("Erreur lors de la mise à jour de l'image dans la base de données: " . $conn->error);
            header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
            exit();
        }
    } else {
        // Redirection vers erreur.php avec le message d'erreur et la page
        $error_message = urlencode("Type d'utilisateur non pris en charge.");
        header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
        exit();
    }
} else {
    // Redirection vers erreur.php avec le message d'erreur et la page
    $error_message = urlencode("Utilisateur non trouvé.");
    header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
    exit();
}

// Fermer la connexion à la base de données
$conn->close();
?>
