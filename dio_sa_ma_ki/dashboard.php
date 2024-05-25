<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Redirection vers la page de connexion si non connecté
    exit;
}

// Récupérer l'email de l'utilisateur à partir de la session
$email = $_SESSION["email"];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

// Requête SQL pour récupérer les conversations liées à l'email de l'utilisateur
$sql = "SELECT utilisateur1, utilisateur2, dernier_message, date_dernier_message
        FROM conversations
        WHERE utilisateur1 = '$email' OR utilisateur2 = '$email'";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations</title>
</head>
<body>
    <h1>Conversations</h1>
    <?php
    // Vérification s'il y a des résultats
    if ($result->num_rows > 0) {
        // Afficher chaque conversation
        ?>
        <div>
            <?php
            // Afficher chaque conversation dans le formulaire
            while ($row = $result->fetch_assoc()) {
                // Déterminer l'autre utilisateur dans la conversation
                $autre_utilisateur = ($row['utilisateur1'] == $email) ? $row['utilisateur2'] : $row['utilisateur1'];
                $msg = $row['dernier_message'];
            ?>
            <div>
                <p>Utilisateur: <?php echo $autre_utilisateur; ?></p>
                <p>Dernier message: <span class="message"><?php echo $msg; ?></span></p>
                <p>Date dernier message: <?php echo $row['date_dernier_message']; ?></p>
            </div>
            <?php
            }
            ?>
        </div>
        <?php
    } else {
        echo "<p>Aucune conversation trouvée.</p>";
    }
    ?>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Sélectionner tous les messages
            var messages = document.querySelectorAll(".message");

            // Ajouter un écouteur d'événements de clic à chaque message
            messages.forEach(function(message, index) {
                message.addEventListener("click", function() {
                    redirectToMessage(message.textContent.trim());
                });
            });

            // Fonction pour rediriger vers la page de message spécifique
            function redirectToMessage(message) {
                window.location.href = "chat.php";
            }
        });
    </script>
</body>
</html>
<?php
// Fermer la connexion à la base de données
$conn->close();
?>
