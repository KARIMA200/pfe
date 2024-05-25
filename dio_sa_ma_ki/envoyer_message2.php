<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Redirection vers la page de connexion si non connecté
    exit;
}

// Récupération de l'email de l'utilisateur à partir de la session
$utilisateur1 = $_SESSION["email"];

// Vérification si l'autre utilisateur et le message sont envoyés par POST
if (isset($_POST["other_user"]) && isset($_POST["message"])) {
    // Récupération de l'autre utilisateur et du message à partir de POST
    $utilisateur2 = $_POST["other_user"];
    $message = $_POST["message"];

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

    // Requête SQL pour sélectionner l'ID de la conversation
    $sql = "SELECT id FROM conversations WHERE (utilisateur1 = '$utilisateur1' AND utilisateur2 = '$utilisateur2') OR (utilisateur1 = '$utilisateur2' AND utilisateur2 = '$utilisateur1')";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupération de l'ID de la conversation
        $row = $result->fetch_assoc();
        $conversation_id = $row["id"];

        // Requête d'insertion du message
        $insert_sql = "INSERT INTO messages (expediteur, destinataire, contenu, lu, conversation_id) 
                       VALUES ('$utilisateur1', '$utilisateur2', '$message', 0, '$conversation_id')";

        if ($conn->query($insert_sql) === TRUE) {
            // Mettre à jour la table de conversations
            $update_conversation_sql = "UPDATE conversations 
                                        SET dernier_message = '$message', date_dernier_message = NOW() 
                                        WHERE id = '$conversation_id'";

            if ($conn->query($update_conversation_sql) === TRUE) {
                echo "Message envoyé avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de la conversation: " . $conn->error;
            }
        } else {
            echo "Erreur lors de l'envoi du message: " . $conn->error;
        }
    } else {
        echo "Conversation introuvable.";
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    // Rediriger vers une page d'erreur si l'autre utilisateur ou le message n'est pas fourni
    echo "ërreur";
    exit;
}
?>
