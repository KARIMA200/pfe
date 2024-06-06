<?php
session_start();

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

$email = $_SESSION['email'];

// Rechercher dans la table vendeurs
$sql_vendeur = "SELECT * FROM vendeurs WHERE email = ?";
$stmt_vendeur = $conn->prepare($sql_vendeur);
$stmt_vendeur->bind_param("s", $email);
$stmt_vendeur->execute();
$result_vendeur = $stmt_vendeur->get_result();

// Rechercher dans la table clients si pas trouvé dans vendeurs
$sql_client = "SELECT * FROM clients WHERE email = ?";
$stmt_client = $conn->prepare($sql_client);
$stmt_client->bind_param("s", $email);
$stmt_client->execute();
$result_client = $stmt_client->get_result();

if ($result_vendeur->num_rows > 0) {
    $row = $result_vendeur->fetch_assoc();
} elseif ($result_client->num_rows > 0) {
    $row = $result_client->fetch_assoc();
} else {
    die("Utilisateur non trouvé");
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
            position: relative;
            transition: transform 0.3s ease;
        }

        .profile-container:hover {
            transform: translateY(-10px);
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            box-shadow: 0 10px 30px rgba(235, 25, 110, 1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .profile-image:hover {
            transform: scale(1.1);
        }

        .file, .change-image-button, .modify-info-button {
            background-color: #ff7e5f;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        .change-image-button:hover, .modify-info-button:hover {
            background-color: #e0674f;
        }

        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .profile-info p {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
        }

        input[type="text"] {
            border: none;
            background: #f0f2f5;
        }

        .upload-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .upload-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            cursor: pointer;
        }

        .file-input {
            display: none;
        }
    </style>
</head>
<body>
<div class="profile-container">
    <form action="modifier_info.php" method="post">
        <h2><input type="text" name="nom_prenom" value="<?php echo  $row['nom'] . ' ' . $row['prenom'];?>"></h2>
        <?php
        $image_path = "image/" . $row['user_image'];
        if (file_exists($image_path) && is_readable($image_path)) {
            echo '<img src="' . $image_path . '" alt="' . $row['nom'] . '" class="profile-image" id="profile-image">';
        } else {
            echo '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut" class="profile-image" id="profile-image">';
        }
        ?>
        <div class="profile-info">
            <p><span class="label">Pays:</span> <input type="text" name="pays" value="<?php echo $row['pays']; ?>"></p>
            <p><span class="label">Ville:</span> <input type="text" name="ville" value="<?php echo $row['ville']; ?>"></p>
            <p><span class="label">Téléphone:</span> <input type="text" name="telephone" value="<?php echo $row['telephone']; ?>"></p>
        </div>
        <input id="modifier-input" type="submit" class="modify-info-button" value="Modifier info">
    </form>

    <form action="upload_image.php" method="post" enctype="multipart/form-data">
        <div class="upload-container">
            <label for="file-input">
                <i class="fas fa-file-upload upload-icon"></i>
            </label>
            <input type="file" id="file-input" class="file-input" name="image">
            <button type="submit" class="modify-info-button">Modifier image</button>
        </div>
    </form>

    <form class="profile-form" action="supprimer_image.php" method="POST">
        <button type="submit" class="modify-info-button">Supprimer image</button>
    </form>

    <form action="motdepasse.php" method="post">
        <button type="submit" id="l-mot" class="modify-info-button">Changer mot de passe</button>
    </form>
</div>
</body>
</html>
