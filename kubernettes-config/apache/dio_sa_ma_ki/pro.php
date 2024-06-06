<?php
// Vérification de l'existence de l'email dans la requête GET
if(isset($_GET['email'])) {
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

    // Récupération de l'email de la requête GET
    $email = $_GET['email'];

    // Requête SQL pour récupérer les informations de l'utilisateur
    $sql = "SELECT nom, prenom, ville, pays, user_image FROM clients WHERE email = '$email'
            UNION
            SELECT nom, prenom, ville, pays, user_image FROM vendeurs WHERE email = '$email'";

    $result = $conn->query($sql);

    // Vérification s'il y a des résultats
    if ($result->num_rows > 0) {
        // Récupération des données de l'utilisateur
        $row = $result->fetch_assoc();
        $nom = $row["nom"];
        $prenom = $row["prenom"];
        $ville = $row["ville"];
        $pays = $row["pays"];
        $user_image = $row["user_image"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="css/all.min.css">
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            overflow: hidden;
            width: 350px;
            max-width: 100%;
            text-align: center;
            position: relative;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card__image {
            border-radius: 50%;
            border: 5px solid #272133;
            margin-top: 20px;
            width: 120px;
            height: 120px;
            object-fit: cover;
            box-shadow: 0 10px 30px rgba(235, 25, 110, 1);
            transition: transform 0.3s ease;
        }

        .card__image:hover {
            transform: scale(1.1);
        }

        .card__name {
            font-size: 1.5em;
            margin: 15px 0;
            color: #333;
        }

        .card__details {
            color: #777;
            font-size: 1.1em;
        }

        .btn {
            background: #ff7e5f;
            border: none;
            border-radius: 25px;
            color: white;
            cursor: pointer;
            font-size: 1em;
            margin: 20px 0;
            padding: 10px 30px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #e0674f;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 20px 0 0;
            padding: 0;
        }

        .social-icons li {
            margin: 0 10px;
        }

        .social-icons a {
            color: #ff7e5f;
            font-size: 1.5em;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #e0674f;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <?php
                echo "<img src='image/$user_image' alt='$prenom $nom' class='card__image'>";
                echo "<p class='card__name'>$prenom $nom</p>";
                echo "<p class='card__details'>Ville: $ville</p>";
                echo "<p class='card__details'>Pays: $pays</p>";
                echo '<a class="btn" href="pro_chat.php?email=' . urlencode($email) . '">Message</a>';
            ?>
            <ul class="social-icons">
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    echo "Aucun email fourni.";
}
?>
