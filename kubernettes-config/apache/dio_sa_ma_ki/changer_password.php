<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    die("Vous n'êtes pas connecté.");
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
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ancien = $_POST['ancien'];
    $nouveau = $_POST['nouveau'];
    $confirmer = $_POST['confirmer'];

    // Vérifier si les champs sont vides
    if (empty($ancien) || empty($nouveau) || empty($confirmer)) {
        die("Veuillez remplir tous les champs.");
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

        // Déterminer dans quelle table mettre à jour le champ password
        $table = '';
        if ($type_utilisateur == 'vendeur') {
            $table = 'vendeurs';
            $success_page = 'uuv.php';
        } elseif ($type_utilisateur == 'client') {
            $table = 'clients';
            $success_page = 'uu.php';
        }

        // Vérifier si le mot de passe actuel est correct
        $sql_password = "SELECT password FROM $table WHERE email = ?";
        $stmt_password = $conn->prepare($sql_password);
        $stmt_password->bind_param("s", $email);
        $stmt_password->execute();
        $result_password = $stmt_password->get_result();
        $row_password = $result_password->fetch_assoc();
        $password_hash = $row_password['password'];
        if (password_verify($ancien, $password_hash)) {
            // Mot de passverifiere correct
            // Connectez l'utilisateur
            
        } else {
         
               $error_message = urlencode("Ancien mot de passe incorrect.");
            header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
            exit();
           
        }

        // Vérifier si les nouveaux mots de passe correspondent
        if ($nouveau !== $confirmer) {
         
           
            $error_message = urlencode("les nouveaux msg ne corresponds pas.");
            header('Location: erreur.php?page=' . $success_page . '&message=' . $error_message);
            exit();
           
        }

        // Hacher les nouveaux mots de passe
        $ancien_hash = password_hash($ancien, PASSWORD_DEFAULT);
        $nouveau_hash = password_hash($nouveau, PASSWORD_DEFAULT);

        // Mettre à jour le mot de passe dans la table appropriée
        $sql_update = "UPDATE $table SET password = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $nouveau_hash, $email);
        $stmt_update->execute();
        $error_message = urlencode("mot de passe  changer avec succes.");
        header('Location: succes.php?page=' . $success_page . '&message=' . $error_message);
        exit();

   
        
    }
}

// Fermer la connexion
$stmt_user->close();
$stmt_password->close();
$stmt_update->close();
$conn->close();
?>
