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
    <table border="1" id="conversationTable">
        <tr>
            <th>Utilisateur</th>
            <th>Dernier message</th>
            <th>Date dernier message</th>
            <th>Actions</th>
        </tr>
        <?php
        // Vérifier s'il y a des résultats
        if ($result->num_rows > 0) {
            // Afficher chaque conversation dans le formulaire
            while ($row = $result->fetch_assoc()) {
                // Déterminer l'autre utilisateur dans la conversation
                $autre_utilisateur = ($row['utilisateur1'] == $email) ? $row['utilisateur2'] : $row['utilisateur1'];
                $msg = $row['dernier_message'];
                ?>  
                <tr> 
                    <td name="user"><?php echo $autre_utilisateur; ?></td>
                    <td class="message"><?php echo $msg; ?></td>
                    <td><?php echo $row['date_dernier_message']; ?></td>
                    <td>
                        <form action="chatter.php" method="post">
                            <input type="hidden" name="other_user" value="<?php echo $autre_utilisateur; ?>">
                            <button type="submit">Chatter</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='4'>Aucune conversation trouvée.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn->close();
?>
