<?php
// Démarrez la session au tout début
session_start();

// Assurez-vous que $_SESSION['email'] est initialisée
if (!isset($_SESSION['email'])) {
    echo "Vous n'êtes pas connecté."; // Message à afficher si l'utilisateur n'est pas connecté
    exit; // Arrête le script
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer l'email de l'utilisateur depuis la session
$email = $_SESSION['email'];

// Récupérer les notifications de la base de données pour cet utilisateur
$sql = "SELECT n.*, 
        COALESCE(v.user_image, c.user_image) AS user_image
        FROM notifications n
        LEFT JOIN vendeurs v ON n.user_1 = v.email
        LEFT JOIN clients c ON n.user_1 = c.email
        WHERE n.user_2 = '$email'";
$result = $conn->query($sql);

// Mettre à jour les notifications comme lues dans la base de données
$update_sql = "UPDATE notifications SET lu = 1 WHERE user_2 = '$email' AND lu = 0";
$conn->query($update_sql);

// Fermer la connexion à la base de données
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center; /* Centrer horizontalement */
            align-items: center; /* Centrer verticalement */
            height: 100vh;
        }

        .notification-container {
            width: 12cm; /* Largeur de 12 cm */
            overflow-y: auto;
            max-height: calc(100vh - 5cm);
            border-radius: 10px;
            padding: 20px;
            background-color: #4bcdA2;
        }

        .notification {
            background-color: #f6f7f8;
            border: 1px solid #dddfe2;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .notification img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .notification a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .notification a:hover {
            color: #4bcdA2;
        }

        .no-notification {
            color: #888;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <?php
        // Afficher les notifications avec le style approprié
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="notification">';
                if (!empty($row['user_image'])) {
                    echo '<a href="pro.php?email=' . urlencode($row['user_1']) . '"><img src="image/' . $row['user_image'] . '" alt="User Image"></a>';
                }
                if (strpos($row['notification'], 'ajouter une favorie sur votre produit') === false) {
                    echo '<a href="commenter.php?produit_id=' . $row['product_id'] . '">' . $row['notification'] . '</a>';
                } else {
                    echo $row['notification'];
                }
                echo '</div>';
            }
        } else {
            echo '<p class="no-notification">Aucune notification.</p>';
        }
        ?>
    </div>
</body>
</html>
