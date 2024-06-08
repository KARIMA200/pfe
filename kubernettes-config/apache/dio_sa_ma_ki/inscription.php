<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        .success-message {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .success-message .icon {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    header("Location: erreur.php?page=inscription&message=" . urlencode("Échec de la connexion à la base de données: " . $conn->connect_error));
    exit();
}

// Récupération des données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$pays = $_POST['pays'];
$ville = $_POST['ville'];
$adresse = $_POST['adresse'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$password = $_POST['password'];
$type_utilisateur = $_POST['type_utilisateur'];

// Vérification si l'e-mail existe déjà dans la base de données
$email_exists_query = "SELECT COUNT(*) as count FROM clients WHERE email = '$email'";
$result = $conn->query($email_exists_query);
if ($result === FALSE) {
    header("Location: erreur.php?page=inscription&message=" . urlencode("Erreur lors de la vérification de l'email client: " . $conn->error));
    exit();
}
$row = $result->fetch_assoc();
$email_exists = $row['count'] > 0;

$email_exists_query_vendeur = "SELECT COUNT(*) as count FROM vendeurs WHERE email = '$email'";
$result_vendeur = $conn->query($email_exists_query_vendeur);
if ($result_vendeur === FALSE) {
    header("Location: erreur.php?page=inscription&message=" . urlencode("Erreur lors de la vérification de l'email vendeur: " . $conn->error));
    exit();
}
$row_vendeur = $result_vendeur->fetch_assoc();
$email_exists_vendeur = $row_vendeur['count'] > 0;

if ($email_exists || $email_exists_vendeur) {
    header("Location: erreur.php?page=inscription&message=" . urlencode("Erreur: Cet email existe déjà dans la base de données."));
    exit();
}

// Vérifier si le fichier a été correctement envoyé
if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Récupérer le nom du fichier
    $image_name = $_FILES['image']['name'];
    // Définir le chemin de destination pour enregistrer l'image
    $upload_directory = "image/"; // Choisissez le répertoire où vous souhaitez enregistrer les images
    // Déplacer le fichier téléchargé vers le répertoire de destination
    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_directory . $image_name)) {
        // Hacher le mot de passe
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertion des données dans la base de données en fonction du type d'utilisateur
        if ($type_utilisateur == 'client') {
            $sql = "INSERT INTO clients (nom, prenom, pays, ville, adresse, telephone, email, password, user_image)
                    VALUES ('$nom', '$prenom', '$pays', '$ville', '$adresse', '$telephone', '$email', '$password_hash', '$image_name')";
            $type_utilisateur_sql = 'client';       
        } elseif ($type_utilisateur == 'vendeur') {
            $sql = "INSERT INTO vendeurs (nom, prenom, pays, ville, adresse, telephone, email, password, user_image)
                    VALUES ('$nom', '$prenom', '$pays', '$ville', '$adresse', '$telephone', '$email', '$password_hash', '$image_name')";
            $type_utilisateur_sql = 'vendeur';      
        }

        if ($conn->query($sql) === TRUE) {
            // Insérer également dans la table utilisateurs
            $insert_utilisateur_sql = "INSERT INTO utilisateurs (email, type)
                                       VALUES ('$email', '$type_utilisateur_sql')";
            if ($conn->query($insert_utilisateur_sql) === TRUE) {
                echo '<div class="success-message" id="success-message">
                        <span class="icon">&#10004;</span> Inscription réussie!
                      </div>';
                echo '<script>
                        document.getElementById("success-message").style.display = "block";
                        setTimeout(function() {
                            window.location.href = "index.html.html";
                        }, 2000); // 2000 millisecondes = 2 secondes
                      </script>';
            } else {
                header("Location: erreur.php?page=inscription&message=" . urlencode("Erreur lors de l\'insertion dans la table utilisateurs: " . $conn->error));
                exit();
            }
        } else {
            header("Location: erreur.php?page=inscription&message=" . urlencode("Erreur: " . $sql . "<br>" . $conn->error));
            exit();
        }
    } else {
        header("Location: erreur.php?page=inscription&message=" . urlencode("Erreur lors de l'envoi du fichier."));
        exit();
    }
} else {
    header("Location: erreur.php?page=inscription&message=" . urlencode("Aucun fichier image envoyé ou erreur lors du téléchargement."));
    exit();
}

$conn->close();
?>
</body>
</html>
