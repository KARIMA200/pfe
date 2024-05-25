<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Redirection vers la page de connexion si non connecté
    exit;
}

// Récupération de l'email de l'utilisateur à partir de la session
$utilisateur2 = $_SESSION["email"];

// Vérification si l'autre utilisateur est envoyé par POST
if (isset($_POST["other_user"])) {
    // Récupération de l'autre utilisateur à partir de POST
    $utilisateur1 = $_POST["other_user"];

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

    // Requête SQL pour sélectionner tous les messages entre l'utilisateur actuel et l'autre utilisateur, triés par date ascendant
    $sql = "SELECT contenu, date_envoi FROM messages WHERE (expediteur = '$utilisateur1' AND destinataire = '$utilisateur2') OR (expediteur = '$utilisateur2' AND destinataire = '$utilisateur1') ORDER BY date_envoi ASC";

    $result = $conn->query($sql);
    $update_sql = "UPDATE messages SET lu = 1 WHERE destinataire = '$utilisateur2' AND expediteur = '$utilisateur1' AND lu = 0";
    $conn->query($update_sql);
    ?>
                        
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chat</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .message {
                margin-bottom: 20px;
                padding: 10px;
                border-radius: 10px;
            }
            .message p {
                margin: 0;
            }
            .message.sender {
                background-color: #DCF8C6;
                align-self: flex-end;
            }
            .message.receiver {
                background-color: #E5E5EA;
            }
            textarea {
                width: calc(100% - 20px);
                margin-bottom: 10px;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                resize: vertical;
            }
            button {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div id="messages">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $classe_message = $utilisateur1;
                        ?>
                        <div class="message <?php echo $classe_message; ?>">
                            <p><?php echo $row['contenu']; ?></p>
                            <p>Date: <?php echo $row['date_envoi']; ?></p>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Aucun message trouvé.</p>";
                }
                ?>
            </div>
            <form id="messageForm" action="envoyer_message2.php" method="post">
                <textarea name="message" id="message" rows="4" placeholder="Écrire un message"></textarea>
                <input type="hidden" name="other_user" value="<?php echo htmlspecialchars($utilisateur1); ?>">
                <button type="submit">Envoyer message</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    // Rediriger vers une page d'erreur si l'autre utilisateur n'est pas fourni
    echo "Erreur : l'autre utilisateur n'est pas spécifié.";
    exit;
}
$conn->close();
?>
